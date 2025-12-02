<script setup lang="ts">
import { ref } from 'vue'
import { useMonitors } from '@/composables/useMonitors'
import MonitorRow from './MonitorRow.vue'
import AddMonitorModal from '@/components/modals/AddMonitorModal.vue'

const { monitors, isLoading, typeFilter, refreshMonitors } = useMonitors()

const sortField = ref('status')
const sortDirection = ref<'asc' | 'desc'>('asc')
const isAddModalOpen = ref(false)

const toggleSort = (field: string) => {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortField.value = field
    sortDirection.value = 'asc'
  }
}

const handleMonitorCreated = () => {
  refreshMonitors()
}
</script>

<template>
  <div class="bg-white dark:bg-sidebar-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
    <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
      <h2 class="text-lg font-bold text-gray-900 dark:text-white">Monitors</h2>
      
      <div class="flex items-center gap-3">
        <select 
          v-model="typeFilter"
          class="text-sm border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 focus:ring-primary focus:border-primary"
        >
          <option :value="null">Type: All</option>
          <option value="website">Website</option>
          <option value="api">API</option>
          <option value="ping">Ping</option>
        </select>
        
        <button 
          @click="isAddModalOpen = true"
          class="btn btn-primary text-sm px-4 py-2 rounded-lg bg-primary text-white hover:bg-primary/90 transition-colors flex items-center gap-2"
        >
          <span class="material-symbols-outlined text-[18px]">add</span>
          Add Monitor
        </button>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800">
        <thead class="bg-gray-50 dark:bg-gray-800/50">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-700 dark:hover:text-gray-300" @click="toggleSort('name')">
              Status & Name
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
              URL
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-700 dark:hover:text-gray-300" @click="toggleSort('latency')">
              Current Latency
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-700 dark:hover:text-gray-300" @click="toggleSort('uptime')">
              Uptime (7d)
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
              Last Check
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
              Tags
            </th>
            <th scope="col" class="relative px-6 py-3">
              <span class="sr-only">Actions</span>
            </th>
          </tr>
        </thead>
        <tbody class="bg-white dark:bg-sidebar-dark divide-y divide-gray-100 dark:divide-gray-800">
          <tr v-if="isLoading && monitors.length === 0">
            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
              <div class="flex justify-center items-center gap-2">
                <span class="material-symbols-outlined animate-spin">refresh</span>
                Loading monitors...
              </div>
            </td>
          </tr>
          <tr v-else-if="monitors.length === 0">
            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
              No monitors found. Add your first monitor to get started.
            </td>
          </tr>
          <MonitorRow 
            v-for="monitor in monitors" 
            :key="monitor.id" 
            :monitor="monitor"
          />
        </tbody>
      </table>
    </div>
    
    <AddMonitorModal 
      :is-open="isAddModalOpen" 
      @close="isAddModalOpen = false"
      @created="handleMonitorCreated"
    />
  </div>
</template>
