<template>
    <AppLayout title="Clientes">
        <template v-slot:actions>
            <Search
                id="client-search-input"
                placeholder="Buscar cliente"
                @search="searchClients"
            />
            <PlusIcon
                v-on:click="createClientModal = true"
                class="w-8 cursor-pointer hover:scale-125 bg-gray-800 rounded-md p-1"
            />
        </template>
        <div class="w-full overflow-auto flex-1 p-2">
            <table>
                <thead>
                    <tr>
                        <th class="w-1/12">Acciones</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Rfc</th>
                        <th>Correo</th>
                        <th>Celular</th>
                        <th>Pais</th>
                        <th>Estado</th>
                        <th>Municipio</th>
                        <th>Dirección</th>
                        <th>Moneda</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-if="!loading">
                        <tr v-for="client in clients.data" :key="client.code">
                            <td>
                                <Icons>
                                    <PencilSquareIcon
                                        v-on:click="setClientToEdit(client)"
                                        class="w-6 cursor-pointer hover:scale-125"
                                    />
                                    <TrashIcon
                                        v-on:click="setClientToDelete(client)"
                                        class="w-6 cursor-pointer hover:scale-125"
                                    />
                                </Icons>
                            </td>
                            <td class="max-w-[100px]">{{ client.code }}</td>
                            <td class="max-w-[100px]">{{ client.name }}</td>
                            <td class="max-w-[100px]">{{ client.rfc }}</td>
                            <td class="max-w-[100px]">{{ client.email }}</td>
                            <td class="max-w-[100px]">{{ client.phone }}</td>
                            <td class="max-w-[100px]">{{ client.country }}</td>
                            <td class="max-w-[100px]">{{ client.state }}</td>
                            <td class="max-w-[100px]">{{ client.city }}</td>
                            <td class="max-w-[100px]">{{ client.address }}</td>
                            <td class="max-w-[100px]">
                                {{ client.currency?.name ?? 'Sin moneda' }}
                            </td>
                        </tr>
                    </template>

                    <RowLoading :show="loading" :colspan="11" />
                </tbody>
            </table>
        </div>
        <Pagination
            v-if="clients.data.length"
            v-model="clients_body.size_page"
            :data="clients"
            @search="searchClients"
            @changePage="changePage"
        />
        <Modal :show="createClientModal || editClientModal" maxWidth="800">
            <template v-slot:header>Crear cliente</template>
            <template v-slot:content>
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 p-4"
                >
                    <Input
                        label="Código"
                        :errors="v$.client.code?.$errors"
                        v-model="client.code"
                        id="client-code-input"
                        next_id="client-name-input"
                    />
                    <Input
                        containerClass="md:col-span-3"
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
                        containerClass="md:col-span-2"
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
                    <Select
                        label="Moneda"
                        :errors="v$.client.currency_id?.$errors"
                        v-model="client.currency_id"
                        id="client-currency_id-input"
                        next_id="client-country-input"
                        catalog="currencies"
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
                        containerClass="md:col-span-4"
                        label="Dirección"
                        :errors="v$.client.address?.$errors"
                        v-model="client.address"
                        id="client-address-input"
                    />
                </div>
            </template>
            <template v-slot:footer>
                <PrimaryButton v-on:click="saveClient">Guardar</PrimaryButton>
                <DangerButton v-on:click="erase">Cancelar</DangerButton>
            </template>
        </Modal>
        <ConfirmationModal
            :show="deleteClientModal"
            text="Estas seguro de querer eliminar este cliente?"
            @success="deleteClient"
            @cancel="erase"
        />
    </AppLayout>
</template>
<script lang="ts">
import Modal from '@components/Containers/Modal.vue'
import PrimaryButton from '@forms/PrimaryButton.vue'
import DangerButton from '@forms/DangerButton.vue'
import AppLayout from '@layouts/App.vue'
import {
    PlusIcon,
    PencilSquareIcon,
    TrashIcon,
} from '@heroicons/vue/24/outline'
import Input from '@forms/Input.vue'
import { useVuelidate } from '@vuelidate/core'
import Icons from '@components/Containers/Icons.vue'
import RowLoading from '@components/Tables/RowLoading.vue'
import ConfirmationModal from '@components/Containers/ConfirmationModal.vue'
import Search from '@forms/Search.vue'
import Pagination from '@components/Tables/Pagination.vue'
import { required, email } from '@vuelidate/validators'
import { Data, Client, RequestBody, Currency } from '@/types'
import Select from '@forms/Select.vue'
import axiosClient from '@/axiosClient';

export default {
    components: {
        Modal,
        PrimaryButton,
        DangerButton,
        AppLayout,
        PlusIcon,
        Input,
        PencilSquareIcon,
        Icons,
        RowLoading,
        TrashIcon,
        ConfirmationModal,
        Search,
        Pagination,
        Select,
    },
    data() {
        return {
            clients: {
                data: [] as Client[],
            } as Data,
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
                currency_id: 0,
                currency: { } as Currency
            } as Client,
            clients_body: {
                size_page: localStorage.getItem('size_page') || 10,
                search: '',
                link: 'clients',
            } as RequestBody,
            createClientModal: false,
            editClientModal: false,
            deleteClientModal: false,
            loading: false,
            v$: useVuelidate(),
            currencies: [] as Currency[],
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
    mounted() {
        this.getClients()
    },
    methods: {
        openClientModal() {
            this.createClientModal = true
        },
        closeClientModal() {
            this.createClientModal = false
        },
        searchClients(search: string) {
            this.clients_body.search = search
            this.clients_body.page = 1
            this.getClients()
        },
        changePage(page: number) {
            this.clients_body.page = page
            this.getClients()
        },
        getClients() {
            this.loading = true
            axiosClient
                .get(this.clients_body.link, {
                    params: {
                        ...this.clients_body,
                    },
                })
                .then(({ data }) => {
                    this.clients = data.data
                    this.loading = false
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        saveClient() {
            if (this.createClientModal) {
                this.createClient()
            } else {
                this.editClient()
            }
        },
        createClient() {
            this.v$.client.$touch()
            if (this.v$.client.$invalid) return
            axiosClient.post('clients', this.client)
                .then(({data}) => {
                    this.clients.data.push(data.data)
                    this.erase()
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        setClientToEdit(client: Client) {
            this.client = { ...client }
            this.editClientModal = true
        },
        setClientToDelete(client: Client) {
            this.client = { ...client }
            this.deleteClientModal = true
        },
        editClient() {
            this.v$.client.$touch()
            if (this.v$.client.$invalid) return
            axiosClient
                .put(
                    `clients/${this.client.id}`,
                    this.client
                )
                .then((response) => {
                    const index = this.clients.data.findIndex(
                        (c) => c.id === this.client.id
                    )
                    this.clients.data[index] = response.data.data
                    this.erase()
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        deleteClient() {
            axiosClient
                .delete(`clients/${this.client.id}`)
                .then(() => {
                    this.clients.data = this.clients.data.filter(
                        (c) => c.id !== this.client.id
                    )
                    this.erase()
                })
                .catch((error) => {
                    console.log(error)
                })
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
                currency_id: 0,
                currency: { } as Currency
            }
            this.createClientModal = false
            this.editClientModal = false
            this.deleteClientModal = false
            this.v$.$reset()
        },
    },
}
</script>
../types@/types@/axiosClient
