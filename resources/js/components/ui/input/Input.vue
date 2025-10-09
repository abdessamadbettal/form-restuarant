<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { useVModel } from '@vueuse/core'
import { ref } from 'vue'

const props = defineProps<{
  defaultValue?: string | number
  modelValue?: string | number
  class?: HTMLAttributes['class']
}>()

const emits = defineEmits<{
  (e: 'update:modelValue', payload: string | number): void
}>()

const modelValue = useVModel(props, 'modelValue', emits, {
  passive: true,
  defaultValue: props.defaultValue,
})

const isFocused = ref(false)
</script>

<template>
  <div class="relative">
    <input
      v-model="modelValue"
      data-slot="input"
      @focus="isFocused = true"
      @blur="isFocused = false"
      :class="cn(
        'file:text-foreground placeholder:text-muted-foreground selection:bg-orange-500 selection:text-white dark:bg-input/30 border-input flex h-12 w-full min-w-0 rounded-lg border bg-white dark:bg-gray-900/50 px-3 py-2.5 text-base shadow-sm transition-all outline-none file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm',
        'hover:border-orange-300 dark:hover:border-orange-700',
        'focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 dark:focus:ring-orange-500/30',
        'aria-invalid:ring-red-500/20 dark:aria-invalid:ring-red-500/40 aria-invalid:border-red-500',
        props.class,
      )"
    >

    <!-- Floating focus indicator -->
    <div
      v-if="isFocused"
      class="absolute -inset-[1px] -z-10 rounded-lg bg-gradient-to-r from-orange-500 to-amber-500 opacity-50 blur-sm animate-pulse"
    ></div>
  </div>
</template>
