<template>
  <div>
    <!-- Header -->
    <div class="relative overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-r from-emerald-600/20 to-teal-600/20"></div>
      <div class="relative px-6 py-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-white mb-2">Properties</h1>
            <p class="text-white/60">Manage your property portfolio and track financial performance</p>
          </div>
          <button
            @click="showCreateModal = true"
            class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl hover:from-emerald-600 hover:to-teal-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105"
          >
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Property
          </button>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="px-6 -mt-4 mb-8">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="glass-premium rounded-2xl p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-white/60 text-sm">Total Properties</p>
              <p class="text-2xl font-bold text-white">{{ stats.total || 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
              </svg>
            </div>
          </div>
        </div>

        <div class="glass-premium rounded-2xl p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-white/60 text-sm">Active Properties</p>
              <p class="text-2xl font-bold text-white">{{ stats.active || 0 }}</p>
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
              <p class="text-white/60 text-sm">Total Units</p>
              <p class="text-2xl font-bold text-white">{{ stats.total_units || 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5v4m8-4v4M8 11v4m8-4v4"></path>
              </svg>
            </div>
          </div>
        </div>

        <div class="glass-premium rounded-2xl p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-white/60 text-sm">Occupancy Rate</p>
              <p class="text-2xl font-bold text-white">{{ stats.occupancy_rate || 0 }}%</p>
            </div>
            <div class="w-12 h-12 bg-orange-500/20 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filters and Search -->
    <div class="px-6 mb-6">
      <div class="glass-premium rounded-2xl p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-white/60 text-sm mb-2">Search</label>
            <input
              v-model="filters.name"
              type="text"
              placeholder="Search properties..."
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
              <option value="inactive">Inactive</option>
              <option value="sold">Sold</option>
              <option value="under_construction">Under Construction</option>
            </select>
          </div>
          <div>
            <label class="block text-white/60 text-sm mb-2">Type</label>
            <select
              v-model="filters.property_type"
              class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
            >
              <option value="">All Types</option>
              <option value="residential">Residential</option>
              <option value="commercial">Commercial</option>
              <option value="industrial">Industrial</option>
              <option value="mixed">Mixed</option>
            </select>
          </div>
          <div class="flex items-end">
            <button
              @click="loadProperties"
              class="w-full px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-lg hover:from-emerald-600 hover:to-teal-600 transition-all duration-300"
            >
              Filter
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Properties Grid -->
    <div class="px-6 mb-8">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="property in properties"
          :key="property.id"
          class="glass-premium rounded-2xl p-6 hover:scale-105 transition-all duration-300 cursor-pointer"
          @click="viewProperty(property)"
        >
          <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
              <h3 class="text-xl font-bold text-white mb-1">{{ property.name }}</h3>
              <p class="text-white/60 text-sm mb-2">{{ property.address }}</p>
              <div class="flex items-center space-x-2">
                <span class="px-2 py-1 bg-emerald-500/20 text-emerald-400 text-xs rounded-full">{{ property.property_type }}</span>
                <span :class="getStatusClass(property.status)" class="px-2 py-1 text-xs rounded-full">{{ property.status }}</span>
              </div>
            </div>
            <div class="flex space-x-2">
              <button
                @click.stop="editProperty(property)"
                class="p-2 bg-blue-500/20 text-blue-400 rounded-lg hover:bg-blue-500/30 transition-colors"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
              </button>
              <button
                @click.stop="deleteProperty(property)"
                class="p-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 transition-colors"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
              </button>
            </div>
          </div>

          <div class="space-y-3">
            <div class="flex justify-between text-sm">
              <span class="text-white/60">Units:</span>
              <span class="text-white">{{ property.units_count || 0 }}</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-white/60">Purchase Price:</span>
              <span class="text-white">${{ formatNumber(property.purchase_price || 0) }}</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-white/60">Current Value:</span>
              <span class="text-white">${{ formatNumber(property.current_value || 0) }}</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-white/60">Total Income:</span>
              <span class="text-emerald-400">${{ formatNumber(property.total_income || 0) }}</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-white/60">ROI:</span>
              <span :class="property.roi >= 0 ? 'text-emerald-400' : 'text-red-400'">{{ property.roi || 0 }}%</span>
            </div>
          </div>

          <div class="mt-4 pt-4 border-t border-white/10">
            <div class="flex justify-between items-center">
              <span class="text-white/60 text-sm">Variance</span>
              <span :class="getVarianceClass(property.variance_percentage)" class="text-sm font-semibold">
                {{ property.variance_percentage || 0 }}%
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <div v-if="showCreateModal || showEditModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
      <div class="glass-premium rounded-2xl p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-white">{{ showEditModal ? 'Edit Property' : 'Add New Property' }}</h2>
          <button @click="closeModal" class="text-white/60 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <form @submit.prevent="saveProperty" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-white/60 text-sm mb-2">Property Name *</label>
              <input
                v-model="form.name"
                type="text"
                required
                class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-emerald-500"
              />
            </div>
            <div>
              <label class="block text-white/60 text-sm mb-2">Property Type *</label>
              <select
                v-model="form.property_type"
                required
                class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
              >
                <option value="">Select Type</option>
                <option value="residential">Residential</option>
                <option value="commercial">Commercial</option>
                <option value="industrial">Industrial</option>
                <option value="mixed">Mixed</option>
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
                <option value="inactive">Inactive</option>
                <option value="sold">Sold</option>
                <option value="under_construction">Under Construction</option>
              </select>
            </div>
            <div>
              <label class="block text-white/60 text-sm mb-2">Purchase Date</label>
              <input
                v-model="form.purchase_date"
                type="date"
                class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
              />
            </div>
            <div>
              <label class="block text-white/60 text-sm mb-2">Purchase Price</label>
              <input
                v-model="form.purchase_price"
                type="number"
                step="0.01"
                class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-emerald-500"
              />
            </div>
            <div>
              <label class="block text-white/60 text-sm mb-2">Current Value</label>
              <input
                v-model="form.current_value"
                type="number"
                step="0.01"
                class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-emerald-500"
              />
            </div>
            <div>
              <label class="block text-white/60 text-sm mb-2">Total Area (sq ft)</label>
              <input
                v-model="form.total_area"
                type="number"
                step="0.01"
                class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-emerald-500"
              />
            </div>
            <div>
              <label class="block text-white/60 text-sm mb-2">Units Count</label>
              <input
                v-model="form.units_count"
                type="number"
                class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-emerald-500"
              />
            </div>
          </div>
          <div>
            <label class="block text-white/60 text-sm mb-2">Address *</label>
            <textarea
              v-model="form.address"
              required
              rows="3"
              class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-emerald-500"
            ></textarea>
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
              {{ showEditModal ? 'Update Property' : 'Create Property' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Loading Overlay -->
    <div v-if="loading" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center">
      <div class="text-white text-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-emerald-500 mx-auto mb-4"></div>
        <p>Loading properties...</p>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

export default {
  name: 'Properties',
  setup() {
    const router = useRouter()
    const properties = ref([])
    const stats = ref({})
    const loading = ref(false)
    const showCreateModal = ref(false)
    const showEditModal = ref(false)
    const editingProperty = ref(null)
    
    const filters = ref({
      name: '',
      status: '',
      property_type: ''
    })

    const form = ref({
      name: '',
      address: '',
      property_type: '',
      status: 'active',
      purchase_date: '',
      purchase_price: '',
      current_value: '',
      total_area: '',
      units_count: '',
      description: '',
      notes: ''
    })

    const loadProperties = async () => {
      loading.value = true
      try {
        const params = {}
        if (filters.value.name) params.name = filters.value.name
        if (filters.value.status) params.status = filters.value.status
        if (filters.value.property_type) params.property_type = filters.value.property_type

        const response = await axios.get('/properties', { params })
        properties.value = response.data.data.data || []
      } catch (error) {
        console.error('Error loading properties:', error)
      } finally {
        loading.value = false
      }
    }

    const loadStats = async () => {
      try {
        const response = await axios.get('/properties/financial-summary')
        stats.value = response.data.data
      } catch (error) {
        console.error('Error loading stats:', error)
      }
    }

    const saveProperty = async () => {
      try {
        if (showEditModal.value) {
          await axios.put(`/properties/${editingProperty.value.id}`, form.value)
        } else {
          await axios.post('/properties', form.value)
        }
        closeModal()
        loadProperties()
        loadStats()
      } catch (error) {
        console.error('Error saving property:', error)
      }
    }

    const editProperty = (property) => {
      editingProperty.value = property
      form.value = { ...property }
      showEditModal.value = true
    }

    const deleteProperty = async (property) => {
      if (confirm('Are you sure you want to delete this property?')) {
        try {
          await axios.delete(`/properties/${property.id}`)
          loadProperties()
          loadStats()
        } catch (error) {
          console.error('Error deleting property:', error)
        }
      }
    }

    const viewProperty = (property) => {
      // Navigate to property detail view
      router.push(`/properties/${property.id}`)
    }

    const closeModal = () => {
      showCreateModal.value = false
      showEditModal.value = false
      editingProperty.value = null
      form.value = {
        name: '',
        address: '',
        property_type: '',
        status: 'active',
        purchase_date: '',
        purchase_price: '',
        current_value: '',
        total_area: '',
        units_count: '',
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
        inactive: 'bg-gray-500/20 text-gray-400',
        sold: 'bg-blue-500/20 text-blue-400',
        under_construction: 'bg-orange-500/20 text-orange-400'
      }
      return classes[status] || 'bg-gray-500/20 text-gray-400'
    }

    const getVarianceClass = (variance) => {
      if (variance > 0) return 'text-red-400'
      if (variance < 0) return 'text-emerald-400'
      return 'text-white'
    }

    onMounted(() => {
      loadProperties()
      loadStats()
    })

    return {
      properties,
      stats,
      loading,
      filters,
      form,
      showCreateModal,
      showEditModal,
      loadProperties,
      saveProperty,
      editProperty,
      deleteProperty,
      viewProperty,
      closeModal,
      formatNumber,
      getStatusClass,
      getVarianceClass
    }
  }
}
</script>
