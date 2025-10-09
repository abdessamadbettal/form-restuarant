<script setup lang="ts">
import RegisteredUserController from '@/actions/App/Http/Controllers/Auth/RegisteredUserController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle, Search, ChevronDown, Check, User, Mail, Phone, Globe, Sparkles } from 'lucide-vue-next';
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

    // Update the hidden input value
    if (countryInputRef.value) {
        countryInputRef.value.value = country.cca2;
        countryInputRef.value.dispatchEvent(new Event('input', { bubbles: true }));
    }

    updateProgress();
};

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};

// Close dropdown when clicking outside
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
        selectedCountry.value
    ];

    const filledFields = fields.filter(field => {
        if (field instanceof HTMLInputElement) {
            return field.value.trim() !== '';
        }
        return field !== null;
    }).length;

    formProgress.value = (filledFields / fields.length) * 100;
};
</script>

<template>
    <AuthBase
        title="Join Our Restaurant"
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
                    <!-- Hidden input for form submission -->
                    <input
                        ref="countryInputRef"
                        type="hidden"
                        name="country"
                        required
                        :value="selectedCountry?.cca2 || ''"
                    />

                    <!-- Custom Select Button -->
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

                    <!-- Dropdown Menu -->
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
                            <!-- Search Input -->
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

                            <!-- Countries List -->
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




            <!-- Submit Button -->
            <Button
                type="submit"
                size="sm"
                class="mt-2 w-full text-sm"
                :tabindex="5"
                :disabled="processing"
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
        </Form>
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
