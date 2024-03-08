<template>
    <AppLayout title="Productos">
        <template v-slot:actions>
            <Search
                id="product-search-input"
                placeholder="Buscar productos"
                @search="searchProducts"
            />
            <PlusIcon
                v-on:click="createProductModal = true"
                class="w-8 cursor-pointer hover:scale-125 bg-gray-800 rounded-md p-1"
            />
        </template>
        <div class="w-full overflow-auto flex-1 p-2">
            <table>
                <thead>
                    <tr>
                        <th class="w-1/12">Acciones</th>
                        <th class="w-1/12">Código</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Costo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in products.data" :key="product.code">
                        <td>
                            <Icons>
                                <PencilSquareIcon
                                    v-on:click="setProductToEdit(product)"
                                    class="w-6 cursor-pointer hover:scale-125"
                                />
                                <TrashIcon
                                    v-on:click="setProductToDelete(product)"
                                    class="w-6 cursor-pointer hover:scale-125"
                                />
                            </Icons>
                        </td>
                        <td class="max-w-[100px]">{{ product.code }}</td>
                        <td class="max-w-[100px]">{{ product.name }}</td>
                        <td class="max-w-[100px]">{{ product.description }}</td>
                        <td class="max-w-[100px]">{{ formatCurrency(product.price) }}</td>
                        <td class="max-w-[100px]">{{ formatCurrency(product.cost) }}</td>
                    </tr>
                    <RowLoading :show="loading" :colspan="10" />
                </tbody>
            </table>
        </div>
        <Pagination
            v-if="products.data.length"
            v-model="products_body.size_page"
            :data="products"
            @search="searchProducts"
            @changePage="changePage"
        />
        <Modal :show="createProductModal || editProductModal" maxWidth="800">
            <template v-slot:header>Crear producte</template>
            <template v-slot:content>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4">
                    <Input
                        label="Código"
                        :errors="v$.product.code?.$errors"
                        v-model="product.code"
                        id="product-code-input"
                        next_id="product-name-input"
                    />
                    <Input
                        containerClass="col-span-3"
                        label="Nombre"
                        :errors="v$.product.name?.$errors"
                        v-model="product.name"
                        id="product-name-input"
                        next_id="product-price-input"
                    />
                    <Input
                        label="Precio"
                        :errors="v$.product.price?.$errors"
                        v-model="product.price"
                        id="product-price-input"
                        next_id="product-cost-input"
                    />
                    <Input
                        label="Costo"
                        :errors="v$.product.cost?.$errors"
                        v-model="product.cost"
                        id="product-cost-input"
                        next_id="product-category_id-input"
                    />
                    <Select
                        containerClass="col-span-2"
                        label="Categoria"
                        :errors="v$.product.category_id?.$errors"
                        v-model="product.category_id"
                        id="product-category_id-input"
                        next_id="product-description-input"

                        catalog="categories"
                    />
                    <Input
                        containerClass="col-span-4"
                        label="Decripción"
                        :errors="v$.product.description?.$errors"
                        v-model="product.description"
                        id="product-description-input"
                    />
                </div>
            </template>
            <template v-slot:footer>
                <PrimaryButton v-on:click="saveProduct">Guardar</PrimaryButton>
                <DangerButton v-on:click="erase">Cancelar</DangerButton>
            </template>
        </Modal>
        <ConfirmationModal
            :show="deleteProductModal"
            text="Estas seguro de querer eliminar este producte?"
            @success="deleteProduct"
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
import { required } from '@vuelidate/validators'
import { Data, RequestBody } from '@/types'
import { EmptyProduct, Product } from '@/types/products'
import axiosClient from '@/axiosClient'
import Select from '@/components/Forms/Select.vue'
import { currencyFormatter } from '@/utils/formats'

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
        Select
    },
    data() {
        return {
            products: {
                data: [] as Product[],
            } as Data,
            product: JSON.parse(JSON.stringify(EmptyProduct)) as Product,
            products_body: {
                size_page: localStorage.getItem('size_page') || 10,
                search: '',
                link: 'products',
            } as RequestBody,
            createProductModal: false,
            editProductModal: false,
            deleteProductModal: false,
            loading: false,
            v$: useVuelidate(),
        }
    },
    validations() {
        return {
            product: {
                code: { required },
                name: { required },
                price: { required },
                cost: { required },
                category_id: { required },
                description: { required },
            },
        }
    },
    mounted() {
        this.getProducts()
    },
    methods: {
        formatCurrency(value: number) {
            return currencyFormatter.format(value)
        },
        openProductModal() {
            this.createProductModal = true
        },
        closeProductModal() {
            this.createProductModal = false
        },
        searchProducts(search: string) {
            this.products_body.search = search
            this.products_body.page = 1
            this.getProducts()
        },
        changePage(page: number) {
            this.products_body.page = page
            this.getProducts()
        },
        getProducts() {
            this.loading = true
            axiosClient
                .get(this.products_body.link, {
                    params: {
                        ...this.products_body,
                    },
                })
                .then(({ data }) => {
                    this.products = data.data
                    this.loading = false
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        saveProduct() {
            if (this.createProductModal) {
                this.createProduct()
            } else {
                this.editProduct()
            }
        },
        createProduct() {
            this.v$.product.$touch()
            if (this.v$.product.$invalid) return
            axiosClient
                .post('products', this.product)
                .then((response) => {
                    this.products.data.push(response.data.data)
                    this.erase()
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        setProductToEdit(product: Product) {
            this.product = { ...product }
            this.editProductModal = true
        },
        setProductToDelete(product: Product) {
            this.product = { ...product }
            this.deleteProductModal = true
        },
        editProduct() {
            this.v$.product.$touch()
            if (this.v$.product.$invalid) return
            axiosClient
                .put(
                    `products/${this.product.id}`,
                    this.product
                )
                .then((response) => {
                    const index = this.products.data.findIndex(
                        (c) => c.id === this.product.id
                    )
                    this.products.data[index] = response.data.data
                    this.erase()
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        deleteProduct() {
            axiosClient
                .delete(`products/${this.product.id}`)
                .then(() => {
                    this.products.data = this.products.data.filter(
                        (c) => c.id !== this.product.id
                    )
                    this.erase()
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        erase() {
            this.product = JSON.parse(JSON.stringify(EmptyProduct))
            this.createProductModal = false
            this.editProductModal = false
            this.deleteProductModal = false
            this.v$.$reset()
        },
    },
}
</script>
../types@/types