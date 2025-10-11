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
            <div class="rounded-xl bg-gradient-to-r from-red-600 to-black p-8 text-white shadow-xl shadow-red-500/20">
                <h1 class="text-3xl font-bold">Welcome back, {{ user.name }}! 👋</h1>
                <p class="mt-2 text-red-100">Here's what's happening with your restaurant today.</p>
            </div>

            <!-- Quick Actions -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <Link
                    :href="'/admin/users'"
                    class="group relative overflow-hidden rounded-xl border border-gray-800 bg-gray-900 p-6 shadow-lg transition-all hover:border-red-500/50 hover:shadow-red-500/20"
                >
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Manage Users</p>
                            <h3 class="mt-2 text-2xl font-bold text-white">View All</h3>
                        </div>
                        <div class="rounded-lg bg-red-950/50 p-3 ring-1 ring-red-900/50">
                            <Users class="h-6 w-6 text-red-500" />
                        </div>
                    </div>
                    <p class="mt-4 text-xs text-gray-500">View and export user data</p>
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-red-600 to-transparent opacity-0 transition-opacity group-hover:opacity-100"></div>
                </Link>

                <div class="relative overflow-hidden rounded-xl border border-gray-800 bg-gray-900 p-6 shadow-lg">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">This Week</p>
                            <h3 class="mt-2 text-2xl font-bold text-white">New Users</h3>
                        </div>
                        <div class="rounded-lg bg-red-950/50 p-3 ring-1 ring-red-900/50">
                            <Calendar class="h-6 w-6 text-red-500" />
                        </div>
                    </div>
                    <p class="mt-4 text-xs text-gray-500">Check user statistics</p>
                </div>

                <div class="relative overflow-hidden rounded-xl border border-gray-800 bg-gray-900 p-6 shadow-lg">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Growth</p>
                            <h3 class="mt-2 text-2xl font-bold text-white">Analytics</h3>
                        </div>
                        <div class="rounded-lg bg-red-950/50 p-3 ring-1 ring-red-900/50">
                            <TrendingUp class="h-6 w-6 text-red-500" />
                        </div>
                    </div>
                    <p class="mt-4 text-xs text-gray-500">View growth trends</p>
                </div>

                <div class="relative overflow-hidden rounded-xl border border-gray-800 bg-gray-900 p-6 shadow-lg">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Recent</p>
                            <h3 class="mt-2 text-2xl font-bold text-white">Activity</h3>
                        </div>
                        <div class="rounded-lg bg-red-950/50 p-3 ring-1 ring-red-900/50">
                            <Clock class="h-6 w-6 text-red-500" />
                        </div>
                    </div>
                    <p class="mt-4 text-xs text-gray-500">Recent registrations</p>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-1 rounded-xl border border-gray-800 bg-gray-900 p-6">
                <div class="flex flex-col items-center justify-center py-12">
                    <div class="rounded-full bg-red-950/50 p-6 ring-1 ring-red-900/50">
                        <Users class="h-12 w-12 text-red-500" />
                    </div>
                    <h2 class="mt-4 text-xl font-semibold text-white">User Management System</h2>
                    <p class="mt-2 text-center text-sm text-gray-400">
                        Manage your registered users, export data, and view analytics
                    </p>
                    <Link :href="'/admin/users'" class="mt-6">
                        <Button size="lg" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 shadow-lg shadow-red-500/30">
                            Go to Users
                        </Button>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
