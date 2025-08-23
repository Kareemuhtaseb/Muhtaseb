<template>
  <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-900">Commercial Complexes</h1>
      <button
        @click="showCreateModal = true"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Add Complex
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-blue-100 rounded-lg">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Complexes</p>
            <p class="text-2xl font-semibold text-gray-900">{{ complexes.length }}</p>
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
            <p class="text-sm font-medium text-gray-600">Total Shops</p>
            <p class="text-2xl font-semibold text-gray-900">{{ totalShops }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-yellow-100 rounded-lg">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Companies</p>
            <p class="text-2xl font-semibold text-gray-900">{{ totalCompanies }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-purple-100 rounded-lg">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Monthly Income</p>
            <p class="text-2xl font-semibold text-gray-900">SAR {{ formatNumber(totalMonthlyIncome) }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Complexes List -->
    <div class="bg-white rounded-lg shadow">
      <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-900">Commercial Complexes</h2>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shops</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monthly Income</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="complex in complexes" :key="complex.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ complex.name }}</div>
                <div class="text-sm text-gray-500">{{ complex.description }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ complex.address }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ complex.shops_count }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">SAR {{ formatNumber(complex.total_monthly_income) }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ complex.contact_person }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <button
                    @click="viewComplex(complex)"
                    class="text-blue-600 hover:text-blue-900"
                  >
                    View
                  </button>
                  <button
                    @click="editComplex(complex)"
                    class="text-indigo-600 hover:text-indigo-900"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteComplex(complex.id)"
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
          {{ showEditModal ? 'Edit Commercial Complex' : 'Add Commercial Complex' }}
        </h3>
      </template>
      
      <template #body>
        <form @submit.prevent="saveComplex" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input
              v-model="form.name"
              type="text"
              required
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea
              v-model="form.description"
              rows="3"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            ></textarea>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700">Address</label>
            <input
              v-model="form.address"
              type="text"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            />
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
            @click="saveComplex"
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
  name: 'CommercialComplexes',
  components: {
    Modal
  },
  setup() {
    const router = useRouter()
    const complexes = ref([])
    const showCreateModal = ref(false)
    const showEditModal = ref(false)
    const editingComplex = ref(null)
    const loading = ref(false)

    const form = ref({
      name: '',
      description: '',
      address: '',
      contact_person: '',
      contact_phone: '',
      contact_email: ''
    })

    const totalShops = computed(() => {
      return complexes.value.reduce((total, complex) => total + (complex.shops_count || 0), 0)
    })

    const totalCompanies = computed(() => {
      return complexes.value.reduce((total, complex) => total + (complex.companies_count || 0), 0)
    })

    const totalMonthlyIncome = computed(() => {
      return complexes.value.reduce((total, complex) => total + (complex.total_monthly_income || 0), 0)
    })

    const fetchComplexes = async () => {
      try {
        loading.value = true
        const response = await fetch('/api/commercial-complexes')
        const data = await response.json()
        if (data.success) {
          complexes.value = data.data
        }
      } catch (error) {
        console.error('Error fetching complexes:', error)
      } finally {
        loading.value = false
      }
    }

    const saveComplex = async () => {
      try {
        const url = showEditModal.value 
          ? `/api/commercial-complexes/${editingComplex.value.id}`
          : '/api/commercial-complexes'
        
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
          await fetchComplexes()
          closeModal()
        }
      } catch (error) {
        console.error('Error saving complex:', error)
      }
    }

    const editComplex = (complex) => {
      editingComplex.value = complex
      form.value = { ...complex }
      showEditModal.value = true
    }

    const deleteComplex = async (id) => {
      if (confirm('Are you sure you want to delete this complex?')) {
        try {
          const response = await fetch(`/api/commercial-complexes/${id}`, {
            method: 'DELETE'
          })
          const data = await response.json()
          if (data.success) {
            await fetchComplexes()
          }
        } catch (error) {
          console.error('Error deleting complex:', error)
        }
      }
    }

    const viewComplex = (complex) => {
      router.push(`/commercial-complexes/${complex.id}`)
    }

    const closeModal = () => {
      showCreateModal.value = false
      showEditModal.value = false
      editingComplex.value = null
      form.value = {
        name: '',
        description: '',
        address: '',
        contact_person: '',
        contact_phone: '',
        contact_email: ''
      }
    }

    const formatNumber = (number) => {
      return new Intl.NumberFormat('en-US').format(number || 0)
    }

    onMounted(() => {
      fetchComplexes()
    })

    return {
      complexes,
      showCreateModal,
      showEditModal,
      form,
      loading,
      totalShops,
      totalCompanies,
      totalMonthlyIncome,
      saveComplex,
      editComplex,
      deleteComplex,
      viewComplex,
      closeModal,
      formatNumber
    }
  }
}
</script>
