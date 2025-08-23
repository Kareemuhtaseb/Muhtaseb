<template>
  <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-900">Shops</h1>
      <button
        @click="showCreateModal = true"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Add Shop
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-blue-100 rounded-lg">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Shops</p>
            <p class="text-2xl font-semibold text-gray-900">{{ shops.length }}</p>
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
            <p class="text-sm font-medium text-gray-600">Occupied</p>
            <p class="text-2xl font-semibold text-gray-900">{{ occupiedShops }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="p-2 bg-yellow-100 rounded-lg">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Available</p>
            <p class="text-2xl font-semibold text-gray-900">{{ availableShops }}</p>
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

    <!-- Shops List -->
    <div class="bg-white rounded-lg shadow">
      <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-900">Shops</h2>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shop Number</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shop Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Complex</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monthly Rent</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="shop in shops" :key="shop.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ shop.shop_number }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ shop.shop_name }}</div>
                <div class="text-sm text-gray-500">{{ shop.location_description }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ shop.commercial_complex?.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getStatusClass(shop.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                  {{ shop.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ shop.current_company_name || 'N/A' }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">SAR {{ formatNumber(shop.current_monthly_rent) }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <button
                    @click="viewShop(shop)"
                    class="text-blue-600 hover:text-blue-900"
                  >
                    View
                  </button>
                  <button
                    @click="editShop(shop)"
                    class="text-indigo-600 hover:text-indigo-900"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteShop(shop.id)"
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
          {{ showEditModal ? 'Edit Shop' : 'Add Shop' }}
        </h3>
      </template>
      
      <template #body>
        <form @submit.prevent="saveShop" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Commercial Complex</label>
            <select
              v-model="form.commercial_complex_id"
              required
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">Select Complex</option>
              <option v-for="complex in complexes" :key="complex.id" :value="complex.id">
                {{ complex.name }}
              </option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700">Shop Number</label>
            <input
              v-model="form.shop_number"
              type="text"
              required
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700">Shop Name</label>
            <input
              v-model="form.shop_name"
              type="text"
              required
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700">Area (sq m)</label>
            <input
              v-model="form.area"
              type="number"
              step="0.01"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700">Location Description</label>
            <textarea
              v-model="form.location_description"
              rows="2"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            ></textarea>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select
              v-model="form.status"
              required
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="available">Available</option>
              <option value="occupied">Occupied</option>
              <option value="maintenance">Maintenance</option>
              <option value="reserved">Reserved</option>
            </select>
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
            @click="saveShop"
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
  name: 'Shops',
  components: {
    Modal
  },
  setup() {
    const router = useRouter()
    const shops = ref([])
    const complexes = ref([])
    const showCreateModal = ref(false)
    const showEditModal = ref(false)
    const editingShop = ref(null)
    const loading = ref(false)

    const form = ref({
      commercial_complex_id: '',
      shop_number: '',
      shop_name: '',
      area: '',
      location_description: '',
      status: 'available'
    })

    const occupiedShops = computed(() => {
      return shops.value.filter(shop => shop.status === 'occupied').length
    })

    const availableShops = computed(() => {
      return shops.value.filter(shop => shop.status === 'available').length
    })

    const totalMonthlyIncome = computed(() => {
      return shops.value.reduce((total, shop) => total + (shop.current_monthly_rent || 0), 0)
    })

    const fetchShops = async () => {
      try {
        loading.value = true
        const response = await fetch('/api/shops')
        const data = await response.json()
        if (data.success) {
          shops.value = data.data
        }
      } catch (error) {
        console.error('Error fetching shops:', error)
      } finally {
        loading.value = false
      }
    }

    const fetchComplexes = async () => {
      try {
        const response = await fetch('/api/commercial-complexes')
        const data = await response.json()
        if (data.success) {
          complexes.value = data.data
        }
      } catch (error) {
        console.error('Error fetching complexes:', error)
      }
    }

    const saveShop = async () => {
      try {
        const url = showEditModal.value 
          ? `/api/shops/${editingShop.value.id}`
          : '/api/shops'
        
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
          await fetchShops()
          closeModal()
        }
      } catch (error) {
        console.error('Error saving shop:', error)
      }
    }

    const editShop = (shop) => {
      editingShop.value = shop
      form.value = { ...shop }
      showEditModal.value = true
    }

    const deleteShop = async (id) => {
      if (confirm('Are you sure you want to delete this shop?')) {
        try {
          const response = await fetch(`/api/shops/${id}`, {
            method: 'DELETE'
          })
          const data = await response.json()
          if (data.success) {
            await fetchShops()
          }
        } catch (error) {
          console.error('Error deleting shop:', error)
        }
      }
    }

    const viewShop = (shop) => {
      router.push(`/shops/${shop.id}`)
    }

    const closeModal = () => {
      showCreateModal.value = false
      showEditModal.value = false
      editingShop.value = null
      form.value = {
        commercial_complex_id: '',
        shop_number: '',
        shop_name: '',
        area: '',
        location_description: '',
        status: 'available'
      }
    }

    const getStatusClass = (status) => {
      switch (status) {
        case 'occupied':
          return 'bg-green-100 text-green-800'
        case 'available':
          return 'bg-blue-100 text-blue-800'
        case 'maintenance':
          return 'bg-yellow-100 text-yellow-800'
        case 'reserved':
          return 'bg-purple-100 text-purple-800'
        default:
          return 'bg-gray-100 text-gray-800'
      }
    }

    const formatNumber = (number) => {
      return new Intl.NumberFormat('en-US').format(number || 0)
    }

    onMounted(() => {
      fetchShops()
      fetchComplexes()
    })

    return {
      shops,
      complexes,
      showCreateModal,
      showEditModal,
      form,
      loading,
      occupiedShops,
      availableShops,
      totalMonthlyIncome,
      saveShop,
      editShop,
      deleteShop,
      viewShop,
      closeModal,
      getStatusClass,
      formatNumber
    }
  }
}
</script>
