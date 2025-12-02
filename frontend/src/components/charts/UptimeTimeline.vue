<script setup lang="ts">
import { computed } from 'vue'
import { Line } from 'vue-chartjs'
import { useChart } from '@/composables/useChart'
import type { Monitor } from '@/types'

const props = defineProps<{
  monitor: Monitor
  data: any[]
}>()

const { defaultOptions, createChartData } = useChart()

const chartData = computed(() => {
  const labels = props.data.map(d => d.timestamp)
  const values = props.data.map(d => d.status === 'up' ? 1 : 0)
  
  return createChartData(labels, values, 'Uptime', '#10B981')
})

const options = computed(() => ({
  ...defaultOptions,
  scales: {
    ...defaultOptions.scales,
    y: {
      ...defaultOptions.scales?.y,
      ticks: {
        display: false
      },
      min: 0,
      max: 1.2
    }
  },
  plugins: {
    ...defaultOptions.plugins,
    tooltip: {
      callbacks: {
        label: (context: any) => context.raw === 1 ? 'Up' : 'Down'
      }
    }
  }
}))
</script>

<template>
  <div class="h-64 w-full">
    <Line :data="chartData" :options="options" />
  </div>
</template>
