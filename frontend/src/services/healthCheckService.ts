import apiClient from './api'
import type { HealthCheck } from '@/types'

export const healthCheckService = {
  /**
   * Get health check history for a monitor
   */
  async getMonitorHealthChecks(
    monitorId: number,
    days: number = 7
  ): Promise<{ data: HealthCheck[] }> {
    const response = await apiClient.get(`/monitors/${monitorId}/health-checks`, {
      params: { days },
    })
    return response.data
  },

  /**
   * Get timeline data for charts
   */
  async getTimeline(monitorId: number, days: number = 7): Promise<{ timestamp: string; status: string; response_time: number | null }[]> {
    const response = await apiClient.get(`/monitors/${monitorId}/timeline`, {
      params: { days },
    })
    return response.data
  },

  /**
   * Get recent health events across all monitors
   */
  async getRecentEvents(hours: number = 24): Promise<{ data: HealthCheck[] }> {
    const response = await apiClient.get('/health-checks/recent', {
      params: { hours },
    })
    return response.data
  },
}
