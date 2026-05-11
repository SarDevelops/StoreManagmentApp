<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import { dashboard } from '@/routes';
import { route } from 'ziggy-js';
import { ref } from 'vue';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { XMarkIcon } from '@heroicons/vue/24/outline';

const open = ref(false)

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'roles',
                href: '/roles',
            },
        ],
    },
});
</script>

<template>

    <Head title="Permissions" />
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <h1>Role Model</h1>
        <div class="flex flex-col items-end">

            <button class="w-full md:w-auto group relative inline-block text-sm font-medium text-indigo-600"
                @click="open = true">
                <span
                    class="absolute inset-0 translate-x-0.5 translate-y-0.5 bg-indigo-600 transition-transform group-hover:translate-x-0 group-hover:translate-y-0"></span>

                <span class="relative block border border-current bg-white px-8 py-3"> Add Role </span>
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y-2 divide-gray-200 dark:divide-gray-700">
                <thead class="ltr:text-left rtl:text-right">
                    <tr class="*:font-medium *:text-gray-900 dark:*:text-white">
                        <th class="px-3 py-2 whitespace-nowrap">Name</th>
                        <th class="px-3 py-2 whitespace-nowrap">DoB</th>
                        <th class="px-3 py-2 whitespace-nowrap">Role</th>
                        <th class="px-3 py-2 whitespace-nowrap">Salary</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr class="*:text-gray-900 *:first:font-medium dark:*:text-white">
                        <td class="px-3 py-2 whitespace-nowrap">Nandor the Relentless</td>
                        <td class="px-3 py-2 whitespace-nowrap">04/06/1262</td>
                        <td class="px-3 py-2 whitespace-nowrap">Vampire Warrior</td>
                        <td class="px-3 py-2 whitespace-nowrap">$0</td>
                    </tr>

                    <tr class="*:text-gray-900 *:first:font-medium dark:*:text-white">
                        <td class="px-3 py-2 whitespace-nowrap">Laszlo Cravensworth</td>
                        <td class="px-3 py-2 whitespace-nowrap">19/10/1678</td>
                        <td class="px-3 py-2 whitespace-nowrap">Vampire Gentleman</td>
                        <td class="px-3 py-2 whitespace-nowrap">$0</td>
                    </tr>

                    <tr class="*:text-gray-900 *:first:font-medium dark:*:text-white">
                        <td class="px-3 py-2 whitespace-nowrap">Nadja</td>
                        <td class="px-3 py-2 whitespace-nowrap">15/03/1593</td>
                        <td class="px-3 py-2 whitespace-nowrap">Vampire Seductress</td>
                        <td class="px-3 py-2 whitespace-nowrap">$0</td>
                    </tr>

                    <tr class="*:text-gray-900 *:first:font-medium dark:*:text-white">
                        <td class="px-3 py-2 whitespace-nowrap">Colin Robinson</td>
                        <td class="px-3 py-2 whitespace-nowrap">01/09/1971</td>
                        <td class="px-3 py-2 whitespace-nowrap">Energy Vampire</td>
                        <td class="px-3 py-2 whitespace-nowrap">$53,000</td>
                    </tr>

                    <tr class="*:text-gray-900 *:first:font-medium dark:*:text-white">
                        <td class="px-3 py-2 whitespace-nowrap">Guillermo de la Cruz</td>
                        <td class="px-3 py-2 whitespace-nowrap">18/11/1991</td>
                        <td class="px-3 py-2 whitespace-nowrap">Familiar/Vampire Hunter</td>
                        <td class="px-3 py-2 whitespace-nowrap">$0</td>
                    </tr>
                </tbody>
            </table>
        </div>


        <template>
            <div>
                <!-- <button
                    class="rounded-md bg-white/10 px-2.5 py-1.5 text-sm font-semibold text-white inset-ring inset-ring-white/5 hover:bg-white/20"
                    @click="open = true">Open drawer</button> -->
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
                                                    <DialogTitle class="text-base font-semibold text-white">Create Role
                                                    </DialogTitle>
                                                </div>
                                                <div class="relative mt-6 flex-1 px-4 sm:px-6">
                                                    <div class="flex flex-col items-start">
                                                        <label for="Email">
                                                            <span class="text-sm font-semibold"> Name </span>

                                                            <input type="text" id="name"
                                                                class="mt-0.5 w-full border-2 border-black shadow-[4px_4px_0_0] focus:ring-2 focus:ring-yellow-300 sm:text-lg">
                                                        </label>
                                                        <div class="flex flex-col items-end mt-6">

                                                            <button
                                                                class="w-full md:w-auto group relative inline-block text-sm font-medium text-indigo-600">
                                                                <span
                                                                    class="absolute inset-0 translate-x-0.5 translate-y-0.5 bg-indigo-600 transition-transform group-hover:translate-x-0 group-hover:translate-y-0"></span>

                                                                <span
                                                                    class="relative block border border-current bg-white px-8 py-3">
                                                                    Save </span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
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

    </div>
</template>
