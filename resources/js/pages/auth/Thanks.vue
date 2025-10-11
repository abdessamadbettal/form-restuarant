<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { CheckCircle2, Instagram, LogOut } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

// Replace with your actual Instagram URL
const instagramUrl = 'https://www.instagram.com/frankmeatdxb';
const countdown = ref(5);

// Auto-redirect after countdown
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
            <div class="rounded-full bg-gradient-to-br from-green-100 to-emerald-100 p-4 dark:from-green-900/20 dark:to-emerald-900/20">
                <CheckCircle2 class="h-16 w-16 text-green-600 dark:text-green-400" />
            </div>

            <div class="space-y-3">
                <h2 class="text-2xl font-bold tracking-tight">
                    🎉 Successfully Registered!
                </h2>
                <p class="text-sm leading-relaxed text-muted-foreground">
                    Welcome to the Frank Meat & Taps family! You'll receive exclusive offers, special promotions, and updates directly to your email.
                </p>
            </div>

            <div class="w-full space-y-3 pt-4">
                <Button
                    @click="redirectNow"
                    class="h-12 w-full bg-gradient-to-r from-pink-500 to-purple-600 text-base font-semibold hover:from-pink-600 hover:to-purple-700"
                    size="lg"
                >
                    <Instagram class="mr-2 h-5 w-5" />
                    Follow Us on Instagram
                </Button>

                <div class="rounded-lg border bg-muted/50 p-3">
                    <p class="text-sm text-muted-foreground">
                        Redirecting automatically in <strong class="text-orange-600">{{ countdown }}</strong> second{{ countdown !== 1 ? 's' : '' }}...
                    </p>
                </div>
            </div>

            <div class="mt-4 space-y-3 rounded-lg border bg-gradient-to-br from-orange-50 to-amber-50 dark:from-orange-950/30 dark:to-amber-950/30 p-4">
                <p class="text-sm font-semibold text-foreground">
                    🎁 What's Next?
                </p>
                <ul class="space-y-2 text-left text-xs text-muted-foreground">
                    <li class="flex items-start gap-2">
                        <span class="text-orange-600">✓</span>
                        <span>Follow us on Instagram for daily specials and exclusive content</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-orange-600">✓</span>
                        <span>Check your email for a welcome message and special offers</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-orange-600">✓</span>
                        <span>Visit us in-store to redeem your welcome discount</span>
                    </li>
                </ul>
            </div>

            <!-- Logout Button -->
            <div class="mt-4 w-full">
                <Button
                    @click="logout"
                    variant="outline"
                    size="sm"
                    class="w-full gap-2"
                >
                    <LogOut class="h-4 w-4" />
                    Logout
                </Button>
            </div>
        </div>
    </AuthBase>
</template>
