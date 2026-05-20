<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3'
import { ref } from 'vue'

import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot
} from '@headlessui/vue'

import { XMarkIcon } from '@heroicons/vue/24/outline'

import DataTable from 'datatables.net-vue3'
import DataTablesLib from 'datatables.net'

import ConfirmModal from '@/components/ConfirmModal.vue'

import Swal from 'sweetalert2'

DataTable.use(DataTablesLib)

type Role = {
    id: number
    name: string
}

type User = {
    id: number
    name: string
    email: string
    roles: Role[]
    created_at: string
}

const props = defineProps<{
    users: User[]
    roles: Role[]
}>()

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Users',
                href: '/users',
            },
        ],
    },
})

const open = ref(false)

const isEdit = ref(false)

const selectedUserId =
    ref<number | null>(null)

const form = useForm({
    name: '',
    email: '',
    password: '',
    role: ''
})

const columns = [
    { data: 'id', title: 'ID' },
    { data: 'name', title: 'Name' },
    { data: 'email', title: 'Email' },
    {
        data: 'roles',
        title: 'Role',
        render: (data: Role[]) =>
            data?.length
                ? data[0].name
                : 'No Role'
    },
    {
        data: null,
        title: 'Actions',
        orderable: false,
        searchable: false
    }
]

// OPEN CREATE

const openCreate = () => {

    isEdit.value = false

    selectedUserId.value = null

    form.reset()

    open.value = true
}

// EDIT USER

const editUser = (user: User) => {

    isEdit.value = true

    selectedUserId.value = user.id

    form.name = user.name

    form.email = user.email

    form.password = ''

    form.role =
        user.roles?.length
            ? user.roles[0].name
            : ''

    open.value = true
}

// SUBMIT

const submit = () => {

    if (
        isEdit.value &&
        selectedUserId.value
    ) {

        form.put(
            `/users/${selectedUserId.value}`,
            {
                preserveScroll: true,

                onSuccess: () => {

                    open.value = false

                    form.reset()

                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: 'User updated successfully',
                        timer: 2000,
                        showConfirmButton: false
                    })
                }
            }
        )

    } else {

        form.post('/users', {
            preserveScroll: true,

            onSuccess: () => {

                open.value = false

                form.reset()

                Swal.fire({
                    icon: 'success',
                    title: 'Created!',
                    text: 'User created successfully',
                    timer: 2000,
                    showConfirmButton: false
                })
            }
        })
    }
}

// DELETE

const confirmOpen =
    ref(false)

const selectedId =
    ref<number | null>(null)

const askDelete = (
    id: number
) => {

    selectedId.value = id

    confirmOpen.value = true
}

const deleteUser = () => {

    if (!selectedId.value)
        return

    router.delete(
        `/users/${selectedId.value}`,
        {
            preserveScroll: true,

            onSuccess: () => {

                confirmOpen.value = false

                selectedId.value = null

                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: 'User deleted successfully',
                    timer: 2000,
                    showConfirmButton: false
                })
            }
        }
    )
}
</script>

