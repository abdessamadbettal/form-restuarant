<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { useVModel } from '@vueuse/core'

const props = defineProps<{
  defaultValue?: string | number
  modelValue?: string | number
  class?: HTMLAttributes['class']
  variant?: 'dark' | 'light'
}>()

const emits = defineEmits<{
  (e: 'update:modelValue', payload: string | number): void
}>()

const modelValue = useVModel(props, 'modelValue', emits, {
  passive: true,
  defaultValue: props.defaultValue,
})
</script>

<template>
    <input
      v-model="modelValue"
      data-slot="input"
      :class="cn(
        'file:text-foreground placeholder:text-gray-400 selection:bg-red-500 selection:text-white',
        'flex h-12 w-full min-w-0 rounded-xl border px-4 py-3 text-sm shadow-sm transition-all outline-none',
        'file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium',
        'disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50',
        'aria-invalid:ring-red-500/50 aria-invalid:border-red-500',
        // Light variant (default for auth pages)
        variant === 'light' && [
          'border-gray-300 bg-gray-50 text-gray-900',
          'hover:border-red-400 hover:bg-white',
          'focus:border-red-500 focus:ring-2 focus:ring-red-500/30 focus:bg-white',
        ],
        // Dark variant (for admin pages)
        variant === 'dark' && [
          'border-gray-800 bg-gray-900/50 text-white',
          'hover:border-red-500/50 hover:bg-gray-900',
          'focus:border-red-500 focus:ring-2 focus:ring-red-500/50 focus:ring-offset-2 focus:ring-offset-black focus:bg-black/50',
        ],
        // Default to dark if no variant specified
        !variant && [
          'border-gray-800 bg-gray-900/50 text-white',
          'hover:border-red-500/50 hover:bg-gray-900',
          'focus:border-red-500 focus:ring-2 focus:ring-red-500/50 focus:ring-offset-2 focus:ring-offset-black focus:bg-black/50',
        ],
        props.class,
      )"
    >
</template>
