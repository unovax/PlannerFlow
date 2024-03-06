<template>
    <nav
        :class="sidebar ? 'w-full md:w-[260px]' : 'w-0 md:w-[75px]'"
        class="bg-gray-900 shadow-xl h-screen transition-all duration-300 flex flex-col border-r border-gray-700"
    >
        <SidebarHeader />
        <ul class="flex-1 w-full p-2 gap-2 flex flex-col">
            <SidebarLink v-for="link in links" :key="link.name" :link="link" />
        </ul>
        <div class="px-4 py-2">
            <DangerButton class="w-full" v-on:click="logout">Cerrar sesion</DangerButton>   
        </div>
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
import axiosClient from '@/axiosClient'

export default {
    components: {
        UsersIcon,
        CurrencyDollarIcon,
        DocumentIcon,
        SidebarHeader,
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
    methods: {
        logout() {
            axiosClient.post('/logout').then(() => {
                localStorage.removeItem('user');
                this.$router.push('/login')
            })
        },
    },
}
</script>
