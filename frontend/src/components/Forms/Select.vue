<template>
    <div class="space-y-1" :class="containerClass">
        <label :for="id" :class="labelClass" class="text-gray-300">{{
            label
        }}</label>
        <div class="flex gap-2 items-center">
            <select
                :id="id"
                v-model="model"
                :class="
                    inputClass +
                    ' border rounded-md p-2 w-full bg-gray-800 text-gray-300'
                "
                :placeholder="label"
                @input="$emit('input', $event)"
                v-on:keyup.enter="nextInput"
            >
                <option v-if="typeof modelValue == 'number'" value="0">{{ defaultOption }}</option>
                <option v-if="typeof modelValue == 'object'" :value="null">{{ defaultOption }}</option>
                <option
                    v-for="item in items"
                    :key="item[value]"
                    :value="canReturnItem ? item : item[value]"
                >
                    {{ item[name] }}
                </option>
            </select>
            <slot name="actions"></slot>
        </div>
        <div v-for="(error, index) in errors" :key="index">
            <span class="text-red-300">{{ error.$message }}</span>
        </div>
    </div>
</template>

<script lang="ts">
import axiosClient from '@/axiosClient'

interface Error {
    $message: string
}

interface Option {
    id: number
    name: string
    [key: string]: number | string
}

export default {
    props: {
        label: {
            type: String,
            required: true,
        },
        modelValue: {
            type: [ String, Number, Object ],
            required: true,
        },
        containerClass: {
            type: String,
            default: '',
        },
        inputClass: {
            type: String,
            default: '',
        },
        labelClass: {
            type: String,
            default: '',
        },
        id: {
            type: String,
            required: true,
        },
        next_id: {
            type: String,
            required: false,
        },
        errors: {
            type: Array as () => Error[],
            default: () => [],
        },
        defaultOption: {
            type: String,
            default: 'Selecciona una opción',
        },
        options: {
            type: Array as () => Option[],
            required: false,
        },
        value: {
            type: String,
            default: 'id',
        },
        name: {
            type: String,
            default: 'name',
        },
        catalog: {
            type: String,
            required: false,
        },
        canReturnItem: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            items: Array as () => Option[],
        }
    },
    created() {
        if (!this.options) {
            axiosClient
                .get('catalogs/' + this.catalog ?? '', {
                    params: {
                        value: this.value,
                        name: this.name,
                    },
                })
                .then((response) => {
                    this.items = response.data
                })
        }
    },
    methods: {
        nextInput() {
            if (this.next_id) {
                const nextInput = document.getElementById(this.next_id)
                if (nextInput) {
                    nextInput.focus()
                }
            }
        },
    },
    computed: {
        model: {
            get() {
                return this.modelValue
            },
            set(value: string | number) {
                this.$emit('update:modelValue', value)
            },
        },
    },
}
</script>
