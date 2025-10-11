<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="relative flex min-h-svh overflow-hidden bg-gradient-to-br from-orange-50 via-white to-amber-50 dark:from-gray-950 dark:via-gray-900 dark:to-orange-950/20">
        <!-- Decorative Background Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Animated floating food icons - smaller and more subtle -->
            <div class="absolute top-10 left-8 text-4xl opacity-5 animate-float">🍕</div>
            <div class="absolute top-32 right-12 text-3xl opacity-5 animate-float-delay-1">🍔</div>
            <div class="absolute bottom-24 left-1/4 text-4xl opacity-5 animate-float-delay-2">🍝</div>
            <div class="absolute bottom-16 right-1/3 text-3xl opacity-5 animate-float-delay-3">🍜</div>

            <!-- Gradient orbs - smaller -->
            <div class="absolute -top-20 -right-20 h-64 w-64 rounded-full bg-gradient-to-br from-orange-400/15 to-pink-400/15 blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 h-64 w-64 rounded-full bg-gradient-to-tr from-amber-400/15 to-orange-400/15 blur-3xl"></div>
        </div>

        <div class="relative flex flex-1 flex-col items-center justify-center gap-4 p-4 md:p-6">
            <div class="w-full max-w-md">
                <div class="flex flex-col gap-4">
                    <!-- Logo and Header - Compact -->
                    <div class="flex flex-col items-center gap-3">
                        <Link
                            :href="home()"
                            class="group flex flex-col items-center gap-2 font-medium transition-transform hover:scale-105"
                        >
                            <div class="relative flex h-16 w-16 items-center justify-center group-hover:rotate-3">
                                <AppLogoIcon class="size-8 fill-current text-white drop-shadow-lg" />
                                <!-- Sparkle effect -->
                                <div class="absolute -right-0.5 -top-0.5 text-sm animate-pulse">✨</div>
                            </div>
                            <span class="sr-only">{{ title }}</span>
                        </Link>

                        <div class="space-y-2 text-center">
                            <!-- <div class="inline-flex items-center gap-1.5 rounded-full bg-orange-100 dark:bg-orange-900/30 px-3 py-1 text-xs font-medium text-orange-700 dark:text-orange-300">
                                <span class="relative flex h-1.5 w-1.5">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-orange-400 opacity-75"></span>
                                    <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-orange-500"></span>
                                </span>
                                Special Offer
                            </div> -->
                            <h1 class="text-2xl font-bold bg-gradient-to-r from-orange-600 to-amber-600 bg-clip-text text-transparent">
                                {{ title }}
                            </h1>
                            <p class="text-xs text-muted-foreground max-w-xs mx-auto leading-relaxed">
                                {{ description }}
                            </p>
                        </div>
                    </div>

                    <!-- Card Container - Compact with glassmorphism -->
                    <div class="relative rounded-xl border border-orange-200/50 dark:border-orange-800/50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-6 shadow-xl shadow-orange-500/10">
                        <!-- Decorative corner accent - smaller -->
                        <div class="absolute -right-px -top-px h-12 w-12 overflow-hidden rounded-tr-xl">
                            <div class="absolute right-0 top-0 h-full w-full bg-gradient-to-bl from-orange-500/15 to-transparent"></div>
                        </div>

                        <slot />
                    </div>

                    <!-- Trust indicators -->
                    <!-- <div class="flex items-center justify-center gap-8 text-xs text-muted-foreground">
                        <div class="flex items-center gap-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                                <span class="text-base">🔒</span>
                            </div>
                            <span class="text-xs">Secure</span>
                        </div>
                        <div class="h-3 w-px bg-border"></div>
                        <div class="flex items-center gap-1.5">
                            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30">
                                <span class="text-sm">⚡</span>
                            </div>
                            <span class="text-xs">Fast</span>
                        </div>
                        <div class="h-3 w-px bg-border"></div>
                        <div class="flex items-center gap-1.5">
                            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900/30">
                                <span class="text-sm">🎁</span>
                            </div>
                            <span class="text-xs">Rewards</span>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes float {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-15px) rotate(3deg);
    }
}

@keyframes float-delay-1 {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-18px) rotate(-3deg);
    }
}

@keyframes float-delay-2 {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-12px) rotate(2deg);
    }
}

@keyframes float-delay-3 {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-20px) rotate(-2deg);
    }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animate-float-delay-1 {
    animation: float-delay-1 7s ease-in-out infinite;
    animation-delay: 1s;
}

.animate-float-delay-2 {
    animation: float-delay-2 8s ease-in-out infinite;
    animation-delay: 2s;
}

.animate-float-delay-3 {
    animation: float-delay-3 9s ease-in-out infinite;
    animation-delay: 3s;
}
</style>
