<script setup lang="ts">
import { AlertCircle } from 'lucide-vue-next'


defineProps<{
    message?: string;
}>();
</script>

<template>
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0 -translate-y-1"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-1"

    >
      <div class="flex items-start gap-2 mt-2" v-show="message">
        <AlertCircle class="h-3 w-3 text-red-600 dark:text-red-500 shrink-0 mt-1.5 animate-in fade-in zoom-in duration-300" />
        <p class="text-sm text-red-600 dark:text-red-500 leading-relaxed animate-in fade-in slide-in-from-left-1 duration-300">
          {{ message }}
        </p>
      </div>
    </Transition>
</template>

<style scoped>
@keyframes fade-in {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes zoom-in {
  from { transform: scale(0.5); }
  to { transform: scale(1); }
}

@keyframes slide-in-from-left-1 {
  from { transform: translateX(-4px); }
  to { transform: translateX(0); }
}

.animate-in {
  animation-fill-mode: both;
}

.fade-in {
  animation-name: fade-in;
}

.zoom-in {
  animation-name: zoom-in;
}

.slide-in-from-left-1 {
  animation-name: slide-in-from-left-1;
}

.duration-300 {
  animation-duration: 300ms;
}
</style>
