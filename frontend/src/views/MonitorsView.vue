<script setup lang="ts">
import { onMounted } from 'vue'
import { useMonitors } from '@/composables/useMonitors'

const { refreshMonitors } = useMonitors()

onMounted(() => {
  refreshMonitors()
})
</script>

<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Monitors</h1>
      <button class="btn btn-primary">Add Monitor</button>
    </div>

    <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-6">
      <p class="text-gray-500 text-center py-8">Monitors list will appear here</p>
    </div>
  </div>
</template>
