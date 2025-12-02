<script setup lang="ts">
import type { Monitor } from '@/types'
import { computed } from 'vue'
import { useDateTime } from '@/composables/useDateTime'

const props = defineProps<{
  monitor: Monitor
}>()

const { formatRelativeTime } = useDateTime()

const statusColor = computed(() => {
  switch (props.monitor.status) {
    case 'up': return 'bg-green-500'
    case 'down': return 'bg-red-500'
    case 'warning': return 'bg-yellow-500'
    default: return 'bg-gray-300'
  }
})

const latencyColor = computed(() => {
  const latency = props.monitor.current_latency
  if (!latency) return 'text-gray-400'
  if (latency < 200) return 'text-green-600 dark:text-green-400'
  if (latency < 500) return 'text-yellow-600 dark:text-yellow-400'
  return 'text-red-600 dark:text-red-400'
})
</script>

<template>
  <tr class="group hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors cursor-pointer">
    <!-- Status & Name -->
    <td class="px-6 py-4 whitespace-nowrap">
      <div class="flex items-center gap-4">
        <div class="relative flex h-3 w-3">
          <span 
            v-if="monitor.status === 'down'"
            class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 bg-red-400"
          ></span>
          <span class="relative inline-flex rounded-full h-3 w-3" :class="statusColor"></span>
        </div>
        <div>
          <div class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-primary transition-colors">
            {{ monitor.name }}
          </div>
          <a 
            :href="monitor.url" 
            target="_blank" 
            @click.stop
            class="text-xs text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 flex items-center gap-1 mt-0.5"
          >
            {{ monitor.url.replace(/^https?:\/\//, '') }}
            <span class="material-symbols-outlined text-[12px]">open_in_new</span>
          </a>
        </div>
      </div>
    </td>

    <!-- Latency -->
    <td class="px-6 py-4 whitespace-nowrap">
      <div class="flex items-center gap-2">
        <span class="text-sm font-medium" :class="latencyColor">
          {{ monitor.formatted_latency }}
        </span>
        <div class="w-16 h-1 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden" v-if="monitor.current_latency">
          <div 
            class="h-full rounded-full transition-all duration-500"
            :class="latencyColor.replace('text-', 'bg-')"
            :style="{ width: Math.min((monitor.current_latency / 1000) * 100, 100) + '%' }"
          ></div>
        </div>
      </div>
    </td>

    <!-- Uptime -->
    <td class="px-6 py-4 whitespace-nowrap">
      <span 
        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
        :class="monitor.uptime_percentage && monitor.uptime_percentage > 99 ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400'"
      >
        {{ monitor.uptime_percentage ? monitor.uptime_percentage + '%' : '-' }}
      </span>
    </td>

    <!-- Last Check -->
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
      {{ monitor.last_checked_at ? formatRelativeTime(monitor.last_checked_at) : 'Never' }}
    </td>

    <!-- Actions -->
    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
      <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
        <span class="material-symbols-outlined text-[20px]">more_vert</span>
      </button>
    </td>
  </tr>
</template>
