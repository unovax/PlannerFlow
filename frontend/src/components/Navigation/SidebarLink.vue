<template>
    <li class="w-full">
        <router-link
            :class="currentRoute == link.name ? 'bg-gray-700' : 'bg-gray-800'"
            class="w-full px-4 py-1.5 hover:bg-gray-700 text-gray-300 rounded-md flex gap-2 items-center"
            :to="link.href"
        >
            <component :is="link.icon" class="w-6 h-6 min-w-6 min-h-6" />
            <span v-if="sidebar">{{ link.name }}</span>
        </router-link>
    </li>
</template>
<script lang="ts">
import { mapState } from 'vuex'
import { Link } from '@/types'
export default {
    props: {
        link: {
            type: Object as () => Link,
            required: true,
        },
    },
    computed: {
        ...mapState(['sidebar']),
        currentRoute() {
            return this.$route.name
        },
    },
}
</script>
