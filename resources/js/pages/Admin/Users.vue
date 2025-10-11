<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import CustomSelect from '@/components/CustomSelect.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import {
    ArrowLeft, Mail, Phone, Globe, Download, FileSpreadsheet,
    FileJson, FileText, Search, Filter, X, Users as UsersIcon,
    Calendar, TrendingUp
} from 'lucide-vue-next';
import { ref, watch, computed } from 'vue';
import { type BreadcrumbItem } from '@/types';

interface User {
    id: number;
    name: string;
    email: string;
    phone: string;
    country: string;
    created_at: string;
}

interface Props {
    users: {
        data: User[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        from: number;
        to: number;
    };
    stats: {
        total: number;
        today: number;
        this_week: number;
        this_month: number;
    };
    countries: string[];
    filters: {
        search?: string;
        start_date?: string;
        end_date?: string;
        country?: string;
    };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/admin/dashboard' },
    { title: 'Users', href: '/admin/users' },
];

const showFilters = ref(false);
const exportFormat = ref('xlsx');

const filterForm = useForm({
    search: props.filters.search || '',
    start_date: props.filters.start_date || '',
    end_date: props.filters.end_date || '',
    country: props.filters.country || '',
});

// Export format options (removed PDF)
const exportFormatOptions = [
    { value: 'xlsx', label: 'Excel (.xlsx)' },
    { value: 'csv', label: 'CSV (.csv)' },
    { value: 'json', label: 'JSON (.json)' },
];

// Country options
const countryOptions = computed(() => {
    return [
        { value: '', label: 'All countries' },
        ...props.countries.map(country => ({
            value: country,
            label: country,
        })),
    ];
});

// Debounced search
let searchTimeout: ReturnType<typeof setTimeout>;
watch(() => filterForm.search, (value) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 300);
});

const applyFilters = () => {
    router.get('/admin/users', filterForm.data(), {
        preserveState: true,
        preserveScroll: true,
    });
};

const clearFilters = () => {
    filterForm.reset();
    router.get('/admin/users');
};

