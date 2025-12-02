import { ref, computed, onMounted } from 'vue'
import { useMonitorStore } from '@/stores/monitorStore'
import { storeToRefs } from 'pinia'

export function useMonitors() {
  const store = useMonitorStore()
  const { monitors, isLoading, error } = storeToRefs(store)

  const searchQuery = ref('')
  const statusFilter = ref<string | null>(null)
  const typeFilter = ref<string | null>(null)

  const filteredMonitors = computed(() => {
    let result = monitors.value

    // Search filter
    if (searchQuery.value) {
      const query = searchQuery.value.toLowerCase()
      result = result.filter(m => 
        m.name.toLowerCase().includes(query) || 
        m.url.toLowerCase().includes(query)
      )
    }

    // Status filter
    if (statusFilter.value && statusFilter.value !== 'all') {
      result = result.filter(m => m.status === statusFilter.value)
    }

    // Type filter
    if (typeFilter.value && typeFilter.value !== 'all') {
      result = result.filter(m => m.type === typeFilter.value)
    }

    return result
  })

  const refreshMonitors = async () => {
    await store.fetchMonitors()
  }

  onMounted(() => {
    if (monitors.value.length === 0) {
      refreshMonitors()
    }
  })

  return {
    monitors: filteredMonitors,
    isLoading,
    error,
    searchQuery,
    statusFilter,
    typeFilter,
    refreshMonitors
  }
}
