<script setup lang="ts">
import MainLayout from '@/components/layout/MainLayout.vue'
import { useUiStore } from '@/stores/uiStore'

// Initialize UI store (handles dark mode init)
const uiStore = useUiStore()
</script>

<template>
  <MainLayout />
</template>
