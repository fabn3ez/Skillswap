AI Matching Module (OpenAI embeddings)

Files:
- ai/ai_match.php          : Helper that calls OpenAI Embeddings API and ranks projects by cosine similarity.
- controllers/MatchController.php : Controller that fetches freelancer skills and projects then calls the AI helper.
- user/freelancer/dashboard.php  : Updated dashboard to show recommended projects with match scores.

Setup:
1. Add your OpenAI API key to config/Constants.php:
   define('OPENAI_API_KEY', 'sk-...');

2. Ensure internet connectivity from the server (curl to https://api.openai.com succeeds).

3. For production/cost-savings: cache embeddings in your DB (add a column projects.ai_embedding JSON).
   Store project embeddings and reuse them, only re-embedding when project text changes.

Notes:
- Uses model text-embedding-3-small for embeddings.
- Watch API usage; embed requests for many projects can add cost. Consider batching and caching.
