import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../pages/Dashboard.vue'
import Income from '../pages/Income.vue'
import Expenses from '../pages/Expenses.vue'
import Payments from '../pages/Payments.vue'
import Invoices from '../pages/Invoices.vue'
import Reports from '../pages/Reports.vue'
import Distributions from '../pages/Distributions.vue'
import Maintenance from '../pages/Maintenance.vue'
import Notifications from '../pages/Notifications.vue'
import Categories from '../pages/Categories.vue'
import Owners from '../pages/Owners.vue'
import Properties from '../pages/Properties.vue'
import Units from '../pages/Units.vue'
import Contracts from '../pages/Contracts.vue'

const routes = [
  {
    path: '/',
    name: 'Dashboard',
    component: Dashboard
  },
  {
    path: '/income',
    name: 'Income',
    component: Income
  },
  {
    path: '/expenses',
    name: 'Expenses',
    component: Expenses
  },
  {
    path: '/payments',
    name: 'Payments',
    component: Payments
  },
  {
    path: '/invoices',
    name: 'Invoices',
    component: Invoices
  },
  {
    path: '/reports',
    name: 'Reports',
    component: Reports
  },
  {
    path: '/distributions',
    name: 'Distributions',
    component: Distributions
  },
  {
    path: '/maintenance',
    name: 'Maintenance',
    component: Maintenance
  },
  {
    path: '/notifications',
    name: 'Notifications',
    component: Notifications
  },
  {
    path: '/categories',
    name: 'Categories',
    component: Categories
  },
  {
    path: '/owners',
    name: 'Owners',
    component: Owners
  },
  {
    path: '/properties',
    name: 'Properties',
    component: Properties
  },
  {
    path: '/units',
    name: 'Units',
    component: Units
  },
  {
    path: '/contracts',
    name: 'Contracts',
    component: Contracts
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
