<template>
    <nav
        :class="sidebar ? 'w-full md:w-[260px]' : 'w-0 md:w-[75px]'"
        class="bg-gray-900 shadow-xl h-screen transition-all duration-300 flex flex-col border-r border-gray-700"
    >
        <SidebarHeader />
        <ul class="flex-1 w-full p-2 gap-2 flex flex-col">
            <SidebarLink v-for="link in links" :key="link.name" :link="link" />
        </ul>
        <SidebarFooter/>
    </nav>
</template>

<script lang="ts">
import { mapState } from 'vuex'
import {
    UsersIcon,
    CurrencyDollarIcon,
    DocumentIcon,
    HomeIcon,
} from '@heroicons/vue/24/outline'
import SidebarHeader from '@navigation/SidebarHeader.vue'
import SidebarLink from '@navigation/SidebarLink.vue'
import { Link } from '@/types'
import DangerButton from '@forms/DangerButton.vue'
import SidebarFooter from '@navigation/SidebarFooter.vue'

export default {
    components: {
        UsersIcon,
        CurrencyDollarIcon,
        DocumentIcon,
        SidebarHeader,
        SidebarFooter,
        SidebarLink,
        DangerButton,
        HomeIcon,
    },
    data() {
        return {
            links: [
                { name: 'Clientes', href: '/clientes', icon: UsersIcon },
                { name: 'Monedas', href: '/monedas', icon: CurrencyDollarIcon },
                { name: 'Pedidos', href: '/pedidos', icon: DocumentIcon },
                { name: 'Almacenes', href: '/almacenes', icon: HomeIcon }
            ] as Link[],
        }
    },
    computed: {
        ...mapState(['sidebar']),
        currentRoute() {
            return this.$route.name
        },
    },
}
</script>
