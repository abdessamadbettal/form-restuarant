<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { CheckCircle2, Instagram, LogOut, Sparkles } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

const instagramUrl = 'https://www.instagram.com/frankmeatdxb';
const countdown = ref(5);

onMounted(() => {
    const timer = setInterval(() => {
        countdown.value--;
        if (countdown.value === 0) {
            clearInterval(timer);
            window.location.href = instagramUrl;
        }
    }, 1000);
});

const redirectNow = () => {
    window.location.href = instagramUrl;
};

const logout = () => {
    router.post('/logout');
};
</script>

<template>
    <AuthBase
        title="Welcome to Frank Meat & Taps!"
        description="Thank you for joining our community"
    >
        <Head title="Thank You" />

        <div class="flex flex-col items-center gap-6 text-center">
            <!-- Success Icon -->
            <div class="relative">
                <div class="absolute inset-0 animate-ping rounded-full bg-red-500/20"></div>
                <div class="relative rounded-full bg-gradient-to-br from-red-600 to-red-700 p-5 shadow-2xl shadow-red-500/50 ring-4 ring-red-500/20">
                    <CheckCircle2 class="h-16 w-16 text-white" />
                </div>
            </div>

            <!-- Success Message -->
            <div class="space-y-3">
                <h2 class="text-3xl font-bold text-white">
                    🎉 Welcome Aboard!
                </h2>
                <p class="text-base leading-relaxed text-gray-400 max-w-sm">
                    You're now part of the Frank Meat & Taps family. Get ready for exclusive offers and updates!
                </p>
            </div>

            <!-- Instagram Button -->
            <div class="w-full space-y-4 pt-2">
                <Button
                    @click="redirectNow"
                    class="h-14 w-full bg-gradient-to-r from-pink-500 via-purple-500 to-purple-600 hover:from-pink-600 hover:via-purple-600 hover:to-purple-700 text-base font-semibold shadow-lg shadow-purple-500/30 hover:shadow-purple-500/50 transition-all"
                    size="lg"
                >
                    <Instagram class="mr-2 h-5 w-5" />
                    Follow Us on Instagram
                    <Sparkles class="ml-2 h-4 w-4" />
                </Button>

                <div class="rounded-xl border border-red-900/50 bg-red-950/30 p-4">
                    <p class="text-sm text-gray-400">
                        Redirecting in <strong class="text-xl font-bold text-red-500">{{ countdown }}</strong> second{{ countdown !== 1 ? 's' : '' }}
                    </p>
                </div>
            </div>

            <!-- What's Next Section -->
            <div class="w-full space-y-4 rounded-xl border border-gray-800 bg-gray-900/50 p-5">
                <div class="flex items-center gap-2">
                    <Sparkles class="h-5 w-5 text-red-500" />
                    <p class="text-base font-semibold text-white">
                        What's Next?
                    </p>
                </div>
                <ul class="space-y-3 text-left">
                    <li class="flex items-start gap-3">
                        <span class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-red-950/50 text-sm font-bold text-red-500 ring-1 ring-red-900/50">1</span>
                        <span class="text-sm text-gray-400">Follow us on Instagram for daily specials and mouth-watering content</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-red-950/50 text-sm font-bold text-red-500 ring-1 ring-red-900/50">2</span>
                        <span class="text-sm text-gray-400">Check your email for exclusive welcome offers</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-red-950/50 text-sm font-bold text-red-500 ring-1 ring-red-900/50">3</span>
                        <span class="text-sm text-gray-400">Visit us in-store and mention this registration for a special discount</span>
                    </li>
                </ul>
            </div>

            <!-- Logout Button -->
            <div class="w-full pt-2">
                <Button
                    @click="logout"
                    variant="outline"
                    size="sm"
                    class="w-full gap-2 border-gray-800 bg-gray-900/50 hover:bg-gray-900 hover:border-red-500/50 text-gray-400 hover:text-white"
                >
                    <LogOut class="h-4 w-4" />
                    Logout
                </Button>
            </div>
        </div>
    </AuthBase>
</template>
