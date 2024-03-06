<template>
    <footer class="flex gap-2 justify-between p-2">
        <select
            v-on:change="search"
            v-model="model"
            class="bg-gray-800 text-gray-300 rounded-md w-[80px]"
            name=""
            id=""
        >
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
        <nav>
            <ul class="flex items-center -space-x-px h-8 text-sm">
                <button
                    :disabled="!data.prev_page_url"
                    v-on:click="$emit('changePage', Number(data.current_page) - 1)"
                    class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white cursor-pointer disabled:cursor-default disabled:bg-gray-700 disabled:border-gray-700 disabled:hover:text-gray-400"
                >
                    <ArrowLeftIcon class="h-5 w-5" />
                </button>
                <li
                    v-for="link in data.links.filter(
                        (link) =>
                            /^\d+$/.test(link.label) || link.label === '...'
                    )"
                    :key="link.label"
                    v-on:click="$emit('changePage', Number(link.label))"
                    :class="[
                        link.url ? 'cursor-pointer' : 'cursor-default',
                        data.current_page == Number(link.label) ? 'bg-gray-500 text-white' : 'text-gray-500 border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white',
                        'flex items-center justify-center px-3 h-8 leading-tight',
                    ]"
                    href="#"
                >
                    {{ link.label }}
                </li>
                <button
                    :disabled="!data.next_page_url"
                    v-on:click="$emit('changePage', Number(data.current_page) + 1)"
                    class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-l-0 border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white cursor-pointer disabled:cursor-default disabled:bg-gray-700 disabled:border-gray-700 disabled:hover:text-gray-400"
                >
                    <ArrowRightIcon class="h-5 w-5" />
                </button>
            </ul>
        </nav>
    </footer>
</template>
<script lang="ts">
import { ArrowLeftIcon, ArrowRightIcon } from '@heroicons/vue/24/outline'
import { Data } from '../../types'

export default {
    components: {
        ArrowLeftIcon,
        ArrowRightIcon,
    },
    props: {
        data: {
            type: Object as () => Data,
            required: true,
        },
        modelValue: {
            type: [String, Number],
            required: true,
        },
    },
    methods: {
        search(event: Event) {
            const target = event.target as HTMLSelectElement
            localStorage.setItem('size_page', target.value)
            this.$emit('search')
        },
    },
    computed: {
        model: {
            get() {
                return this.modelValue
            },
            set(value: string | number) {
                this.$emit('update:modelValue', value)
            },
        },
    },
}
</script>
