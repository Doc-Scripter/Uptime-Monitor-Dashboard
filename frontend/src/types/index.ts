// Type definitions for API responses and models

export interface Monitor {
  id: number
  name: string
  url: string
  type: 'website' | 'api'
  interval: number
  is_active: boolean
  status: 'up' | 'down' | 'warning'
  uptime_percentage: number | null
  current_latency: number | null
  tags: string[]
  last_checked_at: string | null
  created_at: string
  updated_at: string
  latest_health_check?: HealthCheck
  status_label: string
  formatted_latency: string
  last_checked_relative: string
}

export interface HealthCheck {
  id: number
  monitor_id: number
  status: 'up' | 'down' | 'warning'
  response_time: number | null
  http_code: number | null
  error_message: string | null
  checked_at: string
  created_at: string
  monitor_name?: string
  status_label: string
  was_successful: boolean
  formatted_response_time: string
  checked_at_relative: string
}

export interface DashboardStats {
  overall_uptime: number
  average_latency: number
  monitors_count: {
    total: number
    active: number
    up: number
    warning: number
    down: number
  }
  period_days: number
}

export interface MonitorStats {
  uptime_percentage: number
  average_latency: number
  total_checks: number
  failed_checks: number
  last_downtime: string | null
  period_days: number
}

export interface NetworkInfo {
  ip: string
  hostname: string | null
  city: string | null
  region: string | null
  country: string | null
  location: string | null
  organization: string | null
  postal: string | null
  timezone: string | null
  error?: string
}

export interface ApiError {
  message: string
  errors?: Record<string, string[]>
}
