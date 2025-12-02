<script setup lang="ts">
import { ref, computed } from 'vue'
import { useMonitorStore } from '@/stores/monitorStore'
import { storeToRefs } from 'pinia'
import type { Monitor } from '@/types'
import UptimeTimeline from '@/components/charts/UptimeTimeline.vue'
import LatencyChart from '@/components/charts/LatencyChart.vue'

const store = useMonitorStore()
const { monitors } = storeToRefs(store)

// Mock data for now - in real app would come from store/API
const mockChartData = Array.from({ length: 24 }, (_, i) => ({
  timestamp: `${i}:00`,
  status: Math.random() > 0.1 ? 'up' : 'down',
  response_time: Math.floor(Math.random() * 500) + 50
}))

const activeTab = ref('timeline')
const tabs = [
  { id: 'timeline', label: 'Timeline' },
  { id: 'latency', label: 'Latency' },
  { id: 'logs', label: 'Health Logs' },
  { id: 'network', label: 'Network' },
]

// Get the first monitor or create a mock one for demo purposes
const selectedMonitor = computed<Monitor>(() => {
  const firstMonitor = monitors.value[0]
  if (firstMonitor) {
    return firstMonitor
  }
  
  // Always return a valid Monitor object, never undefined
  return {
    id: 0,
    name: 'Demo Monitor',
    url: 'https://example.com',
    type: 'website' as const,
    interval: 300,
    is_active: true,
    status: 'up' as const,
    uptime_percentage: 99.9,
    current_latency: 120,
    tags: [],
    last_checked_at: new Date().toISOString(),
    created_at: new Date().toISOString(),
    updated_at: new Date().toISOString(),
    status_label: 'Up',
    formatted_latency: '120ms',
    last_checked_relative: 'just now'
  }
})
</script>

<template>
  <div class="bg-white dark:bg-sidebar-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 h-full flex flex-col">
    <div class="p-6 border-b border-gray-100 dark:border-gray-800">
      <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Monitor Details</h2>
      
      <div class="flex space-x-6 border-b border-gray-100 dark:border-gray-800">
        <button
          v-for="tab in tabs"
          :key="tab.id"
          @click="activeTab = tab.id"
          class="pb-3 text-sm font-medium transition-colors relative"
          :class="activeTab === tab.id ? 'text-primary' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
        >
          {{ tab.label }}
          <span 
            v-if="activeTab === tab.id" 
            class="absolute bottom-0 left-0 w-full h-0.5 bg-primary rounded-t-full"
          ></span>
        </button>
      </div>
    </div>

    <div class="p-6 flex-1 bg-gray-50/50 dark:bg-gray-800/20">
      <div v-if="activeTab === 'timeline'" class="h-full">
        <UptimeTimeline :monitor="selectedMonitor" :data="mockChartData" />
      </div>
      
      <div v-else-if="activeTab === 'latency'" class="h-full">
        <LatencyChart :monitor="selectedMonitor" :data="mockChartData" />
      </div>

      <div v-else class="flex items-center justify-center h-full text-gray-500">
        Content for {{ activeTab }} tab
      </div>
    </div>
  </div>
</template>
