import { onMounted, onUnmounted, ref } from 'vue'

export function usePolling(callback: () => Promise<void>, interval: number = 60000) {
  const isPolling = ref(false)
  let timer: ReturnType<typeof setInterval> | null = null

  const startPolling = () => {
    if (timer) return
    
    isPolling.value = true
    // Initial call
    callback().catch(console.error)
    
    timer = setInterval(() => {
      callback().catch(console.error)
    }, interval)
  }

  const stopPolling = () => {
    if (timer) {
      clearInterval(timer)
      timer = null
    }
    isPolling.value = false
  }

  onMounted(() => {
    startPolling()
  })

  onUnmounted(() => {
    stopPolling()
  })

  return {
    isPolling,
    startPolling,
    stopPolling
  }
}
