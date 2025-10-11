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
import { LoaderCircle, Search, ChevronDown, Check, User, Mail, Phone, Globe, Sparkles, Info, Shield, Database, AlertCircle, X, Lock } from 'lucide-vue-next';
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

// Fetch countries from REST Countries API
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
    <AuthBase
        title="Frank Meat & Taps - Wi-Fi Terms of Service"
        description="Sign up for exclusive offers and updates"
    >
        <Head title="Register" />

        <Form
            v-bind="RegisteredUserController.store.form()"
            v-slot="{ errors, processing, data }"
            class="space-y-4"
        >
            <!-- Progress Bar -->
            <div class="space-y-1.5">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-xs text-muted-foreground">Profile completion</span>
                    <span class="text-xs font-medium text-orange-600">{{ Math.round(formProgress) }}%</span>
                </div>
                <div class="h-1.5 w-full overflow-hidden rounded-full bg-orange-100 dark:bg-orange-900/30">
                    <div
                        class="h-full bg-gradient-to-r from-orange-500 to-amber-500 transition-all duration-500 ease-out"
                        :style="{ width: `${formProgress}%` }"
                    ></div>
                </div>
            </div>

            <!-- Country Field with Custom Select -->
            <div class="space-y-1.5">
                <Label for="country" class="flex items-center gap-1.5 text-xs">
                    <Globe class="h-3 w-3 text-orange-600" />
                    Country
                    <span class="text-destructive">*</span>
                </Label>

                <div class="country-select-wrapper relative">
                    <input
                        ref="countryInputRef"
                        type="hidden"
                        name="country"
                        required
                        :value="selectedCountry?.cca2 || ''"
                    />

                    <button
                        type="button"
                        @click="toggleDropdown"
                        :tabindex="4"
                        class="flex h-10 w-full items-center justify-between rounded-lg border border-input bg-background px-3 py-1.5 text-sm ring-offset-background transition-all hover:border-orange-300 dark:hover:border-orange-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        :class="{ 'ring-2 ring-orange-500 ring-offset-2': isDropdownOpen }"
                    >
                        <span class="flex items-center gap-2">
                            <template v-if="selectedCountry">
                                <img
                                    :src="selectedCountry.flags.svg"
                                    :alt="selectedCountry.name.common"
                                    class="h-4 w-5 rounded object-cover shadow-sm"
                                />
                                <span class="truncate text-xs">{{ selectedCountry.name.common }}</span>
                            </template>
                            <template v-else>
                                <span class="text-xs text-muted-foreground">
                                    {{ isLoadingCountries ? 'Loading countries...' : 'Select country' }}
                                </span>
                            </template>
                        </span>
                        <ChevronDown
                            class="h-3 w-3 opacity-50 transition-transform duration-200"
                            :class="{ 'rotate-180': isDropdownOpen }"
                        />
                    </button>

                    <Transition
                        enter-active-class="transition duration-200 ease-out"
                        enter-from-class="opacity-0 scale-95"
                        enter-to-class="opacity-100 scale-100"
                        leave-active-class="transition duration-150 ease-in"
                        leave-from-class="opacity-100 scale-100"
                        leave-to-class="opacity-0 scale-95"
                    >
                        <div
                            v-if="isDropdownOpen"
                            class="absolute z-50 mt-1.5 w-full rounded-lg border border-orange-200 dark:border-orange-800 bg-white dark:bg-gray-900 shadow-lg shadow-orange-500/10"
                        >
                            <div class="border-b border-orange-100 dark:border-orange-900 p-2">
                                <div class="relative">
                                    <Search class="absolute left-2.5 top-1/2 h-3 w-3 -translate-y-1/2 text-muted-foreground" />
                                    <input
                                        v-model="searchQuery"
                                        type="text"
                                        placeholder="Search countries..."
                                        class="h-8 w-full rounded-md border border-input bg-background pl-7 pr-2 text-xs ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-500 focus-visible:ring-offset-1"
                                        @click.stop
                                    />
                                </div>
                            </div>

                            <div class="custom-scrollbar max-h-[240px] overflow-y-auto p-2">
                                <template v-if="isLoadingCountries">
                                    <div class="flex items-center justify-center py-6">
                                        <LoaderCircle class="h-5 w-5 animate-spin text-orange-600" />
                                    </div>
                                </template>

                                <template v-else-if="filteredCountries.length === 0">
                                    <div class="py-6 text-center text-xs text-muted-foreground">
                                        No countries found
                                    </div>
                                </template>

                                <template v-else>
                                    <button
                                        v-for="country in filteredCountries"
                                        :key="country.cca2"
                                        type="button"
                                        @click="selectCountry(country)"
                                        class="flex w-full items-center gap-2.5 rounded-md px-2.5 py-2 text-xs transition-colors hover:bg-orange-50 dark:hover:bg-orange-950/30"
                                        :class="{ 'bg-orange-50 dark:bg-orange-950/20 ring-1 ring-orange-200 dark:ring-orange-800': selectedCountry?.cca2 === country.cca2 }"
                                    >
                                        <img
                                            :src="country.flags.svg"
                                            :alt="country.name.common"
                                            class="h-3.5 w-5 rounded object-cover shadow-sm"
                                        />
                                        <span class="flex-1 truncate text-left">{{ country.name.common }}</span>
                                        <Check
                                            v-if="selectedCountry?.cca2 === country.cca2"
                                            class="h-3 w-3 text-orange-600"
                                        />
                                    </button>
                                </template>
                            </div>
                        </div>
                    </Transition>
                </div>

                <InputError :message="errors.country" />
            </div>

            <!-- Name Field -->
            <div class="group space-y-1.5">
                <Label for="name" class="flex items-center gap-1.5 text-xs">
                    <User class="h-3 w-3 text-orange-600" />
                    Full Name
                    <span class="text-destructive">*</span>
                </Label>
                <div class="relative">
                    <Input
                        id="name"
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="name"
                        name="name"
                        placeholder="John Doe"
                        class="h-10 pl-3 pr-8 text-sm transition-all focus:ring-2 focus:ring-orange-500 group-hover:border-orange-300"
                        @input="updateProgress"
                    />
                    <div class="absolute right-2.5 top-1/2 -translate-y-1/2 text-orange-400 opacity-0 transition-opacity group-hover:opacity-100">
                        <Sparkles class="h-3 w-3" />
                    </div>
                </div>
                <InputError :message="errors.name" />
            </div>

            <!-- Email Field -->
            <div class="group space-y-1.5">
                <Label for="email" class="flex items-center gap-1.5 text-xs">
                    <Mail class="h-3 w-3 text-orange-600" />
                    Email Address
                    <span class="text-destructive">*</span>
                </Label>
                <div class="relative">
                    <Input
                        id="email"
                        type="email"
                        required
                        :tabindex="2"
                        autocomplete="email"
                        name="email"
                        placeholder="john@example.com"
                        class="h-10 pl-3 pr-8 text-sm transition-all focus:ring-2 focus:ring-orange-500 group-hover:border-orange-300"
                        @input="updateProgress"
                    />
                    <div class="absolute right-2.5 top-1/2 -translate-y-1/2 text-orange-400 opacity-0 transition-opacity group-hover:opacity-100">
                        <Sparkles class="h-3 w-3" />
                    </div>
                </div>
                <InputError :message="errors.email" />
            </div>

            <!-- Phone Field -->
            <div class="group space-y-1.5">
                <Label for="phone" class="flex items-center gap-1.5 text-xs">
                    <Phone class="h-3 w-3 text-orange-600" />
                    Phone Number
                    <span class="text-destructive">*</span>
                </Label>
                <div class="relative">
                    <Input
                        id="phone"
                        type="tel"
                        required
                        :tabindex="3"
                        autocomplete="tel"
                        name="phone"
                        placeholder="+1 (555) 123-4567"
                        class="h-10 pl-3 pr-8 text-sm transition-all focus:ring-2 focus:ring-orange-500 group-hover:border-orange-300"
                        @input="updateProgress"
                    />
                    <div class="absolute right-2.5 top-1/2 -translate-y-1/2 text-orange-400 opacity-0 transition-opacity group-hover:opacity-100">
                        <Sparkles class="h-3 w-3" />
                    </div>
                </div>
                <InputError :message="errors.phone" />
            </div>

            <!-- Terms of Service -->
            <div class="space-y-2">
                <div class="flex items-start gap-2.5">
                    <input
                        id="terms"
                        type="checkbox"
                        v-model="acceptedTerms"
                        @change="updateProgress"
                        :tabindex="5"
                        required
                        class="mt-0.5 h-4 w-4 rounded border-orange-300 text-orange-600 focus:ring-2 focus:ring-orange-500 focus:ring-offset-1"
                    />
                    <label for="terms" class="flex-1 text-[11px] leading-relaxed text-muted-foreground">
                        I agree to the
                        <button
                            type="button"
                            @click="openTermsModal"
                            class="font-medium text-orange-600 hover:text-orange-700 dark:text-orange-400 dark:hover:text-orange-300 underline decoration-orange-300 underline-offset-2 transition-colors"
                        >
                            Wi-Fi Terms of Service
                        </button>
                        and consent to receive promotional emails
                        <span class="text-destructive">*</span>
                    </label>
                </div>
                <InputError :message="errors.terms" />
            </div>

            <!-- Submit Button -->
            <Button
                type="submit"
                size="sm"
                class="mt-2 w-full text-sm"
                :tabindex="6"
                :disabled="processing || !acceptedTerms"
                variant="default"
                data-test="register-user-button"
            >
                <LoaderCircle
                    v-if="processing"
                    class="mr-1.5 h-3.5 w-3.5 animate-spin"
                />
                {{ processing ? 'Creating account...' : '🍽️ Join Our Family' }}
            </Button>

            <p class="text-center text-[10px] leading-relaxed text-muted-foreground">
                By signing up, you agree to receive promotional emails and updates from us.
            </p>

             <!-- Admin Login Link -->
            <div class="pt-3 border-t border-border">
                <div class="flex items-center justify-center gap-1.5 text-xs text-muted-foreground">
                    <Lock class="h-3 w-3" />
                    <span>Admin?</span>
                    <TextLink :href="login()" class="text-xs font-medium">
                        Login here
                    </TextLink>
                </div>
            </div>
        </Form>

        <!-- Terms of Service Modal -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="showTermsModal"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
                    @click="closeTermsModal"
                >
                    <Transition
                        enter-active-class="transition duration-300 ease-out"
                        enter-from-class="opacity-0 scale-95 translate-y-4"
                        enter-to-class="opacity-100 scale-100 translate-y-0"
                        leave-active-class="transition duration-200 ease-in"
                        leave-from-class="opacity-100 scale-100 translate-y-0"
                        leave-to-class="opacity-0 scale-95 translate-y-4"
                    >
                        <div
                            v-if="showTermsModal"
                            @click.stop
                            class="relative w-full max-w-2xl max-h-[85vh] bg-white dark:bg-gray-900 rounded-2xl shadow-2xl shadow-orange-500/20 overflow-hidden"
                        >
                            <!-- Header -->
                            <div class="sticky top-0 z-10 bg-gradient-to-br from-orange-500 to-amber-600 px-6 py-5">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20 backdrop-blur-sm">
                                            <Shield class="h-5 w-5 text-white" />
                                        </div>
                                        <div>
                                            <h2 class="text-xl font-bold text-white">Wi-Fi Terms of Service</h2>
                                            <p class="mt-1 text-sm text-orange-100">Frank Meat & Taps</p>
                                        </div>
                                    </div>
                                    <button
                                        type="button"
                                        @click="closeTermsModal"
                                        class="rounded-lg p-1.5 text-white/80 transition-colors hover:bg-white/20 hover:text-white"
                                    >
                                        <X class="h-5 w-5" />
                                    </button>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="custom-scrollbar max-h-[calc(85vh-180px)] overflow-y-auto px-6 py-6">
                                <div class="space-y-6 text-sm">
                                    <!-- Introduction -->
                                    <div class="rounded-xl bg-orange-50 dark:bg-orange-950/30 p-4 border border-orange-200 dark:border-orange-800">
                                        <div class="flex items-start gap-3">
                                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-orange-100 dark:bg-orange-900/50">
                                                <Info class="h-4 w-4 text-orange-600" />
                                            </div>
                                            <p class="text-xs leading-relaxed text-muted-foreground">
                                                By accessing this wireless network, you acknowledge and agree to the following terms and conditions.
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Terms List -->
                                    <div class="space-y-5">
                                        <!-- 1. Eligibility -->
                                        <div class="group space-y-2">
                                            <div class="flex items-center gap-2">
                                                <div class="flex h-6 w-6 items-center justify-center rounded-md bg-orange-100 dark:bg-orange-900/50 text-xs font-bold text-orange-600">
                                                    1
                                                </div>
                                                <h3 class="font-semibold text-foreground">Eligibility</h3>
                                            </div>
                                            <p class="pl-8 text-xs leading-relaxed text-muted-foreground">
                                                You confirm that you are of legal age to use this service.
                                            </p>
                                        </div>

                                        <!-- 2. Acceptable Use -->
                                        <div class="group space-y-2">
                                            <div class="flex items-center gap-2">
                                                <div class="flex h-6 w-6 items-center justify-center rounded-md bg-orange-100 dark:bg-orange-900/50 text-xs font-bold text-orange-600">
                                                    2
                                                </div>
                                                <h3 class="font-semibold text-foreground">Acceptable Use</h3>
                                            </div>
                                            <p class="pl-8 text-xs leading-relaxed text-muted-foreground">
                                                You agree not to use the wireless network for any purpose that is unlawful, illegal, or prohibited. You take full responsibility for your actions while connected.
                                            </p>
                                        </div>

                                        <!-- 3. No Warranty -->
                                        <div class="group space-y-2">
                                            <div class="flex items-center gap-2">
                                                <div class="flex h-6 w-6 items-center justify-center rounded-md bg-orange-100 dark:bg-orange-900/50 text-xs font-bold text-orange-600">
                                                    3
                                                </div>
                                                <h3 class="font-semibold text-foreground">No Warranty</h3>
                                            </div>
                                            <p class="pl-8 text-xs leading-relaxed text-muted-foreground">
                                                The wireless network is provided "as is" without warranties of any kind, either expressed or implied. We do not guarantee availability, security, or performance.
                                            </p>
                                        </div>

                                        <!-- 4. Data Collection & Marketing -->
                                        <div class="group space-y-2">
                                            <div class="flex items-center gap-2">
                                                <div class="flex h-6 w-6 items-center justify-center rounded-md bg-orange-100 dark:bg-orange-900/50 text-xs font-bold text-orange-600">
                                                    4
                                                </div>
                                                <h3 class="font-semibold text-foreground">Data Collection & Marketing</h3>
                                            </div>
                                            <div class="pl-8 space-y-3">
                                                <div class="rounded-lg bg-blue-50 dark:bg-blue-950/30 p-3 border border-blue-200 dark:border-blue-800">
                                                    <div class="flex items-start gap-2">
                                                        <Database class="h-3.5 w-3.5 text-blue-600 mt-0.5 shrink-0" />
                                                        <p class="text-xs leading-relaxed text-muted-foreground">
                                                            By using this Wi-Fi service, you consent to the collection of certain personal information (such as your name, email address, and phone number).
                                                        </p>
                                                    </div>
                                                </div>
                                                <ul class="space-y-2 text-xs leading-relaxed text-muted-foreground">
                                                    <li class="flex items-start gap-2">
                                                        <Check class="h-3.5 w-3.5 text-orange-600 mt-0.5 shrink-0" />
                                                        <span>This information may be used for marketing purposes, including promotional messages, special offers, and service updates.</span>
                                                    </li>
                                                    <li class="flex items-start gap-2">
                                                        <Check class="h-3.5 w-3.5 text-orange-600 mt-0.5 shrink-0" />
                                                        <span>We will not sell or share your data with unauthorised third parties.</span>
                                                    </li>
                                                    <li class="flex items-start gap-2">
                                                        <Check class="h-3.5 w-3.5 text-orange-600 mt-0.5 shrink-0" />
                                                        <span>You may opt out of marketing communications at any time by following the unsubscribe instructions provided in our messages.</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <!-- 5. Limitation of Liability -->
                                        <div class="group space-y-2">
                                            <div class="flex items-center gap-2">
                                                <div class="flex h-6 w-6 items-center justify-center rounded-md bg-orange-100 dark:bg-orange-900/50 text-xs font-bold text-orange-600">
                                                    5
                                                </div>
                                                <h3 class="font-semibold text-foreground">Limitation of Liability</h3>
                                            </div>
                                            <div class="pl-8 rounded-lg bg-amber-50 dark:bg-amber-950/30 p-3 border border-amber-200 dark:border-amber-800">
                                                <div class="flex items-start gap-2">
                                                    <AlertCircle class="h-3.5 w-3.5 text-amber-600 mt-0.5 shrink-0" />
                                                    <p class="text-xs leading-relaxed text-muted-foreground">
                                                        We are not liable for any damages, losses, or issues arising from your use of the wireless network.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="sticky bottom-0 z-10 border-t border-border bg-white dark:bg-gray-900 px-6 py-4">
                                <div class="flex items-center justify-between gap-4">
                                    <p class="text-[10px] text-muted-foreground">
                                        Last updated: October 11, 2025
                                    </p>
                                    <div class="flex gap-3">
                                        <Button
                                            type="button"
                                            variant="outline"
                                            size="sm"
                                            @click="closeTermsModal"
                                            class="text-xs"
                                        >
                                            Close
                                        </Button>
                                        <Button
                                            type="button"
                                            size="sm"
                                            @click="acceptTerms"
                                            class="text-xs"
                                        >
                                            <Check class="mr-1.5 h-3.5 w-3.5" />
                                            Accept Terms
                                        </Button>
                                    </div>
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
/* Custom scrollbar for dropdown */
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: hsl(var(--orange-300) / 0.3);
    border-radius: 2px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: hsl(var(--orange-400) / 0.4);
}
</style>
