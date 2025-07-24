<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";
const showFilter = ref(false);

const toggleFilter = () => {
    showFilter.value = !showFilter.value;
};
const showExport = ref(false);

const toggleExportDropdown = () => {
    showExport.value = !showExport.value;
};
</script>

<template>
    <Head title="Accounting" />
    <AppLayout>
        <div class="p-6 space-y-6">
            <!-- Header and Breadcrumb -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Accounting Module</h1>

                    <nav class="flex text-sm" aria-label="Breadcrumb">
                        <ol
                            class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse"
                        >
                            <li class="inline-flex items-center">
                                <Link
                                    href="/"
                                    class="text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white inline-flex items-center"
                                >
                                    <svg
                                        class="w-3 h-3 mr-2"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"
                                        />
                                    </svg>
                                    Home
                                </Link>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg
                                        class="w-3 h-3 text-gray-400 mx-1 rtl:rotate-180"
                                        fill="none"
                                        viewBox="0 0 6 10"
                                    >
                                        <path
                                            stroke="currentColor"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="m1 9 4-4-4-4"
                                        />
                                    </svg>
                                    <span
                                        class="text-gray-500 dark:text-gray-400"
                                        >Accounting</span
                                    >
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>

                <Link
                    :href="`/accounting/create`"
                    class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800"
                >
                    <svg
                        class="h-4 w-4 mr-2"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            clip-rule="evenodd"
                            fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                        />
                    </svg>
                    Add New Product
                </Link>
            </div>

            <!-- Card with Table -->

            <div
                class="relative overflow-hidden bg-white shadow-md sm:rounded-lg"
            >
                <div
                    class="flex flex-col px-4 py-3 space-y-3 lg:flex-row lg:items-center lg:justify-between lg:space-y-0 lg:space-x-4"
                >
                    <div class="flex items-center flex-1 space-x-4">
                        <h5>
                            <span class="text-gray-500">All Enteries:</span>
                            <span class="dark:text-white">123456</span>
                        </h5>
                        <h5>
                            <span class="text-gray-500">Total Expenses:</span>
                            <span class="dark:text-white">$88.4k</span>
                        </h5>
                        <h5>
                            <span class="text-gray-500">Total Income:</span>
                            <span class="dark:text-white">$88.4k</span>
                        </h5>
                        <h5>
                            <span class="text-gray-500">Capital:</span>
                            <span class="dark:text-white">$88.4k || -$99.8</span>
                        </h5>
                    </div>
                    <div class="w-full md:w-1/2">
                        <form class="flex items-center">
                            <label for="simple-search" class="sr-only"
                                >Search</label
                            >
                            <div class="relative w-full">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
                                >
                                    <svg
                                        aria-hidden="true"
                                        class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                        fill="currentColor"
                                        viewbox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    id="simple-search"
                                    class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Search"
                                    required=""
                                />
                            </div>
                        </form>
                    </div>
                    <!-- Filter Toggle Button -->
                    <!-- Filter Button -->

                    <div
                        class="flex flex-col flex-shrink-0 space-y-3 md:flex-row md:items-center lg:justify-end md:space-y-0 md:space-x-3"
                    >
                        <div class="relative inline-block text-left">
                            <button
                                @click="toggleFilter"
                                class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-dark border rounded-md focus:outline-none focus:ring-2 focus:ring-gray-600"
                            >
                                <svg
                                    class="w-4 h-4 text-dark"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                <span>Filter</span>
                                <svg
                                    class="w-4 h-4 text-dark"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </button>

                            <!-- Dropdown -->
                            <div
                                v-show="showFilter"
                                class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20"
                            >
                                <div class="p-3">
                                    <h6 class="mb-2 text-sm font-medium">
                                        Choose brand
                                    </h6>
                                    <ul class="space-y-2 text-sm">
                                        <li
                                            v-for="brand in [
                                                'Apple (56)',
                                                'Microsoft (16)',
                                                'Razor (49)',
                                                'Nikon (12)',
                                                'BenQ (74)',
                                            ]"
                                            :key="brand"
                                            class="flex items-center text-black"
                                        >
                                            <input
                                                type="checkbox"
                                                :id="brand"
                                                class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500"
                                            />
                                            <label
                                                :for="brand"
                                                class="ml-2 text-sm"
                                                >{{ brand }}</label
                                            >
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Export Dropdown -->
                        <div class="relative inline-block text-left">
                            <button
                                @click="toggleExportDropdown"
                                type="button"
                                class="flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                            >
                                <svg
                                    class="w-4 h-4 mr-2"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"
                                    />
                                </svg>
                                Export
                                <svg
                                    class="w-4 h-4 ml-2"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </button>

                            <!-- Dropdown Items -->
                            <div
                                v-show="showExport"
                                class="absolute right-0 z-20 mt-2 w-40 origin-top-right rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none"
                            >
                                <div class="py-1">
                                    <a
                                        href="#"
                                        @click="showExport = false"
                                        class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-100 dark:hover:bg-gray-700"
                                    >
                                        📄 Export PDF
                                    </a>
                                    <a
                                        href="#"
                                        @click="showExport = false"
                                        class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-100 dark:hover:bg-gray-700"
                                    >
                                        📊 Export Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table
                        class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
                    >
                        <thead
                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                        >
                            <tr>
                                <th scope="col" class="p-4">
                                    <div class="flex items-center">
                                        <input
                                            id="checkbox-all"
                                            type="checkbox"
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        />
                                        <label
                                            for="checkbox-all"
                                            class="sr-only"
                                            >checkbox</label
                                        >
                                    </div>
                                </th>
                                <th scope="col" class="px-4 py-3">Product</th>
                                <th scope="col" class="px-4 py-3">Category</th>
                                <th scope="col" class="px-4 py-3">Stock</th>
                                <th scope="col" class="px-4 py-3">Sales/Day</th>
                                <th scope="col" class="px-4 py-3">
                                    Sales/Month
                                </th>
                                <th scope="col" class="px-4 py-3">Rating</th>
                                <th scope="col" class="px-4 py-3">Sales</th>
                                <th scope="col" class="px-4 py-3">Revenue</th>
                                <th scope="col" class="px-4 py-3">
                                    Last Update
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700"
                            >
                                <td class="w-4 px-4 py-3">
                                    <div class="flex items-center">
                                        <input
                                            id="checkbox-table-search-1"
                                            type="checkbox"
                                            onclick="event.stopPropagation()"
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        />
                                        <label
                                            for="checkbox-table-search-1"
                                            class="sr-only"
                                            >checkbox</label
                                        >
                                    </div>
                                </td>
                                <th
                                    scope="row"
                                    class="flex items-center px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                >
                                    <img
                                        src="https://flowbite.s3.amazonaws.com/blocks/application-ui/products/imac-front-image.png"
                                        alt="iMac Front Image"
                                        class="w-auto h-8 mr-3"
                                    />
                                    Apple iMac 27&#34;
                                </th>
                                <td class="px-4 py-2">
                                    <span
                                        class="bg-primary-100 text-primary-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-primary-900 dark:text-primary-300"
                                        >Desktop PC</span
                                    >
                                </td>
                                <td
                                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                >
                                    <div class="flex items-center">
                                        <div
                                            class="inline-block w-4 h-4 mr-2 bg-red-700 rounded-full"
                                        ></div>
                                        95
                                    </div>
                                </td>
                                <td
                                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                >
                                    1.47
                                </td>
                                <td
                                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                >
                                    0.47
                                </td>
                                <td
                                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                >
                                    <div class="flex items-center">
                                        <svg
                                            aria-hidden="true"
                                            class="w-5 h-5 text-yellow-400"
                                            fill="currentColor"
                                            viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                            />
                                        </svg>
                                        <svg
                                            aria-hidden="true"
                                            class="w-5 h-5 text-yellow-400"
                                            fill="currentColor"
                                            viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                            />
                                        </svg>
                                        <svg
                                            aria-hidden="true"
                                            class="w-5 h-5 text-yellow-400"
                                            fill="currentColor"
                                            viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                            />
                                        </svg>
                                        <svg
                                            aria-hidden="true"
                                            class="w-5 h-5 text-yellow-400"
                                            fill="currentColor"
                                            viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                            />
                                        </svg>
                                        <svg
                                            aria-hidden="true"
                                            class="w-5 h-5 text-yellow-400"
                                            fill="currentColor"
                                            viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                            />
                                        </svg>
                                        <span
                                            class="ml-1 text-gray-500 dark:text-gray-400"
                                            >5.0</span
                                        >
                                    </div>
                                </td>
                                <td
                                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                >
                                    <div class="flex items-center">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewbox="0 0 24 24"
                                            fill="currentColor"
                                            class="w-5 h-5 mr-2 text-gray-400"
                                            aria-hidden="true"
                                        >
                                            <path
                                                d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z"
                                            />
                                        </svg>
                                        1.6M
                                    </div>
                                </td>
                                <td class="px-4 py-2">$3.2M</td>
                                <td
                                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                >
                                    Just now
                                </td>
                            </tr>
                            <tr
                                class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700"
                            >
                                <td class="w-4 px-4 py-3">
                                    <div class="flex items-center">
                                        <input
                                            id="checkbox-table-search-1"
                                            type="checkbox"
                                            onclick="event.stopPropagation()"
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        />
                                        <label
                                            for="checkbox-table-search-1"
                                            class="sr-only"
                                            >checkbox</label
                                        >
                                    </div>
                                </td>
                                <th
                                    scope="row"
                                    class="flex items-center px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                >
                                    <img
                                        src="https://flowbite.s3.amazonaws.com/blocks/application-ui/products/imac-front-image.png"
                                        alt="iMac Front Image"
                                        class="w-auto h-8 mr-3"
                                    />
                                    Apple iMac 20&#34;
                                </th>
                                <td class="px-4 py-2">
                                    <span
                                        class="bg-primary-100 text-primary-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-primary-900 dark:text-primary-300"
                                        >Desktop PC</span
                                    >
                                </td>
                                <td
                                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                >
                                    <div class="flex items-center">
                                        <div
                                            class="inline-block w-4 h-4 mr-2 bg-red-700 rounded-full"
                                        ></div>
                                        108
                                    </div>
                                </td>
                                <td
                                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                >
                                    1.15
                                </td>
                                <td
                                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                >
                                    0.32
                                </td>
                                <td
                                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                >
                                    <div class="flex items-center">
                                        <svg
                                            aria-hidden="true"
                                            class="w-5 h-5 text-yellow-400"
                                            fill="currentColor"
                                            viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                            />
                                        </svg>
                                        <svg
                                            aria-hidden="true"
                                            class="w-5 h-5 text-yellow-400"
                                            fill="currentColor"
                                            viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                            />
                                        </svg>
                                        <svg
                                            aria-hidden="true"
                                            class="w-5 h-5 text-yellow-400"
                                            fill="currentColor"
                                            viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                            />
                                        </svg>
                                        <svg
                                            aria-hidden="true"
                                            class="w-5 h-5 text-yellow-400"
                                            fill="currentColor"
                                            viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                            />
                                        </svg>
                                        <svg
                                            aria-hidden="true"
                                            class="w-5 h-5 text-yellow-400"
                                            fill="currentColor"
                                            viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                            />
                                        </svg>
                                        <span
                                            class="ml-1 text-gray-500 dark:text-gray-400"
                                            >5.0</span
                                        >
                                    </div>
                                </td>
                                <td
                                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                >
                                    <div class="flex items-center">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewbox="0 0 24 24"
                                            fill="currentColor"
                                            class="w-5 h-5 mr-2 text-gray-400"
                                            aria-hidden="true"
                                        >
                                            <path
                                                d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z"
                                            />
                                        </svg>
                                        6M
                                    </div>
                                </td>
                                <td class="px-4 py-2">$785K</td>
                                <td
                                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                >
                                    This week
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <nav
                    class="flex flex-col items-start justify-between p-4 space-y-3 md:flex-row md:items-center md:space-y-0"
                    aria-label="Table navigation"
                >
                    <span
                        class="text-sm font-normal text-gray-500 dark:text-gray-400"
                    >
                        Showing
                        <span
                            class="font-semibold text-gray-900 dark:text-white"
                            >1-10</span
                        >
                        of
                        <span
                            class="font-semibold text-gray-900 dark:text-white"
                            >1000</span
                        >
                    </span>
                    <ul class="inline-flex items-stretch -space-x-px">
                        <li>
                            <a
                                href="#"
                                class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                            >
                                <span class="sr-only">Previous</span>
                                <svg
                                    class="w-5 h-5"
                                    aria-hidden="true"
                                    fill="currentColor"
                                    viewbox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a
                                href="#"
                                class="flex items-center justify-center px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                                >1</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="flex items-center justify-center px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                                >2</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                aria-current="page"
                                class="z-10 flex items-center justify-center px-3 py-2 text-sm leading-tight border text-primary-600 bg-primary-50 border-primary-300 hover:bg-primary-100 hover:text-primary-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white"
                                >3</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="flex items-center justify-center px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                                >...</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="flex items-center justify-center px-3 py-2 text-sm leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                                >100</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                            >
                                <span class="sr-only">Next</span>
                                <svg
                                    class="w-5 h-5"
                                    aria-hidden="true"
                                    fill="currentColor"
                                    viewbox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </AppLayout>
</template>
