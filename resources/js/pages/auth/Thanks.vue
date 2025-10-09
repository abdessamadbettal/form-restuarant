<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head } from '@inertiajs/vue3';
import { CheckCircle2 } from 'lucide-vue-next';
import { onMounted } from 'vue';

// Replace with your Instagram URL
const instagramUrl = 'https://www.instagram.com/your_restaurant_handle';

// Auto-redirect after 5 seconds
onMounted(() => {
    setTimeout(() => {
        window.location.href = instagramUrl;
    }, 5000);
});

const redirectNow = () => {
    window.location.href = instagramUrl;
};
</script>

<template>
    <AuthBase
        title="Thank You for Signing Up!"
        description=""
    >
        <Head title="Thank You" />

        <div class="flex flex-col items-center gap-6 text-center">
            <div class="rounded-full bg-green-100 p-4 dark:bg-green-900/20">
                <CheckCircle2 class="h-16 w-16 text-green-600 dark:text-green-400" />
            </div>

            <div class="space-y-3">
                <h2 class="text-2xl font-bold tracking-tight">
                    Welcome to Our Family!
                </h2>
                <p class="text-muted-foreground">
                    We're excited to have you on board. You'll receive exclusive offers and updates directly to your email.
                </p>
            </div>

            <div class="w-full space-y-3 pt-4">
                <Button
                    @click="redirectNow"
                    class="h-11 w-full text-base font-semibold"
                    size="lg"
                >
                    Follow Us on Instagram
                </Button>

                <p class="text-sm text-muted-foreground">
                    Redirecting automatically in 5 seconds...
                </p>
            </div>

            <div class="mt-4 rounded-lg border bg-muted/50 p-4">
                <p class="text-sm text-muted-foreground">
                    💡 <strong>Pro Tip:</strong> Follow us on Instagram for daily specials, behind-the-scenes content, and exclusive giveaways!
                </p>
            </div>
        </div>
    </AuthBase>
</template>
