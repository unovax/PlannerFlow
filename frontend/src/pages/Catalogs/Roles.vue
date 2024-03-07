<template>
    <CatalogsLayout title="Roles">
        <template v-slot:actions>
            <Search
                id="role-search-input"
                placeholder="Buscar Roles"
                @search="searchRoles"
            />
            <PlusIcon
                v-on:click="createRoleModal = true"
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
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="role in roles.data" :key="role.code">
                        <td>
                            <Icons>
                                <PencilSquareIcon
                                    v-on:click="setRoleToEdit(role)"
                                    class="w-6 cursor-pointer hover:scale-125"
                                />
                                <TrashIcon
                                    v-on:click="setRoleToDelete(role)"
                                    class="w-6 cursor-pointer hover:scale-125"
                                />
                            </Icons>
                        </td>
                        <td class="max-w-[100px]">{{ role.code }}</td>
                        <td class="max-w-[100px]">{{ role.name }}</td>
                        <td class="max-w-[100px]">{{ role.description }}</td>
                    </tr>
                    <RowLoading :show="loading" :colspan="10" />
                </tbody>
            </table>
        </div>
        <Pagination
            v-if="roles.data.length"
            v-model="roles_body.size_page"
            :data="roles"
            @search="searchRoles"
            @changePage="changePage"
        />
        <Modal :show="createRoleModal || editRoleModal" maxWidth="800">
            <template v-slot:header>Crear rol</template>
            <template v-slot:content>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4">
                    <Input
                        containerClass="col-span-1 md:col-span-1"
                        label="Código"
                        :errors="v$.role.code?.$errors"
                        v-model="role.code"
                        id="role-code-input"
                        next_id="role-symbol-input"
                    />
                    <Input
                        containerClass="col-span-1 md:col-span-3"
                        label="Nombre"
                        :errors="v$.role.name?.$errors"
                        v-model="role.name"
                        id="role-name-input"
                        next_id="role-symbol-input"
                    />
                    <Input
                        containerClass="col-span-1 md:col-span-4"
                        label="Descripción"
                        :errors="v$.role.description?.$errors"
                        v-model="role.description"
                        id="role-description-input"
                        next_id="permission_id-input"
                    />
                    <SampleList
                        containerClass="col-span-1 md:col-span-4"
                        catalog="permissions"
                        v-model="role.permissions"
                        name="display_name"
                        value="id"
                        :columns="[
                            { name: 'display_name', label: 'Permisos agregados a este rol' },
                        ]"
                    />
                </div>
            </template>
            <template v-slot:footer>
                <PrimaryButton v-on:click="saveRole">Guardar</PrimaryButton>
                <DangerButton v-on:click="erase">Cancelar</DangerButton>
            </template>
        </Modal>
        <ConfirmationModal
            :show="deleteRoleModal"
            text="Estas seguro de querer eliminar este rol?"
            @success="deleteRole"
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
import { Data, RequestBody } from '@/types'
import axiosClient from '@/axiosClient'
import { EmptyRole, Role } from '@/types/users'
import Select from '@forms/Select.vue'
import SampleList from '@forms/SimpleList.vue'
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
        Select,
        SampleList
    },
    data() {
        return {
            roles: {
                data: [] as Role[],
            } as Data,
            role: EmptyRole as Role,
            roles_body: {
                size_page: localStorage.getItem('size_page') || 10,
                search: '',
                link: 'roles',
            } as RequestBody,
            createRoleModal: false,
            editRoleModal: false,
            deleteRoleModal: false,
            loading: false,
            permission_id: 0,
            v$: useVuelidate(),
        }
    },
    validations() {
        return {
            role: {
                code: { required },
                name: { required },
                symbol: { required },
                exchange_rate: { required },
            },
        }
    },
    mounted() {
        this.getRoles()
    },
    methods: {
        openRoleModal() {
            this.createRoleModal = true
        },
        closeRoleModal() {
            this.createRoleModal = false
        },
        searchRoles(search: string) {
            this.roles_body.search = search
            this.roles_body.page = 1
            this.getRoles()
        },
        changePage(page: number) {
            this.roles_body.page = page
            this.getRoles()
        },
        getRoles() {
            this.loading = true
            axiosClient
                .get(this.roles_body.link, {
                    params: {
                        ...this.roles_body,
                    },
                })
                .then(({ data }) => {
                    this.roles = data.data
                    this.loading = false
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        saveRole() {
            if (this.createRoleModal) {
                this.createRole()
            } else {
                this.editRole()
            }
        },
        createRole() {
            this.v$.role.$touch()
            if (this.v$.role.$invalid) return
            axiosClient
                .post('roles', this.role)
                .then((response) => {
                    this.roles.data.push(response.data.data)
                    this.erase()
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        setRoleToEdit(role: Role) {
            this.role = { ...role }
            this.editRoleModal = true
        },
        setRoleToDelete(role: Role) {
            this.role = { ...role }
            this.deleteRoleModal = true
        },
        editRole() {
            this.v$.role.$touch()
            if (this.v$.role.$invalid) return
            axiosClient
                .put(
                    `roles/${this.role.id}`,
                    this.role
                )
                .then((response) => {
                    const index = this.roles.data.findIndex(
                        (c) => c.id === this.role.id
                    )
                    this.roles.data[index] = response.data.data
                    this.erase()
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        deleteRole() {
            axiosClient
                .delete(`roles/${this.role.id}`)
                .then(() => {
                    this.roles.data = this.roles.data.filter(
                        (c) => c.id !== this.role.id
                    )
                    this.erase()
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        erase() {
            this.role = EmptyRole
            this.createRoleModal = false
            this.editRoleModal = false
            this.deleteRoleModal = false
            this.v$.$reset()
        },
    },
}
</script>