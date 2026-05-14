<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { XMarkIcon } from '@heroicons/vue/24/outline';
import DataTable from 'datatables.net-vue3';
import DataTablesLib from 'datatables.net';
import ConfirmModal from '@/components/ConfirmModal.vue';

type Permission = {
    id: number
    name: string
    guard_name: string
    created_at: string
}

defineProps<{
    permissions: Permission[]
}>()
const open = ref(false)

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'permissions',
                href: '/permissions',
            },
        ],
    },
});
// Create Permission ---------------------------
const form = useForm({
    name: ''
})
//  -------------- Datatable-----------
DataTable.use(DataTablesLib)
const columns = [
    { data: 'id', title: 'ID' },
    { data: 'name', title: 'Permission Name' },
    { data: 'guard_name', title: 'Guard' },
    { data: null, title: 'Actions', orderable: false, searchable: false }
]

//  Edit -------------------------
const isEdit = ref(false)
const selectedPermissionId = ref<number | null>(null)
const openCreate = () => {
    isEdit.value = false
    selectedPermissionId.value = null
    form.reset() // 🔥 clears old edit data
    open.value = true
}

const editPermission = (permission: Permission) => {
    isEdit.value = true
    selectedPermissionId.value = permission.id
    form.name = permission.name
    open.value = true
}

const submit = () => {
    if (isEdit.value && selectedPermissionId.value) {
        form.put(`/permissions/${selectedPermissionId.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                open.value = false
                form.reset()
            }
        })
    } else {
        form.post('/permissions', {
            preserveScroll: true,
            onSuccess: () => {
                open.value = false
                form.reset()
            }
        })
    }
}

// Working Delete -----------
const confirmOpen = ref(false)
const selectedId = ref<number | null>(null)
const askDelete = (id: number) => {
    selectedId.value = id
    confirmOpen.value = true
}
const deletePermission = () => {
    if (!selectedId.value) return
    router.delete(`/permissions/${selectedId.value}`, {
        preserveScroll: true,
        onSuccess: () => {
            confirmOpen.value = false
            selectedId.value = null
        }
    })
}
</script>

<template>
    <Head title="Permission" />
    <ConfirmModal :show="confirmOpen" title="Delete Permission"
        message="Are you sure you want to delete this permission? This action cannot be undone." confirm-text="Yes, Delete"
        cancel-text="Cancel" @close="confirmOpen = false" @confirm="deletePermission" />
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <h1>Permission Model</h1>
        <div class="flex flex-col items-end">
            <button class="w-full md:w-auto group relative inline-block text-sm font-medium text-indigo-600"
                @click="openCreate">
                <span
                    class="absolute inset-0 translate-x-0.5 translate-y-0.5 bg-indigo-600 transition-transform group-hover:translate-x-0 group-hover:translate-y-0"></span>
                <span class="relative block border border-current bg-white px-8 py-3"> Add Permission </span>
            </button>
        </div>
        <DataTable :data="permissions" :columns="columns" class="display w-full">
            <!-- Action Column (last index = 3) -->
            <template #column-3="{ rowData }">
                <div class="flex gap-2">
                    <!-- EDIT -->
                    <button class="px-3 py-1 text-sm bg-blue-600 text-white rounded" @click="editPermission(rowData)">
                        Edit
                    </button>
                    <!-- DELETE -->
                    <button class="px-3 py-1 text-sm bg-red-600 text-white rounded" @click="askDelete(rowData.id)">
                        Delete
                    </button>
                </div>
            </template>
        </DataTable>
        <!-- Slider Add Permission -->
        <template>
            <div>
                <TransitionRoot as="template" :show="open">
                    <Dialog class="relative z-10" @close="open = false">
                        <TransitionChild as="template" enter="ease-in-out duration-500" enter-from="opacity-0"
                            enter-to="" leave="ease-in-out duration-500" leave-from="" leave-to="opacity-0">
                            <div class="fixed inset-0 bg-gray-900/50 transition-opacity"></div>
                        </TransitionChild>
                        <div class="fixed inset-0 overflow-hidden">
                            <div class="absolute inset-0 overflow-hidden">
                                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
                                    <TransitionChild as="template"
                                        enter="transform transition ease-in-out duration-500 sm:duration-700"
                                        enter-from="translate-x-full" enter-to="translate-x-0"
                                        leave="transform transition ease-in-out duration-500 sm:duration-700"
                                        leave-from="translate-x-0" leave-to="translate-x-full">
                                        <DialogPanel class="pointer-events-auto relative w-screen max-w-md">
                                            <TransitionChild as="template" enter="ease-in-out duration-500"
                                                enter-from="opacity-0" enter-to="" leave="ease-in-out duration-500"
                                                leave-from="" leave-to="opacity-0">
                                                <div
                                                    class="absolute top-0 left-0 -ml-8 flex pt-4 pr-2 sm:-ml-10 sm:pr-4">
                                                    <button type="button"
                                                        class="relative rounded-md text-gray-400 hover:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
                                                        @click="open = false">
                                                        <span class="absolute -inset-2.5"></span>
                                                        <span class="sr-only">Close panel</span>
                                                        <XMarkIcon class="size-6" aria-hidden="true" />
                                                    </button>
                                                </div>
                                            </TransitionChild>
                                            <div
                                                class="relative flex h-full flex-col overflow-y-auto bg-gray-800 py-6 shadow-xl after:absolute after:inset-y-0 after:left-0 after:w-px after:bg-white/10">
                                                <div class="px-4 sm:px-6">
                                                    <DialogTitle class="text-base font-semibold text-white"> {{ isEdit ?
                                                        'Edit Permission' : 'Create Permission' }}
                                                    </DialogTitle>
                                                </div>
                                                <form @submit.prevent="submit">
                                                    <div class="relative mt-6 flex-1 px-4 sm:px-6">
                                                        <div class="flex flex-col items-start">
                                                            <label for="name">
                                                                <span class="text-sm font-semibold"> Name </span>
                                                                <input type="text" id="name" v-model="form.name"
                                                                    class="mt-0.5 w-full border-2 border-black shadow-[4px_4px_0_0] focus:ring-2 focus:ring-yellow-300 sm:text-lg">
                                                            </label>
                                                            <div v-if="form.errors.name" class="text-red-500">
                                                                {{ form.errors.name }}
                                                            </div>
                                                            <div class="flex flex-col items-end mt-6">
                                                                <button :disabled="form.processing"
                                                                    class="w-full md:w-auto group relative inline-block text-sm font-medium text-indigo-600">
                                                                    <span
                                                                        class="absolute inset-0 translate-x-0.5 translate-y-0.5 bg-indigo-600 transition-transform group-hover:translate-x-0 group-hover:translate-y-0"></span>
                                                                    <span
                                                                        class="relative block border border-current bg-white px-8 py-3">
                                                                        {{ isEdit ? 'Update Permission' : 'Save Permission' }}
                                                                    </span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </DialogPanel>
                                    </TransitionChild>
                                </div>
                            </div>
                        </div>
                    </Dialog>
                </TransitionRoot>
            </div>
        </template>
        <!-- END slider Add Permission -->
    </div>
</template>
