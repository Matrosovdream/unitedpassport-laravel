<template>
    <!-- Hero -->
    <section class="relative overflow-hidden bg-gradient-to-br from-white to-section-gray">
        <div class="max-w-container mx-auto px-6 md:px-12 xl:px-[120px] py-20 md:py-32">
            <div class="max-w-2xl">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight text-navy">
                    Travel the world with
                    <span class="bg-gradient-to-r from-brand-green to-brand-teal bg-clip-text text-transparent">confidence</span>
                </h1>
                <p class="mt-6 text-lg text-navy-light leading-relaxed">
                    Get your visa and travel documents processed quickly and securely. We handle the paperwork so you can focus on your journey.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <router-link
                        to="/register"
                        class="inline-flex items-center px-8 py-3 rounded-full text-white font-semibold bg-gradient-to-r from-brand-green to-brand-teal hover:opacity-90 transition shadow-lg"
                    >
                        Get Started
                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </router-link>
                    <router-link
                        to="/login"
                        class="inline-flex items-center px-8 py-3 rounded-full font-semibold text-navy border border-border-gray hover:border-navy transition"
                    >
                        Sign in
                    </router-link>
                </div>
            </div>
        </div>
        <!-- Decorative blob -->
        <div class="absolute -top-40 -right-40 w-[500px] h-[500px] bg-gradient-to-br from-brand-green/20 to-brand-teal/20 rounded-full blur-3xl pointer-events-none"></div>
    </section>

    <!-- Trust badges -->
    <section class="bg-white border-y border-border-gray">
        <div class="max-w-container mx-auto px-6 md:px-12 xl:px-[120px] py-8">
            <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16 text-navy-light text-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-green" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>256-bit Encrypted</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-green" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>50,000+ Customers</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-green" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>24/7 Support</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-green" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>Trusted Worldwide</span>
                </div>
            </div>
        </div>
    </section>

    <!-- How it works -->
    <section class="bg-white">
        <div class="max-w-container mx-auto px-6 md:px-12 xl:px-[120px] py-20">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-navy">How it works</h2>
            <p class="mt-4 text-center text-navy-light max-w-xl mx-auto">
                Get your travel documents in three simple steps.
            </p>

            <div class="mt-14 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-card shadow-card p-8 text-center">
                    <div class="w-14 h-14 mx-auto rounded-full bg-gradient-to-r from-brand-green/20 to-brand-teal/20 flex items-center justify-center">
                        <span class="text-2xl font-bold bg-gradient-to-r from-brand-green to-brand-teal bg-clip-text text-transparent">1</span>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-navy">Fill out the form</h3>
                    <p class="mt-2 text-sm text-navy-light">Complete your application with your travel details and personal information.</p>
                </div>

                <div class="bg-white rounded-card shadow-card p-8 text-center">
                    <div class="w-14 h-14 mx-auto rounded-full bg-gradient-to-r from-brand-green/20 to-brand-teal/20 flex items-center justify-center">
                        <span class="text-2xl font-bold bg-gradient-to-r from-brand-green to-brand-teal bg-clip-text text-transparent">2</span>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-navy">We process it</h3>
                    <p class="mt-2 text-sm text-navy-light">Our experts review and submit your application to the relevant authorities.</p>
                </div>

                <div class="bg-white rounded-card shadow-card p-8 text-center">
                    <div class="w-14 h-14 mx-auto rounded-full bg-gradient-to-r from-brand-green/20 to-brand-teal/20 flex items-center justify-center">
                        <span class="text-2xl font-bold bg-gradient-to-r from-brand-green to-brand-teal bg-clip-text text-transparent">3</span>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-navy">Receive your visa</h3>
                    <p class="mt-2 text-sm text-navy-light">Get your approved documents delivered to your inbox, ready to travel.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Processing tiers -->
    <section class="bg-section-gray">
        <div class="max-w-container mx-auto px-6 md:px-12 xl:px-[120px] py-20">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-navy">Choose your processing speed</h2>
            <p class="mt-4 text-center text-navy-light max-w-xl mx-auto">
                Select the option that fits your timeline.
            </p>

            <div class="mt-14 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-card shadow-card p-8">
                    <h3 class="text-lg font-semibold text-navy">Standard</h3>
                    <p class="mt-1 text-sm text-navy-light">5-7 business days</p>
                    <div class="mt-4 text-3xl font-bold text-navy">$49</div>
                    <ul class="mt-6 space-y-3 text-sm text-navy-light">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-green flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Full application review
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-green flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Email support
                        </li>
                    </ul>
                    <button class="mt-8 w-full py-3 rounded-full border border-border-gray font-semibold text-navy hover:border-navy transition">
                        Select Standard
                    </button>
                </div>

                <div class="bg-white rounded-card shadow-card p-8 ring-2 ring-brand-teal relative">
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-gradient-to-r from-brand-green to-brand-teal text-white text-xs font-semibold px-4 py-1 rounded-full">
                        Popular
                    </span>
                    <h3 class="text-lg font-semibold text-navy">Rush</h3>
                    <p class="mt-1 text-sm text-navy-light">2-3 business days</p>
                    <div class="mt-4 text-3xl font-bold text-navy">$79</div>
                    <ul class="mt-6 space-y-3 text-sm text-navy-light">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-green flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Priority processing
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-green flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Chat & email support
                        </li>
                    </ul>
                    <button class="mt-8 w-full py-3 rounded-full bg-gradient-to-r from-brand-green to-brand-teal text-white font-semibold hover:opacity-90 transition">
                        Select Rush
                    </button>
                </div>

                <div class="bg-white rounded-card shadow-card p-8">
                    <h3 class="text-lg font-semibold text-navy">Super Rush</h3>
                    <p class="mt-1 text-sm text-navy-light">24 hours or less</p>
                    <div class="mt-4 text-3xl font-bold text-navy">$129</div>
                    <ul class="mt-6 space-y-3 text-sm text-navy-light">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-green flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Fastest processing
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-green flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Dedicated agent
                        </li>
                    </ul>
                    <button class="mt-8 w-full py-3 rounded-full border border-border-gray font-semibold text-navy hover:border-navy transition">
                        Select Super Rush
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="bg-navy">
        <div class="max-w-container mx-auto px-6 md:px-12 xl:px-[120px] py-20 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white">Ready to start your journey?</h2>
            <p class="mt-4 text-white/70 max-w-xl mx-auto">
                Join thousands of travelers who trust UnitedPassport for their visa and travel document needs.
            </p>
            <router-link
                to="/register"
                class="mt-8 inline-flex items-center px-8 py-3 rounded-full text-white font-semibold bg-gradient-to-r from-brand-green to-brand-teal hover:opacity-90 transition shadow-lg"
            >
                Create your account
            </router-link>
        </div>
    </section>
</template>
