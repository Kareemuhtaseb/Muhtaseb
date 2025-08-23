<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

const units = ref([])
const properties = ref([])
const loading = ref(false)
const submitting = ref(false)
const showAddModal = ref(false)
const showEditModal = ref(false)
const editingUnit = ref(null)

const form = ref({
  property_id: '',
  unit_number: '',
  shop_name: '',
  shop_number: '',
  company_name: '',
  monthly_rent_expected: '',
  status: 'available',
  notes: ''
})

// Computed properties for statistics
const stats = computed(() => {
  const total = units.value.length
  const available = units.value.filter(u => u.status === 'available').length
  const occupied = units.value.filter(u => u.status === 'occupied').length
  const maintenance = units.value.filter(u => u.status === 'maintenance').length
  
  return { total, available, occupied, maintenance }
})

async function loadData(){
  try {
    loading.value = true
    const [unitsResponse, propertiesResponse] = await Promise.all([
      axios.get('/units.php'),
      axios.get('/properties.php')
    ])
    units.value = unitsResponse.data
    properties.value = propertiesResponse.data
  } catch (error) {
    console.error('Error loading data:', error)
  } finally {
    loading.value = false
  }
}

onMounted(loadData)

function editUnit(unit) {
  editingUnit.value = unit
  form.value = {
    property_id: unit.property_id,
    unit_number: unit.unit_number,
    shop_name: unit.shop_name || '',
    shop_number: unit.shop_number || '',
    company_name: unit.company_name || '',
    monthly_rent_expected: unit.monthly_rent_expected,
    status: unit.status,
    notes: unit.notes || ''
  }
  showEditModal.value = true
}

async function addUnit() {
  try {
    submitting.value = true
    await axios.post('/units.php', form.value)
    closeModal()
    await loadData()
  } catch (error) {
    console.error('Error adding unit:', error)
    alert('Failed to add unit')
  } finally {
    submitting.value = false
  }
}

async function updateUnit() {
  try {
    submitting.value = true
    await axios.put(`/units.php?id=${editingUnit.value.id}`, form.value)
    closeModal()
    await loadData()
  } catch (error) {
    console.error('Error updating unit:', error)
    alert('Failed to update unit')
  } finally {
    submitting.value = false
  }
}

async function deleteUnit(id) {
  if (!confirm('Are you sure you want to delete this unit?')) {
    return
  }
  
  try {
    await axios.delete(`/units.php?id=${id}`)
    await loadData()
  } catch (error) {
    console.error('Error deleting unit:', error)
    alert('Failed to delete unit')
  }
}

function closeModal() {
  showAddModal.value = false
  showEditModal.value = false
  editingUnit.value = null
  resetForm()
}

function resetForm() {
  form.value = {
    property_id: '',
    unit_number: '',
    shop_name: '',
    shop_number: '',
    company_name: '',
    monthly_rent_expected: '',
    status: 'available',
    notes: ''
  }
}

function formatNumber(num) {
  return new Intl.NumberFormat().format(num || 0)
}
</script>

