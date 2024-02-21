<template>
    <button class="bg-rose-900 hover:bg-rose-700 text-white px-4 py-2 rounded-md transition-all duration-500">
      <slot></slot>
    </button>
  </template>
  
  <script lang="ts">
      export default {
          name: "PrimaryButton",
      };
  </script>
  