<template>

    <Head title="Users" />

    <ConfirmModal :show="confirmOpen" title="Delete User" message="Are you sure?" confirm-text="Delete"
        cancel-text="Cancel" @close="confirmOpen = false" @confirm="deleteUser" />

    <div class="flex flex-col gap-4 p-4">

        <div class="flex justify-between">

            <h1 class="text-2xl font-bold">
                User Management
            </h1>
            <div class="flex flex-col items-end">
                <button class="w-full md:w-auto group relative inline-block text-sm font-medium text-indigo-600"
                    @click="openCreate">
                    <span
                        class="absolute inset-0 translate-x-0.5 translate-y-0.5 bg-indigo-600 transition-transform group-hover:translate-x-0 group-hover:translate-y-0"></span>
                    <span class="relative block border border-current bg-white px-8 py-3"> Add User </span>
                </button>
            </div>
        </div>

        <!-- TABLE -->

        <DataTable :data="users" :columns="columns" class="display w-full">

            <template #column-4="{ rowData }">

                <div class="flex gap-2">

                    <button @click="editUser(rowData)" class="px-3 py-1 text-sm bg-blue-600 text-white rounded">
                        Edit
                    </button>

                    <button @click="askDelete(rowData.id)" class="px-3 py-1 text-sm bg-red-600 text-white rounded">
                        Delete
                    </button>

                </div>

            </template>

        </DataTable>

        <!-- SLIDE OVER -->

        <TransitionRoot as="template" :show="open">

            <Dialog class="relative z-10" @close="open = false">

                <div class="fixed inset-0 bg-black/50" />

                <div class="fixed inset-0 overflow-hidden">

                    <div class="absolute inset-0 overflow-hidden">

                        <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">

                            <DialogPanel class="pointer-events-auto w-screen max-w-md bg-gray-800 text-white">

                                <div class="flex items-center justify-between p-4 border-b">

                                    <DialogTitle>
                                        {{
                                            isEdit
                                                ? 'Edit User'
                                                : 'Create User'
                                        }}
                                    </DialogTitle>

                                    <button @click="open = false">
                                        <XMarkIcon class="size-6" />
                                    </button>

                                </div>

                                <form @submit.prevent="submit" class="p-4 space-y-4">
                                    <div class="relative mt-6 flex-1 px-4 sm:px-6">
                                        <div class="flex flex-col items-start">
                                            <div class="mb-2">
                                                <label for="name">
                                                    <span class="text-sm font-semibold"> Name </span>
                                                    <input type="text" id="name" v-model="form.name"
                                                        class="mt-0.5 w-full border-2 border-black shadow-[4px_4px_0_0] focus:ring-2 focus:ring-yellow-300 sm:text-lg">
                                                </label>
                                                <div v-if="form.errors.name" class="m-1 text-red-500">
                                                    {{ form.errors.name }}
                                                </div>
                                            </div>
                                            <div>
                                                <label for="email">
                                                    <span class="text-sm font-semibold"> Email </span>
                                                    <input type="email" id="email" v-model="form.email"
                                                        class="mt-0.5 w-full border-2 border-black shadow-[4px_4px_0_0] focus:ring-2 focus:ring-yellow-300 sm:text-lg">
                                                </label>
                                                <div v-if="form.errors.email" class="m-1 text-red-500">
                                                    {{ form.errors.email }}
                                                </div>
                                            </div>
                                            <div>
                                                <label for="password">
                                                    <span class="text-sm font-semibold"> Password </span>
                                                    <input type="password" id="password" v-model="form.password"
                                                        class="mt-0.5 w-full border-2 border-black shadow-[4px_4px_0_0] focus:ring-2 focus:ring-yellow-300 sm:text-lg">
                                                </label>
                                                <div v-if="form.errors.password" class="m-1 text-red-500">
                                                    {{ form.errors.password }}
                                                </div>
                                            </div>
                                            <div>
                                                <label for="role">
                                                    <span class="text-sm font-semibold">
                                                        Role
                                                    </span>

                                                    <select id="role" v-model="form.role"
                                                        class="mt-0.5 w-full border-2 border-black bg-white text-black shadow-[4px_4px_0_0] focus:ring-2 focus:ring-yellow-300 sm:text-lg">

                                                        <option value="">
                                                            Select Role
                                                        </option>

                                                        <option v-for="role in roles" :key="role.id" :value="role.name">
                                                            {{ role.name }}
                                                        </option>

                                                    </select>
                                                </label>

                                                <div v-if="form.errors.role" class="text-red-500">
                                                    {{ form.errors.role }}
                                                </div>
                                            </div>

                                            <div class="flex flex-col items-end mt-6">
                                                <button :disabled="form.processing"
                                                    class="w-full md:w-auto group relative inline-block text-sm font-medium text-indigo-600">
                                                    <span
                                                        class="absolute inset-0 translate-x-0.5 translate-y-0.5 bg-indigo-600 transition-transform group-hover:translate-x-0 group-hover:translate-y-0"></span>
                                                    <span
                                                        class="relative block border border-current bg-white px-8 py-3">
                                                        {{ isEdit ? 'Update User' : 'Save User' }}
                                                    </span>
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </form>

                            </DialogPanel>

                        </div>

                    </div>

                </div>
            </Dialog>
        </TransitionRoot>
    </div>
</template>
