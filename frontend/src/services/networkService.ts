import apiClient from './api'
import type { NetworkInfo } from '@/types'

export const networkService = {
  /**
   * Get network information for client IP
   */
  async getNetworkInfo(ip?: string): Promise<NetworkInfo> {
    const response = await apiClient.get('/network/info', {
      params: ip ? { ip } : undefined,
    })
    return response.data
  },
}
