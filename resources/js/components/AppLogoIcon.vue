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
        src="https://static.tildacdn.com/tild6161-6163-4666-a137-383434323138/_73333x.png"
        alt="Frank Meat & Taps Logo"
        :class="className"
        class="object-contain"
    />
</template>
