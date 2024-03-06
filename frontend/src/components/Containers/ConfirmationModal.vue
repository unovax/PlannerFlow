<template>
    <div
        v-if="show"
        class="fixed inset-0 flex items-start h-screen justify-center bg-black bg-opacity-50 p-4 overflow-auto"
    >
        <div
            class="bg-gray-900 rounded-lg w-full"
            v-bind:style="{ 'max-width': maxWidth + 'px' }"
        >
            <section class="text-gray-300 text-lg flex justify-center items-center gap-2 p-4">
                <ExclamationCircleIcon class="w-14"/>
                {{ text }}
            </section>
            <footer
                class="flex gap-2 flex-col md:flex-row justify-end px-4 py-2"
            >
                <PrimaryButton class="min-w-[100px]" v-on:click="$emit('success')">{{ primaryText }}</PrimaryButton>
                <DangerButton class="min-w-[100px]" v-on:click="$emit('cancel')">{{ secondaryText }}</DangerButton>
            </footer>
        </div>
    </div>
</template>

<script lang="ts">
import PrimaryButton from '@forms/PrimaryButton.vue'
import DangerButton from '@forms/DangerButton.vue'
import { ExclamationCircleIcon } from '@heroicons/vue/24/outline'
export default {
    components: {
        PrimaryButton,
        DangerButton,
        ExclamationCircleIcon
    },
    props: {
        show: Boolean,
        maxWidth: {
            type: String,
            default: '500',
        },
        text: {
            type: String,
            default: 'Are you sure?',
        },
        primaryText: {
            type: String,
            default: 'Si',
        },
        secondaryText: {
            type: String,
            default: 'No',
        },
    },
    methods: {
        closeModal() {
            this.$emit('update:show', false)
        },
    },
}
</script>
