import apiClient from './api'
import type { Monitor, DashboardStats } from '@/types'

export const monitorService = {
  /**
   * Get all monitors with optional filters
   */
  async getMonitors(filters?: {
    status?: string
    type?: string
    is_active?: boolean
  }): Promise<{ data: Monitor[] }> {
    const response = await apiClient.get('/monitors', { params: filters })
    return response.data
  },

  /**
   * Get a single monitor by ID
   */
  async getMonitor(id: number): Promise<{ data: Monitor }> {
    const response = await apiClient.get(`/monitors/${id}`)
    return response.data
  },

  /**
   * Create a new monitor
   */
  async createMonitor(data: {
    name: string
    url: string
    type?: string
    interval?: number
    tags?: string[]
  }): Promise<{ data: Monitor; message: string }> {
    const response = await apiClient.post('/monitors', data)
    return response.data
  },

  /**
   * Update an existing monitor
   */
  async updateMonitor(
    id: number,
    data: Partial<Monitor>
  ): Promise<{ data: Monitor; message: string }> {
    const response = await apiClient.put(`/monitors/${id}`, data)
    return response.data
  },

  /**
   * Delete a monitor
   */
  async deleteMonitor(id: number): Promise<{ message: string }> {
    const response = await apiClient.delete(`/monitors/${id}`)
    return response.data
  },

  /**
   * Get dashboard statistics
   */
  async getDashboardStats(days: number = 7): Promise<DashboardStats> {
    const response = await apiClient.get('/stats/dashboard', { params: { days } })
    return response.data
  },
}
