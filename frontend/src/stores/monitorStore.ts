import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { monitorService } from '@/services/monitorService'
import type { Monitor, DashboardStats } from '@/types'

export const useMonitorStore = defineStore('monitor', () => {
  // State
  const monitors = ref<Monitor[]>([])
  const stats = ref<DashboardStats | null>(null)
  const isLoading = ref(false)
  const error = ref<string | null>(null)
  const lastUpdated = ref<Date | null>(null)

  // Getters
  const activeMonitors = computed(() => monitors.value.filter(m => m.is_active))
  const downMonitors = computed(() => monitors.value.filter(m => m.status === 'down'))
  const warningMonitors = computed(() => monitors.value.filter(m => m.status === 'warning'))
  const upMonitors = computed(() => monitors.value.filter(m => m.status === 'up'))
  
  const totalMonitorsCount = computed(() => monitors.value.length)
  const activeMonitorsCount = computed(() => activeMonitors.value.length)
  const downMonitorsCount = computed(() => downMonitors.value.length)

  // Actions
  async function fetchMonitors(filters?: { status?: string; type?: string; is_active?: boolean }) {
    isLoading.value = true
    error.value = null
    
    try {
      const response = await monitorService.getMonitors(filters)
      monitors.value = response.data
      lastUpdated.value = new Date()
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to fetch monitors'
      console.error('Error fetching monitors:', err)
    } finally {
      isLoading.value = false
    }
  }

  async function fetchStats(days: number = 7) {
    try {
      const data = await monitorService.getDashboardStats(days)
      stats.value = data
    } catch (err: any) {
      console.error('Error fetching stats:', err)
    }
  }

  async function createMonitor(data: any) {
    isLoading.value = true
    error.value = null
    
    try {
      const response = await monitorService.createMonitor(data)
      monitors.value.push(response.data)
      return response.data
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to create monitor'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  async function updateMonitor(id: number, data: any) {
    isLoading.value = true
    error.value = null
    
    try {
      const response = await monitorService.updateMonitor(id, data)
      const index = monitors.value.findIndex(m => m.id === id)
      if (index !== -1) {
        monitors.value[index] = response.data
      }
      return response.data
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to update monitor'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  async function deleteMonitor(id: number) {
    isLoading.value = true
    error.value = null
    
    try {
      await monitorService.deleteMonitor(id)
      monitors.value = monitors.value.filter(m => m.id !== id)
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to delete monitor'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  return {
    monitors,
    stats,
    isLoading,
    error,
    lastUpdated,
    activeMonitors,
    downMonitors,
    warningMonitors,
    upMonitors,
    totalMonitorsCount,
    activeMonitorsCount,
    downMonitorsCount,
    fetchMonitors,
    fetchStats,
    createMonitor,
    updateMonitor,
    deleteMonitor
  }
})
