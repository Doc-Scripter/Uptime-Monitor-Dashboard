<script setup lang="ts">
import { ref, reactive } from 'vue'
import type { Monitor } from '@/types'

const props = defineProps<{
  initialData?: Partial<Monitor>
  loading?: boolean
}>()

const emit = defineEmits<{
  (e: 'submit', data: any): void
  (e: 'cancel'): void
}>()

const form = reactive({
  name: props.initialData?.name || '',
  url: props.initialData?.url || '',
  type: props.initialData?.type || 'website',
  interval: props.initialData?.interval || 5,
  tags: props.initialData?.tags || [] as string[]
})

const tagInput = ref('')

const addTag = () => {
  const tag = tagInput.value.trim()
  if (tag && !form.tags.includes(tag)) {
    form.tags.push(tag)
  }
  tagInput.value = ''
}

const removeTag = (tag: string) => {
  form.tags = form.tags.filter(t => t !== tag)
}

const submit = () => {
  emit('submit', { ...form })
}
</script>

<template>
  <form @submit.prevent="submit" class="space-y-6">
    <!-- Name -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
      <input 
        v-model="form.name"
        type="text" 
        required
        placeholder="e.g. Production API"
        class="w-full px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors dark:text-white"
      >
    </div>

    <!-- URL -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">URL</label>
      <input 
        v-model="form.url"
        type="url" 
        required
        placeholder="https://api.example.com"
        class="w-full px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors dark:text-white"
      >
    </div>

    <div class="grid grid-cols-2 gap-6">
      <!-- Type -->
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
        <select 
          v-model="form.type"
          class="w-full px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors dark:text-white"
        >
          <option value="website">Website</option>
          <option value="api">API</option>
          <option value="ping">Ping</option>
        </select>
      </div>

      <!-- Interval -->
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Check Interval</label>
        <select 
          v-model="form.interval"
          class="w-full px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors dark:text-white"
        >
          <option :value="1">1 minute</option>
          <option :value="5">5 minutes</option>
          <option :value="10">10 minutes</option>
          <option :value="30">30 minutes</option>
          <option :value="60">1 hour</option>
        </select>
      </div>
    </div>

    <!-- Tags -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tags</label>
      <div class="flex flex-wrap gap-2 mb-2" v-if="form.tags.length > 0">
        <span 
          v-for="tag in form.tags" 
          :key="tag"
          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary border border-primary/20"
        >
          {{ tag }}
          <button type="button" @click="removeTag(tag)" class="ml-1.5 hover:text-primary/70">
            <span class="material-symbols-outlined text-[14px]">close</span>
          </button>
        </span>
      </div>
      <input 
        v-model="tagInput"
        @keydown.enter.prevent="addTag"
        @blur="addTag"
        type="text" 
        placeholder="Type tag and press Enter"
        class="w-full px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors dark:text-white"
      >
    </div>

    <!-- Actions -->
    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
      <button 
        type="button" 
        @click="emit('cancel')"
        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors"
      >
        Cancel
      </button>
      <button 
        type="submit"
        :disabled="loading"
        class="px-4 py-2 text-sm font-medium text-white bg-primary hover:bg-primary/90 rounded-lg shadow-sm shadow-primary/20 transition-all flex items-center gap-2"
      >
        <span v-if="loading" class="material-symbols-outlined animate-spin text-[18px]">refresh</span>
        {{ loading ? 'Saving...' : 'Save Monitor' }}
      </button>
    </div>
  </form>
</template>
