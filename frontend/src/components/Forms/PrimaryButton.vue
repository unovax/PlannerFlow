<template>
  <button class="bg-indigo-800 hover:bg-indigo-500 text-white px-4 py-2 rounded-md transition-all duration-500">
    <slot></slot>
  </button>
</template>

<script lang="ts">
    export default {
        name: "PrimaryButton",
    };
</script>
