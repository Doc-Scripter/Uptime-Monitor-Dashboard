<script setup lang="ts">
import { useUiStore } from '@/stores/uiStore'
import TimeRangeFilter from '../common/TimeRangeFilter.vue'
import UserDropdown from '../common/UserDropdown.vue'

const uiStore = useUiStore()
</script>

<template>
  <header class="h-16 bg-white/80 dark:bg-sidebar-dark/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 sticky top-0 z-30 transition-colors duration-200">
    <div class="h-full px-6 flex items-center justify-between gap-4">
      <!-- Left: Mobile Toggle & Title -->
      <div class="flex items-center gap-4">
        <button 
          @click="uiStore.toggleSidebar"
          class="md:hidden p-2 -ml-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors"
        >
          <span class="material-symbols-outlined">menu</span>
        </button>
        
        <!-- Search Bar -->
        <div class="hidden md:flex items-center relative max-w-md w-64 lg:w-96">
          <span class="material-symbols-outlined absolute left-3 text-gray-400 text-[20px]">search</span>
          <input 
            type="text" 
            placeholder="Search monitors..." 
            class="w-full pl-10 pr-4 py-2 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder-gray-400 dark:text-white"
          >
          <div class="absolute right-2 flex items-center gap-1">
            <kbd class="hidden lg:inline-flex items-center h-5 px-1.5 text-[10px] font-medium text-gray-400 border border-gray-200 dark:border-gray-700 rounded bg-white dark:bg-gray-800">⌘K</kbd>
          </div>
        </div>
      </div>

      <!-- Right: Actions & Profile -->
      <div class="flex items-center gap-3 sm:gap-4">
        <TimeRangeFilter class="hidden sm:block" />
        
        <!-- Notifications -->
        <button class="relative p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
          <span class="material-symbols-outlined">notifications</span>
          <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white dark:border-sidebar-dark"></span>
        </button>

        <div class="h-8 w-px bg-gray-200 dark:bg-gray-700 hidden sm:block"></div>

        <UserDropdown />
      </div>
    </div>
  </header>
</template>
