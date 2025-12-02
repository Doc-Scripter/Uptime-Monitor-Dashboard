<script setup lang="ts">
import { ref } from 'vue'
import { useMonitorStore } from '@/stores/monitorStore'
import MonitorForm from '../forms/MonitorForm.vue'

const props = defineProps<{
  isOpen: boolean
}>()

const emit = defineEmits<{
  (e: 'close'): void
  (e: 'created'): void
}>()

const store = useMonitorStore()
const loading = ref(false)

const handleSubmit = async (data: any) => {
  loading.value = true
  try {
    await store.createMonitor(data)
    emit('created')
    emit('close')
  } catch (error) {
    console.error('Failed to create monitor:', error)
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <Transition name="modal">
    <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto">
      <!-- Backdrop -->
      <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="emit('close')"></div>

      <!-- Modal Panel -->
      <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white dark:bg-sidebar-dark p-6 text-left align-middle shadow-xl transition-all border border-gray-100 dark:border-gray-800">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Add New Monitor</h3>
            <button @click="emit('close')" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
              <span class="material-symbols-outlined">close</span>
            </button>
          </div>

          <MonitorForm @submit="handleSubmit" @cancel="emit('close')" :loading="loading" />
        </div>
      </div>
    </div>
  </Transition>
</template>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-enter-active .transform,
.modal-leave-active .transform {
  transition: all 0.3s ease-out;
}

.modal-enter-from .transform,
.modal-leave-to .transform {
  opacity: 0;
  transform: scale(0.95);
}
</style>
