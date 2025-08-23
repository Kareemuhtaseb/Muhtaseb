<template>
  <div>
    <!-- Enhanced Loading State with Premium Glass Skeleton -->
    <div v-if="loading" class="p-6">
      <div class="max-w-7xl mx-auto">
        <!-- Header Skeleton -->
        <div class="mb-8">
          <div class="h-8 bg-white/60 backdrop-blur-sm rounded-lg animate-pulse mb-4 w-1/3"></div>
          <div class="h-4 bg-white/40 backdrop-blur-sm rounded animate-pulse w-1/2"></div>
        </div>
        
        <!-- Stats Cards Skeleton -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div v-for="i in 4" :key="i" class="bg-white/60 backdrop-blur-sm rounded-xl p-6 animate-pulse">
            <div class="h-4 bg-white/40 rounded mb-2"></div>
            <div class="h-8 bg-white/40 rounded w-1/2"></div>
          </div>
        </div>
        
        <!-- Table Skeleton -->
        <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6">
          <div class="h-6 bg-white/40 rounded mb-4 w-1/4"></div>
          <div class="space-y-3">
            <div v-for="i in 5" :key="i" class="h-12 bg-white/40 rounded"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div v-else class="p-6">
      <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900 mb-2">Owner Distributions</h1>
          <p class="text-gray-600">Manage and track owner profit distributions</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/20 shadow-lg">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Total Distributions</p>
                <p class="text-2xl font-bold text-gray-900">{{ formatCurrency(totalDistributions) }}</p>
              </div>
              <div class="p-3 bg-green-100 rounded-full">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
              </div>
            </div>
          </div>

          <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/20 shadow-lg">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">This Month</p>
                <p class="text-2xl font-bold text-gray-900">{{ formatCurrency(monthlyDistributions) }}</p>
              </div>
              <div class="p-3 bg-blue-100 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
              </div>
            </div>
          </div>

          <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/20 shadow-lg">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Active Owners</p>
                <p class="text-2xl font-bold text-gray-900">{{ activeOwners }}</p>
              </div>
              <div class="p-3 bg-purple-100 rounded-full">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
              </div>
            </div>
          </div>

          <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/20 shadow-lg">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Pending</p>
                <p class="text-2xl font-bold text-gray-900">{{ formatCurrency(pendingDistributions) }}</p>
              </div>
              <div class="p-3 bg-yellow-100 rounded-full">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Actions Bar -->
        <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/20 shadow-lg mb-6">
          <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
            <div class="flex flex-col sm:flex-row gap-4 items-center">
              <button
                @click="showCreateModal = true"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Distribution
              </button>
              
              <button
                @click="calculateDistributions"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Calculate Distributions
              </button>
            </div>

            <div class="flex items-center gap-4">
              <div class="relative">
                <input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Search distributions..."
                  class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Distributions Table -->
        <div class="bg-white/60 backdrop-blur-sm rounded-xl border border-white/20 shadow-lg overflow-hidden">
          <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Distribution History</h2>
          </div>
          
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50/50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="distribution in filteredDistributions" :key="distribution.id" class="hover:bg-gray-50/50">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                          <span class="text-sm font-medium text-blue-600">{{ distribution.owner_name.charAt(0) }}</span>
                        </div>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">{{ distribution.owner_name }}</div>
                        <div class="text-sm text-gray-500">{{ distribution.owner_email }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ distribution.property_name }}</div>
                    <div class="text-sm text-gray-500">{{ distribution.unit_number }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ formatCurrency(distribution.amount) }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ formatDate(distribution.distribution_date) }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="getStatusClass(distribution.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                      {{ distribution.status }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button
                      @click="viewDistribution(distribution)"
                      class="text-blue-600 hover:text-blue-900 mr-3"
                    >
                      View
                    </button>
                    <button
                      @click="editDistribution(distribution)"
                      class="text-indigo-600 hover:text-indigo-900 mr-3"
                    >
                      Edit
                    </button>
                    <button
                      @click="deleteDistribution(distribution.id)"
                      class="text-red-600 hover:text-red-900"
                    >
                      Delete
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex items-center justify-between">
              <div class="flex-1 flex justify-between sm:hidden">
                <button
                  @click="currentPage--"
                  :disabled="currentPage === 1"
                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
                >
                  Previous
                </button>
                <button
                  @click="currentPage++"
                  :disabled="currentPage >= totalPages"
                  class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
                >
                  Next
                </button>
              </div>
              <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                  <p class="text-sm text-gray-700">
                    Showing <span class="font-medium">{{ startIndex + 1 }}</span> to <span class="font-medium">{{ endIndex }}</span> of <span class="font-medium">{{ totalDistributions }}</span> results
                  </p>
                </div>
                <div>
                  <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                    <button
                      @click="currentPage--"
                      :disabled="currentPage === 1"
                      class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50"
                    >
                      <span class="sr-only">Previous</span>
                      <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                    </button>
                    <button
                      v-for="page in visiblePages"
                      :key="page"
                      @click="currentPage = page"
                      :class="page === currentPage ? 'bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'"
                      class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                    >
                      {{ page }}
                    </button>
                    <button
                      @click="currentPage++"
                      :disabled="currentPage >= totalPages"
                      class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50"
                    >
                      <span class="sr-only">Next</span>
                      <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                      </svg>
                    </button>
                  </nav>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <Modal v-if="showCreateModal" @close="showCreateModal = false" :title="editingDistribution ? 'Edit Distribution' : 'New Distribution'">
      <form @submit.prevent="saveDistribution" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Owner</label>
            <select v-model="form.owner_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <option value="">Select Owner</option>
              <option v-for="owner in owners" :key="owner.id" :value="owner.id">{{ owner.name }}</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Property</label>
            <select v-model="form.property_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <option value="">Select Property</option>
              <option v-for="property in properties" :key="property.id" :value="property.id">{{ property.name }}</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
            <input
              v-model="form.amount"
              type="number"
              step="0.01"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="0.00"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Distribution Date</label>
            <input
              v-model="form.distribution_date"
              type="date"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select v-model="form.status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <option value="pending">Pending</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
          <textarea
            v-model="form.notes"
            rows="3"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="Additional notes..."
          ></textarea>
        </div>
        
        <div class="flex justify-end gap-3">
          <button
            type="button"
            @click="showCreateModal = false"
            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200"
          >
            Cancel
          </button>
          <button
            type="submit"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200"
          >
            {{ editingDistribution ? 'Update' : 'Create' }}
          </button>
        </div>
      </form>
    </Modal>
  </div>