const exportData = () => {
    const params = new URLSearchParams({
        format: exportFormat.value,
        ...(filterForm.start_date && { start_date: filterForm.start_date }),
        ...(filterForm.end_date && { end_date: filterForm.end_date }),
        ...(filterForm.country && { country: filterForm.country }),
    });

    window.location.href = `/admin/users/export?${params.toString()}`;
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const hasActiveFilters = () => {
    return filterForm.search || filterForm.start_date || filterForm.end_date || filterForm.country;
};

const getExportSummary = computed(() => {
    const filters = [];

    if (filterForm.start_date || filterForm.end_date) {
        if (filterForm.start_date && filterForm.end_date) {
            filters.push(`Date: ${filterForm.start_date} to ${filterForm.end_date}`);
        } else if (filterForm.start_date) {
            filters.push(`From: ${filterForm.start_date}`);
        } else {
            filters.push(`Until: ${filterForm.end_date}`);
        }
    }

    if (filterForm.country) {
        const countryName = props.countries.find(c => c === filterForm.country);
        filters.push(`Country: ${countryName || filterForm.country}`);
    }

    return filters.length > 0 ? filters.join(' • ') : 'All users';
});
</script>

<template>
    <Head title="Manage Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Stats Cards -->
            <div class="grid gap-4 md:grid-cols-4">
                <div class="rounded-xl border bg-white p-6 shadow-sm dark:bg-gray-900">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Total Users</p>
                            <h3 class="mt-2 text-3xl font-bold">{{ stats.total }}</h3>
                        </div>
                        <div class="rounded-lg bg-orange-100 p-3 dark:bg-orange-900/30">
                            <UsersIcon class="h-6 w-6 text-orange-600" />
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border bg-white p-6 shadow-sm dark:bg-gray-900">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Today</p>
                            <h3 class="mt-2 text-3xl font-bold">{{ stats.today }}</h3>
                        </div>
                        <div class="rounded-lg bg-blue-100 p-3 dark:bg-blue-900/30">
                            <Calendar class="h-6 w-6 text-blue-600" />
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border bg-white p-6 shadow-sm dark:bg-gray-900">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">This Week</p>
                            <h3 class="mt-2 text-3xl font-bold">{{ stats.this_week }}</h3>
                        </div>
                        <div class="rounded-lg bg-green-100 p-3 dark:bg-green-900/30">
                            <TrendingUp class="h-6 w-6 text-green-600" />
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border bg-white p-6 shadow-sm dark:bg-gray-900">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">This Month</p>
                            <h3 class="mt-2 text-3xl font-bold">{{ stats.this_month }}</h3>
                        </div>
                        <div class="rounded-lg bg-purple-100 p-3 dark:bg-purple-900/30">
                            <Calendar class="h-6 w-6 text-purple-600" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Export -->
            <div class="rounded-xl border bg-white p-6 shadow-sm dark:bg-gray-900">
                <div class="flex flex-col gap-4">
                    <!-- Search and Actions Bar -->
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="relative flex-1 min-w-[200px]">
                            <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                            <Input
                                v-model="filterForm.search"
                                placeholder="Search by name, email, or phone..."
                                class="pl-9"
                            />
                        </div>

                        <Button
                            @click="showFilters = !showFilters"
                            variant="outline"
                            class="gap-2"
                        >
                            <Filter class="h-4 w-4" />
                            Filters
                            <span v-if="hasActiveFilters()" class="ml-1 rounded-full bg-orange-600 px-2 py-0.5 text-xs text-white">
                                {{ [filterForm.search, filterForm.start_date, filterForm.end_date, filterForm.country].filter(Boolean).length }}
                            </span>
                        </Button>

                        <div class="flex items-center gap-2">
                            <div class="flex items-center gap-2">
                                <FileSpreadsheet class="h-4 w-4 text-muted-foreground" />
                                <CustomSelect
                                    v-model="exportFormat"
                                    :options="exportFormatOptions"
                                    placeholder="Select format"
                                    class="w-[140px]"
                                />
                            </div>

                            <Button @click="exportData" class="gap-2">
                                <Download class="h-4 w-4" />
                                Export
                            </Button>
                        </div>
                    </div>

                    <!-- Export Info Banner -->
                    <div v-if="hasActiveFilters()" class="rounded-lg bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800 p-3">
                        <div class="flex items-start gap-2">
                            <FileSpreadsheet class="h-4 w-4 text-blue-600 mt-0.5" />
                            <div class="flex-1">
                                <p class="text-xs font-medium text-blue-900 dark:text-blue-100">
                                    Export will include: {{ getExportSummary }}
                                </p>
                                <p class="mt-1 text-xs text-blue-700 dark:text-blue-300">
                                    Your export will contain {{ users.total }} user{{ users.total !== 1 ? 's' : '' }} based on current filters
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Advanced Filters -->
                    <Transition
                        enter-active-class="transition duration-200 ease-out"
                        enter-from-class="opacity-0 -translate-y-2"
                        enter-to-class="opacity-100 translate-y-0"
                        leave-active-class="transition duration-150 ease-in"
                        leave-from-class="opacity-100 translate-y-0"
                        leave-to-class="opacity-0 -translate-y-2"
                    >
                        <div v-if="showFilters" class="grid gap-4 rounded-lg border bg-muted/50 p-4 md:grid-cols-3">
                            <div class="space-y-2">
                                <Label for="start_date" class="text-xs font-medium">Start Date</Label>
                                <Input
                                    id="start_date"
                                    v-model="filterForm.start_date"
                                    type="date"
                                    class="h-10"
                                />
                            </div>

                            <div class="space-y-2">
                                <Label for="end_date" class="text-xs font-medium">End Date</Label>
                                <Input
                                    id="end_date"
                                    v-model="filterForm.end_date"
                                    type="date"
                                    class="h-10"
                                />
                            </div>

                            <div class="space-y-2">
                                <Label for="country" class="text-xs font-medium">Country</Label>
                                <CustomSelect
                                    v-model="filterForm.country"
                                    :options="countryOptions"
                                    placeholder="All countries"
                                />
                            </div>

                            <div class="flex items-end gap-2 md:col-span-3">
                                <Button @click="applyFilters" class="flex-1">
                                    Apply Filters
                                </Button>
                                <Button @click="clearFilters" variant="outline">
                                    <X class="h-4 w-4 mr-2" />
                                    Clear
                                </Button>
                            </div>
                        </div>
                    </Transition>
                </div>
            </div>

            <!-- Users Table -->
            <div class="rounded-xl border bg-white shadow-sm dark:bg-gray-900">
                <!-- Empty State -->
                <div v-if="users.data.length === 0" class="flex flex-col items-center justify-center py-12">
                    <div class="rounded-full bg-muted p-6">
                        <UsersIcon class="h-12 w-12 text-muted-foreground" />
                    </div>
                    <h3 class="mt-4 text-lg font-semibold">No users found</h3>
                    <p class="mt-2 text-sm text-muted-foreground">
                        {{ hasActiveFilters() ? 'Try adjusting your filters' : 'No users have registered yet' }}
                    </p>
                    <Button v-if="hasActiveFilters()" @click="clearFilters" variant="outline" class="mt-4">
                        Clear Filters
                    </Button>
                </div>

                <!-- Table with Data -->
                <div v-else class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b bg-muted/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground">
                                    Name
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground">
                                    Contact
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground">
                                    Country
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground">
                                    Registered
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <tr v-for="user in users.data" :key="user.id" class="transition-colors hover:bg-muted/50">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="font-medium">{{ user.name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1 text-sm">
                                        <div class="flex items-center gap-2 text-muted-foreground">
                                            <Mail class="h-3.5 w-3.5 shrink-0" />
                                            <span class="truncate">{{ user.email }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-muted-foreground">
                                            <Phone class="h-3.5 w-3.5 shrink-0" />
                                            <span>{{ user.phone }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <Globe class="h-4 w-4 text-muted-foreground" />
                                        <span class="text-sm">{{ user.country }}</span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-muted-foreground">
                                    {{ formatDate(user.created_at) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="users.last_page > 1" class="border-t px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-muted-foreground">
                            Showing {{ users.from }} to {{ users.to }} of {{ users.total }} results
                        </div>
                        <div class="flex gap-2">
                            <Link
                                v-if="users.current_page > 1"
                                :href="`/admin/users?page=${users.current_page - 1}`"
                                preserve-state
                            >
                                <Button variant="outline" size="sm">Previous</Button>
                            </Link>
                            <div class="flex items-center gap-2 px-3">
                                <span class="text-sm text-muted-foreground">
                                    Page {{ users.current_page }} of {{ users.last_page }}
                                </span>
                            </div>
                            <Link
                                v-if="users.current_page < users.last_page"
                                :href="`/admin/users?page=${users.current_page + 1}`"
                                preserve-state
                            >
                                <Button variant="outline" size="sm">Next</Button>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
