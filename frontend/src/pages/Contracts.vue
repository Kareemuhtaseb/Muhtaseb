<template>
  <div>
    <!-- Header -->
    <div class="relative overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-r from-emerald-600/20 to-teal-600/20"></div>
      <div class="relative px-6 py-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-white mb-2">Contracts</h1>
            <p class="text-white/60">Manage contracts and track expected vs actual income variance</p>
          </div>
          <button
            @click="showCreateModal = true"
            class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl hover:from-emerald-600 hover:to-teal-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105"
          >
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Contract
          </button>
        </div>
      </div>
    </div>

    <!-- Variance Analysis Summary -->
    <div class="px-6 -mt-4 mb-8">
      <div class="glass-premium rounded-2xl p-6">
        <h2 class="text-xl font-bold text-white mb-4">Income Variance Analysis</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="text-center">
            <p class="text-white/60 text-sm">Total Expected</p>
            <p class="text-2xl font-bold text-white">${{ formatNumber(varianceSummary.total_expected_income || 0) }}</p>
          </div>
          <div class="text-center">
            <p class="text-white/60 text-sm">Total Actual</p>
            <p class="text-2xl font-bold text-white">${{ formatNumber(varianceSummary.total_actual_income || 0) }}</p>
          </div>
          <div class="text-center">
            <p class="text-white/60 text-sm">Total Variance</p>
            <p :class="getVarianceClass(varianceSummary.total_variance)" class="text-2xl font-bold">
              ${{ formatNumber(varianceSummary.total_variance || 0) }}
            </p>
          </div>
          <div class="text-center">
            <p class="text-white/60 text-sm">Variance %</p>
            <p :class="getVarianceClass(varianceSummary.total_variance_percentage)" class="text-2xl font-bold">
              {{ (varianceSummary.total_variance_percentage || 0).toFixed(1) }}%
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="px-6 mb-8">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="glass-premium rounded-2xl p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-white/60 text-sm">Total Contracts</p>
              <p class="text-2xl font-bold text-white">{{ stats.total_contracts || 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
            </div>
          </div>
        </div>

        <div class="glass-premium rounded-2xl p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-white/60 text-sm">Active Contracts</p>
              <p class="text-2xl font-bold text-white">{{ stats.active_contracts || 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
          </div>
        </div>

        <div class="glass-premium rounded-2xl p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-white/60 text-sm">Expiring Soon</p>
              <p class="text-2xl font-bold text-white">{{ stats.expiring_soon || 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-500/20 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
          </div>
        </div>

        <div class="glass-premium rounded-2xl p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-white/60 text-sm">Total Expected</p>
              <p class="text-2xl font-bold text-white">${{ formatNumber(stats.total_expected_income || 0) }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filters and Search -->
    <div class="px-6 mb-6">
      <div class="glass-premium rounded-2xl p-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
          <div>
            <label class="block text-white/60 text-sm mb-2">Search</label>
            <input
              v-model="filters.client_name"
              type="text"
              placeholder="Search clients..."
              class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-emerald-500"
            />
          </div>
          <div>
            <label class="block text-white/60 text-sm mb-2">Status</label>
            <select
              v-model="filters.status"
              class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
            >
              <option value="">All Status</option>
              <option value="active">Active</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
              <option value="expired">Expired</option>
            </select>
          </div>
          <div>
            <label class="block text-white/60 text-sm mb-2">Property</label>
            <select
              v-model="filters.property_id"
              class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
            >
              <option value="">All Properties</option>
              <option v-for="property in properties" :key="property.id" :value="property.id">
                {{ property.name }}
              </option>
            </select>
          </div>
          <div>
            <label class="block text-white/60 text-sm mb-2">Period</label>
            <select
              v-model="variancePeriod"
              @change="loadVarianceAnalysis"
              class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
            >
              <option value="all">All Time</option>
              <option value="current_month">Current Month</option>
              <option value="current_year">Current Year</option>
            </select>
          </div>
          <div class="flex items-end">
            <button
              @click="loadContracts"
              class="w-full px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-lg hover:from-emerald-600 hover:to-teal-600 transition-all duration-300"
            >
              Filter
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Contracts Table -->
    <div class="px-6 mb-8">
      <div class="glass-premium rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-white/5">
              <tr>
                <th class="px-6 py-4 text-left text-white/60 font-semibold">Contract</th>
                <th class="px-6 py-4 text-left text-white/60 font-semibold">Client</th>
                <th class="px-6 py-4 text-left text-white/60 font-semibold">Property</th>
                <th class="px-6 py-4 text-left text-white/60 font-semibold">Expected</th>
                <th class="px-6 py-4 text-left text-white/60 font-semibold">Actual</th>
                <th class="px-6 py-4 text-left text-white/60 font-semibold">Variance</th>
                <th class="px-6 py-4 text-left text-white/60 font-semibold">Status</th>
                <th class="px-6 py-4 text-left text-white/60 font-semibold">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="contract in contracts" :key="contract.id" class="border-t border-white/10 hover:bg-white/5">
                <td class="px-6 py-4">
                  <div>
                    <p class="text-white font-semibold">{{ contract.contract_number }}</p>
                    <p class="text-white/60 text-sm">{{ contract.title }}</p>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div>
                    <p class="text-white">{{ contract.client_name }}</p>
                    <p class="text-white/60 text-sm">{{ contract.client_email }}</p>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div>
                    <p class="text-white">{{ contract.property?.name || 'N/A' }}</p>
                    <p class="text-white/60 text-sm">{{ contract.unit?.unit_number || 'N/A' }}</p>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <p class="text-white">${{ formatNumber(contract.expected_income || 0) }}</p>
                </td>
                <td class="px-6 py-4">
                  <p class="text-white">${{ formatNumber(contract.actual_income || 0) }}</p>
                </td>
                <td class="px-6 py-4">
                  <div>
                    <p :class="getVarianceClass(contract.variance)" class="font-semibold">
                      ${{ formatNumber(contract.variance || 0) }}
                    </p>
                    <p :class="getVarianceClass(contract.variance_percentage)" class="text-sm">
                      {{ (contract.variance_percentage || 0).toFixed(1) }}%
                    </p>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span :class="getStatusClass(contract.status)" class="px-2 py-1 text-xs rounded-full">
                    {{ contract.status }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex space-x-2">
                    <button
                      @click="viewContract(contract)"
                      class="p-2 bg-blue-500/20 text-blue-400 rounded-lg hover:bg-blue-500/30 transition-colors"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                    </button>
                    <button
                      @click="editContract(contract)"
                      class="p-2 bg-green-500/20 text-green-400 rounded-lg hover:bg-green-500/30 transition-colors"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                      </svg>
                    </button>
                    <button
                      @click="deleteContract(contract)"
                      class="p-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 transition-colors"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <div v-if="showCreateModal || showEditModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
      <div class="glass-premium rounded-2xl p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-white">{{ showEditModal ? 'Edit Contract' : 'Add New Contract' }}</h2>
          <button @click="closeModal" class="text-white/60 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <form @submit.prevent="saveContract" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-white/60 text-sm mb-2">Title *</label>
              <input
                v-model="form.title"
                type="text"
                required
                class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-emerald-500"
              />
            </div>
            <div>
              <label class="block text-white/60 text-sm mb-2">Property *</label>
              <select
                v-model="form.property_id"
                required
                class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
              >
                <option value="">Select Property</option>
                <option v-for="property in properties" :key="property.id" :value="property.id">
                  {{ property.name }}
                </option>
              </select>
            </div>
            <div>
              <label class="block text-white/60 text-sm mb-2">Client Name *</label>
              <input
                v-model="form.client_name"
                type="text"
                required
                class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-emerald-500"
              />
            </div>
            <div>
              <label class="block text-white/60 text-sm mb-2">Client Email</label>
              <input
                v-model="form.client_email"
                type="email"
                class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-emerald-500"
              />
            </div>
            <div>
              <label class="block text-white/60 text-sm mb-2">Start Date *</label>
              <input
                v-model="form.start_date"
                type="date"
                required
                class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
              />
            </div>
            <div>
              <label class="block text-white/60 text-sm mb-2">End Date *</label>
              <input
                v-model="form.end_date"
                type="date"
                required
                class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
              />
            </div>
            <div>
              <label class="block text-white/60 text-sm mb-2">Monthly Amount *</label>
              <input
                v-model="form.monthly_amount"
                type="number"
                step="0.01"
                required
                class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-emerald-500"
              />
            </div>
            <div>
              <label class="block text-white/60 text-sm mb-2">Payment Frequency *</label>
              <select
                v-model="form.payment_frequency"
                required
                class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
              >
                <option value="">Select Frequency</option>
                <option value="monthly">Monthly</option>
                <option value="quarterly">Quarterly</option>
                <option value="annually">Annually</option>
              </select>
            </div>
            <div>
              <label class="block text-white/60 text-sm mb-2">Status *</label>
              <select
                v-model="form.status"
                required
                class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
              >
                <option value="">Select Status</option>
                <option value="active">Active</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
                <option value="expired">Expired</option>
              </select>
            </div>
            <div>
              <label class="block text-white/60 text-sm mb-2">Category *</label>
              <select
                v-model="form.category_id"
                required
                class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
              >
                <option value="">Select Category</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
            </div>
          </div>
          <div>
            <label class="block text-white/60 text-sm mb-2">Description</label>
            <textarea
              v-model="form.description"
              rows="3"
              class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-emerald-500"
            ></textarea>
          </div>
          <div>
            <label class="block text-white/60 text-sm mb-2">Notes</label>
            <textarea
              v-model="form.notes"
              rows="2"
              class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-emerald-500"
            ></textarea>
          </div>

          <div class="flex justify-end space-x-4 pt-4">
            <button
              type="button"
              @click="closeModal"
              class="px-6 py-2 text-white/60 hover:text-white transition-colors"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-lg hover:from-emerald-600 hover:to-teal-600 transition-all duration-300"
            >
              {{ showEditModal ? 'Update Contract' : 'Create Contract' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Loading Overlay -->
    <div v-if="loading" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center">
      <div class="text-white text-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-emerald-500 mx-auto mb-4"></div>
        <p>Loading contracts...</p>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

export default {
  name: 'Contracts',
  setup() {
    const router = useRouter()
    const contracts = ref([])
    const properties = ref([])
    const categories = ref([])
    const stats = ref({})
    const varianceSummary = ref({})
    const loading = ref(false)
    const showCreateModal = ref(false)
    const showEditModal = ref(false)
    const editingContract = ref(null)
    const variancePeriod = ref('all')
    
    const filters = ref({
      client_name: '',
      status: '',
      property_id: ''
    })

    const form = ref({
      title: '',
      property_id: '',
      unit_id: '',
      client_name: '',
      client_email: '',
      client_phone: '',
      start_date: '',
      end_date: '',
      monthly_amount: '',
      payment_frequency: 'monthly',
      status: 'active',
      category_id: '',
      description: '',
      notes: ''
    })

    const loadContracts = async () => {
      loading.value = true
      try {
        const params = {}
        if (filters.value.client_name) params.client_name = filters.value.client_name
        if (filters.value.status) params.status = filters.value.status
        if (filters.value.property_id) params.property_id = filters.value.property_id

        const response = await axios.get('/contracts', { params })
        contracts.value = response.data.data.data || []
      } catch (error) {
        console.error('Error loading contracts:', error)
      } finally {
        loading.value = false
      }
    }

    const loadStats = async () => {
      try {
        const response = await axios.get('/contracts/statistics')
        stats.value = response.data.data
      } catch (error) {
        console.error('Error loading stats:', error)
      }
    }

    const loadVarianceAnalysis = async () => {
      try {
        const response = await axios.get('/contracts/variance-analysis', {
          params: { period: variancePeriod.value }
        })
        varianceSummary.value = response.data.data.summary
      } catch (error) {
        console.error('Error loading variance analysis:', error)
      }
    }

    const loadProperties = async () => {
      try {
        const response = await axios.get('/properties')
        properties.value = response.data.data.data || []
      } catch (error) {
        console.error('Error loading properties:', error)
      }
    }

    const loadCategories = async () => {
      try {
        const response = await axios.get('/categories')
        categories.value = response.data.data.data || []
      } catch (error) {
        console.error('Error loading categories:', error)
      }
    }

    const saveContract = async () => {
      try {
        if (showEditModal.value) {
          await axios.put(`/contracts/${editingContract.value.id}`, form.value)
        } else {
          await axios.post('/contracts', form.value)
        }
        closeModal()
        loadContracts()
        loadStats()
        loadVarianceAnalysis()
      } catch (error) {
        console.error('Error saving contract:', error)
      }
    }

    const editContract = (contract) => {
      editingContract.value = contract
      form.value = { ...contract }
      showEditModal.value = true
    }

    const deleteContract = async (contract) => {
      if (confirm('Are you sure you want to delete this contract?')) {
        try {
          await axios.delete(`/contracts/${contract.id}`)
          loadContracts()
          loadStats()
          loadVarianceAnalysis()
        } catch (error) {
          console.error('Error deleting contract:', error)
        }
      }
    }

    const viewContract = (contract) => {
      // Navigate to contract detail view
      router.push(`/contracts/${contract.id}`)
    }

    const closeModal = () => {
      showCreateModal.value = false
      showEditModal.value = false
      editingContract.value = null
      form.value = {
        title: '',
        property_id: '',
        unit_id: '',
        client_name: '',
        client_email: '',
        client_phone: '',
        start_date: '',
        end_date: '',
        monthly_amount: '',
        payment_frequency: 'monthly',
        status: 'active',
        category_id: '',
        description: '',
        notes: ''
      }
    }

    const formatNumber = (num) => {
      return new Intl.NumberFormat().format(num)
    }

    const getStatusClass = (status) => {
      const classes = {
        active: 'bg-emerald-500/20 text-emerald-400',
        completed: 'bg-blue-500/20 text-blue-400',
        cancelled: 'bg-red-500/20 text-red-400',
        expired: 'bg-gray-500/20 text-gray-400'
      }
      return classes[status] || 'bg-gray-500/20 text-gray-400'
    }

    const getVarianceClass = (variance) => {
      if (variance > 0) return 'text-red-400'
      if (variance < 0) return 'text-emerald-400'
      return 'text-white'
    }

    onMounted(() => {
      loadContracts()
      loadStats()
      loadVarianceAnalysis()
      loadProperties()
      loadCategories()
    })

    return {
      contracts,
      properties,
      categories,
      stats,
      varianceSummary,
      loading,
      filters,
      form,
      variancePeriod,
      showCreateModal,
      showEditModal,
      loadContracts,
      loadVarianceAnalysis,
      saveContract,
      editContract,
      deleteContract,
      viewContract,
      closeModal,
      formatNumber,
      getStatusClass,
      getVarianceClass
    }
  }
}
</script>
