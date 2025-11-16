
<!-- 
<section class="text-center mt-20" data-aos="fade-up">
  <h2 class="text-4xl font-extrabold text-blue-400 mb-4">Welcome to SkillSwap</h2>
  <p class="text-lg max-w-2xl mx-auto text-gray-300">
    An AI-powered freelance marketplace connecting clients and top-tier talent through intelligent matching, blockchain verification, and predictive analytics.
  </p>
  <div class="mt-10 space-x-4">
    <a href="user\register.php" class="bg-blue-500 text-white px-6 py-3 rounded-xl hover:bg-blue-600 transition">Get Started</a>
    <a href="user\login.php" class="border border-blue-400 text-blue-400 px-6 py-3 rounded-xl hover:bg-blue-400 hover:text-white transition">Login</a>
  </div>
</section> -->
<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="relative text-center py-32 overflow-hidden bg-gradient-to-b from-gray-900 via-black to-gray-950" data-aos="fade-up">
  <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_rgba(29,78,216,0.2),_transparent_70%)]"></div>

  <div class="relative z-10">
    <h1 class="text-6xl md:text-7xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-300 animate-pulse drop-shadow-lg mb-6">
      Welcome to SkillSwap
    </h1>
    <p class="text-lg md:text-xl max-w-3xl mx-auto text-gray-300 leading-relaxed mb-8">
      A next-gen freelance marketplace powered by <span class="text-blue-400 font-semibold">AI intelligence</span>, 
      <span class="text-blue-400 font-semibold">blockchain trust</span>, and 
      <span class="text-blue-400 font-semibold">predictive analytics</span>.
    </p>

    <div class="mt-8 space-x-4">
      <a href="user/register.php" 
         class="inline-block bg-blue-500 text-white px-10 py-3 rounded-2xl hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/30 transition transform hover:-translate-y-1 hover:scale-105 font-semibold tracking-wide">
         Get Started
      </a>
      <a href="user/login.php" 
         class="inline-block border border-blue-400 text-blue-400 px-10 py-3 rounded-2xl hover:bg-blue-400 hover:text-white hover:shadow-lg hover:shadow-blue-400/30 transition transform hover:-translate-y-1 hover:scale-105 font-semibold tracking-wide">
         Login
      </a>
    </div>
  </div>

  <!-- floating background circles -->
  <div class="absolute -top-10 -left-10 w-72 h-72 bg-blue-700/20 rounded-full blur-3xl animate-pulse"></div>
  <div class="absolute bottom-0 right-0 w-96 h-96 bg-cyan-600/20 rounded-full blur-3xl animate-pulse"></div>
</section>

<!-- Features -->
<section class="py-24 bg-gray-950 text-center" data-aos="fade-up" data-aos-delay="200">
  <h2 class="text-3xl md:text-4xl font-bold text-white mb-12">
    Why Choose <span class="text-blue-400">SkillSwap</span>?
  </h2>

  <div class="max-w-6xl mx-auto grid md:grid-cols-3 gap-10 px-6">
    <div class="bg-gray-800/80 backdrop-blur-lg p-8 rounded-2xl border border-gray-700 hover:border-blue-500/60 hover:shadow-xl hover:shadow-blue-500/30 transition transform hover:-translate-y-2">
      <div class="text-4xl mb-3">🤖</div>
      <h3 class="text-xl font-bold text-blue-400 mb-2">AI-Powered Matching</h3>
      <p class="text-gray-300 text-sm leading-relaxed">
        Find the perfect talent or opportunity faster with our intelligent matching engine that learns from user behavior.
      </p>
    </div>

    <div class="bg-gray-800/80 backdrop-blur-lg p-8 rounded-2xl border border-gray-700 hover:border-blue-500/60 hover:shadow-xl hover:shadow-blue-500/30 transition transform hover:-translate-y-2">
      <div class="text-4xl mb-3">🔗</div>
      <h3 class="text-xl font-bold text-blue-400 mb-2">Blockchain Verification</h3>
      <p class="text-gray-300 text-sm leading-relaxed">
        Every project, contract, and transaction is verified on-chain for maximum transparency and zero fraud.
      </p>
    </div>

    <div class="bg-gray-800/80 backdrop-blur-lg p-8 rounded-2xl border border-gray-700 hover:border-blue-500/60 hover:shadow-xl hover:shadow-blue-500/30 transition transform hover:-translate-y-2">
      <div class="text-4xl mb-3">📊</div>
      <h3 class="text-xl font-bold text-blue-400 mb-2">Predictive Analytics</h3>
      <p class="text-gray-300 text-sm leading-relaxed">
        Our analytics engine predicts project outcomes and helps freelancers price their bids smartly.
      </p>
    </div>
  </div>
