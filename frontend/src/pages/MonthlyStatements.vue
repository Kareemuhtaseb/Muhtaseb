<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

const owners = ref([])
const statements = ref([])
const loading = ref(false)
const generating = ref(false)
const sending = ref(false)
const showGenerateModal = ref(false)
const showSendModal = ref(false)
const selectedOwner = ref(null)
const selectedMonth = ref('')
const selectedYear = ref('')

const statementForm = ref({
  owner_id: '',
  month: '',
  year: '',
  include_details: true,
  send_email: false
})

// Computed properties
const currentMonth = computed(() => {
  const now = new Date()
  return now.getMonth() + 1
})

const currentYear = computed(() => {
  return new Date().getFullYear()
})

const availableMonths = computed(() => {
  return [
    { value: 1, label: 'January' },
    { value: 2, label: 'February' },
    { value: 3, label: 'March' },
    { value: 4, label: 'April' },
    { value: 5, label: 'May' },
    { value: 6, label: 'June' },
    { value: 7, label: 'July' },
    { value: 8, label: 'August' },
    { value: 9, label: 'September' },
    { value: 10, label: 'October' },
    { value: 11, label: 'November' },
    { value: 12, label: 'December' }
  ]
})

const availableYears = computed(() => {
  const currentYear = new Date().getFullYear()
  const years = []
  for (let i = currentYear; i >= currentYear - 5; i--) {
    years.push(i)
  }
  return years
})

async function load() {
  try {
    loading.value = true
    const [ownersResponse, statementsResponse] = await Promise.all([
      axios.get('/owners.php'),
      axios.get('/monthly-reports.php')
    ])
    owners.value = ownersResponse.data
    statements.value = statementsResponse.data || []
  } catch (error) {
    console.error('Error loading data:', error)
  } finally {
    loading.value = false
  }
}

onMounted(load)

function openGenerateModal(owner = null) {
  selectedOwner.value = owner
  if (owner) {
    statementForm.value.owner_id = owner.id
  }
  statementForm.value.month = currentMonth.value
  statementForm.value.year = currentYear.value
  showGenerateModal.value = true
}

function openSendModal(statement) {
  selectedOwner.value = statement.owner
  statementForm.value.owner_id = statement.owner_id
  statementForm.value.month = statement.month
  statementForm.value.year = statement.year
  showSendModal.value = true
}

async function generateStatement() {
  try {
    generating.value = true
    const response = await axios.post('/monthly-reports.php', statementForm.value)
    await load()
    showGenerateModal.value = false
    // Show success notification
    console.log('Statement generated successfully')
  } catch (error) {
    console.error('Error generating statement:', error)
    alert('Failed to generate statement')
  } finally {
    generating.value = false
  }
}

async function sendStatement() {
  try {
    sending.value = true
    await axios.post('/monthly-reports.php/send', statementForm.value)
    showSendModal.value = false
    // Show success notification
    console.log('Statement sent successfully')
  } catch (error) {
    console.error('Error sending statement:', error)
    alert('Failed to send statement')
  } finally {
    sending.value = false
  }
}

function closeGenerateModal() {
  showGenerateModal.value = false
  selectedOwner.value = null
  statementForm.value = {
    owner_id: '',
    month: currentMonth.value,
    year: currentYear.value,
    include_details: true,
    send_email: false
  }
}

function closeSendModal() {
  showSendModal.value = false
  selectedOwner.value = null
  statementForm.value = {
    owner_id: '',
    month: '',
    year: '',
    include_details: true,
    send_email: false
  }
}

function formatDate(date) {
  return new Date(date).toLocaleDateString()
}

function formatCurrency(amount) {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(amount || 0)
}

function getStatusColor(status) {
  switch (status) {
    case 'generated':
      return 'text-green-400'
    case 'sent':
      return 'text-blue-400'
    case 'pending':
      return 'text-yellow-400'
    default:
      return 'text-gray-400'
  }
}

function getStatusIcon(status) {
  switch (status) {
    case 'generated':
      return 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
    case 'sent':
      return 'M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'
    case 'pending':
      return 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
    default:
      return 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
  }
}
</script>

