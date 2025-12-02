<script setup lang="ts">
import { useUiStore } from '@/stores/uiStore'
import SidebarNav from './SidebarNav.vue'
import QuickFilters from './QuickFilters.vue'

const uiStore = useUiStore()
</script>

<template>
  <aside 
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-sidebar-dark border-r border-gray-200 dark:border-gray-800 transform transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-0"
    :class="uiStore.isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
  >
    <!-- Logo -->
    <div class="flex items-center h-16 px-6 border-b border-gray-100 dark:border-gray-800/50">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary to-orange-600 flex items-center justify-center text-white font-bold shadow-lg shadow-primary/20">
          <span class="material-symbols-outlined text-[20px]">monitoring</span>
        </div>
        <span class="text-lg font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300">
          UptimeMonitor
        </span>
      </div>
    </div>

    <!-- Navigation -->
    <div class="flex flex-col h-[calc(100%-4rem)] overflow-y-auto py-4">
      <SidebarNav />
      <QuickFilters />
      
      <!-- Bottom Actions -->
      <div class="mt-auto px-6 pb-6 pt-4">
        <div class="p-4 rounded-xl bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-800/50 border border-gray-200 dark:border-gray-700">
          <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Pro Plan</h4>
          <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Get advanced monitoring features.</p>
          <button class="w-full py-2 px-3 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-xs font-medium text-gray-900 dark:text-white rounded-lg border border-gray-200 dark:border-gray-600 transition-colors shadow-sm">
            Upgrade Now
          </button>
        </div>
      </div>
    </div>
  </aside>

  <!-- Mobile Overlay -->
  <div 
    v-if="uiStore.isSidebarOpen" 
    @click="uiStore.toggleSidebar"
    class="fixed inset-0 bg-gray-900/50 z-40 md:hidden backdrop-blur-sm transition-opacity"
  ></div>
</template>
