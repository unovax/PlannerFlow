<template>
    <CatalogsLayout title="Monedas">
        <template v-slot:actions>
            <Search
                id="currency-search-input"
                placeholder="Buscar monedas"
                @search="searchCurrencies"
            />
            <PlusIcon
                v-on:click="createCurrencyModal = true"
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
                        <th>Tipo de Cambio</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="currency in currencies.data" :key="currency.code">
                        <td>
                            <Icons>
                                <PencilSquareIcon
                                    v-on:click="setCurrencyToEdit(currency)"
                                    class="w-6 cursor-pointer hover:scale-125"
                                />
                                <TrashIcon
                                    v-on:click="setCurrencyToDelete(currency)"
                                    class="w-6 cursor-pointer hover:scale-125"
                                />
                            </Icons>
                        </td>
                        <td class="max-w-[100px]">{{ currency.code }}</td>
                        <td class="max-w-[100px]">{{ currency.name }}</td>
                        <td class="max-w-[100px]">{{ currency.exchange_rate }}</td>
                    </tr>
                    <RowLoading :show="loading" :colspan="10" />
                </tbody>
            </table>
        </div>
        <Pagination
            v-if="currencies.data.length"
            v-model="currencies_body.size_page"
            :data="currencies"
            @search="searchCurrencies"
            @changePage="changePage"
        />
        <Modal :show="createCurrencyModal || editCurrencyModal" maxWidth="800">
            <template v-slot:header>Crear currencye</template>
            <template v-slot:content>
                <div class="grid grid-cols-2 gap-4 p-4">
                    <Input
                        label="Código"
                        :errors="v$.currency.code?.$errors"
                        v-model="currency.code"
                        id="currency-code-input"
                        next_id="currency-name-input"
                    />
                    <Input
                        label="Nombre"
                        :errors="v$.currency.name?.$errors"
                        v-model="currency.name"
                        id="currency-name-input"
                        next_id="currency-symbol-input"
                    />
                    <Input
                        label="Simbolo"
                        :errors="v$.currency.symbol?.$errors"
                        v-model="currency.symbol"
                        id="currency-symbol-input"
                        next_id="currency-exchange_rate-input"
                    />
                    <Input
                        label="Tipo de cambio"
                        :errors="v$.currency.exchange_rate?.$errors"
                        v-model="currency.exchange_rate"
                        id="currency-exchange_rate-input"
                    />
                </div>
            </template>
            <template v-slot:footer>
                <PrimaryButton v-on:click="saveCurrency">Guardar</PrimaryButton>
                <DangerButton v-on:click="erase">Cancelar</DangerButton>
            </template>
        </Modal>
        <ConfirmationModal
            :show="deleteCurrencyModal"
            text="Estas seguro de querer eliminar este currencye?"
            @success="deleteCurrency"
            @cancel="erase"
        />
    </CatalogsLayout>
</template>
<script lang="ts">
import Modal from '@components/Containers/Modal.vue'
import PrimaryButton from '@forms/PrimaryButton.vue'
import DangerButton from '@forms/DangerButton.vue'
import CatalogsLayout from '@layouts/App.vue'
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
import { required } from '@vuelidate/validators'
import { Data, Currency, RequestBody } from '@/types'
import axiosClient from '@/axiosClient'


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
            currencies: {
                data: [] as Currency[],
            } as Data,
            currency: {
                id:null,
                code: "",
                name: "",
                symbol: "",
                exchange_rate: ""
            } as Currency,
            currencies_body: {
                size_page: localStorage.getItem('size_page') || 10,
                search: '',
                link: 'currencies',
            } as RequestBody,
            createCurrencyModal: false,
            editCurrencyModal: false,
            deleteCurrencyModal: false,
            loading: false,
            v$: useVuelidate(),
        }
    },
    validations() {
        return {
            currency: {
                code: { required },
                name: { required },
                symbol: { required },
                exchange_rate: { required },
            },
        }
    },
    mounted() {
        this.getCurrencies()
    },
    methods: {
        openCurrencyModal() {
            this.createCurrencyModal = true
        },
        closeCurrencyModal() {
            this.createCurrencyModal = false
        },
        searchCurrencies(search: string) {
            this.currencies_body.search = search
            this.currencies_body.page = 1
            this.getCurrencies()
        },
        changePage(page: number) {
            this.currencies_body.page = page
            this.getCurrencies()
        },
        getCurrencies() {
            this.loading = true
            axiosClient
                .get(this.currencies_body.link, {
                    params: {
                        ...this.currencies_body,
                    },
                })
                .then(({ data }) => {
                    this.currencies = data.data
                    this.loading = false
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        saveCurrency() {
            if (this.createCurrencyModal) {
                this.createCurrency()
            } else {
                this.editCurrency()
            }
        },
        createCurrency() {
            this.v$.currency.$touch()
            if (this.v$.currency.$invalid) return
            axiosClient
                .post('currencies', this.currency)
                .then((response) => {
                    this.currencies.data.push(response.data.data)
                    this.erase()
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        setCurrencyToEdit(currency: Currency) {
            this.currency = { ...currency }
            this.editCurrencyModal = true
        },
        setCurrencyToDelete(currency: Currency) {
            this.currency = { ...currency }
            this.deleteCurrencyModal = true
        },
        editCurrency() {
            this.v$.currency.$touch()
            if (this.v$.currency.$invalid) return
            axiosClient
                .put(
                    `currencies/${this.currency.id}`,
                    this.currency
                )
                .then((response) => {
                    const index = this.currencies.data.findIndex(
                        (c) => c.id === this.currency.id
                    )
                    this.currencies.data[index] = response.data.data
                    this.erase()
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        deleteCurrency() {
            axiosClient
                .delete(`currencies/${this.currency.id}`)
                .then(() => {
                    this.currencies.data = this.currencies.data.filter(
                        (c) => c.id !== this.currency.id
                    )
                    this.erase()
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        erase() {
            this.currency = {
                id:null,
                code: "",
                name: "",
                symbol: "",
                exchange_rate: ""
            };
            this.createCurrencyModal = false
            this.editCurrencyModal = false
            this.deleteCurrencyModal = false
            this.v$.$reset()
        },
    },
}
</script>
../types@/types