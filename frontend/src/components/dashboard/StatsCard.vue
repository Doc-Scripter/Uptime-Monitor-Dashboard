<script setup lang="ts">
defineProps<{
  title: string
  value: string | number
  icon: string
  trend?: {
    value: number
    label: string
    positive?: boolean
  }
  loading?: boolean
  color?: string
}>()
</script>

<template>
  <div class="bg-white dark:bg-sidebar-dark rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-800 transition-all duration-200 hover:shadow-md">
    <div class="flex items-start justify-between">
      <div>
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ title }}</p>
        
        <div v-if="loading" class="mt-2 h-8 w-24 bg-gray-100 dark:bg-gray-800 rounded animate-pulse"></div>
        <h3 v-else class="mt-2 text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
          {{ value }}
        </h3>
      </div>
      
      <div 
        class="p-3 rounded-xl bg-gray-50 dark:bg-gray-800/50"
        :class="color ? `text-${color}-500 bg-${color}-50 dark:bg-${color}-900/20` : 'text-gray-400'"
      >
        <span class="material-symbols-outlined text-[24px]">{{ icon }}</span>
      </div>
    </div>

    <div v-if="trend && !loading" class="mt-4 flex items-center gap-2">
      <span 
        class="flex items-center text-xs font-medium px-2 py-1 rounded-full"
        :class="trend.positive ? 'text-green-700 bg-green-50 dark:text-green-400 dark:bg-green-900/20' : 'text-red-700 bg-red-50 dark:text-red-400 dark:bg-red-900/20'"
      >
        <span class="material-symbols-outlined text-[14px] mr-0.5">
          {{ trend.positive ? 'trending_up' : 'trending_down' }}
        </span>
        {{ trend.value }}%
      </span>
      <span class="text-xs text-gray-400">{{ trend.label }}</span>
    </div>
  </div>
</template>