<template>
  <div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-white">Monthly Statements</h1>
        <p class="text-white/60 mt-2">Generate and send monthly owner statements</p>
      </div>
      <div class="flex space-x-4">
        <button 
          @click="openGenerateModal()"
          class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 hover-lift flex items-center space-x-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <span>Generate Statement</span>
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
          <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Generated This Month</p>
            <p class="text-3xl font-bold text-white mt-2">{{ statements.filter(s => s.month === currentMonth && s.year === currentYear).length }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Sent This Month</p>
            <p class="text-3xl font-bold text-white mt-2">{{ statements.filter(s => s.month === currentMonth && s.year === currentYear && s.status === 'sent').length }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Pending</p>
            <p class="text-3xl font-bold text-white mt-2">{{ statements.filter(s => s.status === 'pending').length }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center h-64">
      <div class="text-center">
        <div class="w-16 h-16 border-4 border-white/20 border-t-white rounded-full animate-spin mx-auto mb-4"></div>
        <p class="text-white/60">Loading monthly statements...</p>
      </div>
    </div>

    <!-- Content -->
    <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Owners Section -->
      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-bold text-white">Property Owners</h2>
          <button 
            @click="openGenerateModal()"
            class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center text-white/60 hover:text-white transition-all duration-200"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
          </button>
        </div>

        <div class="space-y-4">
          <div v-for="owner in owners" :key="owner.id" class="bg-white/5 rounded-xl p-4">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-white font-semibold">{{ owner.name }}</h3>
                <p class="text-white/60 text-sm">{{ owner.share_percentage }}% share</p>
                <p class="text-white/60 text-sm">{{ owner.email }}</p>
              </div>
              <div class="flex space-x-2">
                <button 
                  @click="openGenerateModal(owner)"
                  class="w-8 h-8 bg-blue-500/10 hover:bg-blue-500/20 rounded-lg flex items-center justify-center text-blue-400 hover:text-blue-300 transition-all duration-200"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
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

      <!-- Statements Section -->
      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-bold text-white">Recent Statements</h2>
        </div>

        <div class="space-y-4">
          <div v-for="statement in statements.slice(0, 10)" :key="statement.id" class="bg-white/5 rounded-xl p-4">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-white font-semibold">{{ statement.owner_name }}</h3>
                <p class="text-white/60 text-sm">{{ statement.month }}/{{ statement.year }}</p>
                <p class="text-green-400 font-semibold">{{ formatCurrency(statement.total_amount) }}</p>
              </div>
              <div class="flex items-center space-x-2">
                <div class="flex items-center space-x-1">
                  <svg class="w-4 h-4" :class="getStatusColor(statement.status)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getStatusIcon(statement.status)"></path>
                  </svg>
                  <span class="text-xs" :class="getStatusColor(statement.status)">{{ statement.status }}</span>
                </div>
                <button 
                  v-if="statement.status === 'generated'"
                  @click="openSendModal(statement)"
                  class="w-8 h-8 bg-purple-500/10 hover:bg-purple-500/20 rounded-lg flex items-center justify-center text-purple-400 hover:text-purple-300 transition-all duration-200"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <div v-if="statements.length === 0" class="text-center py-8">
            <p class="text-white/60">No statements generated yet</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Generate Statement Modal -->
    <div v-if="showGenerateModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
      <div class="glass rounded-2xl p-8 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-white">Generate Statement</h2>
          <button 
            @click="closeGenerateModal"
            class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center text-white/60 hover:text-white transition-all duration-200"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <form @submit.prevent="generateStatement" class="space-y-6">
          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Owner</label>
            <select 
              v-model="statementForm.owner_id"
              required
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
            >
              <option value="">Select Owner</option>
              <option v-for="owner in owners" :key="owner.id" :value="owner.id">
                {{ owner.name }} ({{ owner.share_percentage }}%)
              </option>
            </select>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-white/80 text-sm font-medium mb-2">Month</label>
              <select 
                v-model="statementForm.month"
                required
                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              >
                <option v-for="month in availableMonths" :key="month.value" :value="month.value">
                  {{ month.label }}
                </option>
              </select>
            </div>

            <div>
              <label class="block text-white/80 text-sm font-medium mb-2">Year</label>
              <select 
                v-model="statementForm.year"
                required
                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              >
                <option v-for="year in availableYears" :key="year" :value="year">
                  {{ year }}
                </option>
              </select>
            </div>
          </div>

          <div class="space-y-4">
            <div class="flex items-center">
              <input 
                v-model="statementForm.include_details"
                type="checkbox"
                class="rounded border-white/20 bg-white/10 text-blue-600 focus:ring-blue-500"
              />
              <label class="ml-3 text-white/80 text-sm">Include detailed breakdown</label>
            </div>
            
            <div class="flex items-center">
              <input 
                v-model="statementForm.send_email"
                type="checkbox"
                class="rounded border-white/20 bg-white/10 text-blue-600 focus:ring-blue-500"
              />
              <label class="ml-3 text-white/80 text-sm">Send via email</label>
            </div>
          </div>

          <div class="flex space-x-4 pt-4">
            <button 
              type="button"
              @click="closeGenerateModal"
              class="flex-1 bg-white/10 hover:bg-white/20 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200"
            >
              Cancel
            </button>
            <button 
              type="submit"
              :disabled="generating"
              class="flex-1 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 disabled:opacity-50 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 flex items-center justify-center"
            >
              <div v-if="generating" class="w-5 h-5 border-2 border-white/20 border-t-white rounded-full animate-spin mr-2"></div>
              {{ generating ? 'Generating...' : 'Generate' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Send Statement Modal -->
    <div v-if="showSendModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
      <div class="glass rounded-2xl p-8 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-white">Send Statement</h2>
          <button 
            @click="closeSendModal"
            class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center text-white/60 hover:text-white transition-all duration-200"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <div class="space-y-6">
          <div class="p-4 bg-white/5 rounded-xl">
            <p class="text-white/80 text-sm">Are you sure you want to send the monthly statement to <strong class="text-white">{{ selectedOwner?.name }}</strong> for {{ statementForm.month }}/{{ statementForm.year }}?</p>
          </div>

          <div class="flex space-x-4 pt-4">
            <button 
              @click="closeSendModal"
              class="flex-1 bg-white/10 hover:bg-white/20 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200"
            >
              Cancel
            </button>
            <button 
              @click="sendStatement"
              :disabled="sending"
              class="flex-1 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 disabled:opacity-50 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 flex items-center justify-center"
            >
              <div v-if="sending" class="w-5 h-5 border-2 border-white/20 border-t-white rounded-full animate-spin mr-2"></div>
              {{ sending ? 'Sending...' : 'Send Statement' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
