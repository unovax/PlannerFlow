<template>
    <section :class="containerClass">
        <Select
            label="Permisos"
            :errors="v$.option_id?.$errors"
            v-model="option_id"
            id="option_id-input"
            next_id="role-symbol-input"
            :catalog="catalog"
            name="display_name"
        >
            <template v-slot:actions>
                <PrimaryButton v-on:click="addItem">
                    <PlusIcon class="w-4 h-4" />
                </PrimaryButton>
            </template>
        </Select>
        <table class="mt-2">
            <thead v-if="withHeaders">
                <tr>
                    <th v-for="column in columns" :key="column.name" class="text-gray-300">{{ column.label }}</th>
                    <th class="w-1/12"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in items" :key="item[value]">
                    <td v-for="column in columns" :key="column.name" class="text-gray-300">{{ item[column.name] }}</td>
                    <td>
                        <TrashIcon class="w-6 h-6 cursor-pointer hover:scale-110" v-on:click="deleteItem(index)" />
                    </td>
                </tr>
                <tr v-if="!items.length">
                    <td :colspan="columns?.length ?? 1" class="text-center text-gray-300">No hay elementos</td>
                </tr>
            </tbody>
        </table>
    </section>
</template>
<script lang="ts">
import { PlusIcon, TrashIcon } from '@heroicons/vue/24/outline'
import { useVuelidate } from '@vuelidate/core'
import { required } from '@vuelidate/validators'
import Select from '@forms/Select.vue'
import PrimaryButton from './PrimaryButton.vue'
import axiosClient from '@/axiosClient'

interface Column {
    name: string;
    label: string;
}

export default {
    components: {
        TrashIcon,
        Select,
        PrimaryButton,
        PlusIcon
    },
    props: {
        columns: {
            type: Array as () => Column[],
            defaults: () => [
                { name: 'name', label: 'Nombre' },
            ],
        },
        value: {
            type: String,
            required: true,
        },
        catalog: {
            type: String,
            required: true,
        },
        containerClass: {
            type: String,
            default: '',
        },
        withHeaders: {
            type: Boolean,
            default: true,
        }
    },
    data() {
        return {
            items: [],
            option_id: 0,
            v$: useVuelidate()
        }
    },
    validations() {
        return {
            option_id: {
                required,
            },
        }
    },
    methods: {
        addItem() {
            console.log("sadas")
            this.v$.option_id.$touch()
            if (this.v$.option_id.$invalid) return
            axiosClient.get(`catalogs/${this.catalog}/${this.option_id}`).then((response) => {
                this.items.push(response.data as never);
            })
        },
        deleteItem(index: number) {
            this.items.splice(index, 1)
        },
    },
}
</script>
