import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../pages/Dashboard.vue'
import CommercialComplexes from '../pages/CommercialComplexes.vue'
import Shops from '../pages/Shops.vue'
import Companies from '../pages/Companies.vue'
import Contracts from '../pages/Contracts.vue'
import Properties from '../pages/Properties.vue'
import Units from '../pages/Units.vue'
import Tenants from '../pages/Tenants.vue'
import Leases from '../pages/Leases.vue'
import Income from '../pages/Income.vue'
import Expenses from '../pages/Expenses.vue'
import Owners from '../pages/Owners.vue'
import Payments from '../pages/Payments.vue'
import Invoices from '../pages/Invoices.vue'
import MonthlyStatements from '../pages/MonthlyStatements.vue'
import Categories from '../pages/Categories.vue'
import Users from '../pages/Users.vue'
import Reports from '../pages/Reports.vue'

const routes = [
  {
    path: '/',
    name: 'Dashboard',
    component: Dashboard
  },
  {
    path: '/commercial-complexes',
    name: 'CommercialComplexes',
    component: CommercialComplexes
  },
  {
    path: '/shops',
    name: 'Shops',
    component: Shops
  },
  {
    path: '/companies',
    name: 'Companies',
    component: Companies
  },
  {
    path: '/contracts',
    name: 'Contracts',
    component: Contracts
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
    path: '/tenants',
    name: 'Tenants',
    component: Tenants
  },
  {
    path: '/leases',
    name: 'Leases',
    component: Leases
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
    path: '/owners',
    name: 'Owners',
    component: Owners
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
    path: '/monthly-statements',
    name: 'MonthlyStatements',
    component: MonthlyStatements
  },
  {
    path: '/categories',
    name: 'Categories',
    component: Categories
  },
  {
    path: '/users',
    name: 'Users',
    component: Users
  },
  {
    path: '/reports',
    name: 'Reports',
    component: Reports
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
