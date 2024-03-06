<template>
    <Guest>
        <section class="w-full max-w-[600px] bg-gray-900 rounded-xl overflow-hidden">
            <h1 class="bg-gray-800 rounded-t-md text-white font-bold text-lg px-4 py-2">Iniciar Sesion</h1>
            <div class="p-4 space-y-2">
                <Input
                    label="Correo electronico"
                    :errors="v$.user.email?.$errors"
                    v-model="user.email"
                    id="user-email-input"
                />
                <Input
                    label="Contraseña"
                    :errors="v$.user.password?.$errors"
                    v-model="user.password"
                    type="password"
                    id="user-password-input"
                />
                <div class="py-2 flex items-center justify-between gap-2">
                    <a href="#" class="text-blue-500 hover:underline">Olvidaste tu contraseña?</a>
                    <PrimaryButton v-on:click="login" >Iniciar Sesion</PrimaryButton>
                </div>
                <div class="flex justify-center">
                    <a href="/register" class="text-blue-500 hover:underline">Crear una cuenta</a>
                </div>
            </div>
        </section>
    </Guest>
</template>
<script lang="ts">
import Guest from '../../layouts/Guest.vue'
import Input from '../../components/Forms/Input.vue'
import PrimaryButton from '../../components/Forms/PrimaryButton.vue'
import { User } from '../../types'
import { useVuelidate } from '@vuelidate/core'
import { required, email } from '@vuelidate/validators'
import axiosClient from '../../axiosClient'

export default {
    components: {
        Guest,
        Input,
        PrimaryButton
    },
    data() {
        return {
            user: {
                email: '',
                password: ''
            } as User,
            v$: useVuelidate(),
        }
    },
    validations() {
        return {
            user: {
                email: { required , email },
                password: { required }
            },
        }
    },
    methods: {
        login() {
            this.v$.user.$touch()
            if (this.v$.user.$invalid) return
            axiosClient.post('/login', this.user)
                .then(response => {
                    console.log(response)
                    response.data.user.token = response.data.token;
                    localStorage.setItem('user', JSON.stringify(response.data.user));
                    this.$router.push({ name: 'Clientes' })
                })
                .catch(error => {
                    console.log(error)
                })
        }
    },
}
</script>