<template>
  <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-900">Companies</h1>
      <button
        @click="showCreateModal = true"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Add Company
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-blue-100 rounded-lg">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Companies</p>
            <p class="text-2xl font-semibold text-gray-900">{{ companies.length }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-green-100 rounded-lg">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Active Contracts</p>
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
          <div class="p-2 bg-purple-100 rounded-lg">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Yearly Income</p>
            <p class="text-2xl font-semibold text-gray-900">SAR {{ formatNumber(totalYearlyIncome) }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Companies List -->
    <div class="bg-white rounded-lg shadow">
      <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-900">Companies</h2>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Business Type</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Active Contracts</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monthly Rent</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="company in companies" :key="company.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ company.name }}</div>
                <div class="text-sm text-gray-500">{{ company.business_number }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ company.business_type }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ company.contact_person }}</div>
                <div class="text-sm text-gray-500">{{ company.contact_phone }}</div>
                <div class="text-sm text-gray-500">{{ company.contact_email }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ company.active_contracts_count || 0 }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">SAR {{ formatNumber(company.total_monthly_rent) }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <button
                    @click="viewCompany(company)"
                    class="text-blue-600 hover:text-blue-900"
                  >
                    View
                  </button>
                  <button
                    @click="editCompany(company)"
                    class="text-indigo-600 hover:text-indigo-900"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteCompany(company.id)"
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
          {{ showEditModal ? 'Edit Company' : 'Add Company' }}
        </h3>
      </template>
      
      <template #body>
        <form @submit.prevent="saveCompany" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Company Name</label>
            <input
              v-model="form.name"
              type="text"
              required
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700">Business Number</label>
            <input
              v-model="form.business_number"
              type="text"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700">Business Type</label>
            <select
              v-model="form.business_type"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">Select Type</option>
              <option value="Restaurant">Restaurant</option>
              <option value="Retail">Retail</option>
              <option value="Office">Office</option>
              <option value="Service">Service</option>
              <option value="Other">Other</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700">Contact Person</label>
            <input
              v-model="form.contact_person"
              type="text"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700">Contact Phone</label>
            <input
              v-model="form.contact_phone"
              type="text"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700">Contact Email</label>
            <input
              v-model="form.contact_email"
              type="email"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700">Address</label>
            <textarea
              v-model="form.address"
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
            @click="saveCompany"
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
  name: 'Companies',
  components: {
    Modal
  },
  setup() {
    const router = useRouter()
    const companies = ref([])
    const showCreateModal = ref(false)
    const showEditModal = ref(false)
    const editingCompany = ref(null)
    const loading = ref(false)

    const form = ref({
      name: '',
      business_number: '',
      business_type: '',
      contact_person: '',
      contact_phone: '',
      contact_email: '',
      address: ''
    })

    const activeContracts = computed(() => {
      return companies.value.reduce((total, company) => total + (company.active_contracts_count || 0), 0)
    })

    const totalMonthlyIncome = computed(() => {
      return companies.value.reduce((total, company) => total + (company.total_monthly_rent || 0), 0)
    })

    const totalYearlyIncome = computed(() => {
      return companies.value.reduce((total, company) => total + (company.total_yearly_rent || 0), 0)
    })

    const fetchCompanies = async () => {
      try {
        loading.value = true
        const response = await fetch('/api/companies')
        const data = await response.json()
        if (data.success) {
          companies.value = data.data
        }
      } catch (error) {
        console.error('Error fetching companies:', error)
      } finally {
        loading.value = false
      }
    }

    const saveCompany = async () => {
      try {
        const url = showEditModal.value 
          ? `/api/companies/${editingCompany.value.id}`
          : '/api/companies'
        
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
          await fetchCompanies()
          closeModal()
        }
      } catch (error) {
        console.error('Error saving company:', error)
      }
    }

    const editCompany = (company) => {
      editingCompany.value = company
      form.value = { ...company }
      showEditModal.value = true
    }

    const deleteCompany = async (id) => {
      if (confirm('Are you sure you want to delete this company?')) {
        try {
          const response = await fetch(`/api/companies/${id}`, {
            method: 'DELETE'
          })
          const data = await response.json()
          if (data.success) {
            await fetchCompanies()
          }
        } catch (error) {
          console.error('Error deleting company:', error)
        }
      }
    }

    const viewCompany = (company) => {
      router.push(`/companies/${company.id}`)
    }

    const closeModal = () => {
      showCreateModal.value = false
      showEditModal.value = false
      editingCompany.value = null
      form.value = {
        name: '',
        business_number: '',
        business_type: '',
        contact_person: '',
        contact_phone: '',
        contact_email: '',
        address: ''
      }
    }

    const formatNumber = (number) => {
      return new Intl.NumberFormat('en-US').format(number || 0)
    }

    onMounted(() => {
      fetchCompanies()
    })

    return {
      companies,
      showCreateModal,
      showEditModal,
      form,
      loading,
      activeContracts,
      totalMonthlyIncome,
      totalYearlyIncome,
      saveCompany,
      editCompany,
      deleteCompany,
      viewCompany,
      closeModal,
      formatNumber
    }
  }
}
</script>
