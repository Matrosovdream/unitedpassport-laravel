<template>
    <div class="min-h-screen bg-white font-sans text-navy">
        <nav class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-border-gray">
            <div class="max-w-container mx-auto px-6 md:px-12 xl:px-[120px]">
                <div class="flex justify-between h-16 items-center">
                    <router-link to="/" class="text-xl font-bold text-navy">
                        United<span class="bg-gradient-to-r from-brand-green to-brand-teal bg-clip-text text-transparent">Passport</span>
                    </router-link>

                    <div class="flex items-center gap-6">
                        <router-link to="/login" class="text-sm font-medium text-navy-light hover:text-navy transition">
                            Sign in
                        </router-link>
                        <router-link
                            to="/register"
                            class="text-sm font-medium text-white bg-gradient-to-r from-brand-green to-brand-teal px-5 py-2 rounded-full hover:opacity-90 transition"
                        >
                            Get Started
                        </router-link>
                    </div>
                </div>
            </div>
        </nav>

        <main>
            <router-view />
        </main>

        <footer class="bg-navy text-white/70">
            <div class="max-w-container mx-auto px-6 md:px-12 xl:px-[120px] py-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <span class="text-lg font-bold text-white">
                            United<span class="text-brand-green">Passport</span>
                        </span>
                        <p class="mt-3 text-sm">Your trusted travel document partner.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-3">Quick Links</h4>
                        <ul class="space-y-2 text-sm">
                            <li><router-link to="/" class="hover:text-white transition">Home</router-link></li>
                            <li><router-link to="/login" class="hover:text-white transition">Sign in</router-link></li>
                            <li><router-link to="/register" class="hover:text-white transition">Register</router-link></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-3">Contact</h4>
                        <p class="text-sm">support@unitedpassport.com</p>
                    </div>
                </div>
                <div class="border-t border-white/10 mt-8 pt-8 text-center text-sm">
                    &copy; {{ new Date().getFullYear() }} UnitedPassport. All rights reserved.
                </div>
            </div>
        </footer>
    </div>
</template>
