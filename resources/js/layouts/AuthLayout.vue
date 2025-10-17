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
    <div class="relative flex min-h-svh overflow-hidden bg-black">
        <!-- Background Image -->
        <div
            class="absolute inset-0 z-0 bg-cover bg-center bg-no-repeat"
            style="background-image: url('/bg.jpg'); filter: brightness(0.4) blur(1px);"
            aria-hidden="true"
        ></div>

        <!-- Decorative Background Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Animated floating food icons -->
            <div class="absolute top-10 left-8 text-4xl opacity-5 animate-float">🥩</div>
            <div class="absolute top-32 right-12 text-3xl opacity-5 animate-float-delay-1">🍖</div>
            <div class="absolute bottom-24 left-1/4 text-4xl opacity-5 animate-float-delay-2">🍺</div>
            <div class="absolute bottom-16 right-1/3 text-3xl opacity-5 animate-float-delay-3">🔥</div>

            <!-- Gradient orbs - red theme -->
            <div class="absolute -top-20 -right-20 h-64 w-64 rounded-full bg-gradient-to-br from-red-500/10 to-black/10 blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 h-64 w-64 rounded-full bg-gradient-to-tr from-red-600/10 to-gray-900/10 blur-3xl"></div>
        </div>

        <div class="relative flex flex-1 flex-col items-center justify-center gap-4 p-4 md:p-6 z-10">
            <div class="w-full max-w-md">
                <div class="flex flex-col gap-4">
                    <!-- Logo and Header -->
                    <div class="flex flex-col items-center gap-3">
                        <Link
                            :href="home()"
                            class="group flex flex-col items-center gap-2 font-medium transition-transform hover:scale-105"
                        >
                            <div class="relative flex h-20 w-20 items-center justify-center rounded-lg">
                                <AppLogoIcon class="h-16 w-16" />
                            </div>
                            <span class="sr-only">{{ title }}</span>
                        </Link>

                        <div class="space-y-2 text-center">
                            <h1 class="text-2xl font-bold text-white drop-shadow-lg">
                                {{ title }}
                            </h1>
                            <p class="text-sm text-gray-300 max-w-xs mx-auto leading-relaxed">
                                {{ description }}
                            </p>
                        </div>
                    </div>

                    <!-- Card Container - LIGHT VARIANT -->
                    <div class="relative rounded-2xl border border-gray-200 bg-white shadow-2xl shadow-black/30 p-8">
                        <!-- Decorative corner accent -->
                        <div class="absolute -right-px -top-px h-16 w-16 overflow-hidden rounded-tr-2xl">
                            <div class="absolute right-0 top-0 h-full w-full bg-gradient-to-bl from-red-500/20 to-transparent"></div>
                        </div>

                        <slot />
                    </div>
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
