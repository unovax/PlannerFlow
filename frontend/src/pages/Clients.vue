<template>
    <CatalogsLayout title="Clientes">
        <template v-slot:actions>
            <PlusIcon
                v-on:click="createClientModal = true"
                class="w-8 cursor-pointer hover:scale-125 bg-gray-800 rounded-md p-1"
            />
        </template>
        <table>
            <thead>
                <tr>
                    <th>Acciones</th>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Rfc</th>
                    <th>Correo</th>
                    <th>Celular</th>
                    <th>Pais</th>
                    <th>Estado</th>
                    <th>Municipio</th>
                    <th>Dirección</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="client in clients" :key="client.code">
                    <td>
                        <Icons>
                            <PencilSquareIcon
                                v-on:click="setClient(client)"
                                class="w-6 cursor-pointer hover:scale-125"
                            />
                        </Icons>
                    </td>
                    <td>{{ client.code }}</td>
                    <td>{{ client.name }}</td>
                    <td>{{ client.rfc }}</td>
                    <td>{{ client.email }}</td>
                    <td>{{ client.phone }}</td>
                    <td>{{ client.country }}</td>
                    <td>{{ client.state }}</td>
                    <td>{{ client.city }}</td>
                    <td>{{ client.address }}</td>
                </tr>
            </tbody>
        </table>

        <Modal :show="createClientModal || editClientModal" maxWidth="800">
            <template v-slot:header>Crear cliente</template>
            <template v-slot:content>
                <div class="grid grid-cols-3 gap-4 p-4">
                    <Input
                        label="Código"
                        :errors="v$.client.code?.$errors"
                        v-model="client.code"
                        id="client-code-input"
                        next_id="client-name-input"
                    />
                    <Input
                        containerClass="col-span-2"
                        label="Nombre"
                        :errors="v$.client.name?.$errors"
                        v-model="client.name"
                        id="client-name-input"
                        next_id="client-rfc-input"
                    />
                    <Input
                        label="Rfc"
                        :errors="v$.client.rfc?.$errors"
                        v-model="client.rfc"
                        id="client-rfc-input"
                        next_id="client-email-input"
                    />
                    <Input
                        label="Correo electronico"
                        :errors="v$.client.email?.$errors"
                        v-model="client.email"
                        id="client-email-input"
                        next_id="client-phone-input"
                    />
                    <Input
                        label="Celular"
                        :errors="v$.client.phone?.$errors"
                        v-model="client.phone"
                        id="client-phone-input"
                        next_id="client-country-input"
                    />
                    <Input
                        label="Pais"
                        :errors="v$.client.country?.$errors"
                        v-model="client.country"
                        id="client-country-input"
                        next_id="client-state-input"
                    />
                    <Input
                        label="Estado"
                        :errors="v$.client.state?.$errors"
                        v-model="client.state"
                        id="client-state-input"
                        next_id="client-city-input"
                    />
                    <Input
                        label="Municipio"
                        :errors="v$.client.city?.$errors"
                        v-model="client.city"
                        id="client-city-input"
                        next_id="client-address-input"
                    />
                    <Input
                        containerClass="col-span-3"
                        label="Dirección"
                        :errors="v$.client.address?.$errors"
                        v-model="client.address"
                        id="client-address-input"
                    />
                </div>
            </template>
            <template v-slot:footer>
                <PrimaryButton v-on:click="saveClient">Guardar</PrimaryButton>
                <DangerButton v-on:click="erase"
                    >Cancelar</DangerButton
                >
            </template>
        </Modal>
    </CatalogsLayout>
</template>
<script lang="ts">
import Modal from '../components/Modal.vue'
import PrimaryButton from '../components/PrimaryButton.vue'
import DangerButton from '../components/DangerButton.vue'
import CatalogsLayout from '../layouts/CatalogsLayout.vue'
import { PlusIcon, PencilSquareIcon } from '@heroicons/vue/24/outline'
import Input from '../components/Input.vue'
import { useVuelidate } from '@vuelidate/core'
import { required, email } from '@vuelidate/validators'
import Icons from '../components/Icons.vue'
import axios from 'axios';

interface Client {
    id: number | null
    code: string
    name: string
    rfc: string
    email: string
    phone: string
    country: string
    state: string
    city: string
    address: string,
    currency_id: number | null
}

export default {
    components: {
        Modal,
        PrimaryButton,
        DangerButton,
        CatalogsLayout,
        PlusIcon,
        Input,
        PencilSquareIcon,
        Icons
    },
    data() {
        return {
            clients: [] as Client[],
            client: {
                id: null,
                code: '',
                name: '',
                rfc: '',
                email: '',
                phone: '',
                address: '',
                country: '',
                state: '',
                city: '',
                currency_id: null,
            } as Client,
            createClientModal: false,
            editClientModal: false,
            v$: useVuelidate(),
        }
    },
    validations() {
        return {
            client: {
                code: { required },
                name: { required },
                rfc: { required },
                email: { required, email },
                phone: { required },
                address: { required },
                country: { required },
                state: { required },
                city: { required },
            },
        }
    },
    methods: {
        openClientModal() {
            this.createClientModal = true
        },
        closeClientModal() {
            this.createClientModal = false
        },
        saveClient() {
            if(this.createClientModal){
                this.createClient()
            }
            else{
                this.editClient()
            }
        },
        createClient() {
            this.v$.client.$touch()
            if (this.v$.client.$invalid) return

            axios.post('http://localhost:8000/clients', this.client).then((response: { status: number }) => {
                if (response.status === 201) {
                    this.clients.push({ ...this.client })
                    this.erase();
                    return;
                }
                throw new Error('Error al guardar el cliente')
            }).catch((error: Error) => {
                console.error(error)
            })
        },
        setClient(client: Client) {
            this.client = { ...client }
            this.editClientModal = true
        },
        editClient() {
            this.v$.client.$touch()
            if (this.v$.client.$invalid) return
            const index = this.clients.findIndex((c) => c.id === this.client.id)
            this.clients[index] = { ...this.client }
            this.erase();
        },
        erase() {
            this.client = {
                id: null,
                code: '',
                name: '',
                rfc: '',
                email: '',
                phone: '',
                address: '',
                country: '',
                state: '',
                city: '',
                currency_id: null,
            }
            this.createClientModal = false
            this.editClientModal = false
            this.v$.$reset();
        },
    },
}
</script>
