<template>
    <AppLayout title="Categorias">
        <template v-slot:actions>
            <Search
                id="category-search-input"
                placeholder="Buscar categorias"
                @search="searchCategories"
            />
            <PlusIcon
                v-on:click="createCategoryModal = true"
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
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="category in categories.data" :key="category.code">
                        <td>
                            <Icons>
                                <PencilSquareIcon
                                    v-on:click="setCategoryToEdit(category)"
                                    class="w-6 cursor-pointer hover:scale-125"
                                />
                                <TrashIcon
                                    v-on:click="setCategoryToDelete(category)"
                                    class="w-6 cursor-pointer hover:scale-125"
                                />
                            </Icons>
                        </td>
                        <td class="max-w-[100px]">{{ category.code }}</td>
                        <td class="max-w-[100px]">{{ category.name }}</td>
                    </tr>
                    <RowLoading :show="loading" :colspan="10" />
                </tbody>
            </table>
        </div>
        <Pagination
            v-if="categories.data.length"
            v-model="categories_body.size_page"
            :data="categories"
            @search="searchCategories"
            @changePage="changePage"
        />
        <Modal :show="createCategoryModal || editCategoryModal" maxWidth="800">
            <template v-slot:header>Crear categoria</template>
            <template v-slot:content>
                <div class="grid grid-cols-4 gap-4 p-4">
                    <Input
                        label="Código"
                        :errors="v$.category.code?.$errors"
                        v-model="category.code"
                        id="category-code-input"
                        next_id="category-name-input"
                    />
                    <Input
                        containerClass="col-span-3"
                        label="Nombre"
                        :errors="v$.category.name?.$errors"
                        v-model="category.name"
                        id="category-name-input"
                    />
                </div>
            </template>
            <template v-slot:footer>
                <PrimaryButton v-on:click="saveCategory">Guardar</PrimaryButton>
                <DangerButton v-on:click="erase">Cancelar</DangerButton>
            </template>
        </Modal>
        <ConfirmationModal
            :show="deleteCategoryModal"
            text="Estas seguro de querer eliminar este categoria?"
            @success="deleteCategory"
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
import { Category, EmptyCategory } from '@/types/categories'
import axiosClient from '@/axiosClient'


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
    },
    data() {
        return {
            categories: {
                data: [] as Category[],
            } as Data,
            category: JSON.parse(JSON.stringify(EmptyCategory)) as Category,
            categories_body: {
                size_page: localStorage.getItem('size_page') || 10,
                search: '',
                link: 'categories',
            } as RequestBody,
            createCategoryModal: false,
            editCategoryModal: false,
            deleteCategoryModal: false,
            loading: false,
            v$: useVuelidate(),
        }
    },
    validations() {
        return {
            category: {
                code: { required },
                name: { required },
            },
        }
    },
    mounted() {
        this.getCategories()
    },
    methods: {
        openCategoryModal() {
            this.createCategoryModal = true
        },
        closeCategoryModal() {
            this.createCategoryModal = false
        },
        searchCategories(search: string) {
            this.categories_body.search = search
            this.categories_body.page = 1
            this.getCategories()
        },
        changePage(page: number) {
            this.categories_body.page = page
            this.getCategories()
        },
        getCategories() {
            this.loading = true
            axiosClient
                .get(this.categories_body.link, {
                    params: {
                        ...this.categories_body,
                    },
                })
                .then(({ data }) => {
                    this.categories = data.data
                    this.loading = false
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        saveCategory() {
            if (this.createCategoryModal) {
                this.createCategory()
            } else {
                this.editCategory()
            }
        },
        createCategory() {
            this.v$.category.$touch()
            if (this.v$.category.$invalid) return
            axiosClient
                .post('categories', this.category)
                .then((response) => {
                    this.categories.data.push(response.data.data)
                    this.erase()
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        setCategoryToEdit(category: Category) {
            this.category = { ...category }
            this.editCategoryModal = true
        },
        setCategoryToDelete(category: Category) {
            this.category = { ...category }
            this.deleteCategoryModal = true
        },
        editCategory() {
            this.v$.category.$touch()
            if (this.v$.category.$invalid) return
            axiosClient
                .put(
                    `categories/${this.category.id}`,
                    this.category
                )
                .then((response) => {
                    const index = this.categories.data.findIndex(
                        (c) => c.id === this.category.id
                    )
                    this.categories.data[index] = response.data.data
                    this.erase()
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        deleteCategory() {
            axiosClient
                .delete(`categories/${this.category.id}`)
                .then(() => {
                    this.categories.data = this.categories.data.filter(
                        (c) => c.id !== this.category.id
                    )
                    this.erase()
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        erase() {
            console.log('erase')
            this.category = JSON.parse(JSON.stringify(EmptyCategory));
            this.createCategoryModal = false
            this.editCategoryModal = false
            this.deleteCategoryModal = false
            this.v$.$reset()
        },
    },
}
</script>
../types@/types