<template>
    <Guest>
        <section class="w-full max-w-[600px] bg-gray-900 rounded-xl overflow-hidden">
            <h1 class="bg-gray-800 text-white font-bold text-lg px-4 py-2">Crear cuenta</h1>
            <div class="p-4 space-y-2">
                <Input
                    label="Nombre"
                    :errors="v$.user.name?.$errors"
                    v-model="user.name"
                    id="user-name-input"
                />
                <Input
                    label="Correo electronico"
                    :errors="v$.user.email?.$errors"
                    v-model="user.email"
                    id="user-email-input"
                />
                <Input
                    label="Contraseña"
                    type="password"
                    :errors="v$.user.password?.$errors"
                    v-model="user.password"
                    id="user-password-input"
                />
                <Input
                    label="Confirmar contraseña"
                    type="password"
                    :errors="v$.user.password_confirmation?.$errors"
                    v-model="user.password_confirmation"
                    id="user-password_confirmation-input"
                />
                <div class="py-2 flex items-center justify-between gap-2">
                    <a href="/login" class="text-blue-500 hover:underline">Ya tengo cuenta</a>
                    <PrimaryButton v-on:click="register">Iniciar Sesion</PrimaryButton>
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
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
            } as User,
            v$: useVuelidate(),
        }
    },
    validations() {
        return {
            user: {
                name: required,
                email: { required , email },
                password: required,
                password_confirmation: required,
            },
        }
    },
    methods:{
        register() {
            this.v$.user.$touch()
            if (this.v$.user.$invalid) return
            axiosClient.post('/register', this.user)
                .then(() => {
                    this.$router.push('/login')
                })
                .catch((error) => {
                    console.log(error)
                })
        }
    }
}
</script>