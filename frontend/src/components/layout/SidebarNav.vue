<script setup lang="ts">
import { useRoute } from 'vue-router'

const route = useRoute()

const navItems = [
  { name: 'Dashboard', path: '/', icon: 'dashboard' },
  { name: 'Monitors', path: '/monitors', icon: 'dns' },
  { name: 'Health Logs', path: '/health-logs', icon: 'history' },
  { name: 'Network', path: '/network', icon: 'public' },
]
</script>

<template>
  <nav class="space-y-1 px-3 mt-6">
    <RouterLink
      v-for="item in navItems"
      :key="item.path"
      :to="item.path"
      class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200"
      :class="[
        route.path === item.path
          ? 'bg-primary/10 text-primary'
          : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white'
      ]"
    >
      <span 
        class="material-symbols-outlined mr-3 text-[22px] transition-colors"
        :class="route.path === item.path ? 'text-primary fill-current' : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300'"
      >
        {{ item.icon }}
      </span>
      {{ item.name }}
    </RouterLink>
  </nav>
</template>

<style scoped>
.material-symbols-outlined {
  font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}

.router-link-active .material-symbols-outlined {
  font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>
