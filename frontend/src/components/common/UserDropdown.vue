<script setup lang="ts">
import { ref } from 'vue'
import { useUiStore } from '@/stores/uiStore'

const uiStore = useUiStore()
const isOpen = ref(false)

const toggleDropdown = () => {
  isOpen.value = !isOpen.value
}

const closeDropdown = () => {
  isOpen.value = false
}
</script>

<template>
  <div class="relative">
    <button 
      @click="toggleDropdown"
      class="flex items-center gap-3 p-1 pr-3 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors border border-transparent hover:border-gray-200 dark:hover:border-gray-700"
    >
      <img 
        src="https://ui-avatars.com/api/?name=Admin+User&background=ff8b3d&color=fff" 
        alt="User" 
        class="w-8 h-8 rounded-full ring-2 ring-white dark:ring-gray-800"
      >
      <div class="hidden md:block text-left">
        <p class="text-sm font-medium text-gray-900 dark:text-white leading-none">Admin User</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">admin@example.com</p>
      </div>
      <span class="material-symbols-outlined text-gray-400">expand_more</span>
    </button>

    <!-- Dropdown -->
    <div 
      v-if="isOpen"
      class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 py-2 z-50 animate-in fade-in zoom-in-95 duration-100"
    >
      <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 md:hidden">
        <p class="text-sm font-medium text-gray-900 dark:text-white">Admin User</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">admin@example.com</p>
      </div>

      <div class="py-1">
        <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
          <span class="material-symbols-outlined text-[20px] text-gray-400">person</span>
          Profile
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
          <span class="material-symbols-outlined text-[20px] text-gray-400">settings</span>
          Settings
        </a>
      </div>

      <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

      <div class="px-4 py-2">
        <div class="flex items-center justify-between">
          <span class="text-sm text-gray-700 dark:text-gray-300">Dark Mode</span>
          <button 
            @click="uiStore.toggleDarkMode"
            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-gray-900"
            :class="uiStore.isDarkMode ? 'bg-primary' : 'bg-gray-200'"
          >
            <span
              class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
              :class="uiStore.isDarkMode ? 'translate-x-6' : 'translate-x-1'"
            />
          </button>
        </div>
      </div>

      <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

      <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
        <span class="material-symbols-outlined text-[20px]">logout</span>
        Sign out
      </a>
    </div>

    <!-- Backdrop -->
    <div 
      v-if="isOpen" 
      @click="closeDropdown"
      class="fixed inset-0 z-40"
    ></div>
  </div>
</template>
