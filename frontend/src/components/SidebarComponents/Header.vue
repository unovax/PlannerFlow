<template>
    <header
        class="w-full h-[60px] bg-gray-800 flex gap-2 text-gray-300 items-center p-2 justify-between"
    >
        <div class="flex gap-2 items-center">
            <SidebarToggleButton class="md:hidden"/>
            <h1 class="font-bold text-lg">{{ title }}</h1>
        </div>
        <div class="flex gap-2 items-center">
            <slot name="actions"></slot>
        </div>
    </header>
</template>
<script lang="ts">
import SidebarToggleButton from './SidebarToggleButton.vue'
export default {
    components: {
        SidebarToggleButton
    },
    props: {
        title: {
            type: String,
            default: 'Catalogs',
        },
    },
}
</script>
./SidebarToggleButton.vue