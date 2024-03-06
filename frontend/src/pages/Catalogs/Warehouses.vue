<template>
    <CatalogsLayout title="Almacenes">
        <template v-slot:actions>
            <Search
                id="warehouse-search-input"
                placeholder="Buscar almacenes"
                @search="searchWarehouses"
            />
            <PlusIcon
                v-on:click="createWarehouseModal = true"
                class="w-8 cursor-pointer hover:scale-125 bg-gray-800 rounded-md p-1"
            />
        </template>
        <div class="w-full overflow-auto flex-1 p-2">
            <table>
                <thead>
                    <tr>
                        <th>Acciones</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="warehouse in warehouses.data" :key="warehouse.code">
                        <td>
                            <Icons>
                                <PencilSquareIcon
                                    v-on:click="setWarehouseToEdit(warehouse)"
                                    class="w-6 cursor-pointer hover:scale-125"
                                />
                                <TrashIcon
                                    v-on:click="setWarehouseToDelete(warehouse)"
                                    class="w-6 cursor-pointer hover:scale-125"
                                />
                            </Icons>
                        </td>
                        <td class="max-w-[100px]">{{ warehouse.code }}</td>
                        <td class="max-w-[100px]">{{ warehouse.name }}</td>
                        <td class="max-w-[100px]">{{ warehouse.address }}</td>
                    </tr>
                    <RowLoading :show="loading" :colspan="10" />
                </tbody>
            </table>
        </div>
        <Pagination
            v-if="warehouses.data.length"
            v-model="warehouses_body.size_page"
            :data="warehouses"
            @search="searchWarehouses"
            @changePage="changePage"
        />
        <Modal :show="createWarehouseModal || editWarehouseModal" maxWidth="800">
            <template v-slot:header>Crear almacen</template>
            <template v-slot:content>
                <div class="grid grid-cols-3 gap-4 p-4">
                    <Input
                        label="Código"
                        :errors="v$.warehouse.code?.$errors"
                        v-model="warehouse.code"
                        id="warehouse-code-input"
                        next_id="warehouse-name-input"
                    />
                    <Input
                        container-class="col-span-2"
                        label="Nombre"
                        :errors="v$.warehouse.name?.$errors"
                        v-model="warehouse.name"
                        id="warehouse-name-input"
                        next_id="warehouse-symbol-input"
                    />
                    <Input
                    label="Pais"
                    :errors="v$.warehouse.country?.$errors"
                    v-model="warehouse.country"
                    id="warehouse-country-input"
                    next_id="warehouse-exchange_rate-input"
                    />
                    <Input
                    label="Estado"
                    :errors="v$.warehouse.state?.$errors"
                    v-model="warehouse.state"
                    id="warehouse-state-input"
                    next_id="warehouse-exchange_rate-input"
                    />
                    <Input
                    label="Ciudad"
                    :errors="v$.warehouse.city?.$errors"
                    v-model="warehouse.city"
                    id="warehouse-city-input"
                    next_id="warehouse-exchange_rate-input"
                    />
                    <Input
                        container-class="col-span-3"
                        label="Dirección"
                        :errors="v$.warehouse.address?.$errors"
                        v-model="warehouse.address"
                        id="warehouse-address-input"
                        next_id="warehouse-exchange_rate-input"
                    />
                </div>
            </template>
            <template v-slot:footer>
                <PrimaryButton v-on:click="saveWarehouse">Guardar</PrimaryButton>
                <DangerButton v-on:click="erase">Cancelar</DangerButton>
            </template>
        </Modal>
        <ConfirmationModal
            :show="deleteWarehouseModal"
            text="Estas seguro de querer eliminar este almacen?"
            @success="deleteWarehouse"
            @cancel="erase"
        />
    </CatalogsLayout>
