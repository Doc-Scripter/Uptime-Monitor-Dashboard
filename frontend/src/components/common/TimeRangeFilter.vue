<script setup lang="ts">
import { ref } from 'vue'

const ranges = [
  { id: '24h', label: 'Last 24 Hours' },
  { id: '7d', label: 'Last 7 Days' },
  { id: '30d', label: 'Last 30 Days' },
]

const selectedRange = ref('7d')
const isOpen = ref(false)

const selectRange = (id: string) => {
  selectedRange.value = id
  isOpen.value = false
  // Emit event would go here
}

const toggleDropdown = () => {
  isOpen.value = !isOpen.value
}
</script>

<template>
  <div class="relative">
    <button 
      @click="toggleDropdown"
      class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
    >
      <span class="material-symbols-outlined text-[20px] text-gray-500">calendar_today</span>
      {{ ranges.find(r => r.id === selectedRange)?.label }}
      <span class="material-symbols-outlined text-[20px] text-gray-400">expand_more</span>
    </button>

    <!-- Dropdown -->
    <div 
      v-if="isOpen"
      class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 py-1 z-50 animate-in fade-in zoom-in-95 duration-100"
    >
      <button
        v-for="range in ranges"
        :key="range.id"
        @click="selectRange(range.id)"
        class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center justify-between"
        :class="selectedRange === range.id ? 'text-primary font-medium' : 'text-gray-700 dark:text-gray-300'"
      >
        {{ range.label }}
        <span v-if="selectedRange === range.id" class="material-symbols-outlined text-[18px]">check</span>
      </button>
    </div>
    
    <!-- Backdrop -->
    <div 
      v-if="isOpen" 
      @click="isOpen = false"
      class="fixed inset-0 z-40"
    ></div>
  </div>
</template>
