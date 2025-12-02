import { createRouter, createWebHistory } from 'vue-router'
import DashboardView from '../views/DashboardView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'dashboard',
      component: DashboardView
    },
    {
      path: '/monitors',
      name: 'monitors',
      component: () => import('../views/MonitorsView.vue')
    },
    {
      path: '/health-logs',
      name: 'health-logs',
      component: () => import('../views/HealthLogsView.vue')
    },
    {
      path: '/network',
      name: 'network',
      component: () => import('../views/NetworkIntelligenceView.vue')
    }
  ]
})

export default router
