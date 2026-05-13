<script setup lang="ts">
import { Dialog, DialogPanel, TransitionRoot, TransitionChild } from '@headlessui/vue'

defineProps<{
    show: boolean
    title?: string
    message?: string
    confirmText?: string
    cancelText?: string
}>()

const emit = defineEmits(['confirm', 'close'])
</script>

<template>
    <TransitionRoot :show="show" as="template">
        <Dialog @close="emit('close')" class="relative z-50">

            <!-- Backdrop -->
            <TransitionChild
                enter="ease-out duration-200"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-150"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/40" />
            </TransitionChild>

            <!-- Modal -->
            <div class="fixed inset-0 flex items-center justify-center p-4">

                <TransitionChild
                    enter="ease-out duration-200"
                    enter-from="opacity-0 scale-95"
                    enter-to="opacity-100 scale-100"
                    leave="ease-in duration-150"
                    leave-from="opacity-100 scale-100"
                    leave-to="opacity-0 scale-95"
                >
                    <DialogPanel class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">

                        <h2 class="text-lg font-bold text-gray-900">
                            {{ title || 'Are you sure?' }}
                        </h2>

                        <p class="mt-2 text-sm text-gray-600">
                            {{ message || 'This action cannot be undone.' }}
                        </p>

                        <div class="mt-6 flex justify-end gap-3">

                            <button
                                class="px-4 py-2 text-sm rounded border bg-gray-800"
                                @click="emit('close')"
                            >
                                {{ cancelText || 'Cancel' }}
                            </button>

                            <button
                                class="px-4 py-2 text-sm rounded bg-red-600 text-white"
                                @click="emit('confirm')"
                            >
                                {{ confirmText || 'Delete' }}
                            </button>

                        </div>

                    </DialogPanel>
                </TransitionChild>

            </div>

        </Dialog>
    </TransitionRoot>
</template>
