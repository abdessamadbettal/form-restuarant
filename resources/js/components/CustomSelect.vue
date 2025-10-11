<script setup lang="ts">
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { ChevronDown, Check } from 'lucide-vue-next';

interface Option {
    value: string;
    label: string;
}

interface Props {
    modelValue: string;
    options: Option[];
    placeholder?: string;
    disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    placeholder: 'Select option',
    disabled: false,
});

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const isOpen = ref(false);
const selectRef = ref<HTMLDivElement | null>(null);

const selectedOption = computed(() => {
    return props.options.find(opt => opt.value === props.modelValue);
});

const toggleDropdown = () => {
    if (!props.disabled) {
        isOpen.value = !isOpen.value;
    }
};

const selectOption = (value: string) => {
    emit('update:modelValue', value);
    isOpen.value = false;
};

const handleClickOutside = (event: MouseEvent) => {
    if (selectRef.value && !selectRef.value.contains(event.target as Node)) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <div ref="selectRef" class="relative">
        <button
            type="button"
            @click="toggleDropdown"
            :disabled="disabled"
            class="flex h-10 w-full items-center justify-between rounded-lg border border-input bg-background px-3 py-2 text-sm ring-offset-background transition-colors hover:bg-muted focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
            :class="{ 'ring-2 ring-ring ring-offset-2': isOpen }"
        >
            <span :class="{ 'text-muted-foreground': !selectedOption }">
                {{ selectedOption ? selectedOption.label : placeholder }}
            </span>
            <ChevronDown
                class="h-4 w-4 opacity-50 transition-transform duration-200"
                :class="{ 'rotate-180': isOpen }"
            />
        </button>

        <Transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="isOpen"
                class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-lg border bg-popover p-1 shadow-lg"
            >
                <button
                    v-for="option in options"
                    :key="option.value"
                    type="button"
                    @click="selectOption(option.value)"
                    class="relative flex w-full cursor-pointer items-center rounded-md px-2 py-1.5 text-sm outline-none transition-colors hover:bg-accent hover:text-accent-foreground"
                    :class="{ 'bg-accent text-accent-foreground': modelValue === option.value }"
                >
                    <span class="flex-1 text-left">{{ option.label }}</span>
                    <Check
                        v-if="modelValue === option.value"
                        class="h-4 w-4 text-orange-600"
                    />
                </button>
            </div>
        </Transition>
    </div>
</template>
