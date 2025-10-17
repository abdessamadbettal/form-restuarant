<script setup lang="ts">
import RegisteredUserController from '@/actions/App/Http/Controllers/Auth/RegisteredUserController';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle, Search, ChevronDown, Check, User, Mail, Phone, Globe, Sparkles, Info, Shield, X, Lock } from 'lucide-vue-next';
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';

interface Country {
    name: {
        common: string;
    };
    cca2: string;
    flags: {
        svg: string;
        png: string;
    };
}

const countries = ref<Country[]>([]);
const isLoadingCountries = ref(true);
const isDropdownOpen = ref(false);
const selectedCountry = ref<Country | null>(null);
const searchQuery = ref('');
const countryInputRef = ref<HTMLInputElement | null>(null);
const formProgress = ref(0);
const showTermsModal = ref(false);
const acceptedTerms = ref(false);

onMounted(async () => {
    try {
        const response = await fetch('https://restcountries.com/v3.1/all?fields=name,cca2,flags');
        const data = await response.json();
        countries.value = data.sort((a: Country, b: Country) =>
            a.name.common.localeCompare(b.name.common)
        );
    } catch (error) {
        console.error('Failed to load countries:', error);
    } finally {
        isLoadingCountries.value = false;
    }

    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});

const filteredCountries = computed(() => {
    if (!searchQuery.value) return countries.value;
    return countries.value.filter(country =>
        country.name.common.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        country.cca2.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const selectCountry = (country: Country) => {
    selectedCountry.value = country;
    isDropdownOpen.value = false;
    searchQuery.value = '';

    if (countryInputRef.value) {
        countryInputRef.value.value = country.cca2;
        countryInputRef.value.dispatchEvent(new Event('input', { bubbles: true }));
    }
    updateProgress();
};

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};

const handleClickOutside = (event: MouseEvent) => {
    const target = event.target as HTMLElement;
    if (!target.closest('.country-select-wrapper')) {
        isDropdownOpen.value = false;
        searchQuery.value = '';
    }
};

const updateProgress = () => {
    const fields = [
        document.getElementById('name') as HTMLInputElement,
        document.getElementById('email') as HTMLInputElement,
        document.getElementById('phone') as HTMLInputElement,
        selectedCountry.value,
        acceptedTerms.value
    ];

    const filledFields = fields.filter(field => {
        if (field instanceof HTMLInputElement) {
            return field.value.trim() !== '';
        }
        return field !== null && field !== false;
    }).length;

    formProgress.value = (filledFields / fields.length) * 100;
};

const openTermsModal = () => {
    showTermsModal.value = true;
};

const closeTermsModal = () => {
    showTermsModal.value = false;
};

const acceptTerms = () => {
    acceptedTerms.value = true;
    showTermsModal.value = false;
    updateProgress();
};
</script>

<template>
    <AuthBase title="Frank Meat & Taps" description="Sign up for exclusive offers and Wi-Fi access">
        <Head title="Register" />

        <Form v-bind="RegisteredUserController.store.form()" v-slot="{ errors, processing, data }" class="space-y-5">
            <!-- Progress Bar -->
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-gray-600">Complete your profile</span>
                    <span class="text-xs font-bold text-red-600">{{ Math.round(formProgress) }}%</span>
                </div>
                <div class="h-2 w-full overflow-hidden rounded-full bg-gray-200 ring-1 ring-gray-300">
                    <div class="h-full bg-gradient-to-r from-red-600 via-red-500 to-red-600 transition-all duration-500 ease-out shadow-lg shadow-red-500/50"
                        :style="{ width: `${formProgress}%` }"></div>
                </div>
            </div>

            <!-- Country Field -->
            <div class="space-y-2">
                <Label for="country" variant="light" class="flex items-center gap-2">
                    <Globe class="h-4 w-4 text-red-600" />
                    <span>Country</span>
                    <span class="text-red-600">*</span>
                </Label>

                <div class="country-select-wrapper relative">
                    <input ref="countryInputRef" type="hidden" name="country" required :value="selectedCountry?.cca2 || ''" />

                    <button type="button" @click="toggleDropdown" :tabindex="4"
                        class="flex h-12 w-full items-center justify-between rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm transition-all hover:border-red-400 hover:bg-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white"
                        :class="{ 'ring-2 ring-red-500 ring-offset-2 ring-offset-white border-red-500': isDropdownOpen }">
                        <span class="flex items-center gap-3">
                            <template v-if="selectedCountry">
                                <img :src="selectedCountry.flags.svg" :alt="selectedCountry.name.common"
                                    class="h-5 w-7 rounded object-cover shadow-md ring-1 ring-gray-300" />
                                <span class="truncate font-medium text-gray-900">{{ selectedCountry.name.common }}</span>
                            </template>
                            <template v-else>
                                <span class="text-gray-400">
                                    {{ isLoadingCountries ? 'Loading countries...' : 'Select your country' }}
                                </span>
                            </template>
                        </span>
                        <ChevronDown class="h-4 w-4 text-gray-500 transition-transform duration-200"
                            :class="{ 'rotate-180 text-red-600': isDropdownOpen }" />
                    </button>

                    <Transition enter-active-class="transition duration-200 ease-out"
                        enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100"
                        leave-active-class="transition duration-150 ease-in"
                        leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
                        <div v-if="isDropdownOpen"
                            class="absolute z-50 mt-2 w-full rounded-xl border border-gray-200 bg-white shadow-2xl shadow-black/20 ring-1 ring-red-500/20">
                            <div class="border-b border-gray-200 p-3">
                                <div class="relative">
                                    <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                                    <input v-model="searchQuery" type="text" placeholder="Search countries..."
                                        class="h-10 w-full rounded-lg border border-gray-300 bg-gray-50 pl-10 pr-3 text-sm text-gray-900 placeholder-gray-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-500"
                                        @click.stop />
                                </div>
                            </div>

                            <div class="custom-scrollbar max-h-[280px] overflow-y-auto p-2">
                                <template v-if="isLoadingCountries">
                                    <div class="flex items-center justify-center py-8">
                                        <LoaderCircle class="h-6 w-6 animate-spin text-red-600" />
                                    </div>
                                </template>

                                <template v-else-if="filteredCountries.length === 0">
                                    <div class="py-8 text-center text-sm text-gray-500">
                                        No countries found
                                    </div>
                                </template>

                                <template v-else>
                                    <button v-for="country in filteredCountries" :key="country.cca2" type="button"
                                        @click="selectCountry(country)"
                                        class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors hover:bg-red-50"
                                        :class="{ 'bg-red-50 ring-1 ring-red-200': selectedCountry?.cca2 === country.cca2 }">
                                        <img :src="country.flags.svg" :alt="country.name.common"
                                            class="h-4 w-6 rounded object-cover shadow-sm ring-1 ring-gray-300" />
                                        <span class="flex-1 truncate text-left text-gray-900">{{ country.name.common }}</span>
                                        <Check v-if="selectedCountry?.cca2 === country.cca2"
                                            class="h-4 w-4 text-red-600" />
                                    </button>
                                </template>
                            </div>
                        </div>
                    </Transition>
                </div>

                <InputError :message="errors.country" />
            </div>

            <!-- Name Field -->
            <div class="group space-y-2">
                <Label for="name" variant="light" class="flex items-center gap-2">
                    <User class="h-4 w-4 text-red-600" />
                    <span>Full Name</span>
                    <span class="text-red-600">*</span>
                </Label>
                <div class="relative">
                    <Input id="name" type="text" variant="light" required autofocus :tabindex="1" autocomplete="name" name="name"
                        placeholder="Enter your full name" class="peer" @input="updateProgress" />
                    <div
                        class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-red-600 opacity-0 transition-opacity peer-focus:opacity-100">
                        <Sparkles class="h-4 w-4" />
                    </div>
                </div>
                <InputError :message="errors.name" />
            </div>

            <!-- Email Field -->
            <div class="group space-y-2">
                <Label for="email" variant="light" class="flex items-center gap-2">
                    <Mail class="h-4 w-4 text-red-600" />
                    <span>Email Address</span>
                    <span class="text-red-600">*</span>
                </Label>
                <div class="relative">
                    <Input id="email" type="email" variant="light" required :tabindex="2" autocomplete="email" name="email"
                        placeholder="your@email.com" class="peer" @input="updateProgress" />
                    <div
                        class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-red-600 opacity-0 transition-opacity peer-focus:opacity-100">
                        <Sparkles class="h-4 w-4" />
                    </div>
                </div>
                <InputError :message="errors.email" />
            </div>

            <!-- Phone Field -->
            <div class="group space-y-2">
                <Label for="phone" variant="light" class="flex items-center gap-2">
                    <Phone class="h-4 w-4 text-red-600" />
                    <span>Phone Number</span>
                    <span class="text-red-600">*</span>
                </Label>
                <div class="relative">
                    <Input id="phone" type="tel" variant="light" required :tabindex="3" autocomplete="tel" name="phone"
                        placeholder="+971 50 123 4567" class="peer" @input="updateProgress" />
                    <div
                        class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-red-600 opacity-0 transition-opacity peer-focus:opacity-100">
                        <Sparkles class="h-4 w-4" />
                    </div>
                </div>
                <InputError :message="errors.phone" />
            </div>

            <!-- Terms -->
            <div class="space-y-2">
                <div class="flex items-start gap-3 rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <input id="terms" type="checkbox" v-model="acceptedTerms" @change="updateProgress" :tabindex="5"
                        required
                        class="mt-0.5 h-5 w-5 rounded border-gray-300 bg-white text-red-600  focus:ring-offset-2 focus:ring-offset-white"
                        style="accent-color: #dc2626" />
                    <label for="terms" class="flex-1 text-sm leading-relaxed text-gray-600">
                        I agree to the
                        <button type="button" @click="openTermsModal"
                            class="font-semibold text-red-600 hover:text-red-700 underline decoration-red-600/30 underline-offset-2 transition-colors">
                            Wi-Fi Terms of Service
                        </button>
                        and consent to receive promotional emails
                        <span class="text-red-600">*</span>
                    </label>
                </div>
                <InputError :message="errors.terms" />
            </div>

            <!-- Submit Button -->
            <Button type="submit"
                class="h-12 w-full text-base font-semibold bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 shadow-lg shadow-red-500/30 hover:shadow-red-500/50 transition-all"
                :tabindex="6" :disabled="processing || !acceptedTerms" data-test="register-user-button">
                <LoaderCircle v-if="processing" class="mr-2 h-5 w-5 animate-spin" />
                {{ processing ? 'Creating account...' : '🔥 Join Frank Meat & Taps' }}
            </Button>

            <!-- <p class="text-center text-xs leading-relaxed text-gray-500">
                By signing up, you agree to receive promotional emails and updates from us.
            </p> -->

            <!-- Admin Login Link -->
            <div class="pt-4 border-t border-gray-200">
                <div class="flex items-center justify-center gap-2 text-sm text-gray-500">
                    <Lock class="h-4 w-4" />
                    <span>Admin?</span>
                    <TextLink :href="login()" class="font-medium text-red-600 hover:text-red-700">
                        Login here
                    </TextLink>
                </div>
            </div>
        </Form>

        <!-- Terms Modal -->
        <Teleport to="body">
            <Transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0"
                enter-to-class="opacity-100" leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="showTermsModal"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4"
                    @click="closeTermsModal">
                    <Transition enter-active-class="transition duration-200 ease-out delay-100"
                        enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100"
                        leave-active-class="transition duration-150 ease-in" leave-from-class="opacity-100 scale-100"
                        leave-to-class="opacity-0 scale-95">
                        <div v-if="showTermsModal" @click.stop
                            class="relative w-full max-w-2xl max-h-[85vh] bg-gray-900 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-gray-800">
                            <!-- Header -->
                            <div class="sticky top-0 z-10 bg-gradient-to-r from-red-600 to-red-700 px-6 py-5">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/10 backdrop-blur-sm">
                                            <Shield class="h-6 w-6 text-white" />
                                        </div>
                                        <div>
                                            <h2 class="text-xl font-bold text-white">Wi-Fi Terms of Service</h2>
                                            <p class="mt-1 text-sm text-red-100">Frank Meat & Taps - Dubai</p>
                                        </div>
                                    </div>
                                    <button type="button" @click="closeTermsModal"
                                        class="rounded-lg p-2 text-white/80 hover:bg-white/20 hover:text-white transition-colors">
                                        <X class="h-5 w-5" />
                                    </button>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="custom-scrollbar max-h-[calc(85vh-180px)] overflow-y-auto px-6 py-6">
                                <div class="space-y-6">
                                    <div class="rounded-xl bg-red-950/30 p-4 border border-red-900/50">
                                        <div class="flex items-start gap-3">
                                            <Info class="h-5 w-5 text-red-500 mt-0.5 flex-shrink-0" />
                                            <p class="text-sm leading-relaxed text-gray-400">
                                                By accessing this wireless network, you acknowledge and agree to the
                                                following terms and conditions.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="space-y-5">
                                        <div class="space-y-3">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-950/50 text-sm font-bold text-red-500 ring-1 ring-red-900/50">
                                                    1</div>
                                                <h3 class="font-semibold text-white">Eligibility</h3>
                                            </div>
                                            <p class="pl-11 text-sm text-gray-400">You confirm that you are of legal age
                                                to use this service and agree to comply with all applicable laws.</p>
                                        </div>

                                        <div class="space-y-3">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-950/50 text-sm font-bold text-red-500 ring-1 ring-red-900/50">
                                                    2</div>
                                                <h3 class="font-semibold text-white">Acceptable Use</h3>
                                            </div>
                                            <p class="pl-11 text-sm text-gray-400">You agree not to use the network for
                                                unlawful purposes or any activity that may harm others.</p>
                                        </div>

                                        <div class="space-y-3">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-950/50 text-sm font-bold text-red-500 ring-1 ring-red-900/50">
                                                    3</div>
                                                <h3 class="font-semibold text-white">Data Collection</h3>
                                            </div>
                                            <p class="pl-11 text-sm text-gray-400">We collect your information for
                                                marketing purposes and to improve our services. You can opt out at any
                                                time.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div
                                class="sticky bottom-0 border-t border-gray-800 px-6 py-4 bg-gray-900/95 backdrop-blur-sm">
                                <div class="flex justify-end gap-3">
                                    <Button variant="outline" size="sm" @click="closeTermsModal"
                                        class="border-gray-700 hover:bg-gray-800">
                                        Close
                                    </Button>
                                    <Button size="sm" @click="acceptTerms"
                                        class="bg-red-600 hover:bg-red-700 shadow-lg shadow-red-500/30">
                                        <Check class="mr-2 h-4 w-4" />
                                        Accept & Continue
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </Transition>
                </div>
            </Transition>
        </Teleport>
    </AuthBase>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: rgb(243 244 246);
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgb(220 38 38 / 0.5);
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgb(220 38 38 / 0.7);
}
</style>
