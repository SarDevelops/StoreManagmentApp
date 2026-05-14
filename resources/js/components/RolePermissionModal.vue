<script setup lang="ts">
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

import {
    Dialog,
    DialogPanel,
    TransitionRoot
} from '@headlessui/vue'


type Permission = {
    id: number
    name: string
}


const props = defineProps<{

    show: boolean

    roleId: number | null

    permissions: Permission[]

    selected: string[]

}>()


const emit =
    defineEmits([
        'close'
    ])


const checked =
    ref<string[]>([])


watch(

    () => props.selected,

    (value) => {

        checked.value =
            [...value]

    },

    {
        immediate: true
    }

)


const save = () => {

    if (!props.roleId) return

    router.put(

        `/roles/${props.roleId}/permissions`,

        {
            permissions: checked.value
        },

        {
            preserveScroll: true,

            onSuccess: () => {

                emit('close')

                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Permissions updated successfully',
                    timer: 2000,
                    showConfirmButton: false
                })

            },

            onError: () => {

                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong'
                })

            }

        }

    )

}
</script>


<template>

    <TransitionRoot :show="show" as="template">

        <Dialog @close="emit('close')">

            <div class="fixed inset-0 bg-black/40" />


            <div class="fixed inset-0 flex items-center justify-center">

                <DialogPanel class="w-full max-w-lg rounded-xl bg-white p-6">

                    <h2 class="text-lg font-bold text-gray-600">
                        Assign Permissions
                    </h2>


                    <div class="mt-4 grid grid-cols-2 gap-3">

                        <label v-for="permission in permissions" :key="permission.id" class="flex gap-2">

                            <input type="checkbox" :value="permission.name" v-model="checked" />

                            <span class="text-gray-600">
                                {{ permission.name }}
                            </span>

                        </label>

                    </div>


                    <button @click="save" class="mt-6 rounded bg-indigo-600 px-4 py-2 text-white">
                        Save
                    </button>


                </DialogPanel>

            </div>

        </Dialog>

    </TransitionRoot>

</template>
