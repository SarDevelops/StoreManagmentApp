<script setup>
import { computed, ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

import {
    Dialog,
    DialogPanel,
    TransitionRoot,
} from '@headlessui/vue'

const page = usePage()

const open = ref(false)

const success = computed(
    () => page.props.flash.success
)

const error = computed(
    () => page.props.flash.error
)

watch(
    [success, error],
    () => {

        if (success.value || error.value) {
            open.value = true
        }

    },
    { immediate: true }
)
</script>

<template>

    <TransitionRoot :show="open" as="template">
        <Dialog @close="open = false" class="relative z-50">

            <div class="fixed inset-0 bg-black/30" />

            <div class="fixed inset-0 flex items-center justify-center p-4">

                <DialogPanel class="w-full max-w-md rounded-lg bg-white p-6 shadow-xl">

                    <!-- Success -->

                    <div v-if="success">
                        <h2 class="text-lg font-bold text-green-600">
                            Success
                        </h2>

                        <p class="mt-2 text-gray-900">
                            {{ success }}
                        </p>

                    </div>


                    <!-- Error -->

                    <div v-if="error">

                        <h2 class="text-lg font-bold text-red-600">
                            Failed
                        </h2>

                        <p class="mt-2">
                            {{ error }}
                        </p>

                    </div>

                    <div class="flex justify-end">
                        <button @click="open = false" class="mt-5 rounded bg-indigo-600 px-4 py-2 text-white">
                            OK
                        </button>
                    </div>

                </DialogPanel>

            </div>

        </Dialog>

    </TransitionRoot>

</template>
