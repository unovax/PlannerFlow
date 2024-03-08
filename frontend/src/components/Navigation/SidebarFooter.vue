<template>
    <section class="p-4 relative">
        <span v-on:click="show = !show" :class="sidebar ? 'justify-between' : 'justify-center bg-gray-800 hover:bg-gray-950 py-2 rounded-full transition-colors duration-200'" class="flex gap-2 items-center text-gray-300 hover:text-gray-100 cursor-pointer">
            <span v-if="sidebar">{{ user.name }} </span>
            <ChevronRightIcon class="w-5 h-5"/>
        </span>
       
       <section v-if="show" class="px-4 py-2 gap-2 grid absolute left-full bottom-0 bg-gray-950 w-[200px] rounded-md m-2">
            <li class="grid gap-2" v-if="!sidebar">
                <span class="text-gray-300 text-center font-medium text-lg"> {{ user.name }}</span>
                <hr class="border-gray-700">
            </li>
            <span class="text-gray-300 bg-gray-900 cursor-pointer hover:bg-gray-800 rounded-md px-4 py-2">Mi perfil</span>
            <DangerButton class="w-full" v-on:click="logout">Cerrar sesion</DangerButton>   
        </section>
    </section>
</template>
<script lang="ts">
import axiosClient from '@/axiosClient';
import { User, EmptyUser } from '@/types/users';
import DangerButton from '@forms/DangerButton.vue';
import { ChevronRightIcon } from '@heroicons/vue/24/outline';
import SidebarLink from '@navigation/SidebarLink.vue';
import { mapState } from 'vuex';
export default {
    components:{
        DangerButton,
        ChevronRightIcon,
        SidebarLink
    },
    data() {
        return {
            user: JSON.parse(localStorage.getItem('user') as string) ?? EmptyUser as User,
            show: false as boolean
        }
    },
    methods: {
        logout() {
            axiosClient.post('/logout').then(() => {
                localStorage.removeItem('user');
                this.$router.push('/login')
            })
        },
    },
    computed: {
        ...mapState(['sidebar'])
    }
}
</script>