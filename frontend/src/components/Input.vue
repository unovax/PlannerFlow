<template>
  <div class="space-y-1" :class="containerClass">
    <label :for="id" :class="labelClass" class="text-gray-300">{{
      label
    }}</label>
    <input
      :id="id"
      v-model="model"
      :class="
        inputClass + ' border rounded-md p-2 w-full bg-gray-800 text-gray-300'
      "
      :placeholder="label"
      @input="$emit('input', $event)"
      v-on:keyup.enter="nextInput"
    />
    <div v-for="error in errors" :key="error">
      <span class="text-red-300">{{ error.$message }}</span>
    </div>
  </div>
</template>

<script lang="ts">
export default {
  props: {
    label: {
      type: String,
      required: true,
    },
    modelValue: {
      type: [String, Number],
      required: true,
    },
    containerClass: {
      type: String,
      default: "",
    },
    inputClass: {
      type: String,
      default: "",
    },
    labelClass: {
      type: String,
      default: "",
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
      type: Array,
      default: () => [],
    },
  },
  methods: {
    nextInput() {
      if (this.next_id) {
        const nextInput = document.getElementById(this.next_id);
        if (nextInput) {
          nextInput.focus();
        }
      }
    },
  },
  computed: {
    model: {
      get() {
        return this.modelValue;
      },
      set(value: string | number) {
        this.$emit("update:modelValue", value);
      },
    },
  },
};
</script>
