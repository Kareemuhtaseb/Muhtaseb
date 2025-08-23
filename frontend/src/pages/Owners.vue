<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

const owners = ref([])
const distributions = ref([])
const calc = ref([])
const loading = ref(false)
const saving = ref(false)
const showAddOwnerModal = ref(false)
const showEditOwnerModal = ref(false)
const showAddDistributionModal = ref(false)
const showEditDistributionModal = ref(false)
const selectedOwner = ref(null)
const selectedDistribution = ref(null)

const ownerForm = ref({
  name: '',
  share_percentage: '',
  email: '',
  phone: ''
})

const distForm = ref({
  owner_id: '',
  date: '',
  amount: '',
  notes: ''
})

// Computed properties for statistics
const totalDistributed = computed(() => {
  return distributions.value.reduce((sum, dist) => sum + parseFloat(dist.amount || 0), 0)
})

const currentMonthDistributed = computed(() => {
  const currentMonth = new Date().getMonth()
  const currentYear = new Date().getFullYear()
  return distributions.value
    .filter(dist => {
      const distDate = new Date(dist.date)
      return distDate.getMonth() === currentMonth && distDate.getFullYear() === currentYear
    })
    .reduce((sum, dist) => sum + parseFloat(dist.amount || 0), 0)
})

async function load(){
  try {
    loading.value = true
    const [ownersResponse, distributionsResponse] = await Promise.all([
      axios.get('/owners.php'),
      axios.get('/distributions.php')
    ])
    owners.value = ownersResponse.data
    distributions.value = distributionsResponse.data
  } catch (error) {
    console.error('Error loading data:', error)
  } finally {
    loading.value = false
  }
}

onMounted(load)

function editOwner(owner) {
  selectedOwner.value = owner
  ownerForm.value = { 
    name: owner.name,
    share_percentage: owner.share_percentage,
    email: owner.email || '',
    phone: owner.phone || ''
  }
  showEditOwnerModal.value = true
}

function editDistribution(distribution) {
  selectedDistribution.value = distribution
  distForm.value = { 
    owner_id: distribution.owner_id,
    date: distribution.date,
    amount: distribution.amount,
    notes: distribution.notes || ''
  }
  showEditDistributionModal.value = true
}

async function submitOwner() {
  try {
    saving.value = true
    if (showEditOwnerModal.value) {
      await axios.put(`/owners.php?id=${selectedOwner.value.id}`, ownerForm.value)
    } else {
      await axios.post('/owners.php', ownerForm.value)
    }
    await load()
    closeOwnerModal()
  } catch (error) {
    console.error('Error saving owner:', error)
    alert('Failed to save owner')
  } finally {
    saving.value = false
  }
}

async function submitDistribution() {
  try {
    saving.value = true
    if (showEditDistributionModal.value) {
      await axios.put(`/distributions.php?id=${selectedDistribution.value.id}`, distForm.value)
    } else {
      await axios.post('/distributions.php', distForm.value)
    }
    await load()
    closeDistributionModal()
  } catch (error) {
    console.error('Error saving distribution:', error)
    alert('Failed to save distribution')
  } finally {
    saving.value = false
  }
}

function closeOwnerModal() {
  showAddOwnerModal.value = false
  showEditOwnerModal.value = false
  selectedOwner.value = null
  ownerForm.value = {
    name: '',
    share_percentage: '',
    email: '',
    phone: ''
  }
}

function closeDistributionModal() {
  showAddDistributionModal.value = false
  showEditDistributionModal.value = false
  selectedDistribution.value = null
  distForm.value = {
    owner_id: '',
    date: '',
    amount: '',
    notes: ''
  }
}

async function removeOwner(id){
  if (!confirm('Are you sure you want to delete this owner?')) {
    return
  }
  
  try {
    await axios.delete(`/owners.php?id=${id}`)
    await load()
  } catch (error) {
    console.error('Error removing owner:', error)
    alert('Failed to delete owner')
  }
}

async function removeDistribution(id){
  if (!confirm('Are you sure you want to delete this distribution?')) {
    return
  }
  
  try {
    await axios.delete(`/distributions.php?id=${id}`)
    await load()
  } catch (error) {
    console.error('Error removing distribution:', error)
    alert('Failed to delete distribution')
  }
}

async function calculate(){
  try {
    calc.value = (await axios.get('/distributions_calculate.php')).data
  } catch (error) {
    console.error('Error calculating distributions:', error)
  }
}

function formatNumber(num) {
  return new Intl.NumberFormat().format(num || 0)
}

function formatDate(date) {
  return new Date(date).toLocaleDateString()
}
</script>

