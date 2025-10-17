<script setup lang="ts">
import { cn } from '@/lib/utils'
import { Label, type LabelProps } from 'reka-ui'
import { computed, type HTMLAttributes } from 'vue'

const props = defineProps<LabelProps & {
  class?: HTMLAttributes['class']
  variant?: 'dark' | 'light'
}>()

const delegatedProps = computed(() => {
  const { class: _, variant: __, ...delegated } = props
  return delegated
})
</script>

<template>
  <Label
    data-slot="label"
    v-bind="delegatedProps"
    :class="
      cn(
        'flex items-center gap-2 text-sm leading-none font-medium select-none',
        'group-data-[disabled=true]:pointer-events-none group-data-[disabled=true]:opacity-50',
        'peer-disabled:cursor-not-allowed peer-disabled:opacity-50',
        variant === 'light' ? 'text-gray-700' : 'text-white',
        !variant && 'text-white', // default to dark
        props.class,
      )
    "
  >
    <slot />
  </Label>
</template>
