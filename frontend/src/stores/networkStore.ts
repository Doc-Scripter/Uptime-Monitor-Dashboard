import { defineStore } from 'pinia'
import { ref } from 'vue'
import { networkService } from '@/services/networkService'
import type { NetworkInfo } from '@/types'

export const useNetworkStore = defineStore('network', () => {
  // State
  const networkInfo = ref<NetworkInfo | null>(null)
  const isLoading = ref(false)
  const error = ref<string | null>(null)

  // Actions
  async function fetchNetworkInfo(ip?: string) {
    // Return cached info if available and no specific IP requested
    if (!ip && networkInfo.value) {
      return
    }

    isLoading.value = true
    error.value = null
    
    try {
      const data = await networkService.getNetworkInfo(ip)
      networkInfo.value = data
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to fetch network info'
      console.error('Error fetching network info:', err)
    } finally {
      isLoading.value = false
    }
  }

  return {
    networkInfo,
    isLoading,
    error,
    fetchNetworkInfo
  }
})
