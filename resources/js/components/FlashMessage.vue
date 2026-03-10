<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { CheckCircle2, XCircle, X } from 'lucide-vue-next';

const page = usePage();

interface Flash {
    success?: string | null;
    error?: string | null;
}

const flash = computed(() => page.props.flash as Flash);

const visible = ref(false);
const message = ref('');
const type = ref<'success' | 'error'>('success');
let timer: ReturnType<typeof setTimeout>;

watch(
    flash,
    (val) => {
        if (val?.success) {
            message.value = val.success;
            type.value = 'success';
            show();
        } else if (val?.error) {
            message.value = val.error;
            type.value = 'error';
            show();
        }
    },
    { immediate: true },
);

function show() {
    visible.value = true;
    clearTimeout(timer);
    timer = setTimeout(() => { visible.value = false; }, 4000);
}

function dismiss() {
    clearTimeout(timer);
    visible.value = false;
}
</script>

<template>
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="translate-y-2 opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="translate-y-0 opacity-100"
        leave-to-class="translate-y-2 opacity-0"
    >
        <div
            v-if="visible"
            class="fixed bottom-6 right-6 z-50 flex items-start gap-3 rounded-xl border shadow-lg px-4 py-3 max-w-sm"
            :class="type === 'success'
                ? 'bg-green-50 border-green-200 text-green-800 dark:bg-green-950 dark:border-green-800 dark:text-green-200'
                : 'bg-red-50 border-red-200 text-red-800 dark:bg-red-950 dark:border-red-800 dark:text-red-200'"
        >
            <CheckCircle2 v-if="type === 'success'" class="h-5 w-5 mt-0.5 shrink-0 text-green-600 dark:text-green-400" />
            <XCircle v-else class="h-5 w-5 mt-0.5 shrink-0 text-red-600 dark:text-red-400" />
            <p class="text-sm font-medium flex-1">{{ message }}</p>
            <button @click="dismiss" class="shrink-0 opacity-60 hover:opacity-100 transition-opacity">
                <X class="h-4 w-4" />
            </button>
        </div>
    </Transition>
</template>