<template>
  <div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-white">Commercial Units</h1>
        <p class="text-white/60 mt-2">Manage commercial units and shops</p>
      </div>
      <button 
        @click="showAddModal = true"
        class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 hover-lift flex items-center space-x-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        <span>Add Unit</span>
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Total Units</p>
            <p class="text-3xl font-bold text-white mt-2">{{ stats.total }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Available</p>
            <p class="text-3xl font-bold text-white mt-2">{{ stats.available }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Occupied</p>
            <p class="text-3xl font-bold text-white mt-2">{{ stats.occupied }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Maintenance</p>
            <p class="text-3xl font-bold text-white mt-2">{{ stats.maintenance }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center h-64">
      <div class="text-center">
        <div class="w-16 h-16 border-4 border-white/20 border-t-white rounded-full animate-spin mx-auto mb-4"></div>
        <p class="text-white/60">Loading units...</p>
      </div>
    </div>

    <!-- Units Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div 
        v-for="unit in units" 
        :key="unit.id"
        class="glass rounded-2xl p-6 hover-lift transition-all duration-200"
      >
        <!-- Unit Header -->
        <div class="flex items-start justify-between mb-4">
          <div>
            <h3 class="text-xl font-bold text-white">{{ unit.shop_name || unit.company_name || `Unit ${unit.unit_number}` }}</h3>
            <p class="text-white/60 text-sm">{{ unit.property_name }}</p>
          </div>
          <div class="flex space-x-2">
            <button 
              @click="editUnit(unit)"
              class="p-2 text-white/60 hover:text-white hover:bg-white/10 rounded-lg transition-colors"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
              </svg>
            </button>
            <button 
              @click="deleteUnit(unit.id)"
              class="p-2 text-red-400 hover:text-red-300 hover:bg-red-400/10 rounded-lg transition-colors"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
              </svg>
            </button>
          </div>
        </div>

        <!-- Status Badge -->
        <div class="mb-4">
          <span 
            :class="{
              'bg-green-400/20 text-green-400': unit.status === 'available',
              'bg-purple-400/20 text-purple-400': unit.status === 'occupied',
              'bg-red-400/20 text-red-400': unit.status === 'maintenance'
            }"
            class="px-3 py-1 rounded-full text-xs font-medium"
          >
            {{ unit.status.charAt(0).toUpperCase() + unit.status.slice(1) }}
          </span>
        </div>

        <!-- Unit Details -->
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-white/60 text-sm">Unit Number:</span>
            <span class="text-white font-medium">{{ unit.unit_number }}</span>
          </div>
          <div v-if="unit.shop_number" class="flex justify-between">
            <span class="text-white/60 text-sm">Shop Number:</span>
            <span class="text-white font-medium">{{ unit.shop_number }}</span>
          </div>
          <div v-if="unit.company_name" class="flex justify-between">
            <span class="text-white/60 text-sm">Company:</span>
            <span class="text-white font-medium">{{ unit.company_name }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-white/60 text-sm">Monthly Rent:</span>
            <span class="text-green-400 font-semibold">${{ formatNumber(unit.monthly_rent_expected) }}</span>
          </div>
        </div>

        <!-- Notes -->
        <div v-if="unit.notes" class="mt-4 pt-4 border-t border-white/10">
          <p class="text-white/60 text-sm">{{ unit.notes }}</p>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="!loading && units.length === 0" class="text-center py-16">
      <div class="w-24 h-24 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-12 h-12 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
      </div>
      <h3 class="text-xl font-bold text-white mb-2">No units found</h3>
      <p class="text-white/60 mb-6">Get started by adding your first commercial unit</p>
      <button 
        @click="showAddModal = true"
        class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 hover-lift"
      >
        Add First Unit
      </button>
    </div>

    <!-- Add/Edit Unit Modal -->
    <div v-if="showAddModal || showEditModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
      <div class="glass rounded-2xl p-8 w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-white">
            {{ showEditModal ? 'Edit Unit' : 'Add New Unit' }}
          </h2>
          <button 
            @click="closeModal"
            class="p-2 text-white/60 hover:text-white hover:bg-white/10 rounded-lg transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <form @submit.prevent="showEditModal ? updateUnit() : addUnit()" class="space-y-6">
          <!-- Property Selection -->
          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Property *</label>
            <select 
              v-model="form.property_id" 
              required
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
              <option value="" disabled>Select a property</option>
              <option v-for="property in properties" :key="property.id" :value="property.id">
                {{ property.name }}
              </option>
            </select>
          </div>

          <!-- Unit Number -->
          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Unit Number *</label>
            <input 
              v-model="form.unit_number" 
              type="text" 
              required
              placeholder="e.g., A1, B2, Shop 1"
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
          </div>

          <!-- Shop Name -->
          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Shop Name</label>
            <input 
              v-model="form.shop_name" 
              type="text" 
              placeholder="e.g., Coffee Shop, Electronics Store"
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
          </div>

          <!-- Shop Number -->
          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Shop Number</label>
            <input 
              v-model="form.shop_number" 
              type="text" 
              placeholder="e.g., 101, 202"
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
          </div>

          <!-- Company Name -->
          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Company Name</label>
            <input 
              v-model="form.company_name" 
              type="text" 
              placeholder="e.g., ABC Corporation Ltd."
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
          </div>

          <!-- Monthly Rent -->
          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Monthly Rent Expected *</label>
            <input 
              v-model="form.monthly_rent_expected" 
              type="number" 
              step="0.01"
              required
              placeholder="0.00"
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
          </div>

          <!-- Status -->
          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Status</label>
            <select 
              v-model="form.status" 
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
              <option value="available">Available</option>
              <option value="occupied">Occupied</option>
              <option value="maintenance">Maintenance</option>
            </select>
          </div>

          <!-- Notes -->
          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Notes</label>
            <textarea 
              v-model="form.notes" 
              rows="3"
              placeholder="Additional notes about this unit..."
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
            ></textarea>
          </div>

          <!-- Form Actions -->
          <div class="flex space-x-4 pt-4">
            <button 
              type="button"
              @click="closeModal"
              class="flex-1 bg-white/10 hover:bg-white/20 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200"
            >
              Cancel
            </button>
            <button 
              type="submit"
              :disabled="submitting"
              class="flex-1 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="submitting">Saving...</span>
              <span v-else>{{ showEditModal ? 'Update Unit' : 'Add Unit' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
