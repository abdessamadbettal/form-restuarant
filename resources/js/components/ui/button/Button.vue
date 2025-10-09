<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { Primitive, type PrimitiveProps } from 'reka-ui'
import { type ButtonVariants, buttonVariants } from '.'
import { ref } from 'vue'

interface Props extends PrimitiveProps {
  variant?: ButtonVariants['variant']
  size?: ButtonVariants['size']
  class?: HTMLAttributes['class']
}

const props = withDefaults(defineProps<Props>(), {
  as: 'button',
})

const ripples = ref<Array<{ id: number; x: number; y: number }>>([])
let rippleId = 0

const createRipple = (event: MouseEvent) => {
  const button = event.currentTarget as HTMLElement
  const rect = button.getBoundingClientRect()
  const x = event.clientX - rect.left
  const y = event.clientY - rect.top

  const id = rippleId++
  ripples.value.push({ id, x, y })

  setTimeout(() => {
    ripples.value = ripples.value.filter(r => r.id !== id)
  }, 600)
}
</script>

<template>
  <Primitive
    data-slot="button"
    :as="as"
    :as-child="asChild"
    :class="cn(buttonVariants({ variant, size }), 'relative overflow-hidden', props.class)"
    @mousedown="createRipple"
  >
    <!-- Ripple effects -->
    <span
      v-for="ripple in ripples"
      :key="ripple.id"
      class="absolute pointer-events-none animate-ripple rounded-full bg-white/30"
      :style="{
        left: ripple.x + 'px',
        top: ripple.y + 'px',
        width: '0px',
        height: '0px',
      }"
    ></span>

    <slot />
  </Primitive>
</template>

<style scoped>
@keyframes ripple {
  to {
    width: 500px;
    height: 500px;
    opacity: 0;
    transform: translate(-50%, -50%);
  }
}

.animate-ripple {
  animation: ripple 0.6s ease-out;
}
</style>
