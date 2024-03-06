<template>
    <div class="space-y-1 relative flex items-center">
        <input
            :id="id"
            class="border rounded-md p-2 w-full bg-gray-800 text-gray-300 pr-10"
            :placeholder="placeholder"
            v-on:keyup.enter="search($event)">
        <MagnifyingGlassCircleIcon class="absolute right-2 h-6 w-6 text-gray-300" />
    </div>
</template>

<script lang="ts">
import { MagnifyingGlassCircleIcon } from '@heroicons/vue/24/outline';

export default {
    components: {
        MagnifyingGlassCircleIcon,
    },
    props: {
        id: {
            type: String,
            required: true,
        },
        placeholder: {
            type: String,
            default: '',
        },
    },
    methods: {
        search(event: KeyboardEvent) {
            if (event.target) {
                this.$emit('search', (event.target as HTMLInputElement).value);
            }
        },
    },
}
</script>
