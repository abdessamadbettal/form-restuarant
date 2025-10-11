<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
     <img
        src="https://frankbybasta.e-rockets.io/assets/logo-GuzdoQUh.webp"
        alt="Frank Meat & Taps Logo"
        :class="className"
        class="object-contain"
    />
</template>