</template>

<script>
import Modal from '../components/Modal.vue'

export default {
  name: 'Distributions',
  components: {
    Modal
  },
  data() {
    return {
      loading: true,
      distributions: [],
      owners: [],
      properties: [],
      searchQuery: '',
      currentPage: 1,
      itemsPerPage: 10,
      showCreateModal: false,
      editingDistribution: null,
      form: {
        owner_id: '',
        property_id: '',
        amount: '',
        distribution_date: '',
        status: 'pending',
        notes: ''
      }
    }
  },
  computed: {
    filteredDistributions() {
      let filtered = this.distributions
      
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase()
        filtered = filtered.filter(distribution => 
          distribution.owner_name.toLowerCase().includes(query) ||
          distribution.property_name.toLowerCase().includes(query) ||
          distribution.unit_number.toLowerCase().includes(query)
        )
      }
      
      return filtered
    },
    paginatedDistributions() {
      const start = (this.currentPage - 1) * this.itemsPerPage
      const end = start + this.itemsPerPage
      return this.filteredDistributions.slice(start, end)
    },
    totalPages() {
      return Math.ceil(this.filteredDistributions.length / this.itemsPerPage)
    },
    startIndex() {
      return (this.currentPage - 1) * this.itemsPerPage
    },
    endIndex() {
      return Math.min(this.startIndex + this.itemsPerPage, this.filteredDistributions.length)
    },
    visiblePages() {
      const pages = []
      const maxVisible = 5
      let start = Math.max(1, this.currentPage - Math.floor(maxVisible / 2))
      let end = Math.min(this.totalPages, start + maxVisible - 1)
      
      if (end - start + 1 < maxVisible) {
        start = Math.max(1, end - maxVisible + 1)
      }
      
      for (let i = start; i <= end; i++) {
        pages.push(i)
      }
      
      return pages
    },
    totalDistributions() {
      return this.distributions.reduce((sum, d) => sum + parseFloat(d.amount || 0), 0)
    },
    monthlyDistributions() {
      const currentMonth = new Date().getMonth()
      const currentYear = new Date().getFullYear()
      return this.distributions
        .filter(d => {
          const date = new Date(d.distribution_date)
          return date.getMonth() === currentMonth && date.getFullYear() === currentYear
        })
        .reduce((sum, d) => sum + parseFloat(d.amount || 0), 0)
    },
    activeOwners() {
      return new Set(this.distributions.map(d => d.owner_id)).size
    },
    pendingDistributions() {
      return this.distributions
        .filter(d => d.status === 'pending')
        .reduce((sum, d) => sum + parseFloat(d.amount || 0), 0)
    }
  },
  async mounted() {
    await this.loadData()
  },
  methods: {
    async loadData() {
      try {
        this.loading = true
        await Promise.all([
          this.loadDistributions(),
          this.loadOwners(),
          this.loadProperties()
        ])
      } catch (error) {
        console.error('Error loading data:', error)
      } finally {
        this.loading = false
      }
    },
    
    async loadDistributions() {
      try {
        const response = await this.$http.get('/distributions')
        this.distributions = response.data?.data?.data || response.data?.data || response.data || []
      } catch (error) {
        console.error('Error loading distributions:', error)
        // Mock data for development
        this.distributions = [
          {
            id: 1,
            owner_name: 'John Doe',
            owner_email: 'john@example.com',
            property_name: 'Sunset Apartments',
            unit_number: 'A101',
            amount: 2500.00,
            distribution_date: '2024-01-15',
            status: 'completed'
          },
          {
            id: 2,
            owner_name: 'Jane Smith',
            owner_email: 'jane@example.com',
            property_name: 'Ocean View',
            unit_number: 'B205',
            amount: 1800.00,
            distribution_date: '2024-01-20',
            status: 'pending'
          }
        ]
      }
    },
    
    async loadOwners() {
      try {
        const response = await this.$http.get('/owners')
        this.owners = response.data?.data?.data || response.data?.data || response.data || []
      } catch (error) {
        console.error('Error loading owners:', error)
        this.owners = []
      }
    },
    
    async loadProperties() {
      try {
        const response = await this.$http.get('/properties')
        this.properties = response.data?.data?.data || response.data?.data || response.data || []
      } catch (error) {
        console.error('Error loading properties:', error)
        this.properties = []
      }
    },
    
    async calculateDistributions() {
      try {
        await this.$http.post('/distributions/calculate')
        await this.loadDistributions()
        alert('Distributions calculated successfully!')
      } catch (error) {
        console.error('Error calculating distributions:', error)
        alert('Error calculating distributions')
      }
    },
    
    async saveDistribution() {
      try {
        const url = this.editingDistribution 
          ? `/distributions/${this.editingDistribution.id}`
          : '/distributions'
        if (this.editingDistribution) {
          await this.$http.put(url, this.form)
        } else {
          await this.$http.post(url, this.form)
        }
        await this.loadDistributions()
        this.showCreateModal = false
        this.resetForm()
        alert(this.editingDistribution ? 'Distribution updated successfully!' : 'Distribution created successfully!')
      } catch (error) {
        console.error('Error saving distribution:', error)
        alert('Error saving distribution')
      }
    },
    
    editDistribution(distribution) {
      this.editingDistribution = distribution
      this.form = {
        owner_id: distribution.owner_id,
        property_id: distribution.property_id,
        amount: distribution.amount,
        distribution_date: distribution.distribution_date,
        status: distribution.status,
        notes: distribution.notes || ''
      }
      this.showCreateModal = true
    },
    
    async deleteDistribution(id) {
      if (!confirm('Are you sure you want to delete this distribution?')) {
        return
      }
      
      try {
        await this.$http.delete(`/distributions/${id}`)
        await this.loadDistributions()
        alert('Distribution deleted successfully!')
      } catch (error) {
        console.error('Error deleting distribution:', error)
        alert('Error deleting distribution')
      }
    },
    
    viewDistribution(distribution) {
      // Implement view functionality
      console.log('View distribution:', distribution)
    },
    
    resetForm() {
      this.form = {
        owner_id: '',
        property_id: '',
        amount: '',
        distribution_date: '',
        status: 'pending',
        notes: ''
      }
      this.editingDistribution = null
    },
    
    getStatusClass(status) {
      switch (status) {
        case 'completed':
          return 'bg-green-100 text-green-800'
        case 'pending':
          return 'bg-yellow-100 text-yellow-800'
        case 'cancelled':
          return 'bg-red-100 text-red-800'
        default:
          return 'bg-gray-100 text-gray-800'
      }
    },
    
    formatCurrency(amount) {
      return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
      }).format(amount || 0)
    },
    
    formatDate(date) {
      return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      })
    }
  }
}
</script>
