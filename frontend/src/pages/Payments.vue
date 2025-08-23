<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const payments = ref([])
const tenants = ref([])
const form = ref({tenant_id:'',payment_date:'',amount:'',method:'cash',notes:''})
const loading = ref(false)

async function load(){
  try {
    loading.value = true
    const [paymentsResponse, tenantsResponse] = await Promise.all([
      axios.get('/payments'),
      axios.get('/tenants')
    ])
    payments.value = paymentsResponse.data?.data?.data || paymentsResponse.data?.data || paymentsResponse.data || []
    tenants.value = tenantsResponse.data?.data?.data || tenantsResponse.data?.data || tenantsResponse.data || []
  } catch (error) {
    console.error('Error loading data:', error)
  } finally {
    loading.value = false
  }
}

onMounted(load)

async function submit(){
  try {
    await axios.post('/payments', form.value)
    form.value = {tenant_id:'',payment_date:'',amount:'',method:'cash',notes:''}
    await load()
  } catch (error) {
    console.error('Error adding payment:', error)
  }
}

async function remove(id){
  try {
    await axios.delete(`/payments/${id}`)
    await load()
  } catch (error) {
    console.error('Error removing payment:', error)
  }
}
</script>

<template>
  <div class="space-y-6">
    <!-- Header Card -->
    <div class="bg-gray-800 rounded-lg shadow-lg p-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-white">Payments Management</h1>
          <p class="text-gray-400 mt-1">Track rent payments and financial transactions</p>
        </div>
        <div class="text-right">
          <div class="text-3xl font-bold text-green-500">${{ payments.reduce((sum, p) => sum + parseFloat(p.amount), 0).toFixed(2) }}</div>
          <div class="text-gray-400 text-sm">Total Payments</div>
        </div>
      </div>
    </div>

    <!-- Add Payment Form Card -->
    <div class="bg-gray-800 rounded-lg shadow-lg p-6">
      <h2 class="text-xl font-semibold text-white mb-4">Record New Payment</h2>
      <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Tenant</label>
          <select 
            v-model="form.tenant_id" 
            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            required
          >
            <option value="">Select Tenant</option>
            <option v-for="tenant in tenants" :value="tenant.id" :key="tenant.id">
              {{ tenant.name }}
            </option>
          </select>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Payment Date</label>
          <input 
            v-model="form.payment_date" 
            type="date" 
            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            required
          />
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Amount</label>
          <input 
            v-model="form.amount" 
            placeholder="e.g., 1500" 
            type="number"
            step="0.01"
            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            required
          />
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Payment Method</label>
          <select 
            v-model="form.method" 
            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          >
            <option value="cash">Cash</option>
            <option value="bank_transfer">Bank Transfer</option>
            <option value="credit_card">Credit Card</option>
          </select>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Notes</label>
          <input 
            v-model="form.notes" 
            placeholder="Optional notes" 
            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          />
        </div>
        
        <div class="flex items-end">
          <button 
            type="submit" 
            class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-gray-800"
          >
            Record Payment
          </button>
        </div>
      </form>
    </div>

    <!-- Payments List Card -->
    <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-700">
        <h2 class="text-xl font-semibold text-white">Payments History</h2>
      </div>
      
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-700">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Tenant</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Amount</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Method</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Notes</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-gray-800 divide-y divide-gray-700">
            <tr v-if="loading" class="text-center">
              <td colspan="6" class="px-6 py-4 text-gray-400">Loading payments...</td>
            </tr>
            <tr v-else-if="payments.length === 0" class="text-center">
              <td colspan="6" class="px-6 py-4 text-gray-400">No payments found</td>
            </tr>
            <tr v-else v-for="payment in payments" :key="payment.id" class="hover:bg-gray-700 transition-colors duration-200">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-white">
                  {{ tenants.find(t => t.id == payment.tenant_id)?.name || 'N/A' }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-300">{{ payment.payment_date }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-green-400">${{ payment.amount }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span 
                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  :class="{
                    'bg-blue-100 text-blue-800': payment.method === 'bank_transfer',
                    'bg-green-100 text-green-800': payment.method === 'cash',
                    'bg-purple-100 text-purple-800': payment.method === 'credit_card'
                  }"
                >
                  {{ payment.method.replace('_', ' ').toUpperCase() }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-300">{{ payment.notes || '-' }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button 
                  @click="remove(payment.id)"
                  class="text-red-400 hover:text-red-300 transition-colors duration-200"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