</section>

<!-- Testimonials -->
<section class="py-24 bg-gradient-to-b from-gray-950 to-black text-center" data-aos="fade-up" data-aos-delay="300">
  <h2 class="text-3xl md:text-4xl font-bold text-white mb-12">
    Voices from our <span class="text-blue-400">Community</span>
  </h2>

  <div class="max-w-5xl mx-auto grid md:grid-cols-3 gap-8 px-6">
    <div class="bg-gray-800/80 p-6 rounded-2xl border border-gray-700 hover:border-blue-500/50 transition">
      <p class="text-gray-300 italic mb-4">
        "SkillSwap made it incredibly easy to find high-quality freelance developers for my startup. The AI suggestions were spot on!"
      </p>
      <h4 class="text-blue-400 font-semibold">— Alex, Startup Founder</h4>
    </div>
    <div class="bg-gray-800/80 p-6 rounded-2xl border border-gray-700 hover:border-blue-500/50 transition">
      <p class="text-gray-300 italic mb-4">
        "As a freelancer, I love how transparent the bidding system is. The dashboard makes managing projects so smooth!"
      </p>
      <h4 class="text-blue-400 font-semibold">— Michelle, UX Designer</h4>
    </div>
    <div class="bg-gray-800/80 p-6 rounded-2xl border border-gray-700 hover:border-blue-500/50 transition">
      <p class="text-gray-300 italic mb-4">
        "Moderating projects and resolving disputes has never been this streamlined. SkillSwap truly bridges trust."
      </p>
      <h4 class="text-blue-400 font-semibold">— Samuel, Platform Moderator</h4>
    </div>
  </div>
</section>

<!-- Final Call to Action -->
<!-- Final Call to Action -->
<section class="relative text-center py-28 bg-gray-900 border-t border-gray-800 overflow-hidden" data-aos="fade-up" data-aos-delay="400">
  <h3 class="text-4xl font-bold text-white mb-6">Ready to Elevate Your Skills?</h3>
  <p class="text-gray-400 max-w-2xl mx-auto mb-10">
    Whether you’re a freelancer seeking opportunity or a client hiring top-tier talent,SkillSwap is your home for collaboration and growth.
  </p>

  <!-- Animated Robot -->
  <!-- <div class="absolute left-1/2 transform -translate-x-1/2 bottom-32 md:bottom-36 z-10">
    <div class="animate-jump flex justify-center items-center">
      <span class="text-5xl md:text-6xl animate-wave">🤖</span>
    </div>
  </div> -->

  <div class="absolute left-1/2 transform -translate-x-1/2 bottom-32 md:bottom-36 z-10">
  <div class="animate-jump inline-block">
    <img src="assets/gif/robo.gif" alt="Robot" class="w-20 md:w-24 animate-wave" />
  </div>
</div>


  <!-- Get Started Button -->
  <a href="user/register.php" 
     class="animated-btn relative z-20 text-white px-10 py-3 rounded-2xl font-semibold tracking-wide transition transform hover:scale-105">
     Get Started
  </a>
</section>

<!-- Custom Styles -->
<style>
@keyframes wave {
  0%, 100% { transform: rotate(0deg); }
  25% { transform: rotate(15deg); }
  50% { transform: rotate(-10deg); }
  75% { transform: rotate(10deg); }
}
.animate-wave { animation: wave 2s infinite ease-in-out; display: inline-block; }

@keyframes jump {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-20px); }
}
.animate-jump { animation: jump 2.5s infinite ease-in-out; }

@keyframes gradientShift {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}
.animated-btn {
  background: linear-gradient(270deg, #2563eb, #06b6d4, #2563eb);
  background-size: 200% 200%;
  animation: gradientShift 4s ease infinite;
  box-shadow: 0 0 20px rgba(37, 99, 235, 0.4);
}
.animated-btn:hover {
  box-shadow: 0 0 30px rgba(6, 182, 212, 0.6);
  background-position: 100% 50%;
}
</style>
<?php include 'includes/footer.php'; ?> 