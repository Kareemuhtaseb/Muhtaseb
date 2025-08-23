<template>
  <div class="space-y-6">
    <!-- Enhanced Table Header with Search and Actions -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
      <div class="flex items-center space-x-4">
        <!-- Enhanced Search -->
        <div class="relative group">
          <input 
            v-model="searchQuery"
            type="text"
            :placeholder="searchPlaceholder"
            class="w-full sm:w-64 bg-white/10 border border-white/20 rounded-xl pl-10 pr-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 form-input"
          />
          <svg class="w-5 h-5 text-white/40 absolute left-3 top-1/2 transform -translate-y-1/2 group-hover:text-white/60 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </div>

        <!-- Enhanced Filters -->
        <div class="relative">
          <button 
            @click="showFilters = !showFilters"
            class="flex items-center space-x-2 bg-gradient-to-r from-white/10 to-white/5 border border-white/20 rounded-xl px-4 py-3 text-white hover:from-white/20 hover:to-white/10 transition-all duration-300 hover-lift"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
            </svg>
            <span>Filters</span>
            <svg class="w-4 h-4 transition-transform duration-300" :class="{ 'rotate-180': showFilters }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>
          
          <!-- Enhanced Filter Dropdown -->
          <div v-if="showFilters" class="absolute top-full right-0 mt-2 glass-premium rounded-xl p-4 w-64 z-50 slide-in-up">
            <slot name="filters">
              <div class="space-y-4">
                <div>
                  <label class="block text-white/80 text-sm font-medium mb-2">Status</label>
                  <select v-model="localFilters.status" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white form-input">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                  </select>
                </div>
                <div class="flex space-x-2">
                  <button 
                    @click="clearFilters"
                    class="flex-1 bg-white/10 hover:bg-white/20 text-white px-3 py-2 rounded-lg text-sm transition-all duration-300 hover-lift"
                  >
                    Clear
                  </button>
                  <button 
                    @click="applyFilters"
                    class="flex-1 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-3 py-2 rounded-lg text-sm transition-all duration-300 hover-lift"
                  >
                    Apply
                  </button>
                </div>
              </div>
            </slot>
          </div>
        </div>
      </div>

      <!-- Enhanced Actions -->
      <div class="flex items-center space-x-3">
        <slot name="actions"></slot>
      </div>
    </div>

    <!-- Enhanced Loading State -->
    <div v-if="loading" class="glass-premium rounded-2xl overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gradient-to-r from-white/10 to-white/5">
            <tr>
              <th v-for="column in columns" :key="column.key" class="px-6 py-4 text-left">
                <div class="h-4 bg-gradient-to-r from-white/10 via-white/20 to-white/10 rounded w-24 loading-shimmer"></div>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="i in 5" :key="i" class="border-b border-white/10">
              <td v-for="column in columns" :key="column.key" class="px-6 py-4">
                <div class="h-4 bg-gradient-to-r from-white/10 via-white/20 to-white/10 rounded w-20 loading-shimmer"></div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Enhanced Data Table -->
    <div v-else class="glass-premium rounded-2xl overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gradient-to-r from-white/10 to-white/5">
            <tr>
              <th 
                v-for="column in columns" 
                :key="column.key"
                @click="sortBy(column.key)"
                class="px-6 py-4 text-left cursor-pointer hover:bg-white/10 transition-all duration-300"
                :class="{ 'cursor-pointer': column.sortable !== false }"
              >
                <div class="flex items-center space-x-2">
                  <span class="text-white/80 font-medium">{{ column.label }}</span>
                  <div v-if="column.sortable !== false" class="flex flex-col">
                    <svg 
                      class="w-3 h-3 text-white/40 hover:text-white/60 transition-colors duration-300"
                      :class="{ 'text-blue-400': sortKey === column.key && sortOrder === 'asc' }"
                      fill="none" 
                      stroke="currentColor" 
                      viewBox="0 0 24 24"
                    >
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                    </svg>
                    <svg 
                      class="w-3 h-3 text-white/40 hover:text-white/60 transition-colors duration-300 -mt-1"
                      :class="{ 'text-blue-400': sortKey === column.key && sortOrder === 'desc' }"
                      fill="none" 
                      stroke="currentColor" 
                      viewBox="0 0 24 24"
                    >
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                  </div>
                </div>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr 
              v-for="(item, index) in paginatedData" 
              :key="item.id || index"
              class="border-b border-white/10 hover:bg-white/10 transition-all duration-300 cursor-pointer table-row"
              @click="$emit('row-click', item)"
              :style="{ animationDelay: `${index * 0.05}s` }"
            >
              <td 
                v-for="column in columns" 
                :key="column.key"
                class="px-6 py-4 table-cell"
              >
                <slot :name="`cell-${column.key}`" :item="item" :value="item[column.key]">
                  <div v-if="column.type === 'status'" class="flex items-center">
                    <div 
                      class="w-2 h-2 rounded-full mr-2 animate-pulse"
                      :class="getStatusColor(item[column.key])"
                    ></div>
                    <span class="text-white/80 capitalize font-medium">{{ item[column.key] }}</span>
                  </div>
                  <div v-else-if="column.type === 'currency'" class="text-white/80 font-medium">
                    ${{ formatNumber(item[column.key]) }}
                  </div>
                  <div v-else-if="column.type === 'date'" class="text-white/80">
                    {{ formatDate(item[column.key]) }}
                  </div>
                  <div v-else-if="column.type === 'actions'" class="flex items-center space-x-2">
                    <slot name="actions" :item="item"></slot>
                  </div>
                  <div v-else class="text-white/80">
                    {{ item[column.key] }}
                  </div>
                </slot>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Enhanced Empty State -->
      <div v-if="filteredData.length === 0" class="text-center py-16">
        <div class="w-20 h-20 bg-gradient-to-br from-white/10 to-white/5 rounded-full flex items-center justify-center mx-auto mb-6 animate-float">
          <svg class="w-10 h-10 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-3 text-gradient">No Data Found</h3>
        <p class="text-white/60 text-lg">{{ searchQuery ? 'Try adjusting your search criteria' : 'No data available' }}</p>
      </div>
    </div>

    <!-- Enhanced Pagination -->
    <div v-if="showPagination && totalPages > 1" class="flex items-center justify-between">
      <div class="text-white/60 text-sm">
        Showing {{ startIndex + 1 }} to {{ endIndex }} of {{ filteredData.length }} results
      </div>
      <div class="flex items-center space-x-2">
        <button 
          @click="previousPage"
          :disabled="currentPage === 1"
          class="px-4 py-2 bg-gradient-to-r from-white/10 to-white/5 hover:from-white/20 hover:to-white/10 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-xl transition-all duration-300 hover-lift"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
        </button>
        
        <div class="flex items-center space-x-1">
          <button 
            v-for="page in visiblePages" 
            :key="page"
            @click="goToPage(page)"
            :class="[
              'px-4 py-2 rounded-xl transition-all duration-300 hover-lift',
              page === currentPage 
                ? 'bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg' 
                : 'bg-gradient-to-r from-white/10 to-white/5 hover:from-white/20 hover:to-white/10 text-white'
            ]"
          >
            {{ page }}
          </button>
        </div>
        
        <button 
          @click="nextPage"
          :disabled="currentPage === totalPages"
          class="px-4 py-2 bg-gradient-to-r from-white/10 to-white/5 hover:from-white/20 hover:to-white/10 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-xl transition-all duration-300 hover-lift"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'DataTable',
  props: {
    data: {
      type: Array,
      required: true
    },
    columns: {
      type: Array,
      required: true
    },
    loading: {
      type: Boolean,
      default: false
    },
    searchPlaceholder: {
      type: String,
      default: 'Search...'
    },
    showPagination: {
      type: Boolean,
      default: true
    },
    itemsPerPage: {
      type: Number,
      default: 10
    },
    filters: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      searchQuery: '',
      showFilters: false,
      sortKey: '',
      sortOrder: 'asc',
      currentPage: 1,
      localFilters: { ...this.filters }
    }
  },
  computed: {
    filteredData() {
      let filtered = this.data

      // Search filter
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase()
        filtered = filtered.filter(item => {
          return this.columns.some(column => {
            const value = item[column.key]
            return value && value.toString().toLowerCase().includes(query)
          })
        })
      }

      // Apply local filters
      Object.keys(this.localFilters).forEach(key => {
        if (this.localFilters[key]) {
          filtered = filtered.filter(item => item[key] === this.localFilters[key])
        }
      })

      // Sort
      if (this.sortKey) {
        filtered.sort((a, b) => {
          const aVal = a[this.sortKey]
          const bVal = b[this.sortKey]
          
          if (aVal < bVal) return this.sortOrder === 'asc' ? -1 : 1
          if (aVal > bVal) return this.sortOrder === 'asc' ? 1 : -1
          return 0
        })
      }

      return filtered
    },
    totalPages() {
      return Math.ceil(this.filteredData.length / this.itemsPerPage)
    },
    startIndex() {
      return (this.currentPage - 1) * this.itemsPerPage
    },
    endIndex() {
      return Math.min(this.startIndex + this.itemsPerPage, this.filteredData.length)
    },
    paginatedData() {
      return this.filteredData.slice(this.startIndex, this.endIndex)
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
    }
  },
  watch: {
    searchQuery() {
      this.currentPage = 1
    },
    localFilters: {
      handler() {
        this.currentPage = 1
      },
      deep: true
    }
  },
  methods: {
    sortBy(key) {
      if (this.sortKey === key) {
        this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc'
      } else {
        this.sortKey = key
        this.sortOrder = 'asc'
      }
    },
    previousPage() {
      if (this.currentPage > 1) {
        this.currentPage--
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++
      }
    },
    goToPage(page) {
      this.currentPage = page
    },
    clearFilters() {
      this.localFilters = {}
      this.searchQuery = ''
    },
    applyFilters() {
      this.$emit('filters-changed', this.localFilters)
      this.showFilters = false
    },
    getStatusColor(status) {
      const colors = {
        active: 'bg-green-400',
        inactive: 'bg-red-400',
        pending: 'bg-yellow-400',
        completed: 'bg-blue-400'
      }
      return colors[status] || 'bg-gray-400'
    },
    formatNumber(num) {
      return new Intl.NumberFormat().format(num || 0)
    },
    formatDate(date) {
      if (!date) return ''
      return new Date(date).toLocaleDateString()
    }
  }
}
</script>

<style scoped>
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

/* Table hover effects */
tbody tr:hover {
  background-color: rgba(255, 255, 255, 0.05);
}

/* Responsive table */
@media (max-width: 768px) {
  .overflow-x-auto {
    -webkit-overflow-scrolling: touch;
  }
}
</style>
