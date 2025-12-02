import { formatDistanceToNow, format } from 'date-fns'

export function useDateTime() {
  const formatRelativeTime = (date: string | Date | null) => {
    if (!date) return 'Never'
    return formatDistanceToNow(new Date(date), { addSuffix: true })
  }

  const formatDateTime = (date: string | Date | null) => {
    if (!date) return '-'
    return format(new Date(date), 'MMM d, yyyy HH:mm')
  }

  const formatDate = (date: string | Date | null) => {
    if (!date) return '-'
    return format(new Date(date), 'MMM d, yyyy')
  }

  return {
    formatRelativeTime,
    formatDateTime,
    formatDate
  }
}
