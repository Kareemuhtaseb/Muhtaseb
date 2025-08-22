import { createRouter, createWebHistory } from 'vue-router';
import Tenants from '../pages/Tenants.vue';
import Units from '../pages/Units.vue';
import Payments from '../pages/Payments.vue';
import Expenses from '../pages/Expenses.vue';
import Owners from '../pages/Owners.vue';
import Reports from '../pages/Reports.vue';

const routes = [
  { path: '/', component: Tenants },
  { path: '/units', component: Units },
  { path: '/payments', component: Payments },
  { path: '/expenses', component: Expenses },
  { path: '/owners', component: Owners },
  { path: '/reports', component: Reports }
];

export default createRouter({
  history: createWebHistory(),
  routes
});
