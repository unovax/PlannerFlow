<template>
    <AppLayout title="Pedidos">
        <template v-slot:actions>
            <PlusIcon v-on:click="openOrderModal" class="w-8 cursor-pointer hover:scale-125 bg-gray-800 rounded-md p-1"/>
        </template>
        <h1 class="text-green-500">Orders</h1>
        <h1 class="text-white">{{ order }}</h1>
        <input type="text" v-model="order.name">
        <Modal :show="orderModal">
            <template v-slot:header>Crear pedido</template>
            <template v-slot:content>
                
            </template>
            <template v-slot:footer>
                <PrimaryButton>Guardar</PrimaryButton>
                <DangerButton v-on:click="closeOrderModal">Cancelar</DangerButton>
            </template>
        </Modal>
    </AppLayout>
</template>
<script lang="ts" setup>
    import { ref } from "vue";
    import Modal from "@components/Containers/Modal.vue";
    import PrimaryButton from "@forms/PrimaryButton.vue";
    import DangerButton from "@forms/DangerButton.vue";
    import AppLayout from "@layouts/App.vue";
    import { PlusIcon  } from "@heroicons/vue/24/outline";

    const order = ref({
        id: 1,
        name: "Order 1",
        price: 100,
    });

    const orderModal = ref(false);

    const openOrderModal = () => {
        orderModal.value = true;
    };

    const closeOrderModal = () => {
        orderModal.value = false;
    };

</script>