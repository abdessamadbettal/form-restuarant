<script setup lang="ts">
import AuthenticatedSessionController from '@/actions/App/Http/Controllers/Auth/AuthenticatedSessionController';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { register } from '@/routes';
import { request } from '@/routes/password';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle, Mail, Lock, UserPlus } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();
</script>

<template>
    <AuthBase
        title="Admin Login"
        description="Access your dashboard"
    >
        <Head title="Log in" />

        <div
            v-if="status"
            class="mb-4 rounded-lg bg-green-50 border border-green-200 p-3 text-center text-sm text-green-700"
        >
            {{ status }}
        </div>

        <Form
            v-bind="AuthenticatedSessionController.store.form()"
            :reset-on-success="['password']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="email" variant="light" class="flex items-center gap-1.5">
                        <Mail class="h-3.5 w-3.5 text-red-600" />
                        Email address
                    </Label>
                    <Input
                        id="email"
                        type="email"
                        variant="light"
                        name="email"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
                        placeholder="admin@frankmeat.com"
                        class="h-10"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password" variant="light" class="flex items-center gap-1.5">
                            <Lock class="h-3.5 w-3.5 text-red-600" />
                            Password
                        </Label>
                        <TextLink
                            v-if="canResetPassword"
                            :href="request()"
                            class="text-xs text-red-600 hover:text-red-700"
                            :tabindex="5"
                        >
                            Forgot password?
                        </TextLink>
                    </div>
                    <Input
                        id="password"
                        type="password"
                        variant="light"
                        name="password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        placeholder="Enter password"
                        class="h-10"
                    />
                    <InputError :message="errors.password" />
                </div>

                <Label for="remember" class="flex items-center space-x-3 cursor-pointer text-gray-700">
                    <Checkbox id="remember" name="remember" :tabindex="3" />
                    <span class="text-sm">Remember me</span>
                </Label>

                <Button
                    type="submit"
                    class="h-10 w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800"
                    :tabindex="4"
                    :disabled="processing"
                    data-test="login-button"
                >
                    <LoaderCircle v-if="processing" class="mr-2 h-4 w-4 animate-spin" />
                    {{ processing ? 'Logging in...' : 'Log in' }}
                </Button>
            </div>

            <!-- Customer Link -->
            <div class="space-y-3 pt-3 border-t border-gray-200">
                <div class="text-center text-sm text-gray-500">
                    Are you a customer?
                </div>
                <TextLink
                    :href="register()"
                    :tabindex="6"
                    class="flex items-center justify-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-100"
                >
                    <UserPlus class="h-4 w-4" />
                    Sign up for Wi-Fi
                </TextLink>
            </div>
        </Form>
    </AuthBase>
</template>
