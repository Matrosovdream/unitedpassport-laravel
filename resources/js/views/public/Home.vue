<template>
    <!-- Hero -->
    <section class="relative overflow-hidden bg-gradient-to-br from-white to-section-gray">
        <div class="max-w-container mx-auto px-6 md:px-12 xl:px-[120px] py-20 md:py-32">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                <div class="max-w-xl">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight text-navy">
                        Get Your
                        <span class="bg-gradient-to-r from-brand-green to-brand-teal bg-clip-text text-transparent">U.S. Passport</span>
                    </h1>
                    <p class="mt-6 text-lg text-navy-light leading-relaxed">
                        Trusted online U.S. Passport Courier Service. We help you navigate the passport application process quickly and securely.
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row gap-3">
                        <select
                            v-model="selectedType"
                            class="flex-1 px-4 py-3 rounded-full border border-border-gray bg-white text-navy font-medium focus:outline-none focus:ring-2 focus:ring-brand-teal"
                        >
                            <option value="" disabled>Select Application Type</option>
                            <option v-for="t in applicationTypes" :key="t.slug" :value="t.slug">{{ t.title }}</option>
                        </select>
                        <button
                            @click="goToApply"
                            class="inline-flex items-center justify-center px-8 py-3 rounded-full text-white font-semibold bg-gradient-to-r from-brand-green to-brand-teal hover:opacity-90 transition shadow-lg"
                        >
                            GET STARTED
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="hidden lg:block flex-shrink-0">
                    <div class="w-64 h-80 bg-gradient-to-br from-brand-green/20 to-brand-teal/20 rounded-card flex items-center justify-center">
                        <svg class="w-32 h-32 text-navy/30" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 2a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2H6zm6 5a3 3 0 110 6 3 3 0 010-6zm-5 10a5 5 0 0110 0H7z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
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
                    <span>Government Registered</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Choose Your Application Type -->
    <section id="application-types" class="bg-white">
        <div class="max-w-container mx-auto px-6 md:px-12 xl:px-[120px] py-20">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-navy">Choose Your Application Type</h2>
            <p class="mt-4 text-center text-navy-light max-w-xl mx-auto">
                Select the passport service that fits your needs. We handle all types of U.S. passport applications.
            </p>

            <div class="mt-14 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div
                    v-for="t in applicationTypes"
                    :key="t.slug"
                    class="bg-white rounded-card shadow-card p-6 hover:shadow-lg transition-shadow cursor-pointer group"
                    @click="$router.push({ name: 'apply', query: { type: t.slug } })"
                >
                    <div class="w-14 h-14 rounded-full bg-gradient-to-r from-brand-green/20 to-brand-teal/20 flex items-center justify-center mb-4">
                        <span class="text-2xl">{{ t.icon }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-navy group-hover:text-brand-teal transition-colors">{{ t.title }}</h3>
                    <p class="mt-2 text-sm text-navy-light leading-relaxed">{{ t.description }}</p>
                    <div class="mt-4">
                        <span class="inline-flex items-center text-sm font-semibold bg-gradient-to-r from-brand-green to-brand-teal bg-clip-text text-transparent group-hover:opacity-80">
                            Apply Now
                            <svg class="ml-1 w-4 h-4 text-brand-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How it Works -->
    <section id="how-it-works" class="bg-section-gray">
        <div class="max-w-container mx-auto px-6 md:px-12 xl:px-[120px] py-20">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-navy">How it Works</h2>
            <p class="mt-4 text-center text-navy-light max-w-xl mx-auto">
                Get your passport in four simple steps.
            </p>

            <div class="mt-14 grid grid-cols-1 md:grid-cols-4 gap-8">
                <div v-for="(step, i) in howItWorks" :key="i" class="bg-white rounded-card shadow-card p-8 text-center relative">
                    <div class="w-14 h-14 mx-auto rounded-full bg-gradient-to-r from-brand-green/20 to-brand-teal/20 flex items-center justify-center">
                        <span class="text-2xl font-bold bg-gradient-to-r from-brand-green to-brand-teal bg-clip-text text-transparent">{{ i + 1 }}</span>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-navy">{{ step.title }}</h3>
                    <p class="mt-2 text-sm text-navy-light">{{ step.desc }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="bg-navy">
        <div class="max-w-container mx-auto px-6 md:px-12 xl:px-[120px] py-20 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white">Ready to get your passport?</h2>
            <p class="mt-4 text-white/70 max-w-xl mx-auto">
                Join thousands of travelers who trust UnitedPassport for fast, reliable passport processing.
            </p>
            <router-link
                to="/apply"
                class="mt-8 inline-flex items-center px-8 py-3 rounded-full text-white font-semibold bg-gradient-to-r from-brand-green to-brand-teal hover:opacity-90 transition shadow-lg"
            >
                Start Your Application
            </router-link>
        </div>
    </section>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const selectedType = ref('');

const applicationTypes = [
    {
        slug: 'new-passport',
        title: 'New Adult Passport',
        icon: '\u{1F4D8}',
        description: 'First-time applicant or previous passport was issued before age 16. Apply for a brand new U.S. passport book.',
    },
    {
        slug: 'passport-renewal',
        title: 'Passport Renewal',
        icon: '\u{1F504}',
        description: 'Renew your current or recently expired passport. Must be undamaged and issued within the last 15 years.',
    },
    {
        slug: 'lost-passport',
        title: 'Lost Passport',
        icon: '\u{1F50D}',
        description: 'Replace a passport that has been lost. You will need to report the loss and apply for a new one.',
    },
    {
        slug: 'stolen-passport',
        title: 'Stolen Passport',
        icon: '\u{1F6A8}',
        description: 'Replace a passport that has been stolen. File a police report and apply for a replacement.',
    },
    {
        slug: 'child-passport',
        title: 'Child Passport',
        icon: '\u{1F476}',
        description: 'Apply for a passport for a child under 16. Both parents or guardians must authorize the application.',
    },
    {
        slug: 'damaged-passport',
        title: 'Damaged Passport',
        icon: '\u{1F4C4}',
        description: 'Replace a passport that is significantly damaged, water-damaged, torn, or has missing pages.',
    },
    {
        slug: 'name-change',
        title: 'Name Change',
        icon: '\u{270F}\u{FE0F}',
        description: 'Update your passport due to a legal name change from marriage, divorce, or court order.',
    },
    {
        slug: 'second-passport',
        title: 'Second Passport',
        icon: '\u{1F4D7}',
        description: 'Apply for an additional valid passport for frequent international travelers or visa processing needs.',
    },
];

const howItWorks = [
    { title: 'Select Passport Type', desc: 'Choose the application type that matches your situation from our easy-to-use options.' },
    { title: 'Online Application', desc: 'Complete our secure online form with your personal details and travel information.' },
    { title: 'Photo & Requirements', desc: 'Upload your passport photo and any required supporting documents.' },
    { title: 'Start Processing', desc: 'We submit your application and keep you updated every step of the way.' },
];

function goToApply() {
    if (selectedType.value) {
        router.push({ name: 'apply', query: { type: selectedType.value } });
    } else {
        router.push({ name: 'apply' });
    }
}
</script>
