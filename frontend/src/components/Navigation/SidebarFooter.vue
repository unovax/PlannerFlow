<template>
    <div class="p-4 relative">
        <span v-on:click="show = !show" class="flex gap-2 items-center justify-between text-gray-300 hover:text-gray-100 cursor-pointer">
            {{ user.name }} 
            <ChevronRightIcon class="w-5 h-5"/>
        </span>
       
       <div v-if="show" class="px-4 py-2 gap-2 grid absolute left-full bottom-0 bg-gray-950 w-full rounded-md m-2">
            <span class="text-gray-300">Mi perfil</span>
            <hr class="border-gray-700">
            <DangerButton class="w-full" v-on:click="logout">Cerrar sesion</DangerButton>   
        </div>
    </div>
</template>
<script lang="ts">
import axiosClient from '@/axiosClient';
import { User, EmptyUser } from '@/types/users';
import DangerButton from '@forms/DangerButton.vue';
import { ChevronRightIcon } from '@heroicons/vue/24/outline';
import SidebarLink from '@navigation/SidebarLink.vue';
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
}
</script>