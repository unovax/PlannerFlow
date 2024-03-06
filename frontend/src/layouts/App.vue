<template>
    <div class="bg-black h-screen w-screen flex">
        <Sidebar />
        <div class="h-full flex flex-col w-full">
            <Header :title="title">
                <template v-slot:actions>
                    <slot name="actions"></slot>
                </template>
            </Header>
            <main class="flex-1 overflow-hidden bg-gray-900 flex flex-col">
                <slot></slot>
            </main>
        </div>
    </div>
</template>
<script lang="ts">
import Header from "@navigation/Header.vue";
import Sidebar from "@navigation/Sidebar.vue";
export default {
    components: {
        Header,
        Sidebar
    },
    props: {
        title: {
            type: String,
            default: 'Catalogs',
        },
    },
}
</script>
