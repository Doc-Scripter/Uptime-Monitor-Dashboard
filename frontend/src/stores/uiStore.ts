import { defineStore } from 'pinia'
import { ref, watch } from 'vue'

export const useUiStore = defineStore('ui', () => {
  // State
  const isSidebarOpen = ref(true)
  const isDarkMode = ref(false)
  const activeModal = ref<string | null>(null)
  
  // Initialize dark mode from system preference or local storage
  const initDarkMode = () => {
    const savedMode = localStorage.getItem('darkMode')
    if (savedMode !== null) {
      isDarkMode.value = savedMode === 'true'
    } else {
      isDarkMode.value = window.matchMedia('(prefers-color-scheme: dark)').matches
    }
    applyDarkMode()
  }

  // Actions
  function toggleSidebar() {
    isSidebarOpen.value = !isSidebarOpen.value
  }

  function toggleDarkMode() {
    isDarkMode.value = !isDarkMode.value
    applyDarkMode()
    localStorage.setItem('darkMode', String(isDarkMode.value))
  }

  function applyDarkMode() {
    if (isDarkMode.value) {
      document.documentElement.classList.add('dark')
    } else {
      document.documentElement.classList.remove('dark')
    }
  }

  function openModal(modalId: string) {
    activeModal.value = modalId
  }

  function closeModal() {
    activeModal.value = null
  }

  // Initialize
  initDarkMode()

  return {
    isSidebarOpen,
    isDarkMode,
    activeModal,
    toggleSidebar,
    toggleDarkMode,
    openModal,
    closeModal
  }
})
