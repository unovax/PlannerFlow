<template>
    <div v-if="show" class="fixed inset-0 flex items-start h-screen justify-center bg-black bg-opacity-50 p-4 overflow-auto">
      <div class="bg-gray-900 rounded-lg w-full" v-bind:style="{'max-width' : maxWidth + 'px'}">

        <header class="bg-indigo-800 text-white text-lg px-4 py-2 rounded-t-md">
          <slot name="header"></slot>
        </header>
        <section>
          <slot name="content"></slot>
        </section>
        <footer class="flex gap-2 flex-col md:flex-row justify-end px-4 py-2">
          <slot name="footer"></slot>
        </footer>
      </div>
    </div>
  </template>
  
<script lang="ts">
    export default {
        props: {
            show: Boolean,
            maxWidth: {
                type: String,
                default: "500",
            },
        },
        methods: {
            closeModal() {
                this.$emit("update:show", false);
            },
        },
    };
</script>