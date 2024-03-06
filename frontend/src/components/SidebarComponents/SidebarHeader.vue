<template>
    <h1 
        :class="sidebar ? 'justify-between' : 'justify-center'"
        class="w-full h-[60px] p-4 border-b border-gray-700 text-gray-100 font-medium text-xl truncate flex items-center">
        <span v-if="sidebar">Planner Flow</span>
        <SidebarToggleButton/>
      </h1>
</template>

<script lang="ts">
import { mapState } from 'vuex'
import SidebarToggleButton from './SidebarToggleButton.vue'

export default {
  components: {
    SidebarToggleButton
  },
  computed: {
    ...mapState(['sidebar']),
  },
}
</script>