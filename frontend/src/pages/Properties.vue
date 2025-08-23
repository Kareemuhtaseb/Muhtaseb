<template>
  <div class="space-y-8">
    <!-- Enhanced Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
      <div>
        <h1 class="text-3xl font-bold text-white">Properties</h1>
        <p class="text-white/60 mt-2">Manage your property portfolio</p>
      </div>
      <div class="flex flex-col sm:flex-row gap-4">
        <!-- Search Bar -->
        <div class="relative">
          <input 
            v-model="searchQuery"
            type="text"
            placeholder="Search properties..."
            class="w-full sm:w-64 bg-white/10 border border-white/20 rounded-xl pl-10 pr-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
          />
          <svg class="w-5 h-5 text-white/40 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </div>
        
        <!-- Filter Dropdown -->
        <div class="relative">
          <button 
            @click="showFilters = !showFilters"
            class="flex items-center space-x-2 bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white hover:bg-white/20 transition-all duration-200"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
            </svg>
            <span>Filters</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>
          
          <!-- Filter Dropdown Menu -->
          <div v-if="showFilters" class="absolute top-full right-0 mt-2 glass rounded-xl p-4 w-64 z-50">
            <div class="space-y-4">
              <div>
                <label class="block text-white/80 text-sm font-medium mb-2">Status</label>
                <select v-model="filters.status" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white">
                  <option value="">All Status</option>
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
              <div>
                <label class="block text-white/80 text-sm font-medium mb-2">Sort By</label>
                <select v-model="filters.sortBy" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white">
                  <option value="name">Name</option>
                  <option value="units">Units</option>
                  <option value="income">Monthly Income</option>
                  <option value="created">Date Created</option>
                </select>
              </div>
              <div class="flex space-x-2">
                <button 
                  @click="clearFilters"
                  class="flex-1 bg-white/10 hover:bg-white/20 text-white px-3 py-2 rounded-lg text-sm transition-colors"
                >
                  Clear
                </button>
                <button 
                  @click="applyFilters"
                  class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg text-sm transition-colors"
                >
                  Apply
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Add Property Button -->
        <button 
          @click="showAddModal = true"
          class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 hover-lift flex items-center justify-center space-x-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          <span>Add Property</span>
        </button>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="glass rounded-2xl p-6 hover-lift">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Total Properties</p>
            <p class="text-3xl font-bold text-white mt-2">{{ filteredProperties.length }}</p>
          </div>
          <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="glass rounded-2xl p-6 hover-lift">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Total Units</p>
            <p class="text-3xl font-bold text-white mt-2">{{ totalUnits }}</p>
          </div>
          <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="glass rounded-2xl p-6 hover-lift">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Occupied Units</p>
            <p class="text-3xl font-bold text-white mt-2">{{ totalOccupiedUnits }}</p>
          </div>
          <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="glass rounded-2xl p-6 hover-lift">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Monthly Income</p>
            <p class="text-3xl font-bold text-white mt-2">${{ formatNumber(totalMonthlyIncome) }}</p>
          </div>
          <div class="w-12 h-12 bg-yellow-500/20 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading State with Skeleton -->
    <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="i in 6" :key="i" class="glass rounded-2xl p-6">
        <div class="flex items-start justify-between mb-4">
          <div class="flex-1">
            <div class="h-6 bg-white/10 rounded w-32 mb-2 animate-pulse"></div>
            <div class="h-4 bg-white/10 rounded w-48 animate-pulse"></div>
          </div>
          <div class="flex space-x-2">
            <div class="w-8 h-8 bg-white/10 rounded-lg animate-pulse"></div>
            <div class="w-8 h-8 bg-white/10 rounded-lg animate-pulse"></div>
          </div>
        </div>
        <div class="space-y-3">
          <div class="flex items-center justify-between">
            <div class="h-4 bg-white/10 rounded w-16 animate-pulse"></div>
            <div class="h-4 bg-white/10 rounded w-8 animate-pulse"></div>
          </div>
          <div class="flex items-center justify-between">
            <div class="h-4 bg-white/10 rounded w-20 animate-pulse"></div>
            <div class="h-4 bg-white/10 rounded w-8 animate-pulse"></div>
          </div>
          <div class="flex items-center justify-between">
            <div class="h-4 bg-white/10 rounded w-24 animate-pulse"></div>
            <div class="h-4 bg-white/10 rounded w-16 animate-pulse"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Properties Grid -->
    <div v-else-if="filteredProperties.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div 
        v-for="property in filteredProperties" 
        :key="property.id"
        class="glass rounded-2xl p-6 hover-lift cursor-pointer transition-all duration-200 group"
        @click="selectProperty(property)"
      >
        <!-- Property Header -->
        <div class="flex items-start justify-between mb-4">
          <div class="flex-1">
            <h3 class="text-xl font-bold text-white mb-1 group-hover:text-blue-400 transition-colors">{{ property.name }}</h3>
            <p class="text-white/60 text-sm">{{ property.location }}</p>
          </div>
          <div class="flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
            <button 
              @click.stop="editProperty(property)"
              class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center text-white/60 hover:text-white transition-all duration-200"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
              </svg>
            </button>
            <button 
              @click.stop="deleteProperty(property.id)"
              class="w-8 h-8 bg-red-500/10 hover:bg-red-500/20 rounded-lg flex items-center justify-center text-red-400 hover:text-red-300 transition-all duration-200"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
              </svg>
            </button>
          </div>
        </div>

        <!-- Property Stats -->
        <div class="space-y-3">
          <div class="flex items-center justify-between p-2 bg-white/5 rounded-lg">
            <div class="flex items-center">
              <svg class="w-4 h-4 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
              </svg>
              <span class="text-white/60 text-sm">Units</span>
            </div>
            <span class="text-white font-semibold">{{ property.units_count || 0 }}</span>
          </div>
          <div class="flex items-center justify-between p-2 bg-white/5 rounded-lg">
            <div class="flex items-center">
              <svg class="w-4 h-4 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
              </svg>
              <span class="text-white/60 text-sm">Occupied</span>
            </div>
            <span class="text-green-400 font-semibold">{{ property.occupied_units || 0 }}</span>
          </div>
          <div class="flex items-center justify-between p-2 bg-white/5 rounded-lg">
            <div class="flex items-center">
              <svg class="w-4 h-4 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
              </svg>
              <span class="text-white/60 text-sm">Monthly Income</span>
            </div>
            <span class="text-white font-semibold">${{ formatNumber(property.monthly_income || 0) }}</span>
          </div>
        </div>

        <!-- Notes Preview -->
        <div v-if="property.notes" class="mt-4 pt-4 border-t border-white/10">
          <p class="text-white/60 text-sm line-clamp-2">{{ property.notes }}</p>
        </div>

        <!-- View Details Button -->
        <div class="mt-4 pt-4 border-t border-white/10">
          <button class="w-full bg-white/10 hover:bg-white/20 text-white py-2 rounded-lg text-sm font-medium transition-colors">
            View Details
          </button>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else-if="!loading" class="text-center py-16">
      <div class="w-24 h-24 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-12 h-12 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
      </div>
      <h3 class="text-xl font-semibold text-white mb-2">No Properties Found</h3>
      <p class="text-white/60 mb-6">{{ searchQuery ? 'Try adjusting your search criteria' : 'Get started by adding your first property' }}</p>
      <button 
        @click="showAddModal = true"
        class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 hover-lift"
      >
        Add Your First Property
      </button>
    </div>

    <!-- Enhanced Add/Edit Property Modal -->
    <div v-if="showAddModal || showEditModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
      <div class="glass rounded-2xl p-8 w-full max-w-md mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-white">
            {{ showEditModal ? 'Edit Property' : 'Add Property' }}
          </h2>
          <button 
            @click="closeModal"
            class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center text-white/60 hover:text-white transition-all duration-200"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <form @submit.prevent="saveProperty" class="space-y-6">
          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Property Name *</label>
            <input 
              v-model="form.name"
              type="text"
              required
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              placeholder="Enter property name"
            />
          </div>

          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Location *</label>
            <textarea 
              v-model="form.location"
              required
              rows="3"
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"
              placeholder="Enter property address"
            ></textarea>
          </div>

          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Property Type</label>
            <select 
              v-model="form.type"
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
            >
              <option value="">Select property type</option>
              <option value="commercial">Commercial</option>
              <option value="retail">Retail</option>
              <option value="office">Office</option>
              <option value="mixed">Mixed Use</option>
            </select>
          </div>

          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Notes (Optional)</label>
            <textarea 
              v-model="form.notes"
              rows="3"
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"
              placeholder="Add any additional notes"
            ></textarea>
          </div>

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
              :disabled="saving"
              class="flex-1 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 disabled:opacity-50 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 flex items-center justify-center"
            >
              <div v-if="saving" class="w-5 h-5 border-2 border-white/20 border-t-white rounded-full animate-spin mr-2"></div>
              {{ saving ? 'Saving...' : (showEditModal ? 'Update' : 'Add') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Properties',
  data() {
    return {
      loading: true,
      saving: false,
      properties: [],
      showAddModal: false,
      showEditModal: false,
      showFilters: false,
      selectedProperty: null,
      searchQuery: '',
      filters: {
        status: '',
        sortBy: 'name'
      },
      form: {
        name: '',
        location: '',
        type: '',
        notes: ''
      }
    }
  },
  computed: {
    filteredProperties() {
      let filtered = this.properties

      // Search filter
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase()
        filtered = filtered.filter(property => 
          property.name.toLowerCase().includes(query) ||
          property.location.toLowerCase().includes(query)
        )
      }

      // Status filter
      if (this.filters.status) {
        filtered = filtered.filter(property => property.status === this.filters.status)
      }

      // Sort
      filtered.sort((a, b) => {
        switch (this.filters.sortBy) {
          case 'name':
            return a.name.localeCompare(b.name)
          case 'units':
            return (b.units_count || 0) - (a.units_count || 0)
          case 'income':
            return (b.monthly_income || 0) - (a.monthly_income || 0)
          case 'created':
            return new Date(b.created_at) - new Date(a.created_at)
          default:
            return 0
        }
      })

      return filtered
    },
    totalUnits() {
      return this.properties.reduce((sum, property) => sum + (property.units_count || 0), 0)
    },
    totalOccupiedUnits() {
      return this.properties.reduce((sum, property) => sum + (property.occupied_units || 0), 0)
    },
    totalMonthlyIncome() {
      return this.properties.reduce((sum, property) => sum + (property.monthly_income || 0), 0)
    }
  },
  async mounted() {
    await this.loadProperties()
    // Close filters dropdown when clicking outside
    document.addEventListener('click', this.handleClickOutside)
  },
  beforeUnmount() {
    document.removeEventListener('click', this.handleClickOutside)
  },
  methods: {
    async loadProperties() {
      try {
        const response = await this.$http.get('/properties.php')
        this.properties = response.data
      } catch (error) {
        console.error('Error loading properties:', error)
        this.properties = []
      } finally {
        this.loading = false
      }
    },
    selectProperty(property) {
      this.$router.push(`/units?property=${property.id}`)
    },
    editProperty(property) {
      this.selectedProperty = property
      this.form = { ...property }
      this.showEditModal = true
    },
    async deleteProperty(id) {
      if (!confirm('Are you sure you want to delete this property? This will also delete all associated units, tenants, and financial records.')) {
        return
      }

      try {
        await this.$http.delete(`/properties.php?id=${id}`)
        await this.loadProperties()
        this.$parent.addNotification('success', 'Property Deleted', 'Property has been successfully deleted.')
      } catch (error) {
        console.error('Error deleting property:', error)
        this.$parent.addNotification('error', 'Delete Failed', 'Failed to delete property. Please try again.')
      }
    },
    async saveProperty() {
      this.saving = true
      try {
        if (this.showEditModal) {
          await this.$http.put(`/properties.php?id=${this.selectedProperty.id}`, this.form)
          this.$parent.addNotification('success', 'Property Updated', 'Property has been successfully updated.')
        } else {
          await this.$http.post('/properties.php', this.form)
          this.$parent.addNotification('success', 'Property Added', 'Property has been successfully added.')
        }
        await this.loadProperties()
        this.closeModal()
      } catch (error) {
        console.error('Error saving property:', error)
        this.$parent.addNotification('error', 'Save Failed', 'Failed to save property. Please try again.')
      } finally {
        this.saving = false
      }
    },
    closeModal() {
      this.showAddModal = false
      this.showEditModal = false
      this.selectedProperty = null
      this.form = {
        name: '',
        location: '',
        type: '',
        notes: ''
      }
    },
    clearFilters() {
      this.filters = {
        status: '',
        sortBy: 'name'
      }
    },
    applyFilters() {
      this.showFilters = false
    },
    handleClickOutside(event) {
      if (!this.$el.contains(event.target)) {
        this.showFilters = false
      }
    },
    formatNumber(num) {
      return new Intl.NumberFormat().format(num)
    }
  }
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Enhanced animations */
@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.fade-in-up {
  animation: slideInUp 0.6s ease-out;
}

/* Enhanced hover effects */
.hover-lift {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.hover-lift:hover {
  transform: translateY(-4px);
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
}
</style>
