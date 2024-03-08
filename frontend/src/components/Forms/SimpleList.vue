<template>
    <section :class="containerClass">
        <Select
            canReturnItem
            :label="label"
            :errors="v$.option?.$errors"
            v-model="option"
            id="option-input"
            next_id="role-symbol-input"
            :catalog="catalog"
            :name="name"
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

interface Column {
    id?: number;
    name: string;
    label: string;
    display_name?: string;
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
                { name: 'display_name', label: 'Nombre' },
            ],
        },
        name: {
            type: String,
            required: true,
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
        },
        noRepeatItems: {
            type: Boolean,
            default: false,
        },
        modelValue: {
            type: [ Array ],
            required: true,
        },
        label: {
            type: String,
            default: 'Elementos',
        },
    },
    data() {
        return {
            items: [],
            option: {} as Column,
            v$: useVuelidate()
        }
    },
    validations() {
        return {
            option: {
                required,
            },
        }
    },
    created(){
        this.items = this.modelValue as never[];
    },
    methods: {
        addItem() {
            this.v$.option.$touch()
            if (this.v$.option.$invalid) return
            console.log(this.option)
            this.items.push({ id: this.option.id, display_name: this.option.display_name, name: this.option.name, label: this.option.label } as never);
            this.$emit('update:modelValue', this.items)
        },
        deleteItem(index: number) {
            this.items.splice(index, 1)
        },
    },
}
</script>
