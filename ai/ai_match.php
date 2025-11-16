<?php
// ai/ai_match.php
// Simple OpenAI embeddings-based matching helper for SkillSwap.
// Requires you to set OPENAI_API_KEY in config/Constants.php (or replace with your key).


/**
 * getAIRecommendations
 * @param array $freelancerSkills array of skill strings for the freelancer
 * @param array $projects array of projects (each must contain 'project_id','title','description')
 * @param int $limit max results to return
 * @return array ranked projects with 'similarity' score (0..1)
 */
function getAIRecommendations(array $freelancerSkills, array $projects, int $limit = 5) {
    if (empty($freelancerSkills) || empty($projects)) return [];

    $apiKey = defined('OPENAI_API_KEY') ? OPENAI_API_KEY : '';
    if (!$apiKey) return []; // Key missing. Make sure config/Constants.php defines OPENAI_API_KEY

    // Build texts
    $freelancerText = implode(', ', $freelancerSkills);

    // Create embeddings for freelancer + all projects in a single call
    $url = 'https://api.openai.com/v1/embeddings';
    $model = 'text-embedding-3-small';

    // Prepare project texts
    $projectTexts = [];
    foreach ($projects as $p) {
        $projectTexts[] = ($p['title'] ?? '') . "\n" . ($p['description'] ?? '');
    }

    $inputs = array_merge([$freelancerText], $projectTexts); // first is freelancer

    $payload = json_encode([
        'model' => $model,
        'input' => $inputs
    ]);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: ' . 'Bearer ' . $apiKey
        ],
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_TIMEOUT => 30
    ]);

    $resp = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err || !$resp) return [];

    $decoded = json_decode($resp, true);
    if (!isset($decoded['data']) || count($decoded['data']) < 1) return [];

    // first embedding is freelancer embedding
    $freelancerEmbedding = $decoded['data'][0]['embedding'] ?? null;
    if (!$freelancerEmbedding) return [];

    // project embeddings follow
    $ranked = [];
    for ($i = 1; $i < count($decoded['data']); $i++) {
        $projEmb = $decoded['data'][$i]['embedding'] ?? null;
        if (!$projEmb) continue;
        $similarity = cosineSimilarity($freelancerEmbedding, $projEmb);
        $proj = $projects[$i-1];
        $proj['similarity'] = $similarity;
        $ranked[] = $proj;
    }

    usort($ranked, function($a,$b){ return $b['similarity'] <=> $a['similarity']; });
    return array_slice($ranked, 0, $limit);
}

/**
 * cosineSimilarity - computes cosine similarity between two vectors (arrays)
 */
function cosineSimilarity(array $a, array $b) {
    $dot = 0.0; $na = 0.0; $nb = 0.0;
    $len = min(count($a), count($b));
    for ($i = 0; $i < $len; $i++) {
        $dot += $a[$i] * $b[$i];
        $na += $a[$i] * $a[$i];
        $nb += $b[$i] * $b[$i];
    }
    if ($na == 0 || $nb == 0) return 0;
    return $dot / (sqrt($na) * sqrt($nb));
}
?>