<script setup lang="ts">
const logs = [
  { id: 1, type: 'down', message: 'API Gateway is down', detail: 'HTTP 503 error', time: '2m ago' },
  { id: 2, type: 'up', message: 'API Gateway is back up', detail: 'Resolved after 5m', time: '30s ago' },
  { id: 3, type: 'warning', message: 'Staging Server latency high', detail: 'Response time > 400ms', time: '10m ago' },
  { id: 4, type: 'up', message: 'Staging Server latency normal', detail: 'Response time back to 150ms', time: '5m ago' },
]
</script>

<template>
  <div class="bg-white dark:bg-sidebar-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 h-full">
    <div class="p-6 border-b border-gray-100 dark:border-gray-800">
      <h2 class="text-lg font-bold text-gray-900 dark:text-white">Recent Health Logs</h2>
    </div>

    <div class="p-6">
      <div class="space-y-6">
        <div v-for="log in logs" :key="log.id" class="flex gap-4">
          <div class="flex-shrink-0 mt-0.5">
            <span v-if="log.type === 'down'" class="material-symbols-outlined text-red-500 text-[20px]">error</span>
            <span v-else-if="log.type === 'warning'" class="material-symbols-outlined text-yellow-500 text-[20px]">warning</span>
            <span v-else class="material-symbols-outlined text-green-500 text-[20px]">check_circle</span>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ log.message }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ log.detail }}</p>
          </div>
          <div class="text-xs text-gray-400 whitespace-nowrap">{{ log.time }}</div>
        </div>
      </div>
    </div>
  </div>
</template>
