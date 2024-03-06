<template>
    <CatalogsLayout title="Pedidos">
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
    </CatalogsLayout>
</template>
<script lang="ts" setup>
    import { ref } from "vue";
    import Modal from "../components/Containers/Modal.vue";
    import PrimaryButton from "../components/Forms/PrimaryButton.vue";
    import DangerButton from "../components/Forms/DangerButton.vue";
    import CatalogsLayout from "../layouts/App.vue";
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