<template>
  <div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-white">Owners & Distributions</h1>
        <p class="text-white/60 mt-2">Manage property owners and profit distributions</p>
      </div>
      <div class="flex space-x-4">
        <button 
          @click="showAddOwnerModal = true"
          class="bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 hover-lift flex items-center space-x-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          <span>Add Owner</span>
        </button>
        <button 
          @click="showAddDistributionModal = true"
          class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 hover-lift flex items-center space-x-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
          </svg>
          <span>Add Distribution</span>
        </button>
        <button 
          @click="calculate"
          class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 hover-lift flex items-center space-x-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
          </svg>
          <span>Calculate</span>
        </button>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Total Owners</p>
            <p class="text-3xl font-bold text-white mt-2">{{ owners.length }}</p>
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
            <p class="text-white/60 text-sm font-medium">Total Distributions</p>
            <p class="text-3xl font-bold text-white mt-2">{{ distributions.length }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Total Distributed</p>
            <p class="text-3xl font-bold text-white mt-2">${{ formatNumber(totalDistributed) }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">This Month</p>
            <p class="text-3xl font-bold text-white mt-2">${{ formatNumber(currentMonthDistributed) }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Average Share %</p>
            <p class="text-2xl font-bold text-white mt-2">
              {{ owners.length > 0 ? (owners.reduce((sum, owner) => sum + parseFloat(owner.share_percentage || 0), 0) / owners.length).toFixed(1) : 0 }}%
            </p>
          </div>
          <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Total Share %</p>
            <p class="text-2xl font-bold text-white mt-2">
              {{ owners.reduce((sum, owner) => sum + parseFloat(owner.share_percentage || 0), 0).toFixed(1) }}%
            </p>
          </div>
          <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Active Owners</p>
            <p class="text-2xl font-bold text-white mt-2">
              {{ owners.filter(owner => parseFloat(owner.share_percentage || 0) > 0).length }}
            </p>
          </div>
          <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center h-64">
      <div class="text-center">
        <div class="w-16 h-16 border-4 border-white/20 border-t-white rounded-full animate-spin mx-auto mb-4"></div>
        <p class="text-white/60">Loading owners and distributions...</p>
      </div>
    </div>

    <!-- Calculation Results -->
    <div v-if="calc.length > 0" class="glass rounded-2xl p-6 mb-8">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-white">Calculated Distributions</h2>
        <button 
          @click="calc = []"
          class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center text-white/60 hover:text-white transition-all duration-200"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div v-for="calculation in calc" :key="calculation.owner_id" class="bg-white/5 rounded-xl p-4">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500/20 to-indigo-500/20 rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
            </div>
            <div>
              <h3 class="text-white font-semibold">{{ calculation.name }}</h3>
              <p class="text-blue-400 font-bold">${{ formatNumber(calculation.amount) }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Content -->
    <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Owners Section -->
      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-bold text-white">Property Owners</h2>
          <button 
            @click="showAddOwnerModal = true"
            class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center text-white/60 hover:text-white transition-all duration-200"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
          </button>
        </div>

        <div class="space-y-4">
          <div v-for="owner in owners" :key="owner.id" class="bg-white/5 rounded-xl p-4 hover:bg-white/10 transition-all duration-300">
            <div class="flex items-center justify-between">
              <div class="flex-1">
                <div class="flex items-center space-x-3 mb-2">
                  <div class="w-10 h-10 bg-gradient-to-br from-purple-500/20 to-indigo-500/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-white font-semibold text-lg">{{ owner.name }}</h3>
                    <div class="flex items-center space-x-4 mt-1">
                      <span class="text-purple-400 font-medium text-sm">{{ owner.share_percentage }}% share</span>
                      <span class="text-white/40 text-xs">•</span>
                      <span class="text-white/60 text-sm">{{ owner.email || 'No email' }}</span>
                    </div>
                  </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mt-3">
                  <div class="bg-white/5 rounded-lg p-3">
                    <p class="text-white/60 text-xs font-medium">Phone</p>
                    <p class="text-white text-sm">{{ owner.phone || 'Not provided' }}</p>
                  </div>
                  <div class="bg-white/5 rounded-lg p-3">
                    <p class="text-white/60 text-xs font-medium">Status</p>
                    <div class="flex items-center space-x-1 mt-1">
                      <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                      <span class="text-green-400 text-xs font-medium">Active</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="flex flex-col space-y-2 ml-4">
                <button 
                  @click="editOwner(owner)"
                  class="w-8 h-8 bg-blue-500/10 hover:bg-blue-500/20 rounded-lg flex items-center justify-center text-blue-400 hover:text-blue-300 transition-all duration-200"
                  title="Edit Owner"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                  </svg>
                </button>
                <button 
                  @click="removeOwner(owner.id)"
                  class="w-8 h-8 bg-red-500/10 hover:bg-red-500/20 rounded-lg flex items-center justify-center text-red-400 hover:text-red-300 transition-all duration-200"
                  title="Delete Owner"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <div v-if="owners.length === 0" class="text-center py-8">
            <p class="text-white/60">No owners found</p>
          </div>
        </div>
      </div>

      <!-- Distributions Section -->
      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-bold text-white">Distributions</h2>
          <button 
            @click="showAddDistributionModal = true"
            class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center text-white/60 hover:text-white transition-all duration-200"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
          </button>
        </div>

        <div class="space-y-4">
          <div v-for="distribution in distributions" :key="distribution.id" class="bg-white/5 rounded-xl p-4 hover:bg-white/10 transition-all duration-300">
            <div class="flex items-center justify-between">
              <div class="flex-1">
                <div class="flex items-center space-x-3 mb-2">
                  <div class="w-10 h-10 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-white font-semibold text-lg">{{ distribution.owner_name }}</h3>
                    <div class="flex items-center space-x-4 mt-1">
                      <span class="text-green-400 font-bold text-lg">${{ formatNumber(distribution.amount) }}</span>
                      <span class="text-white/40 text-xs">•</span>
                      <span class="text-white/60 text-sm">{{ formatDate(distribution.date) }}</span>
                    </div>
                  </div>
                </div>
                
                <div v-if="distribution.notes" class="mt-3 bg-white/5 rounded-lg p-3">
                  <p class="text-white/60 text-xs font-medium">Notes</p>
                  <p class="text-white text-sm">{{ distribution.notes }}</p>
                </div>
              </div>
              
              <div class="flex flex-col space-y-2 ml-4">
                <button 
                  @click="editDistribution(distribution)"
                  class="w-8 h-8 bg-blue-500/10 hover:bg-blue-500/20 rounded-lg flex items-center justify-center text-blue-400 hover:text-blue-300 transition-all duration-200"
                  title="Edit Distribution"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                  </svg>
                </button>
                <button 
                  @click="removeDistribution(distribution.id)"
                  class="w-8 h-8 bg-red-500/10 hover:bg-red-500/20 rounded-lg flex items-center justify-center text-red-400 hover:text-red-300 transition-all duration-200"
                  title="Delete Distribution"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <div v-if="distributions.length === 0" class="text-center py-8">
            <p class="text-white/60">No distributions found</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Add/Edit Owner Modal -->
    <div v-if="showAddOwnerModal || showEditOwnerModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
      <div class="glass rounded-2xl p-8 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-white">
            {{ showEditOwnerModal ? 'Edit Owner' : 'Add Owner' }}
          </h2>
          <button 
            @click="closeOwnerModal"
            class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center text-white/60 hover:text-white transition-all duration-200"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <form @submit.prevent="submitOwner" class="space-y-6">
          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Owner Name</label>
            <input 
              v-model="ownerForm.name"
              type="text"
              required
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
              placeholder="Enter owner name"
            />
          </div>

          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Share Percentage</label>
            <input 
              v-model="ownerForm.share_percentage"
              type="number"
              step="0.01"
              required
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
              placeholder="0.00"
            />
          </div>

          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Email (Optional)</label>
            <input 
              v-model="ownerForm.email"
              type="email"
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
              placeholder="Enter email address"
            />
          </div>

          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Phone (Optional)</label>
            <input 
              v-model="ownerForm.phone"
              type="text"
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
              placeholder="Enter phone number"
            />
          </div>

          <div class="flex space-x-4 pt-4">
            <button 
              type="button"
              @click="closeOwnerModal"
              class="flex-1 bg-white/10 hover:bg-white/20 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200"
            >
              Cancel
            </button>
            <button 
              type="submit"
              :disabled="saving"
              class="flex-1 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 disabled:opacity-50 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 flex items-center justify-center"
            >
              <div v-if="saving" class="w-5 h-5 border-2 border-white/20 border-t-white rounded-full animate-spin mr-2"></div>
              {{ saving ? 'Saving...' : (showEditOwnerModal ? 'Update' : 'Add') }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Add/Edit Distribution Modal -->
    <div v-if="showAddDistributionModal || showEditDistributionModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
      <div class="glass rounded-2xl p-8 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-white">
            {{ showEditDistributionModal ? 'Edit Distribution' : 'Add Distribution' }}
          </h2>
          <button 
            @click="closeDistributionModal"
            class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center text-white/60 hover:text-white transition-all duration-200"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <form @submit.prevent="submitDistribution" class="space-y-6">
          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Owner</label>
            <select 
              v-model="distForm.owner_id"
              required
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
            >
              <option value="">Select Owner</option>
              <option v-for="owner in owners" :key="owner.id" :value="owner.id">
                {{ owner.name }} ({{ owner.share_percentage }}%)
              </option>
            </select>
          </div>

          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Date</label>
            <input 
              v-model="distForm.date"
              type="date"
              required
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
            />
          </div>

          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Amount</label>
            <input 
              v-model="distForm.amount"
              type="number"
              step="0.01"
              required
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
              placeholder="0.00"
            />
          </div>

          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Notes (Optional)</label>
            <textarea 
              v-model="distForm.notes"
              rows="3"
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 resize-none"
              placeholder="Add any additional notes"
            ></textarea>
          </div>

          <div class="flex space-x-4 pt-4">
            <button 
              type="button"
              @click="closeDistributionModal"
              class="flex-1 bg-white/10 hover:bg-white/20 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200"
            >
              Cancel
            </button>
            <button 
              type="submit"
              :disabled="saving"
              class="flex-1 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 disabled:opacity-50 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 flex items-center justify-center"
            >
              <div v-if="saving" class="w-5 h-5 border-2 border-white/20 border-t-white rounded-full animate-spin mr-2"></div>
              {{ saving ? 'Saving...' : (showEditDistributionModal ? 'Update' : 'Add') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

