<template>
  <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-900">Contracts</h1>
      <button
        @click="showCreateModal = true"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Add Contract
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-blue-100 rounded-lg">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Contracts</p>
            <p class="text-2xl font-semibold text-gray-900">{{ contracts.length }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-green-100 rounded-lg">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Active</p>
            <p class="text-2xl font-semibold text-gray-900">{{ activeContracts }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-yellow-100 rounded-lg">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Monthly Income</p>
            <p class="text-2xl font-semibold text-gray-900">SAR {{ formatNumber(totalMonthlyIncome) }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-red-100 rounded-lg">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Overdue</p>
            <p class="text-2xl font-semibold text-gray-900">SAR {{ formatNumber(totalOverdue) }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Contracts List -->
    <div class="bg-white rounded-lg shadow">
      <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-900">Contracts</h2>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shop</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monthly Rent</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Overdue</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="contract in contracts" :key="contract.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ contract.shop?.shop_number }}</div>
                <div class="text-sm text-gray-500">{{ contract.shop?.shop_name }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ contract.company?.name }}</div>
                <div class="text-sm text-gray-500">{{ contract.company?.business_type }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <div>{{ formatDate(contract.start_date) }}</div>
                <div class="text-gray-500">to {{ formatDate(contract.end_date) }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getStatusClass(contract.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                  {{ contract.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">SAR {{ formatNumber(contract.monthly_rent_with_tax) }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <span v-if="contract.overdue_amount > 0" class="text-red-600">
                  SAR {{ formatNumber(contract.overdue_amount) }}
                </span>
                <span v-else class="text-green-600">-</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <button
                    @click="viewContract(contract)"
                    class="text-blue-600 hover:text-blue-900"
                  >
                    View
                  </button>
                  <button
                    @click="editContract(contract)"
                    class="text-indigo-600 hover:text-indigo-900"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteContract(contract.id)"
                    class="text-red-600 hover:text-red-900"
                  >
                    Delete
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <Modal v-if="showCreateModal || showEditModal" @close="closeModal">
      <template #header>
        <h3 class="text-lg font-medium text-gray-900">
          {{ showEditModal ? 'Edit Contract' : 'Add Contract' }}
        </h3>
      </template>
      
      <template #body>
        <form @submit.prevent="saveContract" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Shop</label>
            <select
              v-model="form.shop_id"
              required
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">Select Shop</option>
              <option v-for="shop in shops" :key="shop.id" :value="shop.id">
                {{ shop.shop_number }} - {{ shop.shop_name }}
              </option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700">Company</label>
            <select
              v-model="form.company_id"
              required
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">Select Company</option>
              <option v-for="company in companies" :key="company.id" :value="company.id">
                {{ company.name }}
              </option>
            </select>
          </div>
          
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Start Date</label>
              <input
                v-model="form.start_date"
                type="date"
                required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700">End Date</label>
              <input
                v-model="form.end_date"
                type="date"
                required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
          </div>
          
          <div class="grid grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Yearly Rent</label>
              <input
                v-model="form.yearly_rent"
                type="number"
                step="0.01"
                required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700">Yearly Rent + Tax</label>
              <input
                v-model="form.yearly_rent_with_tax"
                type="number"
                step="0.01"
                required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700">Yearly Services</label>
              <input
                v-model="form.yearly_services"
                type="number"
                step="0.01"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select
              v-model="form.status"
              required
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="active">Active</option>
              <option value="expired">Expired</option>
              <option value="terminated">Terminated</option>
              <option value="pending">Pending</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700">Notes</label>
            <textarea
              v-model="form.notes"
              rows="3"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            ></textarea>
          </div>
        </form>
      </template>
      
      <template #footer>
        <div class="flex justify-end space-x-3">
          <button
            @click="closeModal"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
          >
            Cancel
          </button>
          <button
            @click="saveContract"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700"
          >
            {{ showEditModal ? 'Update' : 'Create' }}
          </button>
        </div>
      </template>
    </Modal>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue'
import Modal from '../components/Modal.vue'
import { useRouter } from 'vue-router'

export default {
  name: 'Contracts',
  components: {
    Modal
  },
  setup() {
    const router = useRouter()
    const contracts = ref([])
    const shops = ref([])
    const companies = ref([])
    const showCreateModal = ref(false)
    const showEditModal = ref(false)
    const editingContract = ref(null)
    const loading = ref(false)

    const form = ref({
      shop_id: '',
      company_id: '',
      start_date: '',
      end_date: '',
      yearly_rent: '',
      yearly_rent_with_tax: '',
      yearly_services: '',
      status: 'active',
      notes: ''
    })

    const activeContracts = computed(() => {
      return contracts.value.filter(contract => contract.status === 'active').length
    })

    const totalMonthlyIncome = computed(() => {
      return contracts.value
        .filter(contract => contract.status === 'active')
        .reduce((total, contract) => total + (contract.monthly_rent_with_tax || 0), 0)
    })

    const totalOverdue = computed(() => {
      return contracts.value.reduce((total, contract) => total + (contract.overdue_amount || 0), 0)
    })

    const fetchContracts = async () => {
      try {
        loading.value = true
        const response = await fetch('/api/contracts')
        const data = await response.json()
        if (data.success) {
          contracts.value = data.data
        }
      } catch (error) {
        console.error('Error fetching contracts:', error)
      } finally {
        loading.value = false
      }
    }

    const fetchShops = async () => {
      try {
        const response = await fetch('/api/shops')
        const data = await response.json()
        if (data.success) {
          shops.value = data.data
        }
      } catch (error) {
        console.error('Error fetching shops:', error)
      }
    }

    const fetchCompanies = async () => {
      try {
        const response = await fetch('/api/companies')
        const data = await response.json()
        if (data.success) {
          companies.value = data.data
        }
      } catch (error) {
        console.error('Error fetching companies:', error)
      }
    }

    const saveContract = async () => {
      try {
        const url = showEditModal.value 
          ? `/api/contracts/${editingContract.value.id}`
          : '/api/contracts'
        
        const method = showEditModal.value ? 'PUT' : 'POST'
        
        const response = await fetch(url, {
          method,
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(form.value)
        })
        
        const data = await response.json()
        if (data.success) {
          await fetchContracts()
          closeModal()
        }
      } catch (error) {
        console.error('Error saving contract:', error)
      }
    }

    const editContract = (contract) => {
      editingContract.value = contract
      form.value = { 
        ...contract,
        start_date: contract.start_date?.split('T')[0],
        end_date: contract.end_date?.split('T')[0]
      }
      showEditModal.value = true
    }

    const deleteContract = async (id) => {
      if (confirm('Are you sure you want to delete this contract?')) {
        try {
          const response = await fetch(`/api/contracts/${id}`, {
            method: 'DELETE'
          })
          const data = await response.json()
          if (data.success) {
            await fetchContracts()
          }
        } catch (error) {
          console.error('Error deleting contract:', error)
        }
      }
    }

    const viewContract = (contract) => {
      router.push(`/contracts/${contract.id}`)
    }

    const closeModal = () => {
      showCreateModal.value = false
      showEditModal.value = false
      editingContract.value = null
      form.value = {
        shop_id: '',
        company_id: '',
        start_date: '',
        end_date: '',
        yearly_rent: '',
        yearly_rent_with_tax: '',
        yearly_services: '',
        status: 'active',
        notes: ''
      }
    }

    const getStatusClass = (status) => {
      switch (status) {
        case 'active':
          return 'bg-green-100 text-green-800'
        case 'expired':
          return 'bg-red-100 text-red-800'
        case 'terminated':
          return 'bg-gray-100 text-gray-800'
        case 'pending':
          return 'bg-yellow-100 text-yellow-800'
        default:
          return 'bg-gray-100 text-gray-800'
      }
    }

    const formatNumber = (number) => {
      return new Intl.NumberFormat('en-US').format(number || 0)
    }

    const formatDate = (date) => {
      if (!date) return ''
      return new Date(date).toLocaleDateString()
    }

    onMounted(() => {
      fetchContracts()
      fetchShops()
      fetchCompanies()
    })

    return {
      contracts,
      shops,
      companies,
      showCreateModal,
      showEditModal,
      form,
      loading,
      activeContracts,
      totalMonthlyIncome,
      totalOverdue,
      saveContract,
      editContract,
      deleteContract,
      viewContract,
      closeModal,
      getStatusClass,
      formatNumber,
      formatDate
    }
  }
}
</script>
