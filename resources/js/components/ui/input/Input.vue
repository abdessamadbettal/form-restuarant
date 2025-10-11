<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { useVModel } from '@vueuse/core'

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
</script>

<template>
    <input
      v-model="modelValue"
      data-slot="input"
      :class="cn(
        'file:text-foreground placeholder:text-gray-500 selection:bg-red-500 selection:text-white',
        'flex h-12 w-full min-w-0 rounded-xl border border-gray-800 bg-gray-900/50 px-4 py-3 text-sm text-white shadow-sm transition-all outline-none',
        'file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium',
        'hover:border-red-500/50 hover:bg-gray-900',
        'focus:border-red-500 focus:ring-2 focus:ring-red-500/50 focus:ring-offset-2 focus:ring-offset-black focus:bg-black/50',
        'disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50',
        'aria-invalid:ring-red-500/50 aria-invalid:border-red-500',
        props.class,
      )"
    >
</template>
