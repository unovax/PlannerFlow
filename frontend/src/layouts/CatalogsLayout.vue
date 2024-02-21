<template>
    <div class="h-full flex flex-col w-full">
        <header class="bg-indigo-900 text-white text-lg px-4 py-2 flex justify-between items-center">
            <h1>{{ title }}</h1>
            <slot name="actions"></slot>
        </header>
        <main class="flex-1 overflow-auto p-4 bg-gray-900">
            <slot></slot>
        </main>
    </div>
</template>
<script lang="ts">
    export default {
        props: {
            title: {
                type: String,
                default: "Catalogs",
            }
        },
    };
</script>