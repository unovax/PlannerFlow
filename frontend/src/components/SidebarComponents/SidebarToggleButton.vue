<template>
    <Bars4Icon v-on:click="toggleSidebar" class="w-6 h-6 cursor-pointer hover:scale-125 transition-all duration-500"/>
</template>
<script lang="ts">
import { Bars4Icon } from "@heroicons/vue/24/outline";
import { mapActions } from 'vuex';
     export default {
        components: {
            Bars4Icon
        },
        props: {
            title: {
                type: String,
                default: "Catalogs",
            }
        },
        methods: {
            ...mapActions(['toggleSidebar']),
        }
    };
</script>