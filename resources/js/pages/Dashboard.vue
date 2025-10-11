<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Users, Calendar, TrendingUp, Clock } from 'lucide-vue-next';
import { computed } from 'vue';

interface PageProps {
    auth: {
        user: {
            name: string;
            email: string;
        };
    };
}

const page = usePage<PageProps>();
const user = computed(() => page.props.auth.user);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Welcome Header -->
            <div class="rounded-xl bg-gradient-to-r from-orange-500 to-amber-600 p-6 text-white shadow-lg">
                <h1 class="text-2xl font-bold">Welcome back, {{ user.name }}! 👋</h1>
                <p class="mt-2 text-orange-100">Here's what's happening with your restaurant today.</p>
            </div>

            <!-- Quick Actions -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <Link
                    :href="'/admin/users'"
                    class="group relative overflow-hidden rounded-xl border bg-white p-6 shadow-sm transition-all hover:shadow-md dark:bg-gray-900"
                >
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Manage Users</p>
                            <h3 class="mt-2 text-2xl font-bold">View All</h3>
                        </div>
                        <div class="rounded-lg bg-orange-100 p-3 dark:bg-orange-900/30">
                            <Users class="h-6 w-6 text-orange-600" />
                        </div>
                    </div>
                    <p class="mt-4 text-xs text-muted-foreground">View and export user data</p>
                </Link>

                <div class="relative overflow-hidden rounded-xl border bg-white p-6 shadow-sm dark:bg-gray-900">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">This Week</p>
                            <h3 class="mt-2 text-2xl font-bold">New Users</h3>
                        </div>
                        <div class="rounded-lg bg-blue-100 p-3 dark:bg-blue-900/30">
                            <Calendar class="h-6 w-6 text-blue-600" />
                        </div>
                    </div>
                    <p class="mt-4 text-xs text-muted-foreground">Check user statistics</p>
                </div>

                <div class="relative overflow-hidden rounded-xl border bg-white p-6 shadow-sm dark:bg-gray-900">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Growth</p>
                            <h3 class="mt-2 text-2xl font-bold">Analytics</h3>
                        </div>
                        <div class="rounded-lg bg-green-100 p-3 dark:bg-green-900/30">
                            <TrendingUp class="h-6 w-6 text-green-600" />
                        </div>
                    </div>
                    <p class="mt-4 text-xs text-muted-foreground">View growth trends</p>
                </div>

                <div class="relative overflow-hidden rounded-xl border bg-white p-6 shadow-sm dark:bg-gray-900">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Recent</p>
                            <h3 class="mt-2 text-2xl font-bold">Activity</h3>
                        </div>
                        <div class="rounded-lg bg-purple-100 p-3 dark:bg-purple-900/30">
                            <Clock class="h-6 w-6 text-purple-600" />
                        </div>
                    </div>
                    <p class="mt-4 text-xs text-muted-foreground">Recent registrations</p>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-1 rounded-xl border bg-white p-6 dark:bg-gray-900">
                <div class="flex flex-col items-center justify-center py-12">
                    <div class="rounded-full bg-orange-100 p-6 dark:bg-orange-900/30">
                        <Users class="h-12 w-12 text-orange-600" />
                    </div>
                    <h2 class="mt-4 text-xl font-semibold">User Management System</h2>
                    <p class="mt-2 text-center text-sm text-muted-foreground">
                        Manage your registered users, export data, and view analytics
                    </p>
                    <Link :href="'/admin/users'" class="mt-6">
                        <Button size="lg">
                            Go to Users
                        </Button>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