</template>
<script lang="ts">
import Modal from '../../components/Containers/Modal.vue'
import PrimaryButton from '../../components/Forms/PrimaryButton.vue'
import DangerButton from '../../components/Forms/DangerButton.vue'
import CatalogsLayout from '../../layouts/App.vue'
import {
    PlusIcon,
    PencilSquareIcon,
    TrashIcon,
} from '@heroicons/vue/24/outline'
import Input from '../../components/Forms/Input.vue'
import { useVuelidate } from '@vuelidate/core'
import Icons from '../../components/Containers/Icons.vue'
import axios from 'axios'
import RowLoading from '../../components/Tables/RowLoading.vue'
import ConfirmationModal from '../../components/Containers/ConfirmationModal.vue'
import Search from '../../components/Forms/Search.vue'
import Pagination from '../../components/Tables/Pagination.vue'
import { required } from '@vuelidate/validators'
import { Data, Warehouse, RequestBody } from '../../types'


export default {
    components: {
        Modal,
        PrimaryButton,
        DangerButton,
        CatalogsLayout,
        PlusIcon,
        Input,
        PencilSquareIcon,
        Icons,
        RowLoading,
        TrashIcon,
        ConfirmationModal,
        Search,
        Pagination,
    },
    data() {
        return {
            warehouses: {
                data: [] as Warehouse[],
            } as Data,
            warehouse: {
                id:null,
                code:"",
                name:"",
                address:"",
                city:"",
                state:"",
                country:"",
            } as Warehouse,
            warehouses_body: {
                size_page: localStorage.getItem('size_page') || 10,
                search: '',
                link: 'http://127.0.0.1:8000/api/warehouses',
            } as RequestBody,
            createWarehouseModal: false,
            editWarehouseModal: false,
            deleteWarehouseModal: false,
            loading: false,
            v$: useVuelidate(),
        }
    },
    validations() {
        return {
            warehouse: {
                code: { required },
                name: { required },
                address: { required },
                city: { required },
                state: { required },
                country: { required },
            },
        }
    },
    mounted() {
        this.getWarehouses()
    },
    methods: {
        openWarehouseModal() {
            this.createWarehouseModal = true
        },
        closeWarehouseModal() {
            this.createWarehouseModal = false
        },
        searchWarehouses(search: string) {
            this.warehouses_body.search = search
            this.warehouses_body.page = 1
            this.getWarehouses()
        },
        changePage(page: number) {
            this.warehouses_body.page = page
            this.getWarehouses()
        },
        getWarehouses() {
            this.loading = true
            axios
                .get(this.warehouses_body.link, {
                    params: {
                        ...this.warehouses_body,
                    },
                })
                .then(({ data }) => {
                    this.warehouses = data.data
                    this.loading = false
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        saveWarehouse() {
            if (this.createWarehouseModal) {
                this.createWarehouse()
            } else {
                this.editWarehouse()
            }
        },
        createWarehouse() {
            this.v$.warehouse.$touch()
            if (this.v$.warehouse.$invalid) return
            axios
                .post('http://127.0.0.1:8000/api/warehouses', this.warehouse)
                .then((response) => {
                    this.warehouses.data.push(response.data.data)
                    this.erase()
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        setWarehouseToEdit(warehouse: Warehouse) {
            this.warehouse = { ...warehouse }
            this.editWarehouseModal = true
        },
        setWarehouseToDelete(warehouse: Warehouse) {
            this.warehouse = { ...warehouse }
            this.deleteWarehouseModal = true
        },
        editWarehouse() {
            this.v$.warehouse.$touch()
            if (this.v$.warehouse.$invalid) return
            axios
                .put(
                    `http://127.0.0.1:8000/api/warehouses/${this.warehouse.id}`,
                    this.warehouse
                )
                .then((response) => {
                    const index = this.warehouses.data.findIndex(
                        (c) => c.id === this.warehouse.id
                    )
                    this.warehouses.data[index] = response.data.data
                    this.erase()
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        deleteWarehouse() {
            axios
                .delete(`http://127.0.0.1:8000/api/warehouses/${this.warehouse.id}`)
                .then(() => {
                    this.warehouses.data = this.warehouses.data.filter(
                        (c) => c.id !== this.warehouse.id
                    )
                    this.erase()
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        erase() {
            this.warehouse = {
                id:null,
                code:"",
                name:"",
                address:"",
                city:"",
                state:"",
                country:""
            };
            this.createWarehouseModal = false
            this.editWarehouseModal = false
            this.deleteWarehouseModal = false
            this.v$.$reset()
        },
    },
}
</script>
../types../../types