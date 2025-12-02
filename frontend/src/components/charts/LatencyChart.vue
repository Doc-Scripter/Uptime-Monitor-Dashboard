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
  const values = props.data.map(d => d.response_time)
  
  return createChartData(labels, values, 'Latency (ms)', '#3B82F6')
})

const options = computed(() => ({
  ...defaultOptions,
  scales: {
    ...defaultOptions.scales,
    y: {
      ...defaultOptions.scales?.y,
      title: {
        display: true,
        text: 'ms'
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
