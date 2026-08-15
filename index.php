<?php 
$pageTitle = "RegrowthX - Extra Strength 5% Minoxidil Hair Regrowth Serum";
require_once __DIR__ . '/includes/store-header.php';

// Fetch all active products and their variants
$stmt = $pdo->query("
    SELECT p.id as product_id, p.title, p.description, p.slug, 
           pv.id as variant_id, pv.variant_name, pv.variant_key, pv.price_inr, pv.mrp_inr, pv.image_path
    FROM products p
    JOIN product_variants pv ON p.id = pv.product_id
    WHERE p.active = 1
    ORDER BY p.id ASC, pv.price_inr ASC
");
$store_variants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- BEGIN: Main Content -->
<main class="pt-20">

<!-- BEGIN: Hero Section -->
<section class="relative hero-gradient text-white overflow-hidden min-h-[600px] lg:min-h-[750px] flex items-center">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full py-12 lg:py-20 grid lg:grid-cols-2 gap-12 items-center">
    
    <!-- Hero Text -->
    <div class="max-w-xl reveal-on-scroll is-visible">
      <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full glass-pill text-sm font-medium mb-8 animate-pulse-subtle hover:border-brand-light/50 transition-colors cursor-default">
        <svg class="w-4 h-4 text-emerald-400 animate-spin" style="animation-duration: 8s;" fill="currentColor" viewBox="0 0 24 24">
          <path clip-rule="evenodd" d="M12.516 2.17a.75.75 0 0 0-1.032 0 11.209 11.209 0 0 1-7.877 3.08.75.75 0 0 0-.722.515A12.74 12.74 0 0 0 2.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 0 0 .374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.39-.223-2.73-.635-3.985a.75.75 0 0 0-.722-.516l-.143.001c-2.996 0-5.717-1.17-7.734-3.08Zm3.094 8.016a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" fill-rule="evenodd"></path>
        </svg>
        Clinically Proven 5% Minoxidil USP
      </div>

      <h1 class="text-5xl lg:text-7xl font-extrabold tracking-tight mb-6 leading-[1.1]">
        Regrow Hair.<br/>
        Restore <span class="text-brand-light inline-block transition-transform duration-300 hover:translate-x-1">Confidence.</span>
      </h1>

      <p class="text-lg text-gray-300 mb-8 max-w-md">
        RegrowthX Extra Strength 5% Minoxidil is the dermatologist-recommended topical solution engineered to reactivate dormant hair follicles and reverse thinning.
      </p>

      <!-- Trust Badges -->
      <div class="flex flex-wrap gap-4 mb-10 text-sm font-medium text-gray-300">
        <div class="flex items-center gap-1.5 transition-transform duration-300 hover:scale-105">
          <svg class="w-5 h-5 text-brand-light" fill="currentColor" viewBox="0 0 24 24">
            <path clip-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" fill-rule="evenodd"></path>
          </svg>
          Clinically Proven
        </div>
        <div class="flex items-center gap-1.5 transition-transform duration-300 hover:scale-105">
          <svg class="w-5 h-5 text-brand-light" fill="currentColor" viewBox="0 0 24 24">
            <path clip-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" fill-rule="evenodd"></path>
          </svg>
          Dermatologist Recommended
        </div>
        <div class="flex items-center gap-1.5 transition-transform duration-300 hover:scale-105">
          <svg class="w-5 h-5 text-brand-light" fill="currentColor" viewBox="0 0 24 24">
            <path clip-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" fill-rule="evenodd"></path>
          </svg>
          Made in USA
        </div>
      </div>

      <!-- CTA Buttons -->
      <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <a class="inline-flex items-center justify-center px-8 py-3.5 text-base font-semibold text-white bg-brand-primary hover:bg-brand-primary/90 rounded-full transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 active:scale-95 group" href="#products">
          Shop Now <span class="ml-1 inline-block transition-transform duration-300 group-hover:translate-x-1.5">→</span>
        </a>
        <a class="inline-flex items-center justify-center px-8 py-3.5 text-base font-semibold text-white bg-transparent border border-gray-500 hover:border-white rounded-full transition-all duration-300 hover:bg-white/10 active:scale-95 group" href="#results">
          <svg class="w-5 h-5 mr-2 transition-transform duration-300 group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24">
            <path clip-rule="evenodd" d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z" fill-rule="evenodd"></path>
          </svg>
          Watch Results
        </a>
      </div>

      <!-- Reviews -->
      <div class="flex items-center gap-2 text-sm text-gray-300">
        <div class="flex text-brand-light">
          <span class="material-symbols-outlined text-lg">star</span>
          <span class="material-symbols-outlined text-lg">star</span>
          <span class="material-symbols-outlined text-lg">star</span>
          <span class="material-symbols-outlined text-lg">star</span>
          <span class="material-symbols-outlined text-lg">star</span>
        </div>
        <span class="font-medium">4.9/5 (12,000+ Verified Reviews)</span>
      </div>
    </div>

    <!-- Hero Image -->
    <div class="relative flex justify-center lg:justify-end reveal-on-scroll is-visible delay-200">
      <div class="relative group cursor-pointer" onclick="handleAddToCart('60ml')">
        <div class="absolute -inset-4 bg-emerald-500/20 rounded-full blur-2xl group-hover:bg-emerald-500/30 transition-all duration-500"></div>
        <img alt="RegrowthX Minoxidil 5% Serum Box & Bottle" class="relative z-10 max-w-full h-auto max-h-[460px] object-contain animate-float hover:scale-105 transition-transform duration-700 drop-shadow-2xl rounded-2xl" src="img/product-box-bottle.jpg"/>
      </div>
    </div>

  </div>
</section>
<!-- END: Hero Section -->

<!-- Counter Stats Section -->
<section class="bg-white py-10 border-b border-gray-100 counter-section">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-gray-100">
      <div class="transition-transform duration-300 hover:-translate-y-1">
        <div class="text-3xl font-extrabold text-gray-900 mb-1 counter" data-target="12000" data-suffix="+">12,000+</div>
        <div class="text-sm text-gray-500 font-medium uppercase tracking-wide">Happy Customers</div>
      </div>
      <div class="transition-transform duration-300 hover:-translate-y-1">
        <div class="text-3xl font-extrabold text-gray-900 mb-1 counter" data-target="97" data-suffix="%">97%</div>
        <div class="text-sm text-gray-500 font-medium uppercase tracking-wide">Satisfaction Rate</div>
      </div>
      <div class="transition-transform duration-300 hover:-translate-y-1">
        <div class="flex justify-center mb-2">
          <svg class="w-8 h-8 text-emerald-600 animate-pulse" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.516 2.17a.75.75 0 0 0-1.032 0 11.209 11.209 0 0 1-7.877 3.08.75.75 0 0 0-.722.515A12.74 12.74 0 0 0 2.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 0 0 .374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.39-.223-2.73-.635-3.985a.75.75 0 0 0-.722-.516l-.143.001c-2.996 0-5.717-1.17-7.734-3.08Zm3.094 8.016a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z"></path>
          </svg>
        </div>
        <div class="text-sm text-gray-500 font-medium uppercase tracking-wide">Dermatologist Approved</div>
      </div>
      <div class="transition-transform duration-300 hover:-translate-y-1">
        <div class="text-3xl font-extrabold text-gray-900 mb-1 counter" data-target="30" data-suffix=" Day">30 Day</div>
        <div class="text-sm text-gray-500 font-medium uppercase tracking-wide">Money Back Guarantee</div>
      </div>
    </div>
  </div>
</section>

<!-- BEGIN: Benefits Section -->
<section class="py-20 bg-brand-bg">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group reveal-on-scroll">
        <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
          <div class="w-4 h-4 bg-red-500 rounded-full animate-ping"></div>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-brand-primary transition-colors">Stops Hairfall</h3>
        <p class="text-gray-500 text-sm leading-relaxed">Reduces shedding by strengthening hair at the follicle level within 4 to 8 weeks.</p>
      </div>

      <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group reveal-on-scroll delay-100">
        <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
          <svg class="w-6 h-6 text-brand-primary group-hover:rotate-12 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12.378 1.602a.75.75 0 0 0-.756 0L3 6.632l9 5.25 9-5.25-8.622-5.03ZM21.75 7.93l-9 5.25v9l8.628-5.032a.75.75 0 0 0 .372-.648V7.93ZM11.25 22.18v-9l-9-5.25v8.57a.75.75 0 0 0 .372.648l8.628 5.033Z"></path>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-brand-primary transition-colors">Stimulates Growth</h3>
        <p class="text-gray-500 text-sm leading-relaxed">Reactivates dormant vertex follicles to kickstart new, dense hair growth.</p>
      </div>

      <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group reveal-on-scroll delay-200">
        <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
          <svg class="w-6 h-6 text-blue-500 group-hover:rotate-12 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24">
            <path clip-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm0 13.5a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z" fill-rule="evenodd"></path>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-brand-primary transition-colors">5% Minoxidil USP</h3>
        <p class="text-gray-500 text-sm leading-relaxed">The clinically proven, FDA-approved concentration for max regrowth efficiency.</p>
      </div>

      <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group reveal-on-scroll delay-300">
        <div class="w-12 h-12 bg-amber-50 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
          <svg class="w-6 h-6 text-amber-500 group-hover:rotate-12 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24">
            <path clip-rule="evenodd" d="M9 4.5a.75.75 0 0 1 .721.544l.813 2.846a3.75 3.75 0 0 0 2.576 2.576l2.846.813a.75.75 0 0 1 0 1.442l-2.846.813a3.75 3.75 0 0 0-2.576 2.576l-.813 2.846a.75.75 0 0 1-1.442 0l-.813-2.846a3.75 3.75 0 0 0-2.576-2.576l-2.846-.813a.75.75 0 0 1 0-1.442l2.846-.813A3.75 3.75 0 0 0 7.466 7.89l.813-2.846A.75.75 0 0 1 9 4.5Z" fill-rule="evenodd"></path>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-brand-primary transition-colors">Visible Results</h3>
        <p class="text-gray-500 text-sm leading-relaxed">Most men observe noticeable crown density within 8 to 12 weeks of daily use.</p>
      </div>
    </div>
  </div>
</section>

<!-- BEGIN: Science & About Section -->
<section class="py-20 bg-white border-y border-gray-100" id="about">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid lg:grid-cols-2 gap-12 items-center">
      <div class="reveal-on-scroll">
        <div class="text-brand-primary font-bold tracking-widest text-xs uppercase mb-3">Clinical Foundation</div>
        <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight mb-8">The Science Behind RegrowthX</h2>
        <div class="space-y-8">
          <div class="flex gap-4 group cursor-default">
            <div class="flex-shrink-0 w-10 h-10 bg-emerald-50 rounded-full flex items-center justify-center text-brand-primary font-bold transition-transform duration-300 group-hover:scale-110 group-hover:bg-brand-primary group-hover:text-white">1</div>
            <div>
              <h4 class="font-bold text-gray-900 mb-1 group-hover:text-brand-primary transition-colors">5% Minoxidil Active Formula</h4>
              <p class="text-gray-500 text-sm leading-relaxed">The clinically proven gold standard for male pattern hair loss, delivering maximum potency directly to scalp receptors.</p>
            </div>
          </div>
          <div class="flex gap-4 group cursor-default">
            <div class="flex-shrink-0 w-10 h-10 bg-emerald-50 rounded-full flex items-center justify-center text-brand-primary font-bold transition-transform duration-300 group-hover:scale-110 group-hover:bg-brand-primary group-hover:text-white">2</div>
            <div>
              <h4 class="font-bold text-gray-900 mb-1 group-hover:text-brand-primary transition-colors">Micro-Vessel Reactivation</h4>
              <p class="text-gray-500 text-sm leading-relaxed">Dilates blood supply around miniaturized hair follicles, boosting oxygen and vital nutrient intake.</p>
            </div>
          </div>
          <div class="flex gap-4 group cursor-default">
            <div class="flex-shrink-0 w-10 h-10 bg-emerald-50 rounded-full flex items-center justify-center text-brand-primary font-bold transition-transform duration-300 group-hover:scale-110 group-hover:bg-brand-primary group-hover:text-white">3</div>
            <div>
              <h4 class="font-bold text-gray-900 mb-1 group-hover:text-brand-primary transition-colors">Extended Anagen (Growth) Phase</h4>
              <p class="text-gray-500 text-sm leading-relaxed">Extends the active growth period of individual hair strands, allowing hair to become thicker and resist premature shedding.</p>
            </div>
          </div>
        </div>
      </div>
      
      <div class="relative rounded-3xl overflow-hidden shadow-2xl reveal-on-scroll delay-200 group border border-emerald-900/10">
        <img alt="Scalp Application & Microcirculation" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" src="img/scalp-application.jpg"/>
        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
        <div class="absolute bottom-6 left-6 right-6 text-white">
          <span class="bg-emerald-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Unscented Topical Solution</span>
          <p class="text-sm text-gray-200 mt-2 font-medium">Easy 1 mL twice-daily application directly onto vertex scalp.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- BEGIN: Interactive Before / After Section -->
<section class="py-20 bg-brand-bg border-b border-gray-100" id="results">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12 reveal-on-scroll">
      <span class="inline-block bg-emerald-100 text-emerald-800 text-xs font-extrabold px-4 py-1.5 rounded-full uppercase tracking-wider mb-3">
        Proven Hair Transformations
      </span>
      <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight mb-4">
        Interactive Before & After Comparison
      </h2>
      <p class="text-gray-600 max-w-2xl mx-auto">
        Drag the slider left or right to compare vertex hair density before treatment and after 12 weeks of RegrowthX 5% Minoxidil solution.
      </p>
    </div>

    <!-- BA Slider Card Container -->
    <div class="max-w-3xl mx-auto reveal-on-scroll delay-100">
      <div class="relative w-full h-[380px] sm:h-[480px] rounded-3xl overflow-hidden shadow-2xl border-4 border-white select-none group bg-gray-900" id="ba-container">
        
        <!-- AFTER Image (Base / Right) -->
        <img src="img/after.jpg" alt="After RegrowthX Minoxidil Regrowth" class="absolute inset-0 w-full h-full object-cover object-top pointer-events-none" />
        <span class="absolute bottom-5 right-5 bg-emerald-700/90 text-white font-bold text-xs uppercase tracking-widest px-4 py-2 rounded-full backdrop-blur-md z-10 shadow-lg border border-emerald-500/30">
          AFTER (Week 12)
        </span>

        <!-- BEFORE Image (Clipped Overlay / Left) -->
        <div class="absolute inset-0 w-1/2 overflow-hidden border-r-2 border-white shadow-2xl transition-all duration-75" id="before-layer">
          <img src="img/before.jpg" alt="Before RegrowthX Treatment" class="absolute top-0 left-0 h-full object-cover object-top pointer-events-none" id="before-img" />
          <span class="absolute bottom-5 left-5 bg-gray-900/85 text-white font-bold text-xs uppercase tracking-widest px-4 py-2 rounded-full backdrop-blur-md z-10 shadow-lg border border-gray-700">
            BEFORE (Week 0)
          </span>
        </div>

        <!-- Handle Divider & Knob -->
        <div class="absolute top-0 bottom-0 left-1/2 w-1 bg-white shadow-[0_0_15px_rgba(0,0,0,0.6)] z-20 pointer-events-none -translate-x-1/2" id="handle-line">
          <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white text-emerald-950 shadow-2xl flex items-center justify-center font-bold border-2 border-emerald-600 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6 text-emerald-800" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" transform="rotate(-90 12 12)" />
            </svg>
          </div>
        </div>

        <!-- Range Slider Control -->
        <input type="range" min="0" max="100" value="50" class="absolute inset-0 w-full h-full opacity-0 cursor-ew-resize z-30 m-0" id="ba-range-slider" oninput="updateBaSlider(this.value)" />

      </div>
      <div class="flex justify-between items-center mt-3 text-xs text-gray-500 px-2 font-medium">
        <span>← Drag left to reveal full regrowth</span>
        <span>Drag right for baseline thinning →</span>
      </div>
    </div>
  </div>
</section>

<!-- BEGIN: Testimonials Section -->
<section class="py-20 bg-white border-b border-gray-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12 reveal-on-scroll">
      <div class="flex justify-center items-center gap-2 mb-4">
        <div class="flex text-amber-400">
          <span class="material-symbols-outlined text-2xl">star</span>
          <span class="material-symbols-outlined text-2xl">star</span>
          <span class="material-symbols-outlined text-2xl">star</span>
          <span class="material-symbols-outlined text-2xl">star</span>
          <span class="material-symbols-outlined text-2xl">star</span>
        </div>
        <span class="text-2xl font-extrabold text-gray-900">4.9/5.0</span>
      </div>
      <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight mb-4">What Our Customers Say</h2>
      <p class="text-gray-600 max-w-2xl mx-auto">Real results from men who restored scalp density and confidence with RegrowthX.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <div class="bg-brand-bg/50 p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 reveal-on-scroll">
        <div class="flex items-center gap-1 mb-4 text-amber-400">
          <span class="material-symbols-outlined text-sm">star</span>
          <span class="material-symbols-outlined text-sm">star</span>
          <span class="material-symbols-outlined text-sm">star</span>
          <span class="material-symbols-outlined text-sm">star</span>
          <span class="material-symbols-outlined text-sm">star</span>
        </div>
        <p class="text-gray-700 italic mb-6 leading-relaxed">"After 3 months of consistent twice-daily use, my barber actually pointed out how much fuller my crown is. Thinning spot is almost completely filled."</p>
        <div class="flex items-center justify-between">
          <div>
            <div class="font-bold text-gray-900">David R.</div>
            <div class="flex items-center gap-1 text-brand-primary text-xs font-semibold">
              <span class="material-symbols-outlined text-xs">verified</span> Verified Buyer
            </div>
          </div>
        </div>
      </div>

      <div class="bg-brand-bg/50 p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 reveal-on-scroll delay-100">
        <div class="flex items-center gap-1 mb-4 text-amber-400">
          <span class="material-symbols-outlined text-sm">star</span>
          <span class="material-symbols-outlined text-sm">star</span>
          <span class="material-symbols-outlined text-sm">star</span>
          <span class="material-symbols-outlined text-sm">star</span>
          <span class="material-symbols-outlined text-sm">star</span>
        </div>
        <p class="text-gray-700 italic mb-6 leading-relaxed">"I was hesitant about minoxidil grease, but RegrowthX dries quickly and leaves no residue. Seeing real new hairs sprouting on my crown."</p>
        <div class="flex items-center justify-between">
          <div>
            <div class="font-bold text-gray-900">Michael S.</div>
            <div class="flex items-center gap-1 text-brand-primary text-xs font-semibold">
              <span class="material-symbols-outlined text-xs">verified</span> Verified Buyer
            </div>
          </div>
        </div>
      </div>

      <div class="bg-brand-bg/50 p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 reveal-on-scroll delay-200">
        <div class="flex items-center gap-1 mb-4 text-amber-400">
          <span class="material-symbols-outlined text-sm">star</span>
          <span class="material-symbols-outlined text-sm">star</span>
          <span class="material-symbols-outlined text-sm">star</span>
          <span class="material-symbols-outlined text-sm">star</span>
          <span class="material-symbols-outlined text-sm">star</span>
        </div>
        <p class="text-gray-700 italic mb-6 leading-relaxed">"The 6-month supply pack is unmatched value. Hair feels significantly thicker and shedding in the shower has practically stopped."</p>
        <div class="flex items-center justify-between">
          <div>
            <div class="font-bold text-gray-900">James L.</div>
            <div class="flex items-center gap-1 text-brand-primary text-xs font-semibold">
              <span class="material-symbols-outlined text-xs">verified</span> Verified Buyer
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ================= OUR PRODUCTS SECTION ================= -->
<section class="py-20 bg-gray-50" id="products">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center mb-14">
    <span class="inline-block bg-emerald-100 text-emerald-800 text-sm font-semibold px-4 py-2 rounded-full uppercase tracking-wider mb-4">
      Premium Hair Care Solutions
    </span>
    <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">Our Products</h2>
    <p class="max-w-2xl mx-auto text-lg text-gray-600 leading-relaxed">
      Choose the right Minoxidil 5% supply for your regrowth journey. Start with a 1-month trial or save more with our 6-month value bundle.
    </p>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-8">

      <?php if (empty($store_variants)): ?>
        <div class="col-span-full text-center text-gray-500 py-10">
            <p>No products available at the moment.</p>
        </div>
      <?php else: 
        $display_variants = array_slice($store_variants, 0, 4);
        foreach ($display_variants as $variant): 
      ?>
        <div class="bg-white rounded-[32px] p-8 flex flex-col lg:flex-row items-center gap-8 border border-gray-100 shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group overflow-hidden">
          <div class="relative w-full lg:w-[48%] h-[380px] rounded-[28px] bg-gradient-to-br from-[#0c1f11] via-[#173922] to-[#0c1f11] flex items-center justify-center overflow-hidden">
            <div class="absolute w-80 h-80 bg-emerald-400/20 rounded-full blur-[90px]"></div>
            <img src="<?= htmlspecialchars($variant['image_path'] ?? 'img/product-box-bottle.jpg') ?>" alt="<?= htmlspecialchars($variant['title']) ?>" class="relative z-10 max-h-[85%] object-contain drop-shadow-[0_25px_35px_rgba(0,0,0,0.5)] transition-all duration-700 group-hover:scale-110 group-hover:-rotate-2 rounded-xl"/>
          </div>
          <div class="w-full lg:w-[52%] flex flex-col justify-center">
            <h3 class="text-3xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($variant['title']) ?> (<?= htmlspecialchars($variant['variant_name']) ?>)</h3>
            <p class="text-emerald-700 font-bold text-2xl mb-3">₹<?= number_format($variant['price_inr'], 0) ?> <span class="text-sm text-gray-400 line-through font-normal">₹<?= number_format($variant['mrp_inr'], 0) ?></span></p>
            <p class="text-gray-500 text-sm leading-relaxed mb-6">
              <?= htmlspecialchars($variant['description']) ?>
            </p>
            <div class="flex flex-col sm:flex-row gap-3">
              <button onclick="addToCartAPI(<?= $variant['variant_id'] ?>, 1)" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold py-3.5 px-4 rounded-full transition-all duration-300 hover:scale-[1.02] hover:shadow-xl active:scale-95 cursor-pointer flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-lg">add_shopping_cart</span> Add to Cart
              </button>
              <a href="product-details.php?id=<?= $variant['product_id'] ?>" class="px-5 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-semibold rounded-full transition-all text-center flex items-center justify-center gap-1">
                View Details <span class="material-symbols-outlined text-sm">arrow_forward</span>
              </a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>

    </div>

    <?php if (count($store_variants) > 4): ?>
    <div class="text-center mt-12">
      <a href="products.php" class="inline-flex items-center gap-2 px-8 py-4 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-base rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 active:scale-95">
        View All Products (<?= count($store_variants) ?>)
        <span class="material-symbols-outlined text-xl">arrow_forward</span>
      </a>
    </div>
    <?php endif; ?>

  </div>
</section>

<!-- POP-UP MODAL OVERLAY -->
<div id="product-modal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
  <div class="relative w-full max-w-5xl bg-white rounded-[2rem] border border-gray-100 shadow-2xl overflow-hidden max-h-[92vh] flex flex-col my-auto">
    
    <!-- Modal Sticky Header -->
    <div class="sticky top-0 z-20 flex items-center justify-between px-6 py-4 bg-white/95 backdrop-blur-md border-b border-gray-100">
      <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Product Quick View</span>
      <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold text-base transition-all active:scale-90 cursor-pointer">
        ✕
      </button>
    </div>

    <!-- Scrollable Content Body -->
    <div class="p-6 sm:p-8 space-y-8 overflow-y-auto">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
        
        <!-- LEFT SIDE: Image Gallery -->
        <div class="space-y-3">
          <div class="w-full bg-[#0d1e12] rounded-2xl p-4 flex justify-center items-center h-64 md:h-72 shadow-inner relative group border border-emerald-900/30 overflow-hidden">
            <img id="main-gallery-img" alt="Selected Product Image" class="max-h-full max-w-full object-contain drop-shadow-2xl transition-all duration-300 group-hover:scale-105 rounded-lg" src="img/product-box-bottle.jpg"/>
          </div>

          <div class="grid grid-cols-4 gap-2">
            <button onclick="changeGalleryImage(0)" class="thumb-btn border-2 border-emerald-600 rounded-xl bg-[#0d1e12] p-1 h-16 flex items-center justify-center transition-all overflow-hidden focus:outline-none">
              <img id="thumb-img-0" class="max-h-full max-w-full object-contain rounded" src="img/product-box-bottle.jpg" alt="Thumb 1">
            </button>
            <button onclick="changeGalleryImage(1)" class="thumb-btn border-2 border-gray-200 hover:border-emerald-300 rounded-xl bg-[#0d1e12] p-1 h-16 flex items-center justify-center transition-all overflow-hidden focus:outline-none">
              <img id="thumb-img-1" class="max-h-full max-w-full object-contain rounded" src="img/product-dropper.jpg" alt="Thumb 2">
            </button>
            <button onclick="changeGalleryImage(2)" class="thumb-btn border-2 border-gray-200 hover:border-emerald-300 rounded-xl bg-[#0d1e12] p-1 h-16 flex items-center justify-center transition-all overflow-hidden focus:outline-none">
              <img id="thumb-img-2" class="max-h-full max-w-full object-contain rounded" src="img/routine-guide.jpg" alt="Thumb 3">
            </button>
            <button onclick="changeGalleryImage(3)" class="thumb-btn border-2 border-gray-200 hover:border-emerald-300 rounded-xl bg-[#0d1e12] p-1 h-16 flex items-center justify-center transition-all overflow-hidden focus:outline-none">
              <img id="thumb-img-3" class="max-h-full max-w-full object-contain rounded" src="img/scalp-application.jpg" alt="Thumb 4">
            </button>
          </div>
        </div>

        <!-- RIGHT SIDE: Product Controls -->
        <div class="space-y-5">
          <div>
            <h1 id="modal-product-title" class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">RegrowthX 5% Minoxidil Hair Serum</h1>
            <div class="flex items-center gap-2 mt-2">
              <div class="flex text-amber-400">
                <span class="material-symbols-outlined text-lg">star</span>
                <span class="material-symbols-outlined text-lg">star</span>
                <span class="material-symbols-outlined text-lg">star</span>
                <span class="material-symbols-outlined text-lg">star</span>
                <span class="material-symbols-outlined text-lg">star</span>
              </div>
              <span class="text-xs text-gray-500 font-medium">(12,480 verified reviews)</span>
            </div>

            <div class="flex items-baseline gap-3 mt-3">
              <span id="original-price" class="text-base text-gray-400 line-through font-medium">₹2,499</span>
              <span id="current-price" class="text-3xl font-extrabold text-emerald-700">₹1,299</span>
            </div>
          </div>

          <div class="flex items-center gap-4 pt-1">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Quantity:</span>
            <div class="inline-flex items-center border border-emerald-300 rounded-lg bg-emerald-50/50 px-2 py-1 shadow-sm">
              <button onclick="updateQty(-1)" class="w-7 h-7 rounded text-emerald-900 hover:bg-emerald-100 font-bold text-base flex items-center justify-center transition-all active:scale-90 cursor-pointer">-</button>
              <span id="product-qty" class="w-8 text-center font-bold text-gray-900 text-sm">1</span>
              <button onclick="updateQty(1)" class="w-7 h-7 rounded text-emerald-900 hover:bg-emerald-100 font-bold text-base flex items-center justify-center transition-all active:scale-90 cursor-pointer">+</button>
            </div>
          </div>

          <div class="space-y-2">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Select Supply Pack:</label>
            <div class="flex flex-wrap gap-3">
              <label id="variant-label-60ml" onclick="selectVariant(1299, 2499, '60ml')" class="variant-card cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-full border-2 border-emerald-600 bg-emerald-50/30 text-gray-900 font-medium text-xs sm:text-sm transition-all shadow-sm">
                <input type="radio" id="variant-radio-60ml" name="variant" value="60ml" checked class="accent-emerald-600 h-4 w-4">
                <span>60 mL (1 Month Supply)</span>
              </label>
              <label id="variant-label-360ml" onclick="selectVariant(4999, 7999, '360ml')" class="variant-card cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-full border-2 border-gray-200 bg-white text-gray-700 font-medium text-xs sm:text-sm hover:border-gray-300 transition-all shadow-sm">
                <input type="radio" id="variant-radio-360ml" name="variant" value="360ml" class="accent-emerald-600 h-4 w-4">
                <span>360 mL (6 Months Bundle)</span>
              </label>
            </div>
          </div>

          <div class="pt-2 border-t border-gray-100">
            <div class="text-sm font-bold text-gray-900">
              Total Amount: <span id="total-price-display" class="text-emerald-700 font-extrabold text-xl ml-1">₹1,299</span>
              <span class="text-xs font-normal text-gray-400 ml-1">(Free Shipping & GST Included)</span>
            </div>
          </div>

          <div class="flex items-center gap-3 pt-2">
            <button onclick="buyNow()" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-6 rounded-xl transition-all shadow-md active:scale-95 text-center text-sm cursor-pointer">
              Pay Now & Checkout
            </button>
            <button onclick="toggleWishlist()" id="wishlist-btn" class="inline-flex items-center justify-center gap-1.5 px-4 py-3.5 rounded-xl border border-gray-200 bg-white hover:bg-emerald-50 text-gray-700 font-semibold text-sm transition-all shadow-sm active:scale-95 cursor-pointer">
              <span id="wishlist-icon" class="text-emerald-600">💚</span>
              <span id="wishlist-count">1</span>
            </button>
          </div>
        </div>

      </div>

      <!-- Tab Information -->
      <div id="product-details-container" class="bg-emerald-50/40 rounded-2xl p-6 border border-emerald-100/60 shadow-inner">
        <div class="flex items-center justify-center gap-2 sm:gap-3 mb-6 flex-wrap border-b border-gray-200/60 pb-4">
          <button onclick="switchProductTab('overview')" id="p-tab-overview" class="product-tab-pill bg-emerald-600 text-white font-semibold px-5 py-2 rounded-full text-xs sm:text-sm shadow-sm transition-all cursor-pointer">Overview</button>
          <button onclick="switchProductTab('howtouse')" id="p-tab-howtouse" class="product-tab-pill bg-gray-200/80 text-gray-600 hover:bg-gray-300 font-semibold px-5 py-2 rounded-full text-xs sm:text-sm transition-all cursor-pointer">How to Use</button>
          <button onclick="switchProductTab('ingredients')" id="p-tab-ingredients" class="product-tab-pill bg-gray-200/80 text-gray-600 hover:bg-gray-300 font-semibold px-5 py-2 rounded-full text-xs sm:text-sm transition-all cursor-pointer">Specs & Formula</button>
          <button onclick="switchProductTab('reviews')" id="p-tab-reviews" class="product-tab-pill bg-gray-200/80 text-gray-600 hover:bg-gray-300 font-semibold px-5 py-2 rounded-full text-xs sm:text-sm transition-all cursor-pointer">Reviews</button>
        </div>

        <div id="p-content-overview" class="p-tab-content space-y-4">
          <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight leading-snug">RegrowthX Extra Strength 5% Minoxidil Solution</h2>
          <p class="text-gray-600 text-sm leading-relaxed">
            RegrowthX Hair Growth Serum is a dermatologist-recommended topical solution engineered to reactivate dormant hair follicles and reverse hair thinning. Formulated with 5% Minoxidil USP, it stimulates vertex scalp micro-circulation, ensuring essential nutrients reach hair roots for optimal density.
          </p>
        </div>

        <div id="p-content-howtouse" class="p-tab-content hidden space-y-4">
          <h3 class="text-xl font-bold text-gray-900 mb-1">Simple 4-Step Daily Routine</h3>
          <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-2">
            <div class="bg-white p-4 rounded-xl text-center border border-gray-100 shadow-sm">
              <div class="w-8 h-8 bg-emerald-600 text-white font-bold text-xs rounded-full flex items-center justify-center mx-auto mb-2">1</div>
              <h4 class="font-bold text-gray-900 mb-1 text-xs">Dry Scalp</h4>
              <p class="text-[11px] text-gray-500">Ensure vertex scalp is clean and completely dry.</p>
            </div>
            <div class="bg-white p-4 rounded-xl text-center border border-gray-100 shadow-sm">
              <div class="w-8 h-8 bg-emerald-600 text-white font-bold text-xs rounded-full flex items-center justify-center mx-auto mb-2">2</div>
              <h4 class="font-bold text-gray-900 mb-1 text-xs">Fill 1 mL</h4>
              <p class="text-[11px] text-gray-500">Fill dropper to exact 1.0 mL mark line.</p>
            </div>
            <div class="bg-white p-4 rounded-xl text-center border border-gray-100 shadow-sm">
              <div class="w-8 h-8 bg-emerald-600 text-white font-bold text-xs rounded-full flex items-center justify-center mx-auto mb-2">3</div>
              <h4 class="font-bold text-gray-900 mb-1 text-xs">Apply Directly</h4>
              <p class="text-[11px] text-gray-500">Drop solution onto target thinning areas twice daily.</p>
            </div>
            <div class="bg-white p-4 rounded-xl text-center border border-gray-100 shadow-sm">
              <div class="w-8 h-8 bg-emerald-600 text-white font-bold text-xs rounded-full flex items-center justify-center mx-auto mb-2">4</div>
              <h4 class="font-bold text-gray-900 mb-1 text-xs">Massage & Dry</h4>
              <p class="text-[11px] text-gray-500">Massage gently for 30 seconds. Allow solution to dry completely.</p>
            </div>
          </div>
        </div>

        <div id="p-content-ingredients" class="p-tab-content hidden space-y-4">
          <h3 class="text-xl font-bold text-gray-900 mb-2">Drug Facts & Specifications</h3>
          <div class="overflow-x-auto bg-white rounded-xl border border-gray-100">
            <table class="w-full text-left text-xs text-gray-600 border-collapse">
              <tbody>
                <tr class="border-b border-gray-100">
                  <td class="py-2.5 px-4 font-bold text-gray-900 bg-gray-50 w-1/3">Active Ingredient</td>
                  <td class="py-2.5 px-4">Minoxidil 5% w/v (Extra Strength Hair Regrowth Treatment)</td>
                </tr>
                <tr class="border-b border-gray-100">
                  <td class="py-2.5 px-4 font-bold text-gray-900 bg-gray-50">Inactive Ingredients</td>
                  <td class="py-2.5 px-4">Alcohol, Propylene Glycol, Purified Water</td>
                </tr>
                <tr class="border-b border-gray-100">
                  <td class="py-2.5 px-4 font-bold text-gray-900 bg-gray-50">Volume per Bottle</td>
                  <td class="py-2.5 px-4">60 mL (2 fl oz) — 30 Day Supply</td>
                </tr>
                <tr>
                  <td class="py-2.5 px-4 font-bold text-gray-900 bg-gray-50">Formulation Standards</td>
                  <td class="py-2.5 px-4">Unscented, Non-Greasy, Fast-Drying, Made in USA</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div id="p-content-reviews" class="p-tab-content hidden space-y-4">
          <h3 class="text-xl font-bold text-gray-900">Verified Customer Reviews</h3>
          <div class="space-y-3">
            <div class="p-3.5 bg-white rounded-xl border border-gray-100 shadow-sm">
              <div class="flex items-center justify-between mb-1">
                <span class="font-bold text-gray-900 text-xs">Daniel M.</span>
                <span class="text-[10px] text-emerald-600 font-bold">Verified Purchase ★★★★★</span>
              </div>
              <p class="text-xs text-gray-600">Great results after 2 months of disciplined morning and night routine. Scalp thinning is filling in.</p>
            </div>
            <div class="p-3.5 bg-white rounded-xl border border-gray-100 shadow-sm">
              <div class="flex items-center justify-between mb-1">
                <span class="font-bold text-gray-900 text-xs">Kevin S.</span>
                <span class="text-[10px] text-emerald-600 font-bold">Verified Purchase ★★★★★</span>
              </div>
              <p class="text-xs text-gray-600">Clean formula, no harsh perfume smell. Dries fast before putting on a hat.</p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- CTA SECTION -->
<section class="py-16 px-6 bg-[#f8f7f2]">
  <div class="max-w-7xl mx-auto">
    <div class="bg-[#071A0E] rounded-[30px] px-8 md:px-12 py-10 flex flex-col lg:flex-row items-center justify-between gap-8 shadow-xl">
      <div class="flex-1">
        <h2 class="text-white text-3xl md:text-4xl font-bold leading-tight">Ready To Regrow Your Hair?</h2>
        <p class="text-gray-400 mt-3 text-base">Take control of hair thinning today with <span class="text-white font-medium">RegrowthX 5% Minoxidil Hair Serum.</span></p>
      </div>

      <div class="flex flex-wrap justify-center gap-6 text-sm text-gray-300">
        <div class="flex items-center gap-2"><span>🚚</span> <span>Free US Shipping</span></div>
        <div class="flex items-center gap-2"><span>🛡️</span> <span>30 Day Guarantee</span></div>
        <div class="flex items-center gap-2"><span>🔒</span> <span>Secure Encrypted Checkout</span></div>
      </div>

      <div>
        <a href="#products" class="inline-flex items-center gap-2 bg-[#5E8E2E] hover:bg-[#6FAE35] text-white font-semibold px-8 py-4 rounded-full transition duration-300 hover:scale-105">
          Order Now
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- FREQUENTLY ASKED QUESTIONS SECTION -->
<section class="max-w-4xl mx-auto my-16 px-4 sm:px-6" id="faq">
  <div class="text-center mb-10 space-y-3">
    <span class="inline-block px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-extrabold tracking-wide uppercase">Support & Help Center</span>
    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">Frequently Asked Questions</h2>
    <p class="text-gray-500 text-sm max-w-lg mx-auto">Everything you need to know about application, dosage, shipping, and guarantees.</p>
  </div>

  <div class="space-y-4" id="faq-accordion-group">
    <div class="faq-card bg-white rounded-2xl border border-gray-100 shadow-sm transition-all duration-300 hover:shadow-md overflow-hidden">
      <button class="faq-btn w-full px-6 py-5 flex items-center justify-between text-left focus:outline-none cursor-pointer group" aria-expanded="false" onclick="toggleFaq(this)">
        <span class="text-base sm:text-lg font-bold text-gray-800 group-hover:text-emerald-700 transition-colors pr-4">What active ingredient does RegrowthX use?</span>
        <div class="faq-icon-wrapper w-9 h-9 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 transition-all shrink-0">
          <svg class="faq-icon w-5 h-5 transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </div>
      </button>
      <div class="faq-answer max-h-0 overflow-hidden transition-all duration-300 ease-in-out bg-emerald-50/20">
        <div class="px-6 pb-6 pt-1 text-sm text-gray-600 leading-relaxed border-t border-emerald-100/40">
          RegrowthX contains 5% Minoxidil USP w/v, the FDA-approved dermatologist recommended topical dosage clinically proven to help regrow hair in men experiencing vertex thinning.
        </div>
      </div>
    </div>

    <div class="faq-card bg-white rounded-2xl border border-gray-100 shadow-sm transition-all duration-300 hover:shadow-md overflow-hidden">
      <button class="faq-btn w-full px-6 py-5 flex items-center justify-between text-left focus:outline-none cursor-pointer group" aria-expanded="false" onclick="toggleFaq(this)">
        <span class="text-base sm:text-lg font-bold text-gray-800 group-hover:text-emerald-700 transition-colors pr-4">How often should I apply the solution?</span>
        <div class="faq-icon-wrapper w-9 h-9 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 transition-all shrink-0">
          <svg class="faq-icon w-5 h-5 transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </div>
      </button>
      <div class="faq-answer max-h-0 overflow-hidden transition-all duration-300 ease-in-out bg-emerald-50/20">
        <div class="px-6 pb-6 pt-1 text-sm text-gray-600 leading-relaxed border-t border-emerald-100/40">
          Apply 1 mL with the included dropper twice a day (morning and night) directly onto dry scalp in the hair loss area. Using more or more often will not improve results.
        </div>
      </div>
    </div>

    <div class="faq-card bg-white rounded-2xl border border-gray-100 shadow-sm transition-all duration-300 hover:shadow-md overflow-hidden">
      <button class="faq-btn w-full px-6 py-5 flex items-center justify-between text-left focus:outline-none cursor-pointer group" aria-expanded="false" onclick="toggleFaq(this)">
        <span class="text-base sm:text-lg font-bold text-gray-800 group-hover:text-emerald-700 transition-colors pr-4">How long does shipping take?</span>
        <div class="faq-icon-wrapper w-9 h-9 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 transition-all shrink-0">
          <svg class="faq-icon w-5 h-5 transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </div>
      </button>
      <div class="faq-answer max-h-0 overflow-hidden transition-all duration-300 ease-in-out bg-emerald-50/20">
        <div class="px-6 pb-6 pt-1 text-sm text-gray-600 leading-relaxed border-t border-emerald-100/40">
          Standard domestic shipping takes 3-5 business days with full tracking included. Express 2-day shipping options are available at checkout.
        </div>
      </div>
    </div>

    <div class="faq-card bg-white rounded-2xl border border-gray-100 shadow-sm transition-all duration-300 hover:shadow-md overflow-hidden">
      <button class="faq-btn w-full px-6 py-5 flex items-center justify-between text-left focus:outline-none cursor-pointer group" aria-expanded="false" onclick="toggleFaq(this)">
        <span class="text-base sm:text-lg font-bold text-gray-800 group-hover:text-emerald-700 transition-colors pr-4">What is your 30-day return policy?</span>
        <div class="faq-icon-wrapper w-9 h-9 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 transition-all shrink-0">
          <svg class="faq-icon w-5 h-5 transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </div>
      </button>
      <div class="faq-answer max-h-0 overflow-hidden transition-all duration-300 ease-in-out bg-emerald-50/20">
        <div class="px-6 pb-6 pt-1 text-sm text-gray-600 leading-relaxed border-t border-emerald-100/40">
          We stand by our product with a 30-day money-back guarantee. If you are not satisfied, simply contact customer support for a quick refund or replacement.
        </div>
      </div>
    </div>
  </div>

  <div class="mt-10 p-6 bg-[#071a0e] rounded-2xl text-center text-white flex flex-col sm:flex-row items-center justify-between gap-4 shadow-lg" id="contact">
    <div class="text-left">
      <h4 class="font-bold text-base">Have more questions?</h4>
      <p class="text-xs text-emerald-200/80">Our hair health team is here to support your routine.</p>
    </div>
    <a href="mailto:rickw@nimexgrp.com" class="bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs py-3 px-6 rounded-full transition-all whitespace-nowrap shadow-md hover:scale-105">
      Contact Support
    </a>
  </div>
</section>

</main>

<?php require_once __DIR__ . '/includes/store-footer.php'; ?>

<!-- JAVASCRIPT INTERACTIVITY -->
<script>
  /* ===== GLOBAL STATE ===== */
  let currentUnitPrice = 1299;
  let currentQty = 1;
  let currentGalleryList = [];
  let currentUser = null;
  let currentAuthEmail = '';

  const productGalleries = {
    '60ml': ['img/product-box-bottle.jpg','img/product-dropper.jpg','img/routine-guide.jpg','img/scalp-application.jpg'],
    '360ml': ['img/product-box-bottle.jpg','img/product-dropper.jpg','img/timeline-results.jpg','img/routine-guide.jpg']
  };

  /* ===== UTILITY: Format INR ===== */
  function formatINR(amount) {
    return '₹' + Number(amount).toLocaleString('en-IN');
  }

  /* ===== TOAST NOTIFICATIONS ===== */
  function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    const colors = {
      success: 'bg-emerald-600',
      error: 'bg-red-600',
      info: 'bg-blue-600'
    };
    const icons = {
      success: 'check_circle',
      error: 'error',
      info: 'info'
    };
    toast.className = `toast ${colors[type]} text-white px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-2 text-sm font-semibold mb-2 min-w-[250px]`;
    toast.innerHTML = `<span class="material-symbols-outlined text-lg">${icons[type]}</span> ${message}`;
    container.appendChild(toast);
    requestAnimationFrame(() => { requestAnimationFrame(() => { toast.classList.add('show'); }); });
    setTimeout(() => {
      toast.classList.remove('show');
      setTimeout(() => toast.remove(), 400);
    }, 3000);
  }

  /* ===== AUTH MODAL ===== */
  function openAuthModal() {
    document.getElementById('auth-modal').classList.add('open');
    document.body.style.overflow = 'hidden';
    document.getElementById('auth-email-input').focus();
  }

  function closeAuthModal() {
    document.getElementById('auth-modal').classList.remove('open');
    document.body.style.overflow = '';
    switchAuthView('login');
  }

  function switchAuthView(view) {
    document.getElementById('auth-step-login').classList.add('hidden');
    document.getElementById('auth-step-signup').classList.add('hidden');
    document.getElementById('auth-step-forgot').classList.add('hidden');
    document.getElementById('auth-step-reset').classList.add('hidden');

    document.getElementById('auth-step-' + view).classList.remove('hidden');

    const titleEl = document.getElementById('auth-modal-title');
    const subtitleEl = document.getElementById('auth-modal-subtitle');
    
    if (view === 'login') {
      titleEl.innerText = 'Welcome Back';
      subtitleEl.innerText = 'Login to your account';
    } else if (view === 'signup') {
      titleEl.innerText = 'Create Account';
      subtitleEl.innerText = 'Join RegrowthX today';
    } else if (view === 'forgot') {
      titleEl.innerText = 'Reset Password';
      subtitleEl.innerText = 'We will send a reset code to your email';
    } else if (view === 'reset') {
      titleEl.innerText = 'Set New Password';
      subtitleEl.innerText = 'Enter the code and your new password';
    }
    
    // Hide all errors
    document.getElementById('login-error').classList.add('hidden');
    document.getElementById('signup-error').classList.add('hidden');
    document.getElementById('forgot-error').classList.add('hidden');
    document.getElementById('reset-error').classList.add('hidden');
  }

  async function handleLogin() {
    const email = document.getElementById('login-email').value.trim();
    const password = document.getElementById('login-password').value;
    const errorEl = document.getElementById('login-error');
    const btn = document.getElementById('login-btn');

    if (!email || !password) {
      errorEl.innerText = 'Please enter both email and password';
      errorEl.classList.remove('hidden');
      return;
    }
    errorEl.classList.add('hidden');
    btn.disabled = true;
    btn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Logging in...';

    try {
      const res = await fetch('api/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
      });
      const data = await res.json();

      if (data.success) {
        if (data.user.role === 'admin') {
            window.location.href = 'admin/index.php';
            return;
        }
        currentUser = data.user;
        updateAuthUI(true);
        closeAuthModal();
        showToast('Logged in successfully!', 'success');
        if (window._pendingCheckout) {
          window._pendingCheckout = false;
          window.location.href = 'checkout.php';
        }
      } else {
        errorEl.innerText = data.message;
        errorEl.classList.remove('hidden');
      }
    } catch (err) {
      errorEl.innerText = 'Network error. Please try again.';
      errorEl.classList.remove('hidden');
    }
    btn.disabled = false;
    btn.innerHTML = 'Login';
  }

  async function handleSignup() {
    const name = document.getElementById('signup-name').value.trim();
    const email = document.getElementById('signup-email').value.trim();
    const phone = document.getElementById('signup-phone').value.trim();
    const password = document.getElementById('signup-password').value;
    const confirmPassword = document.getElementById('signup-confirm-password').value;
    const errorEl = document.getElementById('signup-error');
    const btn = document.getElementById('signup-btn');

    if (!name || !email || !phone || !password || !confirmPassword) {
      errorEl.innerText = 'All fields are required';
      errorEl.classList.remove('hidden');
      return;
    }
    
    if (password !== confirmPassword) {
      errorEl.innerText = 'Passwords do not match';
      errorEl.classList.remove('hidden');
      return;
    }

    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    if (!passwordPattern.test(password)) {
      errorEl.innerText = 'Password does not meet the strict security requirements';
      errorEl.classList.remove('hidden');
      return;
    }

    errorEl.classList.add('hidden');
    btn.disabled = true;
    btn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Creating Account...';

    try {
      const res = await fetch('api/register.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ full_name: name, email, phone, password })
      });
      const data = await res.json();

      if (data.success) {
        currentUser = data.user;
        updateAuthUI(true);
        closeAuthModal();
        showToast('Account created successfully!', 'success');
        if (window._pendingCheckout) {
          window._pendingCheckout = false;
          window.location.href = 'checkout.php';
        }
      } else {
        errorEl.innerText = data.message;
        errorEl.classList.remove('hidden');
      }
    } catch (err) {
      errorEl.innerText = 'Network error. Please try again.';
      errorEl.classList.remove('hidden');
    }
    btn.disabled = false;
    btn.innerHTML = 'Create Account';
  }

  let resetEmailTarget = '';
  async function handleForgotPassword() {
    const email = document.getElementById('forgot-email').value.trim();
    const errorEl = document.getElementById('forgot-error');
    const btn = document.getElementById('forgot-btn');

    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      errorEl.innerText = 'Please enter a valid email address';
      errorEl.classList.remove('hidden');
      return;
    }
    errorEl.classList.add('hidden');
    btn.disabled = true;
    btn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Sending...';

    try {
      const res = await fetch('api/forgot-password.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email })
      });
      const data = await res.json();

      if (data.success) {
        resetEmailTarget = email;
        switchAuthView('reset');
        showToast('Reset code sent to your email', 'success');
        
        // Check for dev mode message
        if (data.message.includes('123456')) {
            showToast(data.message, 'success');
        }
      } else {
        errorEl.innerText = data.message;
        errorEl.classList.remove('hidden');
      }
    } catch (err) {
      errorEl.innerText = 'Network error. Please try again.';
      errorEl.classList.remove('hidden');
    }
    btn.disabled = false;
    btn.innerHTML = 'Send Reset Code';
  }

  async function handleResetPassword() {
    const code = document.getElementById('reset-code').value.trim();
    const password = document.getElementById('reset-password').value;
    const errorEl = document.getElementById('reset-error');
    const btn = document.getElementById('reset-btn');

    if (!code || !password) {
      errorEl.innerText = 'Please enter the code and a new password';
      errorEl.classList.remove('hidden');
      return;
    }
    errorEl.classList.add('hidden');
    btn.disabled = true;
    btn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Saving...';

    try {
      const res = await fetch('api/reset-password.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email: resetEmailTarget, code, password })
      });
      const data = await res.json();

      if (data.success) {
        switchAuthView('login');
        showToast('Password reset successfully! Please log in.', 'success');
        document.getElementById('login-email').value = resetEmailTarget;
      } else {
        errorEl.innerText = data.message;
        errorEl.classList.remove('hidden');
      }
    } catch (err) {
      errorEl.innerText = 'Network error. Please try again.';
      errorEl.classList.remove('hidden');
    }
    btn.disabled = false;
    btn.innerHTML = 'Save New Password';
  }

  function updateAuthUI(loggedIn) {
    const loginBtn = document.getElementById('nav-login-btn');
    const userInfo = document.getElementById('nav-user-info');
    const mobileLoginBtn = document.getElementById('mobile-login-btn');

    if (loggedIn && currentUser) {
      loginBtn.style.display = 'none';
      userInfo.classList.remove('hidden');
      userInfo.classList.add('flex');
      const initial = (currentUser.name || currentUser.email || 'U').charAt(0).toUpperCase();
      document.getElementById('nav-user-avatar').innerText = initial;
      document.getElementById('nav-user-name').innerText = currentUser.name || currentUser.email.split('@')[0];
      document.getElementById('dropdown-email').innerText = currentUser.email;
      if (mobileLoginBtn) mobileLoginBtn.classList.add('hidden');
    } else {
      loginBtn.style.display = '';
      userInfo.classList.add('hidden');
      userInfo.classList.remove('flex');
      if (mobileLoginBtn) mobileLoginBtn.classList.remove('hidden');
    }
  }

  function toggleUserMenu() {
    document.getElementById('user-dropdown').classList.toggle('hidden');
  }

  // Close dropdown on outside click
  document.addEventListener('click', (e) => {
    const dropdown = document.getElementById('user-dropdown');
    const container = document.getElementById('nav-user-info');
    if (dropdown && container && !container.contains(e.target)) {
      dropdown.classList.add('hidden');
    }
  });

  async function handleLogout() {
    try {
      await fetch('api/logout.php', { method: 'POST' });
    } catch(e) {}
    currentUser = null;
    updateAuthUI(false);
    document.getElementById('user-dropdown').classList.add('hidden');
    showToast('Logged out successfully', 'info');
  }

  async function checkAuthState() {
    try {
      const res = await fetch('api/get-user.php');
      const data = await res.json();
      if (data.logged_in) {
        currentUser = data.user;
        updateAuthUI(true);
      }
    } catch(e) {}
  }

  /* ===== MOBILE MENU ===== */
  function toggleMobileMenu() {
    document.getElementById('mobile-menu').classList.toggle('hidden');
  }

  /* ===== CART DRAWER ===== */
  function toggleCartDrawer() {
    const drawer = document.getElementById('cart-drawer');
    const overlay = document.getElementById('cart-overlay');
    if (drawer.classList.contains('open')) {
      closeCartDrawer();
    } else {
      openCartDrawer();
    }
  }

  function openCartDrawer() {
    document.getElementById('cart-drawer').classList.add('open');
    document.getElementById('cart-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
    refreshCartUI();
  }

  function closeCartDrawer() {
    document.getElementById('cart-drawer').classList.remove('open');
    document.getElementById('cart-overlay').classList.remove('open');
    document.body.style.overflow = '';
  }

  async function addToCartAPI(variantId, quantity) {
    try {
      const res = await fetch('api/cart-add.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ variant_id: variantId, quantity: quantity || 1 })
      });
      const data = await res.json();
      if (data.success) {
        updateCartBadge(data.item_count);
        showToast('Added to cart! ✓', 'success');
        openCartDrawer();
      } else {
        showToast(data.message || 'Failed to add to cart', 'error');
      }
      return data;
    } catch (err) {
      showToast('Network error', 'error');
      return null;
    }
  }

  async function updateCartItemAPI(variantId, quantity) {
    try {
      const res = await fetch('api/cart-update.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ variant_id: variantId, quantity })
      });
      const data = await res.json();
      if (data.success) {
        updateCartBadge(data.item_count);
        renderCartItems(data.cart, data.cart_total, data.item_count);
      }
      return data;
    } catch (err) {
      showToast('Network error', 'error');
      return null;
    }
  }

  async function removeCartItemAPI(variantId) {
    try {
      const res = await fetch('api/cart-remove.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ variant_id: variantId })
      });
      const data = await res.json();
      if (data.success) {
        updateCartBadge(data.item_count);
        renderCartItems(data.cart, data.cart_total, data.item_count);
        showToast('Item removed', 'info');
      }
      return data;
    } catch (err) {
      showToast('Network error', 'error');
      return null;
    }
  }

  async function refreshCartUI() {
    try {
      const res = await fetch('api/cart-get.php');
      const data = await res.json();
      if (data.success) {
        updateCartBadge(data.item_count);
        renderCartItems(data.cart, data.cart_total, data.item_count);
      }
    } catch (err) {}
  }

  function updateCartBadge(count) {
    const badge = document.getElementById('cart-badge-count');
    if (count > 0) {
      badge.innerText = count;
      badge.classList.remove('hidden');
    } else {
      badge.classList.add('hidden');
    }
  }

  function renderCartItems(cart, cartTotal, itemCount) {
    const listEl = document.getElementById('cart-items-list');
    const emptyEl = document.getElementById('cart-empty-state');
    const footerEl = document.getElementById('cart-footer');
    const countEl = document.getElementById('drawer-item-count');

    countEl.innerText = itemCount + (itemCount === 1 ? ' item' : ' items');

    if (!cart || cart.length === 0) {
      listEl.classList.add('hidden');
      listEl.innerHTML = '';
      emptyEl.classList.remove('hidden');
      footerEl.classList.add('hidden');
      return;
    }

    emptyEl.classList.add('hidden');
    listEl.classList.remove('hidden');
    footerEl.classList.remove('hidden');
    footerEl.classList.add('flex', 'flex-col');

    let html = '';
    cart.forEach(item => {
      const itemName = item.title || item.item_name || 'RegrowthX Minoxidil 5%';
      const unitPrice = item.price_inr || item.unit_price || 0;
      const totalPrice = item.total_price || (unitPrice * item.quantity);
      const imagePath = item.image_path || 'img/product-box-bottle.jpg';
      const variantName = item.variant_name || '';

      html += `
        <div class="flex gap-4 p-3 bg-gray-50 rounded-2xl border border-gray-100 hover:shadow-sm transition-shadow" data-variant-id="${item.variant_id}">
          <div class="w-20 h-20 rounded-xl bg-[#0d1e12] flex items-center justify-center shrink-0 overflow-hidden">
            <img src="${imagePath}" alt="${variantName}" class="max-h-full max-w-full object-contain rounded-lg">
          </div>
          <div class="flex-1 min-w-0">
            <h4 class="text-sm font-bold text-gray-900 truncate">${itemName}</h4>
            <p class="text-xs text-gray-500 mt-0.5">${variantName}</p>
            <div class="flex items-center justify-between mt-2">
              <div class="inline-flex items-center border border-gray-200 rounded-lg bg-white">
                <button onclick="updateCartItemAPI(${item.variant_id}, ${item.quantity - 1})" class="w-7 h-7 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-l-lg text-sm font-bold cursor-pointer">−</button>
                <span class="w-8 text-center text-sm font-bold text-gray-900">${item.quantity}</span>
                <button onclick="updateCartItemAPI(${item.variant_id}, ${item.quantity + 1})" class="w-7 h-7 flex items-center justify-center text-gray-600 hover:bg-gray-100 rounded-r-lg text-sm font-bold cursor-pointer">+</button>
              </div>
              <div class="text-right">
                <p class="text-sm font-bold text-gray-900">${formatINR(totalPrice)}</p>
                ${item.quantity > 1 ? `<p class="text-[10px] text-gray-400">${formatINR(unitPrice)} each</p>` : ''}
              </div>
            </div>
          </div>
          <button onclick="removeCartItemAPI(${item.variant_id})" class="self-start p-1 text-gray-400 hover:text-red-500 transition-colors cursor-pointer" title="Remove">
            <span class="material-symbols-outlined text-lg">close</span>
          </button>
        </div>
      `;
    });
    listEl.innerHTML = html;

    document.getElementById('cart-subtotal').innerText = formatINR(cartTotal);
    document.getElementById('cart-total').innerText = formatINR(cartTotal);
  }

  function proceedToCheckout() {
    closeCartDrawer();
    if (!currentUser) {
      window._pendingCheckout = true;
      openAuthModal();
      showToast('Please login to continue checkout', 'info');
    } else {
      window.location.href = 'checkout.php';
    }
  }

  /* ===== PRODUCT MODAL (Quick View) ===== */
  function handleAddToCart(variant) {
    const modal = document.getElementById('product-modal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    currentQty = 1;
    document.getElementById('product-qty').innerText = currentQty;

    if (variant === '60ml') {
      selectVariant(1299, 2499, '60ml');
    } else if (variant === '360ml') {
      selectVariant(4999, 7999, '360ml');
    }
  }

  function closeModal() {
    const modal = document.getElementById('product-modal');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
  }

  document.getElementById('product-modal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
  });

  function selectVariant(price, originalPrice, variantKey) {
    currentUnitPrice = price;
    document.getElementById('current-price').innerText = formatINR(price);
    document.getElementById('original-price').innerText = formatINR(originalPrice);

    currentGalleryList = productGalleries[variantKey] || productGalleries['60ml'];
    for (let i = 0; i < 4; i++) {
      const thumbEl = document.getElementById(`thumb-img-${i}`);
      if (thumbEl && currentGalleryList[i]) {
        thumbEl.src = currentGalleryList[i];
      }
    }
    changeGalleryImage(0);

    const modalTitle = document.getElementById('modal-product-title');
    modalTitle.innerText = variantKey === '60ml' ? "Minoxidil 5% (1 Month Supply)" : "Minoxidil 5% (6 Month Bundle)";

    const label60 = document.getElementById('variant-label-60ml');
    const label360 = document.getElementById('variant-label-360ml');
    const radio60 = document.getElementById('variant-radio-60ml');
    const radio360 = document.getElementById('variant-radio-360ml');

    if (variantKey === '60ml') {
      radio60.checked = true;
      label60.className = "variant-card cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-full border-2 border-emerald-600 bg-emerald-50/30 text-gray-900 font-medium text-xs sm:text-sm transition-all shadow-sm";
      label360.className = "variant-card cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-full border-2 border-gray-200 bg-white text-gray-700 font-medium text-xs sm:text-sm hover:border-gray-300 transition-all shadow-sm";
    } else {
      radio360.checked = true;
      label360.className = "variant-card cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-full border-2 border-emerald-600 bg-emerald-50/30 text-gray-900 font-medium text-xs sm:text-sm transition-all shadow-sm";
      label60.className = "variant-card cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-full border-2 border-gray-200 bg-white text-gray-700 font-medium text-xs sm:text-sm hover:border-gray-300 transition-all shadow-sm";
    }

    recalculateTotal();
  }

  function changeGalleryImage(index) {
    if (!currentGalleryList[index]) return;
    document.getElementById('main-gallery-img').src = currentGalleryList[index];

    const thumbBtns = document.querySelectorAll('.thumb-btn');
    thumbBtns.forEach((btn, idx) => {
      if (idx === index) {
        btn.className = "thumb-btn border-2 border-emerald-600 rounded-xl bg-[#0d1e12] p-1 h-16 flex items-center justify-center transition-all overflow-hidden focus:outline-none";
      } else {
        btn.className = "thumb-btn border-2 border-gray-200 hover:border-emerald-300 rounded-xl bg-[#0d1e12] p-1 h-16 flex items-center justify-center transition-all overflow-hidden focus:outline-none";
      }
    });
  }

  function updateQty(amount) {
    currentQty += amount;
    if (currentQty < 1) currentQty = 1;
    document.getElementById('product-qty').innerText = currentQty;
    recalculateTotal();
  }

  function recalculateTotal() {
    const total = currentUnitPrice * currentQty;
    document.getElementById('total-price-display').innerText = formatINR(total);
  }

  function switchProductTab(tabName) {
    const tabs = ['overview', 'howtouse', 'ingredients', 'reviews'];
    tabs.forEach(tab => {
      const content = document.getElementById(`p-content-${tab}`);
      const btn = document.getElementById(`p-tab-${tab}`);
      
      if (tab === tabName) {
        content.classList.remove('hidden');
        btn.className = "product-tab-pill bg-emerald-600 text-white font-semibold px-5 py-2 rounded-full text-xs sm:text-sm shadow-sm transition-all cursor-pointer";
      } else {
        content.classList.add('hidden');
        btn.className = "product-tab-pill bg-gray-200/80 text-gray-600 hover:bg-gray-300 font-semibold px-5 py-2 rounded-full text-xs sm:text-sm transition-all cursor-pointer";
      }
    });
  }

  function buyNow() {
    // Add to cart via API, then redirect to checkout
    const variantId = document.getElementById('variant-radio-60ml').checked ? 1 : 2;
    closeModal();
    addToCartAPI(variantId, currentQty).then(() => {
      proceedToCheckout();
    });
  }

  function toggleWishlist() {
    const countEl = document.getElementById('wishlist-count');
    let current = parseInt(countEl.innerText);
    countEl.innerText = current === 1 ? 0 : 1;
  }

  /* ===== FAQ ACCORDION ===== */
  function toggleFaq(button) {
    const card = button.closest('.faq-card');
    const answer = card.querySelector('.faq-answer');
    const icon = card.querySelector('.faq-icon');
    const iconWrapper = card.querySelector('.faq-icon-wrapper');
    const isExpanded = button.getAttribute('aria-expanded') === 'true';

    document.querySelectorAll('.faq-card').forEach(otherCard => {
      if (otherCard !== card) {
        const otherBtn = otherCard.querySelector('.faq-btn');
        const otherAnswer = otherCard.querySelector('.faq-answer');
        const otherIcon = otherCard.querySelector('.faq-icon');
        const otherWrapper = otherCard.querySelector('.faq-icon-wrapper');
        
        if (otherBtn) otherBtn.setAttribute('aria-expanded', 'false');
        if (otherAnswer) otherAnswer.style.maxHeight = '0px';
        if (otherIcon) otherIcon.style.transform = 'rotate(0deg)';
        if (otherWrapper) {
          otherWrapper.classList.remove('bg-emerald-600', 'text-white');
          otherWrapper.classList.add('bg-emerald-50', 'text-emerald-600');
        }
        otherCard.classList.remove('border-emerald-500', 'ring-2', 'ring-emerald-500/20');
      }
    });

    if (isExpanded) {
      button.setAttribute('aria-expanded', 'false');
      answer.style.maxHeight = '0px';
      icon.style.transform = 'rotate(0deg)';
      iconWrapper.classList.remove('bg-emerald-600', 'text-white');
      iconWrapper.classList.add('bg-emerald-50', 'text-emerald-600');
      card.classList.remove('border-emerald-500', 'ring-2', 'ring-emerald-500/20');
    } else {
      button.setAttribute('aria-expanded', 'true');
      answer.style.maxHeight = answer.scrollHeight + 'px';
      icon.style.transform = 'rotate(180deg)';
      iconWrapper.classList.remove('bg-emerald-50', 'text-emerald-600');
      iconWrapper.classList.add('bg-emerald-600', 'text-white');
      card.classList.add('border-emerald-500', 'ring-2', 'ring-emerald-500/20');
    }
  }

  /* ===== SCROLL OBSERVER ===== */
  document.addEventListener('DOMContentLoaded', () => {
    const observerOptions = {
      root: null,
      rootMargin: '0px',
      threshold: 0.15
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
        }
      });
    }, observerOptions);

    const revealElements = document.querySelectorAll('.reveal-on-scroll');
    revealElements.forEach(el => observer.observe(el));

    const slider = document.getElementById('ba-range-slider');
    if (slider) updateBaSlider(slider.value);

    // Check auth state on load
    checkAuthState();

    // Refresh cart badge on load
    refreshCartUI();
  });
</script>

<?php if (isset($_SESSION['admin_error'])): ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        showToast("<?= htmlspecialchars($_SESSION['admin_error']) ?>", "error");
    });
</script>
<?php unset($_SESSION['admin_error']); endif; ?>
</body>
</html>
