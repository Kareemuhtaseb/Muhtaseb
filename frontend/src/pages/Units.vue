<template>
  <div>
    <!-- Header -->
    <div class="relative overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-r from-emerald-600/20 to-teal-600/20"></div>
      <div class="relative px-6 py-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-white mb-2">Units</h1>
            <p class="text-white/60">Manage property units and track occupancy rates</p>
          </div>
          <button
            @click="showCreateModal = true"
            class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl hover:from-emerald-600 hover:to-teal-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105"
          >
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Unit
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
              <p class="text-white/60 text-sm">Total Units</p>
              <p class="text-2xl font-bold text-white">{{ stats.total || 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5v4m8-4v4M8 11v4m8-4v4"></path>
              </svg>
            </div>
          </div>
        </div>

        <div class="glass-premium rounded-2xl p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-white/60 text-sm">Occupied Units</p>
              <p class="text-2xl font-bold text-white">{{ stats.occupied || 0 }}</p>
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
              <p class="text-white/60 text-sm">Available Units</p>
              <p class="text-2xl font-bold text-white">{{ stats.available || 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
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
            <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
          <div>
            <label class="block text-white/60 text-sm mb-2">Search</label>
            <input
              v-model="filters.unit_number"
              type="text"
              placeholder="Search units..."
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
              <option value="available">Available</option>
              <option value="occupied">Occupied</option>
              <option value="maintenance">Maintenance</option>
              <option value="reserved">Reserved</option>
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
            <label class="block text-white/60 text-sm mb-2">Type</label>
            <select
              v-model="filters.unit_type"
              class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
            >
              <option value="">All Types</option>
              <option value="apartment">Apartment</option>
              <option value="office">Office</option>
              <option value="shop">Shop</option>
              <option value="warehouse">Warehouse</option>
            </select>
          </div>
          <div class="flex items-end">
            <button
              @click="loadUnits"
              class="w-full px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-lg hover:from-emerald-600 hover:to-teal-600 transition-all duration-300"
            >
              Filter
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Units Grid -->
    <div class="px-6 mb-8">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="unit in units"
          :key="unit.id"
          class="glass-premium rounded-2xl p-6 hover:scale-105 transition-all duration-300 cursor-pointer"
          @click="viewUnit(unit)"
        >
          <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
              <h3 class="text-xl font-bold text-white mb-1">{{ unit.unit_number }}</h3>
              <p class="text-white/60 text-sm mb-2">{{ unit.property?.name || 'N/A' }}</p>
              <div class="flex items-center space-x-2">
                <span class="px-2 py-1 bg-emerald-500/20 text-emerald-400 text-xs rounded-full">{{ unit.unit_type }}</span>
                <span :class="getStatusClass(unit.status)" class="px-2 py-1 text-xs rounded-full">{{ unit.status }}</span>
              </div>
            </div>
            <div class="flex space-x-2">
              <button
                @click.stop="editUnit(unit)"
                class="p-2 bg-blue-500/20 text-blue-400 rounded-lg hover:bg-blue-500/30 transition-colors"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
              </button>
              <button
                @click.stop="deleteUnit(unit)"
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
              <span class="text-white/60">Area:</span>
              <span class="text-white">{{ unit.area || 0 }} sq ft</span>
            </div>
            <div class="flex justify-between text-sm" v-if="unit.contracts && unit.contracts.length">
              <span class="text-white/60">Current Tenant:</span>
              <span class="text-white">{{ unit.contracts.find(c => c.status === 'active')?.client_name || unit.contracts[0]?.client_name || 'N/A' }}</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-white/60">Bedrooms:</span>
              <span class="text-white">{{ unit.bedrooms || 0 }}</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-white/60">Bathrooms:</span>
              <span class="text-white">{{ unit.bathrooms || 0 }}</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-white/60">Monthly Rent:</span>
              <span class="text-emerald-400">${{ formatNumber(unit.monthly_rent || 0) }}</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-white/60">Expected Income:</span>
              <span class="text-emerald-400">${{ formatNumber(unit.expected_income || 0) }}</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-white/60">Actual Income:</span>
              <span class="text-blue-400">${{ formatNumber(unit.actual_income || 0) }}</span>
            </div>
          </div>

          <div class="mt-4 pt-4 border-t border-white/10">
            <div class="flex justify-between items-center">
              <span class="text-white/60 text-sm">Variance</span>
              <span :class="getVarianceClass(unit.variance_percentage)" class="text-sm font-semibold">
                {{ (unit.variance_percentage || 0).toFixed(1) }}%
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
          <h2 class="text-2xl font-bold text-white">{{ showEditModal ? 'Edit Unit' : 'Add New Unit' }}</h2>
          <button @click="closeModal" class="text-white/60 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <form @submit.prevent="saveUnit" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
              <label class="block text-white/60 text-sm mb-2">Unit Number *</label>
              <input
                v-model="form.unit_number"
                type="text"
                required
                class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-emerald-500"
              />
            </div>
            <div>
              <label class="block text-white/60 text-sm mb-2">Unit Type *</label>
              <select
                v-model="form.unit_type"
                required
                class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
              >
                <option value="">Select Type</option>
                <option value="apartment">Apartment</option>
                <option value="office">Office</option>
                <option value="shop">Shop</option>
                <option value="warehouse">Warehouse</option>
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
                <option value="available">Available</option>
                <option value="occupied">Occupied</option>
                <option value="maintenance">Maintenance</option>
                <option value="reserved">Reserved</option>
              </select>
            </div>
            <div>
              <label class="block text-white/60 text-sm mb-2">Area (sq ft)</label>
              <input
                v-model="form.area"
                type="number"
                step="0.01"
                class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-emerald-500"
              />
            </div>
            <div>
              <label class="block text-white/60 text-sm mb-2">Bedrooms</label>
              <input
                v-model="form.bedrooms"
                type="number"
                class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-emerald-500"
              />
            </div>
            <div>
              <label class="block text-white/60 text-sm mb-2">Bathrooms</label>
              <input
                v-model="form.bathrooms"
                type="number"
                class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-emerald-500"
              />
            </div>
            <div>
              <label class="block text-white/60 text-sm mb-2">Monthly Rent</label>
              <input
                v-model="form.monthly_rent"
                type="number"
                step="0.01"
                class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-emerald-500"
              />
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
            <label class="block text-white/60 text-sm mb-2">Amenities</label>
            <textarea
              v-model="form.amenities"
              rows="2"
              placeholder="Enter amenities separated by commas"
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
              {{ showEditModal ? 'Update Unit' : 'Create Unit' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Loading Overlay -->
    <div v-if="loading" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center">
      <div class="text-white text-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-emerald-500 mx-auto mb-4"></div>
        <p>Loading units...</p>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

export default {
  name: 'Units',
  setup() {
    const router = useRouter()
    const units = ref([])
    const properties = ref([])
    const stats = ref({})
    const loading = ref(false)
    const showCreateModal = ref(false)
    const showEditModal = ref(false)
    const editingUnit = ref(null)
    
    const filters = ref({
      unit_number: '',
      status: '',
      property_id: '',
      unit_type: ''
    })

    const form = ref({
      property_id: '',
      unit_number: '',
      unit_type: '',
      status: 'available',
      area: '',
      bedrooms: '',
      bathrooms: '',
      monthly_rent: '',
      description: '',
      amenities: '',
      notes: ''
    })

    const loadUnits = async () => {
      loading.value = true
      try {
        const params = {}
        if (filters.value.unit_number) params.unit_number = filters.value.unit_number
        if (filters.value.status) params.status = filters.value.status
        if (filters.value.property_id) params.property_id = filters.value.property_id
        if (filters.value.unit_type) params.unit_type = filters.value.unit_type

        const response = await axios.get('/units', { params })
        units.value = response.data.data.data || []
      } catch (error) {
        console.error('Error loading units:', error)
      } finally {
        loading.value = false
      }
    }

    const loadStats = async () => {
      try {
        // Calculate stats from units data
        const total = units.value.length
        const occupied = units.value.filter(u => u.status === 'occupied').length
        const available = units.value.filter(u => u.status === 'available').length
        const occupancyRate = total > 0 ? (occupied / total) * 100 : 0

        stats.value = {
          total,
          occupied,
          available,
          occupancy_rate: Math.round(occupancyRate)
        }
      } catch (error) {
        console.error('Error loading stats:', error)
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

    const saveUnit = async () => {
      try {
        if (showEditModal.value) {
          await axios.put(`/units/${editingUnit.value.id}`, form.value)
        } else {
          await axios.post('/units', form.value)
        }
        closeModal()
        loadUnits()
        loadStats()
      } catch (error) {
        console.error('Error saving unit:', error)
      }
    }

    const editUnit = (unit) => {
      editingUnit.value = unit
      form.value = { ...unit }
      showEditModal.value = true
    }

    const deleteUnit = async (unit) => {
      if (confirm('Are you sure you want to delete this unit?')) {
        try {
          await axios.delete(`/units/${unit.id}`)
          loadUnits()
          loadStats()
        } catch (error) {
          console.error('Error deleting unit:', error)
        }
      }
    }

    const viewUnit = (unit) => {
      // Navigate to unit detail view
      router.push(`/units/${unit.id}`)
    }

    const closeModal = () => {
      showCreateModal.value = false
      showEditModal.value = false
      editingUnit.value = null
      form.value = {
        property_id: '',
        unit_number: '',
        unit_type: '',
        status: 'available',
        area: '',
        bedrooms: '',
        bathrooms: '',
        monthly_rent: '',
        description: '',
        amenities: '',
        notes: ''
      }
    }

    const formatNumber = (num) => {
      return new Intl.NumberFormat().format(num)
    }

    const getStatusClass = (status) => {
      const classes = {
        available: 'bg-green-500/20 text-green-400',
        occupied: 'bg-blue-500/20 text-blue-400',
        maintenance: 'bg-orange-500/20 text-orange-400',
        reserved: 'bg-purple-500/20 text-purple-400'
      }
      return classes[status] || 'bg-gray-500/20 text-gray-400'
    }

    const getVarianceClass = (variance) => {
      if (variance > 0) return 'text-red-400'
      if (variance < 0) return 'text-emerald-400'
      return 'text-white'
    }

    onMounted(() => {
      loadUnits()
      loadProperties()
    })

    return {
      units,
      properties,
      stats,
      loading,
      filters,
      form,
      showCreateModal,
      showEditModal,
      loadUnits,
      loadStats,
      saveUnit,
      editUnit,
      deleteUnit,
      viewUnit,
      closeModal,
      formatNumber,
      getStatusClass,
      getVarianceClass
    }
  }
}
</script>
