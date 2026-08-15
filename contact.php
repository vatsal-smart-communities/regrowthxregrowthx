<?php 
$pageTitle = "Contact Us - RegrowthX Customer Support & Care";
require_once __DIR__ . '/includes/store-header.php'; 
?>

<!-- MAIN CONTENT -->
<main class="pt-20">

  <!-- Hero Banner -->
  <section class="hero-gradient text-white py-16 lg:py-20 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
      <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full glass-pill text-xs font-semibold uppercase tracking-wider mb-6 text-emerald-300">
        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
        Customer Support & Care Center
      </div>
      <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-4 leading-tight">
        We're Here To Help Your Journey
      </h1>
      <p class="text-gray-300 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
        Have questions about RegrowthX 5% Minoxidil, product usage, or hair regrowth guidance? Get in touch with our team.
      </p>
    </div>
  </section>

  <!-- Contact Form & Info Grid -->
  <section class="py-16 bg-brand-bg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid lg:grid-cols-12 gap-12 items-start">
        
        <!-- Left: Contact Form -->
        <div class="lg:col-span-6 bg-white p-8 sm:p-10 rounded-3xl shadow-sm border border-gray-100">
          <div class="mb-8">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Send Us A Message</h2>
            <p class="text-gray-500 text-sm mt-2">Fill out the form below. Our support team typically responds within 2 business hours.</p>
          </div>

          <form id="contact-form" onsubmit="handleContactSubmit(event)" class="space-y-6">
            <div>
              <label for="full-name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Full Name *</label>
              <input type="text" id="full-name" required placeholder="e.g. John Doe" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 text-sm outline-none transition-all" />
            </div>

            <div>
              <label for="email-address" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Email Address *</label>
              <input type="email" id="email-address" required placeholder="john@example.com" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 text-sm outline-none transition-all" />
            </div>

            <div>
              <label for="message" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Your Message *</label>
              <textarea id="message" rows="5" required placeholder="How can we assist your hair growth routine today?" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 text-sm outline-none transition-all resize-none"></textarea>
            </div>

            <button type="submit" id="submit-btn" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-xl transition-all shadow-md hover:shadow-lg active:scale-95 flex items-center justify-center gap-2 text-base cursor-pointer">
              <span>Send Message</span>
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3 21l18-9L3 3l3 9zm0 0h75"/></svg>
            </button>
          </form>

          <!-- Success Alert Box -->
          <div id="success-message" class="hidden mt-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm flex items-center gap-3">
            <span class="material-symbols-outlined text-emerald-600">check_circle</span>
            <div>
              <strong class="font-bold">Thank you for contacting RegrowthX!</strong>
              <p class="text-xs text-emerald-800">Your message has been sent successfully. Our team will get back to you shortly.</p>
            </div>
          </div>
        </div>

        <!-- Right: Support Info Cards & Location Map -->
        <div class="lg:col-span-6 space-y-6">
          
          <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-gray-100 space-y-6">
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-2xl">call</span>
              </div>
              <div>
                <h4 class="font-bold text-gray-900 text-base">Phone Support</h4>
                <p class="text-sm font-semibold text-emerald-700 mt-0.5"><a href="tel:7184387400" class="hover:underline">(718) 438-7400</a></p>
                <p class="text-xs text-gray-500 mt-1">Monday – Saturday: 9:00 AM – 6:00 PM EST</p>
              </div>
            </div>

            <div class="border-t border-gray-100 pt-6 flex items-start gap-4">
              <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-2xl">mail</span>
              </div>
              <div>
                <h4 class="font-bold text-gray-900 text-base">Direct Business Email</h4>
                <p class="text-sm font-semibold text-emerald-700 mt-0.5"><a href="mailto:rickw@nimexgrp.com" class="hover:underline">rickw@nimexgrp.com</a></p>
                <p class="text-xs text-gray-500 mt-1">Average response time: &lt; 2 hours</p>
              </div>
            </div>

            <div class="border-t border-gray-100 pt-6 flex items-start gap-4">
              <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-2xl">location_on</span>
              </div>
              <div>
                <h4 class="font-bold text-gray-900 text-base">RegrowthX HQ (Nimex Group)</h4>
                <p class="text-xs text-gray-600 leading-relaxed mt-1">
                  Nimex Group / RegrowthX USA Labs<br/>
                  United States
                </p>
              </div>
            </div>
          </div>

          <div class="bg-white p-4 rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between px-3 py-2 mb-2">
              <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600">map</span>
                <span class="text-xs font-bold text-gray-900 uppercase tracking-wider">Our Location</span>
              </div>
              <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full">US HQ</span>
            </div>
            <div class="w-full h-64 sm:h-72 rounded-2xl overflow-hidden border border-gray-100 shadow-inner">
              <iframe 
                title="RegrowthX Headquarters Location Map"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d241317.11609823315!2d72.7410999570966!3d19.08219783958742!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7c6306644edc1%3A0x5da4ed8f8d648c69!2sMumbai%2C%20Maharashtra!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin" 
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
              </iframe>
            </div>
          </div>

        </div>

      </div>
    </div>
  </section>

</main>

<script>
  function handleContactSubmit(event) {
    event.preventDefault();
    const btn = document.getElementById('submit-btn');
    const msg = document.getElementById('success-message');
    
    btn.disabled = true;
    btn.innerHTML = `<span>Sending...</span>`;

    setTimeout(() => {
      btn.disabled = false;
      btn.innerHTML = `<span>Send Message</span> <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3 21l18-9L3 3l3 9zm0 0h75"/></svg>`;
      msg.classList.remove('hidden');
      document.getElementById('contact-form').reset();
      showToast('Thank you! Message sent.', 'success');
      setTimeout(() => { msg.classList.add('hidden'); }, 6000);
    }, 1000);
  }
</script>

<?php require_once __DIR__ . '/includes/store-footer.php'; ?>
