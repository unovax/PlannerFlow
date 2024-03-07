<template>
    <Guest>
        <section class="w-full max-w-[600px] bg-gray-900 rounded-xl overflow-hidden">
            <h1 class="bg-gray-800 text-white font-bold text-lg px-4 py-2">Crear cuenta</h1>
            <div class="p-4 gap-2 grid grid-cols-1">
                <Input
                    label="Empresa"
                    :errors="v$.tenant.name?.$errors"
                    v-model="tenant.name"
                    id="tenant-name-input"
                />
                <Input
                    label="Nombre"
                    :errors="v$.tenant.user.name?.$errors"
                    v-model="tenant.user.name"
                    id="tenant.user-name-input"
                />
                <Input
                    label="Correo electronico"
                    :errors="v$.tenant.user.email?.$errors"
                    v-model="tenant.user.email"
                    id="tenant.user-email-input"
                />
                <Input
                    label="Telefono"
                    :errors="v$.tenant.phone?.$errors"
                    v-model="tenant.phone"
                    id="tenant-phone-input"
                />
                <Input
                    label="Pais"
                    :errors="v$.tenant.country?.$errors"
                    v-model="tenant.country"
                    id="tenant-country-input"
                />
                <Input
                    label="Estado"
                    :errors="v$.tenant.state?.$errors"
                    v-model="tenant.state"
                    id="tenant-state-input"
                />
                <Input
                    label="Ciudad"
                    :errors="v$.tenant.city?.$errors"
                    v-model="tenant.city"
                    id="tenant-city-input"
                />
                <Input
                    label="Dirección"
                    :errors="v$.tenant.address?.$errors"
                    v-model="tenant.address"
                    id="tenant-address-input"
                />
                <Input
                    label="Contraseña"
                    type="password"
                    :errors="v$.tenant.user.password?.$errors"
                    v-model="tenant.user.password"
                    id="tenant.user-password-input"
                />
                <Input
                    label="Confirmar contraseña"
                    type="password"
                    :errors="v$.tenant.user.password_confirmation?.$errors"
                    v-model="tenant.user.password_confirmation"
                    id="tenant.user-password_confirmation-input"
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
import Guest from '@layouts/Guest.vue'
import Input from '@forms/Input.vue'
import PrimaryButton from '@forms/PrimaryButton.vue'
import { Tenant, EmptyTenant } from '@/types/users'
import { useVuelidate } from '@vuelidate/core'
import { required, email } from '@vuelidate/validators'
import axiosClient from '@/axiosClient'
import { mapActions } from 'vuex'

export default {
    components: {
        Guest,
        Input,
        PrimaryButton
    },
    data() {
        return {
            tenant: EmptyTenant as Tenant,
            v$: useVuelidate(),
        }
    },
    validations() {
        return {
            tenant: {
                name: required,
                phone: required,
                country: required,
                state: required,
                city: required,
                address: required,
                user: {
                    name: required,
                    email: { required, email },
                    password: required,
                    password_confirmation: required
                }
            },
        }
    },
    methods:{
        ...mapActions(['loginUser']),
        register() {
            this.v$.tenant.$touch()
            if (this.v$.tenant.$invalid) return
            axiosClient.post('/register', this.tenant)
                .then(({ data }) => {
                    data.user.token = data.token;
                    this.loginUser(data.user);
                    this.$router.push({ name: 'Clientes' })
                })
                .catch((error) => {
                    console.log(error)
                })
        }
    }
}
</script>