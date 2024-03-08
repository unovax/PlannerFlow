<template>
    <AppLayout title="Usuarios">
        <template v-slot:actions>
            <Search
                id="user-search-input"
                placeholder="Buscar Usuarios"
                @search="searchUsers"
            />
            <PlusIcon
                v-on:click="createUserModal = true"
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
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users.data" :key="user.code">
                        <td>
                            <Icons>
                                <PencilSquareIcon
                                    v-on:click="setUserToEdit(user)"
                                    class="w-6 cursor-pointer hover:scale-125"
                                />
                                <TrashIcon
                                    v-on:click="setUserToDelete(user)"
                                    class="w-6 cursor-pointer hover:scale-125"
                                />
                            </Icons>
                        </td>
                        <td class="max-w-[100px]">{{ user.code }}</td>
                        <td class="max-w-[100px]">{{ user.name }}</td>
                        <td class="max-w-[100px]">{{ user.description }}</td>
                    </tr>
                    <RowLoading :show="loading" :colspan="10" />
                </tbody>
            </table>
        </div>
        <Pagination
            v-if="users.data.length"
            v-model="users_body.size_page"
            :data="users"
            @search="searchUsers"
            @changePage="changePage"
        />
        <Modal :show="createUserModal || editUserModal" maxWidth="800">
            <template v-slot:header>Crear usuario</template>
            <template v-slot:content>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4">
                    <Input
                        containerClass="col-span-1 md:col-span-4"
                        label="Nombre"
                        :errors="v$.user.name?.$errors"
                        v-model="user.name"
                        id="user-name-input"
                        next_id="user-email-input"
                    />
                    <Input
                        containerClass="col-span-1 md:col-span-4"
                        label="Correo electronico"
                        :errors="v$.user.email?.$errors"
                        v-model="user.email"
                        id="user-email-input"
                        next_id="user-password-input"
                    />
                    <Input
                        containerClass="col-span-1 md:col-span-4"
                        label="Contraseña"
                        type="password"
                        :errors="v$.user.password?.$errors"
                        v-model="user.password"
                        id="user-password-input"
                    />
                    <SampleList
                        containerClass="col-span-1 md:col-span-4"
                        catalog="roles"
                        v-model="user.roles"
                        name="name"
                        value="id"
                        label="Roles"
                        :columns="[
                            { name: 'name', label: 'Roles agregados a este usuario' },
                        ]"
                    />
                </div>
            </template>
            <template v-slot:footer>
                <PrimaryButton v-on:click="saveUser">Guardar</PrimaryButton>
                <DangerButton v-on:click="erase">Cancelar</DangerButton>
            </template>
        </Modal>
        <ConfirmationModal
            :show="deleteUserModal"
            text="Estas seguro de querer eliminar este usuario?"
            @success="deleteUser"
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
import axiosClient from '@/axiosClient'
import { EmptyUser, User } from '@/types/users'
import Select from '@forms/Select.vue'
import SampleList from '@forms/SimpleList.vue'
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
        SampleList
    },
    data() {
        return {
            users: {
                data: [] as User[],
            } as Data,
            user: EmptyUser as User,
            users_body: {
                size_page: localStorage.getItem('size_page') || 10,
                search: '',
                link: 'users',
            } as RequestBody,
            createUserModal: false,
            editUserModal: false,
            deleteUserModal: false,
            loading: false,
            v$: useVuelidate(),
        }
    },
    validations() {
        return {
            user: {
                name: { required },
                email: { required },
                password: { required },
                roles: { required },
            },
        }
    },
    mounted() {
        this.getUsers()
    },
    methods: {
        openUserModal() {
            this.createUserModal = true
        },
        closeUserModal() {
            this.createUserModal = false
        },
        searchUsers(search: string) {
            this.users_body.search = search
            this.users_body.page = 1
            this.getUsers()
        },
        changePage(page: number) {
            this.users_body.page = page
            this.getUsers()
        },
        getUsers() {
            this.loading = true
            axiosClient
                .get(this.users_body.link, {
                    params: {
                        ...this.users_body,
                    },
                })
                .then(({ data }) => {
                    this.users = data.data
                    this.loading = false
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        saveUser() {
            if (this.createUserModal) {
                this.createUser()
            } else {
                this.editUser()
            }
        },
        createUser() {
            this.v$.user.$touch()
            if (this.v$.user.$invalid) return
            axiosClient
                .post('users', this.user)
                .then((response) => {
                    this.users.data.push(response.data.data)
                    this.erase()
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        setUserToEdit(user: User) {
            this.user = { ...user }
            this.editUserModal = true
        },
        setUserToDelete(user: User) {
            this.user = { ...user }
            this.deleteUserModal = true
        },
        editUser() {
            this.v$.user.$touch()
            if (this.v$.user.$invalid) return
            axiosClient
                .put(
                    `users/${this.user.id}`,
                    this.user
                )
                .then((response) => {
                    const index = this.users.data.findIndex(
                        (c) => c.id === this.user.id
                    )
                    this.users.data[index] = response.data.data
                    this.erase()
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        deleteUser() {
            axiosClient
                .delete(`users/${this.user.id}`)
                .then(() => {
                    this.users.data = this.users.data.filter(
                        (c) => c.id !== this.user.id
                    )
                    this.erase()
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        erase() {
            this.user = EmptyUser
            this.createUserModal = false
            this.editUserModal = false
            this.deleteUserModal = false
            this.v$.$reset()
        },
    },
}
</script>