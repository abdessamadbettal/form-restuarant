<script setup lang="ts">
import RegisteredUserController from '@/actions/App/Http/Controllers/Auth/RegisteredUserController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle, Search, ChevronDown, Check } from 'lucide-vue-next';
import { ref, computed, onMounted } from 'vue';

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

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});
</script>

<template>
    <AuthBase
        title="Join Our Restaurant Family"
        description="Sign up to receive exclusive offers and updates"
    >
        <Head title="Register" />

        <Form
            v-bind="RegisteredUserController.store.form()"
            v-slot="{ errors, processing, data }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-6">
                <!-- Name Field -->
                <div class="grid gap-2">
                    <Label for="name" class="text-sm font-medium">
                        Full Name
                        <span class="text-destructive">*</span>
                    </Label>
                    <Input
                        id="name"
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="name"
                        name="name"
                        placeholder="John Doe"
                        class="h-11"
                    />
                    <InputError :message="errors.name" />
                </div>

                <!-- Email Field -->
                <div class="grid gap-2">
                    <Label for="email" class="text-sm font-medium">
                        Email Address
                        <span class="text-destructive">*</span>
                    </Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        :tabindex="2"
                        autocomplete="email"
                        name="email"
                        placeholder="john@example.com"
                        class="h-11"
                    />
                    <InputError :message="errors.email" />
                </div>

                <!-- Phone Field -->
                <div class="grid gap-2">
                    <Label for="phone" class="text-sm font-medium">
                        Phone Number
                        <span class="text-destructive">*</span>
                    </Label>
                    <Input
                        id="phone"
                        type="tel"
                        required
                        :tabindex="3"
                        autocomplete="tel"
                        name="phone"
                        placeholder="+1 (555) 123-4567"
                        class="h-11"
                    />
                    <InputError :message="errors.phone" />
                </div>

                <!-- Country Field with Custom Select -->
                <div class="grid gap-2">
                    <Label for="country" class="text-sm font-medium">
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
                            class="flex h-11 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            :class="{ 'ring-2 ring-ring ring-offset-2': isDropdownOpen }"
                        >
                            <span class="flex items-center gap-2">
                                <template v-if="selectedCountry">
                                    <img
                                        :src="selectedCountry.flags.svg"
                                        :alt="selectedCountry.name.common"
                                        class="h-4 w-6 rounded object-cover"
                                    />
                                    <span class="truncate">{{ selectedCountry.name.common }}</span>
                                </template>
                                <template v-else>
                                    <span class="text-muted-foreground">
                                        {{ isLoadingCountries ? 'Loading countries...' : 'Select your country' }}
                                    </span>
                                </template>
                            </span>
                            <ChevronDown
                                class="h-4 w-4 opacity-50 transition-transform"
                                :class="{ 'rotate-180': isDropdownOpen }"
                            />
                        </button>

                        <!-- Dropdown Menu -->
                        <div
                            v-if="isDropdownOpen"
                            class="absolute z-50 mt-2 w-full rounded-md border bg-popover shadow-lg"
                        >
                            <!-- Search Input -->
                            <div class="border-b p-2">
                                <div class="relative">
                                    <Search class="absolute left-2 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                    <input
                                        v-model="searchQuery"
                                        type="text"
                                        placeholder="Search countries..."
                                        class="h-9 w-full rounded-md border border-input bg-background pl-8 pr-3 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                                        @click.stop
                                    />
                                </div>
                            </div>

                            <!-- Countries List -->
                            <div class="max-h-[300px] overflow-y-auto p-1">
                                <template v-if="isLoadingCountries">
                                    <div class="flex items-center justify-center py-6">
                                        <LoaderCircle class="h-6 w-6 animate-spin text-muted-foreground" />
                                    </div>
                                </template>

                                <template v-else-if="filteredCountries.length === 0">
                                    <div class="py-6 text-center text-sm text-muted-foreground">
                                        No countries found
                                    </div>
                                </template>

                                <template v-else>
                                    <button
                                        v-for="country in filteredCountries"
                                        :key="country.cca2"
                                        type="button"
                                        @click="selectCountry(country)"
                                        class="flex w-full items-center gap-2 rounded-sm px-2 py-2 text-sm transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                                        :class="{ 'bg-accent': selectedCountry?.cca2 === country.cca2 }"
                                    >
                                        <img
                                            :src="country.flags.svg"
                                            :alt="country.name.common"
                                            class="h-4 w-6 rounded object-cover"
                                        />
                                        <span class="flex-1 truncate text-left">{{ country.name.common }}</span>
                                        <Check
                                            v-if="selectedCountry?.cca2 === country.cca2"
                                            class="h-4 w-4"
                                        />
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <InputError :message="errors.country" />
                </div>

                <!-- Submit Button -->
                <Button
                    type="submit"
                    class="mt-4 h-11 w-full text-base font-semibold"
                    tabindex="5"
                    :disabled="processing"
                    data-test="register-user-button"
                >
                    <LoaderCircle
                        v-if="processing"
                        class="mr-2 h-5 w-5 animate-spin"
                    />
                    {{ processing ? 'Signing up...' : 'Sign Up' }}
                </Button>

                <p class="text-center text-xs text-muted-foreground">
                    By signing up, you agree to receive promotional emails and updates from our restaurant.
                </p>
            </div>
        </Form>
    </AuthBase>
</template>

<style scoped>
/* Custom scrollbar for dropdown */
.max-h-\[300px\]::-webkit-scrollbar {
    width: 8px;
}

.max-h-\[300px\]::-webkit-scrollbar-track {
    background: transparent;
}

.max-h-\[300px\]::-webkit-scrollbar-thumb {
    background: hsl(var(--muted-foreground) / 0.3);
    border-radius: 4px;
}

.max-h-\[300px\]::-webkit-scrollbar-thumb:hover {
    background: hsl(var(--muted-foreground) / 0.5);
}
</style>
