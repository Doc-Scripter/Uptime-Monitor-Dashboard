<script setup lang="ts">
import { onMounted } from 'vue'
import { useMonitors } from '@/composables/useMonitors'
import { usePolling } from '@/composables/usePolling'
import { useMonitorStore } from '@/stores/monitorStore'
import { storeToRefs } from 'pinia'
import StatsCard from '@/components/dashboard/StatsCard.vue'
import MonitorsTable from '@/components/dashboard/MonitorsTable.vue'
import MonitorDetailsPanel from '@/components/dashboard/MonitorDetailsPanel.vue'
import HealthLogsPanel from '@/components/dashboard/HealthLogsPanel.vue'

const { refreshMonitors } = useMonitors()
const store = useMonitorStore()
const { stats, isLoading } = storeToRefs(store)

// Poll for updates every 30 seconds
usePolling(async () => {
  await refreshMonitors()
  await store.fetchStats()
}, 30000)

onMounted(async () => {
  await refreshMonitors()
  await store.fetchStats()
})
</script>

<template>
  <div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
      <StatsCard
        title="Overall Uptime (7d)"
        :value="stats?.overall_uptime ? `${stats.overall_uptime}%` : '-'"
        icon="check_circle"
        :loading="isLoading"
      />
      <StatsCard
        title="Average Latency"
        :value="stats?.average_latency ? `${stats.average_latency}ms` : '-'"
        icon="speed"
        :loading="isLoading"
      />
      <div class="bg-white dark:bg-sidebar-dark rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-800">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Monitors</p>
            <div class="mt-2 flex items-baseline gap-4">
              <div>
                <span class="text-2xl font-bold text-green-600 dark:text-green-400">{{ stats?.monitors_count?.up || 0 }}</span>
                <span class="text-xs text-gray-500 ml-1">Up</span>
              </div>
              <div>
                <span class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ stats?.monitors_count?.warning || 0 }}</span>
                <span class="text-xs text-gray-500 ml-1">Warn</span>
              </div>
              <div>
                <span class="text-2xl font-bold text-red-600 dark:text-red-400">{{ stats?.monitors_count?.down || 0 }}</span>
                <span class="text-xs text-gray-500 ml-1">Down</span>
              </div>
            </div>
          </div>
          <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-800/50 text-gray-400">
            <span class="material-symbols-outlined text-[24px]">dns</span>
          </div>
        </div>
      </div>
      <div class="bg-white dark:bg-sidebar-dark rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-800">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Public IP & Network</p>
            <h3 class="mt-2 text-xl font-bold text-gray-900 dark:text-white tracking-tight">
              192.168.1.1, NY
            </h3>
          </div>
          <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-800/50 text-gray-400">
            <span class="material-symbols-outlined text-[24px]">public</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 gap-6">
      <!-- Monitors List -->
      <MonitorsTable />

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Monitor Details -->
        <div class="lg:col-span-2">
          <MonitorDetailsPanel />
        </div>

        <!-- Recent Health Logs -->
        <div>
          <HealthLogsPanel />
        </div>
      </div>
    </div>
  </div>
</template>
