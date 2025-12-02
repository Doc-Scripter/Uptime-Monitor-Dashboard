<script setup lang="ts">
import { useMonitors } from '@/composables/useMonitors'

const { statusFilter } = useMonitors()

const filters = [
  { id: 'all', label: 'All', color: 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' },
  { id: 'up', label: 'Up', color: 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' },
  { id: 'warning', label: 'Warning', color: 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' },
  { id: 'down', label: 'Down', color: 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' },
]

const setFilter = (id: string) => {
  statusFilter.value = id === 'all' ? null : id
}
</script>

<template>
  <div class="px-6 mt-8">
    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
      Quick Filters
    </h3>
    <div class="mt-3 flex flex-wrap gap-2 px-3">
      <button
        v-for="filter in filters"
        :key="filter.id"
        @click="setFilter(filter.id)"
        class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium transition-colors cursor-pointer border border-transparent"
        :class="[
          filter.color,
          statusFilter === (filter.id === 'all' ? null : filter.id) 
            ? 'ring-2 ring-offset-1 ring-primary dark:ring-offset-sidebar-dark' 
            : 'hover:opacity-80'
        ]"
      >
        {{ filter.label }}
      </button>
    </div>
  </div>
</template>
