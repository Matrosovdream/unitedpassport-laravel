<template>
    <section class="bg-section-gray min-h-screen">
        <div class="max-w-container mx-auto px-6 md:px-12 xl:px-[120px] py-12">
            <!-- Progress -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-3">
                    <h1 class="text-2xl md:text-3xl font-bold text-navy">Passport Application</h1>
                    <span class="text-sm text-navy-light font-medium">Step {{ currentStep }} of {{ totalSteps }} &mdash; {{ Math.round((currentStep / totalSteps) * 100) }}% Complete</span>
                </div>
                <div class="w-full bg-white rounded-full h-2 shadow-sm">
                    <div
                        class="h-2 rounded-full bg-gradient-to-r from-brand-green to-brand-teal transition-all duration-500"
                        :style="{ width: ((currentStep / totalSteps) * 100) + '%' }"
                    ></div>
                </div>
                <div class="flex justify-between mt-3">
                    <button
                        v-for="(s, i) in steps"
                        :key="i"
                        @click="jumpToStep(i + 1)"
                        class="text-xs font-medium transition-colors hidden md:block"
                        :class="i + 1 === currentStep ? 'text-brand-teal' : i + 1 < currentStep ? 'text-navy' : 'text-navy-light/50'"
                    >
                        {{ s.label }}
                    </button>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-card shadow-card p-8 md:p-12">
                <transition name="fade" mode="out-in">
                    <!-- Step 1: Application Type -->
                    <div v-if="currentStep === 1" key="step1">
                        <h2 class="text-xl font-bold text-navy mb-6">Select Your Passport Type</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <button
                                v-for="t in passportTypes"
                                :key="t.value"
                                @click="form.passportType = t.value"
                                class="p-4 rounded-xl border-2 text-left transition-all"
                                :class="form.passportType === t.value
                                    ? 'border-brand-teal bg-brand-teal/5'
                                    : 'border-border-gray hover:border-navy-light'"
                            >
                                <span class="text-xl mr-2">{{ t.icon }}</span>
                                <span class="font-semibold text-navy">{{ t.label }}</span>
                            </button>
                        </div>

                        <div v-if="form.passportType === 'passport-renewal'" class="mt-8">
                            <h3 class="text-lg font-semibold text-navy mb-4">Eligibility Check</h3>
                            <p class="text-sm text-navy-light mb-4">To renew by mail, confirm all of the following:</p>
                            <div class="space-y-3">
                                <label v-for="(item, i) in eligibilityChecks" :key="i" class="flex items-start gap-3 cursor-pointer">
                                    <input type="checkbox" v-model="form.eligibility[i]" class="mt-1 w-4 h-4 rounded border-border-gray text-brand-teal focus:ring-brand-teal" />
                                    <span class="text-sm text-navy-light">{{ item }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-8">
                            <label class="block text-sm font-semibold text-navy mb-2">Primary Contact Email <span class="text-red-500">*</span></label>
                            <input
                                v-model="form.email"
                                type="email"
                                placeholder="your@email.com"
                                class="w-full md:w-1/2 px-4 py-3 rounded-xl border border-border-gray focus:outline-none focus:ring-2 focus:ring-brand-teal"
                            />
                        </div>
                    </div>

                    <!-- Step 2: Personal Information -->
                    <div v-else-if="currentStep === 2" key="step2">
                        <h2 class="text-xl font-bold text-navy mb-6">Personal Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-navy mb-2">First Name <span class="text-red-500">*</span></label>
                                <input v-model="form.firstName" type="text" class="w-full px-4 py-3 rounded-xl border border-border-gray focus:outline-none focus:ring-2 focus:ring-brand-teal" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-navy mb-2">Last Name <span class="text-red-500">*</span></label>
                                <input v-model="form.lastName" type="text" class="w-full px-4 py-3 rounded-xl border border-border-gray focus:outline-none focus:ring-2 focus:ring-brand-teal" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-navy mb-2">Middle Name</label>
                                <input v-model="form.middleName" type="text" class="w-full px-4 py-3 rounded-xl border border-border-gray focus:outline-none focus:ring-2 focus:ring-brand-teal" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-navy mb-2">Date of Birth <span class="text-red-500">*</span></label>
                                <input v-model="form.dob" type="date" class="w-full px-4 py-3 rounded-xl border border-border-gray focus:outline-none focus:ring-2 focus:ring-brand-teal" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-navy mb-2">Sex <span class="text-red-500">*</span></label>
                                <select v-model="form.sex" class="w-full px-4 py-3 rounded-xl border border-border-gray focus:outline-none focus:ring-2 focus:ring-brand-teal">
                                    <option value="" disabled>Select</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">X (Unspecified)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-navy mb-2">Social Security Number</label>
                                <input v-model="form.ssn" type="text" placeholder="XXX-XX-XXXX" class="w-full px-4 py-3 rounded-xl border border-border-gray focus:outline-none focus:ring-2 focus:ring-brand-teal" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-navy mb-2">Place of Birth (City) <span class="text-red-500">*</span></label>
                                <input v-model="form.birthCity" type="text" class="w-full px-4 py-3 rounded-xl border border-border-gray focus:outline-none focus:ring-2 focus:ring-brand-teal" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-navy mb-2">State / Country of Birth <span class="text-red-500">*</span></label>
                                <input v-model="form.birthState" type="text" class="w-full px-4 py-3 rounded-xl border border-border-gray focus:outline-none focus:ring-2 focus:ring-brand-teal" />
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Address & Contact -->
                    <div v-else-if="currentStep === 3" key="step3">
                        <h2 class="text-xl font-bold text-navy mb-6">Mailing Address & Contact</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-navy mb-2">Street Address <span class="text-red-500">*</span></label>
                                <input v-model="form.street" type="text" class="w-full px-4 py-3 rounded-xl border border-border-gray focus:outline-none focus:ring-2 focus:ring-brand-teal" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-navy mb-2">Apartment / Suite</label>
                                <input v-model="form.apt" type="text" class="w-full px-4 py-3 rounded-xl border border-border-gray focus:outline-none focus:ring-2 focus:ring-brand-teal" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-navy mb-2">City <span class="text-red-500">*</span></label>
                                <input v-model="form.city" type="text" class="w-full px-4 py-3 rounded-xl border border-border-gray focus:outline-none focus:ring-2 focus:ring-brand-teal" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-navy mb-2">State <span class="text-red-500">*</span></label>
                                <select v-model="form.state" class="w-full px-4 py-3 rounded-xl border border-border-gray focus:outline-none focus:ring-2 focus:ring-brand-teal">
                                    <option value="" disabled>Select State</option>
                                    <option v-for="s in usStates" :key="s" :value="s">{{ s }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-navy mb-2">ZIP Code <span class="text-red-500">*</span></label>
                                <input v-model="form.zip" type="text" placeholder="XXXXX" class="w-full px-4 py-3 rounded-xl border border-border-gray focus:outline-none focus:ring-2 focus:ring-brand-teal" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-navy mb-2">Phone Number <span class="text-red-500">*</span></label>
                                <input v-model="form.phone" type="tel" placeholder="(XXX) XXX-XXXX" class="w-full px-4 py-3 rounded-xl border border-border-gray focus:outline-none focus:ring-2 focus:ring-brand-teal" />
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Travel Plans -->
                    <div v-else-if="currentStep === 4" key="step4">
                        <h2 class="text-xl font-bold text-navy mb-6">Travel Plans</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-navy mb-2">Planned Travel Date</label>
                                <input v-model="form.travelDate" type="date" class="w-full px-4 py-3 rounded-xl border border-border-gray focus:outline-none focus:ring-2 focus:ring-brand-teal" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-navy mb-2">Destination Country</label>
                                <input v-model="form.destination" type="text" placeholder="e.g. France" class="w-full px-4 py-3 rounded-xl border border-border-gray focus:outline-none focus:ring-2 focus:ring-brand-teal" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-navy mb-2">Return Date</label>
                                <input v-model="form.returnDate" type="date" class="w-full px-4 py-3 rounded-xl border border-border-gray focus:outline-none focus:ring-2 focus:ring-brand-teal" />
                            </div>
                        </div>

                        <div class="mt-8">
                            <h3 class="text-lg font-semibold text-navy mb-4">Emergency Contact</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-navy mb-2">Contact Name</label>
                                    <input v-model="form.emergencyName" type="text" class="w-full px-4 py-3 rounded-xl border border-border-gray focus:outline-none focus:ring-2 focus:ring-brand-teal" />
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-navy mb-2">Contact Phone</label>
                                    <input v-model="form.emergencyPhone" type="tel" class="w-full px-4 py-3 rounded-xl border border-border-gray focus:outline-none focus:ring-2 focus:ring-brand-teal" />
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-navy mb-2">Relationship</label>
                                    <input v-model="form.emergencyRelation" type="text" placeholder="e.g. Spouse, Parent" class="w-full px-4 py-3 rounded-xl border border-border-gray focus:outline-none focus:ring-2 focus:ring-brand-teal" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5: Photo & Documents -->
                    <div v-else-if="currentStep === 5" key="step5">
                        <h2 class="text-xl font-bold text-navy mb-6">Photo & Documents</h2>

                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-navy mb-3">Passport Photo</h3>
                            <p class="text-sm text-navy-light mb-4">Upload a recent 2x2 inch color photo with a white background.</p>
                            <div
                                class="border-2 border-dashed border-border-gray rounded-xl p-8 text-center cursor-pointer hover:border-brand-teal transition-colors"
                                @click="simulateUpload('photo')"
                            >
                                <div v-if="!form.photoUploaded" class="text-navy-light">
                                    <svg class="mx-auto w-12 h-12 mb-3 text-navy-light/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="font-medium">Click to upload photo</p>
                                    <p class="text-xs mt-1">JPG or PNG, max 10MB</p>
                                </div>
                                <div v-else class="text-brand-teal">
                                    <svg class="mx-auto w-12 h-12 mb-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    <p class="font-medium">passport_photo.jpg uploaded</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-navy mb-3">Supporting Documents</h3>
                            <p class="text-sm text-navy-light mb-4">Upload any required documents (birth certificate, previous passport, legal name change documents, etc.)</p>
                            <div
                                class="border-2 border-dashed border-border-gray rounded-xl p-8 text-center cursor-pointer hover:border-brand-teal transition-colors"
                                @click="simulateUpload('docs')"
                            >
                                <div v-if="!form.docsUploaded" class="text-navy-light">
                                    <svg class="mx-auto w-12 h-12 mb-3 text-navy-light/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="font-medium">Click to upload documents</p>
                                    <p class="text-xs mt-1">PDF, JPG, PNG &mdash; max 10MB per file</p>
                                </div>
                                <div v-else class="text-brand-teal">
                                    <svg class="mx-auto w-12 h-12 mb-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    <p class="font-medium">documents.pdf uploaded</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 6: Processing Speed -->
                    <div v-else-if="currentStep === 6" key="step6">
                        <h2 class="text-xl font-bold text-navy mb-6">Select Processing Speed</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div
                                v-for="tier in processingTiers"
                                :key="tier.id"
                                @click="form.processing = tier.id"
                                class="p-6 rounded-xl border-2 cursor-pointer transition-all relative"
                                :class="form.processing === tier.id
                                    ? 'border-brand-teal bg-brand-teal/5'
                                    : 'border-border-gray hover:border-navy-light'"
                            >
                                <span v-if="tier.popular" class="absolute -top-3 left-1/2 -translate-x-1/2 bg-gradient-to-r from-brand-green to-brand-teal text-white text-xs font-semibold px-3 py-1 rounded-full">
                                    Popular
                                </span>
                                <h3 class="text-lg font-semibold text-navy">{{ tier.label }}</h3>
                                <p class="text-sm text-navy-light mt-1">{{ tier.time }}</p>
                                <div class="mt-3 text-2xl font-bold text-navy">{{ tier.price }}</div>
                                <ul class="mt-4 space-y-2 text-sm text-navy-light">
                                    <li v-for="(f, fi) in tier.features" :key="fi" class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-brand-green flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        {{ f }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Step 7: Review & Submit -->
                    <div v-else-if="currentStep === 7" key="step7">
                        <h2 class="text-xl font-bold text-navy mb-6">Review & Submit</h2>

                        <div class="space-y-6">
                            <div class="bg-section-gray rounded-xl p-6">
                                <div class="flex justify-between items-center mb-3">
                                    <h3 class="font-semibold text-navy">Application Type</h3>
                                    <button @click="currentStep = 1" class="text-sm text-brand-teal font-medium hover:underline">Edit</button>
                                </div>
                                <p class="text-sm text-navy-light">{{ passportTypeLabel }}</p>
                                <p class="text-sm text-navy-light">{{ form.email }}</p>
                            </div>

                            <div class="bg-section-gray rounded-xl p-6">
                                <div class="flex justify-between items-center mb-3">
                                    <h3 class="font-semibold text-navy">Personal Information</h3>
                                    <button @click="currentStep = 2" class="text-sm text-brand-teal font-medium hover:underline">Edit</button>
                                </div>
                                <p class="text-sm text-navy-light">{{ form.firstName }} {{ form.middleName }} {{ form.lastName }}</p>
                                <p class="text-sm text-navy-light">DOB: {{ form.dob || 'Not provided' }}</p>
                                <p class="text-sm text-navy-light">Born in: {{ form.birthCity }}, {{ form.birthState }}</p>
                            </div>

                            <div class="bg-section-gray rounded-xl p-6">
                                <div class="flex justify-between items-center mb-3">
                                    <h3 class="font-semibold text-navy">Mailing Address</h3>
                                    <button @click="currentStep = 3" class="text-sm text-brand-teal font-medium hover:underline">Edit</button>
                                </div>
                                <p class="text-sm text-navy-light">{{ form.street }} {{ form.apt }}</p>
                                <p class="text-sm text-navy-light">{{ form.city }}, {{ form.state }} {{ form.zip }}</p>
                                <p class="text-sm text-navy-light">{{ form.phone }}</p>
                            </div>

                            <div class="bg-section-gray rounded-xl p-6">
                                <div class="flex justify-between items-center mb-3">
                                    <h3 class="font-semibold text-navy">Processing Speed</h3>
                                    <button @click="currentStep = 6" class="text-sm text-brand-teal font-medium hover:underline">Edit</button>
                                </div>
                                <p class="text-sm text-navy-light">{{ processingLabel }}</p>
                            </div>
                        </div>

                        <div class="mt-8 p-4 bg-brand-teal/5 border border-brand-teal/20 rounded-xl">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" v-model="form.agree" class="mt-1 w-4 h-4 rounded border-border-gray text-brand-teal focus:ring-brand-teal" />
                                <span class="text-sm text-navy-light">
                                    I certify that all information provided is true and correct. I understand that false statements may result in the denial or revocation of my passport.
                                </span>
                            </label>
                        </div>
                    </div>
                </transition>

                <!-- Navigation -->
                <div class="mt-10 flex justify-between items-center">
                    <button
                        v-if="currentStep > 1"
                        @click="currentStep--"
                        class="inline-flex items-center px-6 py-3 rounded-full border border-border-gray font-semibold text-navy hover:border-navy transition"
                    >
                        <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back
                    </button>
                    <div v-else></div>

                    <button
                        v-if="currentStep < totalSteps"
                        @click="nextStep"
                        class="inline-flex items-center px-8 py-3 rounded-full text-white font-semibold bg-gradient-to-r from-brand-green to-brand-teal hover:opacity-90 transition shadow-lg"
                    >
                        Next
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <button
                        v-else
                        @click="submitApplication"
                        :disabled="!form.agree"
                        class="inline-flex items-center px-8 py-3 rounded-full text-white font-semibold bg-gradient-to-r from-brand-green to-brand-teal hover:opacity-90 transition shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Submit Application
                    </button>
                </div>
            </div>

            <!-- Success Modal -->
            <div v-if="submitted" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
                <div class="bg-white rounded-card shadow-card p-10 max-w-md mx-4 text-center">
                    <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gradient-to-r from-brand-green/20 to-brand-teal/20 flex items-center justify-center">
                        <svg class="w-10 h-10 text-brand-teal" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-navy">Application Submitted!</h2>
                    <p class="mt-3 text-navy-light">Your passport application has been received. We'll send a confirmation to <strong>{{ form.email }}</strong>.</p>
                    <p class="mt-2 text-sm text-navy-light">Reference #: <strong>UP-{{ referenceNumber }}</strong></p>
                    <button
                        @click="$router.push('/')"
                        class="mt-6 inline-flex items-center px-8 py-3 rounded-full text-white font-semibold bg-gradient-to-r from-brand-green to-brand-teal hover:opacity-90 transition"
                    >
                        Back to Home
                    </button>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const currentStep = ref(1);
const totalSteps = 7;
const submitted = ref(false);

const steps = [
    { label: 'Type' },
    { label: 'Personal' },
    { label: 'Address' },
    { label: 'Travel' },
    { label: 'Documents' },
    { label: 'Processing' },
    { label: 'Review' },
];

const form = reactive({
    passportType: '',
    email: '',
    eligibility: [false, false, false, false, false, false],
    firstName: '',
    lastName: '',
    middleName: '',
    dob: '',
    sex: '',
    ssn: '',
    birthCity: '',
    birthState: '',
    street: '',
    apt: '',
    city: '',
    state: '',
    zip: '',
    phone: '',
    travelDate: '',
    destination: '',
    returnDate: '',
    emergencyName: '',
    emergencyPhone: '',
    emergencyRelation: '',
    photoUploaded: false,
    docsUploaded: false,
    processing: 'rush',
    agree: false,
});

const passportTypes = [
    { value: 'new-passport', label: 'New Adult Passport', icon: '\u{1F4D8}' },
    { value: 'passport-renewal', label: 'Passport Renewal', icon: '\u{1F504}' },
    { value: 'lost-passport', label: 'Lost Passport', icon: '\u{1F50D}' },
    { value: 'stolen-passport', label: 'Stolen Passport', icon: '\u{1F6A8}' },
    { value: 'child-passport', label: 'Child Passport', icon: '\u{1F476}' },
    { value: 'damaged-passport', label: 'Damaged Passport', icon: '\u{1F4C4}' },
    { value: 'name-change', label: 'Name Change', icon: '\u{270F}\u{FE0F}' },
    { value: 'second-passport', label: 'Second Passport', icon: '\u{1F4D7}' },
];

const eligibilityChecks = [
    'My most recent passport is in my possession and I can submit it.',
    'I was at least 16 years old when my most recent passport was issued.',
    'My most recent passport was issued less than 15 years ago.',
    'My most recent passport is not damaged, lost, or stolen.',
    'My passport was issued for the full 10-year validity period.',
    'My name is the same, or I can document my legal name change.',
];

const processingTiers = [
    {
        id: 'standard',
        label: 'Standard',
        time: '4-6 weeks',
        price: '$199',
        features: ['Full application review', 'Email support', 'Document checklist'],
        popular: false,
    },
    {
        id: 'rush',
        label: 'Rush',
        time: '2-3 weeks',
        price: '$299',
        features: ['Priority processing', 'Chat & email support', 'Express document review'],
        popular: true,
    },
    {
        id: 'super-rush',
        label: 'Super Rush',
        time: '5-7 business days',
        price: '$449',
        features: ['Fastest processing', 'Dedicated agent', 'Real-time tracking'],
        popular: false,
    },
];

const usStates = [
    'Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware',
    'Florida', 'Georgia', 'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky',
    'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi',
    'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico',
    'New York', 'North Carolina', 'North Dakota', 'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania',
    'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
    'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming', 'District of Columbia',
];

const passportTypeLabel = computed(() => {
    const t = passportTypes.find(p => p.value === form.passportType);
    return t ? t.label : 'Not selected';
});

const processingLabel = computed(() => {
    const t = processingTiers.find(p => p.id === form.processing);
    return t ? `${t.label} — ${t.time} — ${t.price}` : '';
});

const referenceNumber = computed(() => {
    return Math.random().toString(36).substring(2, 8).toUpperCase();
});

onMounted(() => {
    if (route.query.type) {
        form.passportType = route.query.type;
    }
});

function jumpToStep(step) {
    if (step <= currentStep.value) {
        currentStep.value = step;
    }
}

function nextStep() {
    if (currentStep.value < totalSteps) {
        currentStep.value++;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

function simulateUpload(type) {
    if (type === 'photo') form.photoUploaded = !form.photoUploaded;
    if (type === 'docs') form.docsUploaded = !form.docsUploaded;
}

function submitApplication() {
    submitted.value = true;
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
