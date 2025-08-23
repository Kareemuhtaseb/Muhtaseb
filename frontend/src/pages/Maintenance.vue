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
          <h1 class="text-3xl font-bold text-gray-900 mb-2">Maintenance Requests</h1>
          <p class="text-gray-600">Track and manage property maintenance requests</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/20 shadow-lg">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Total Requests</p>
                <p class="text-2xl font-bold text-gray-900">{{ totalRequests }}</p>
              </div>
              <div class="p-3 bg-blue-100 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
              </div>
            </div>
          </div>

          <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/20 shadow-lg">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Open Requests</p>
                <p class="text-2xl font-bold text-gray-900">{{ openRequests }}</p>
              </div>
              <div class="p-3 bg-yellow-100 rounded-full">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
            </div>
          </div>

          <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/20 shadow-lg">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">In Progress</p>
                <p class="text-2xl font-bold text-gray-900">{{ inProgressRequests }}</p>
              </div>
              <div class="p-3 bg-orange-100 rounded-full">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
              </div>
            </div>
          </div>

          <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/20 shadow-lg">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Completed</p>
                <p class="text-2xl font-bold text-gray-900">{{ completedRequests }}</p>
              </div>
              <div class="p-3 bg-green-100 rounded-full">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
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
                New Request
              </button>
            </div>

            <div class="flex items-center gap-4">
              <div class="relative">
                <input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Search requests..."
                  class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </div>
              
              <select
                v-model="statusFilter"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="">All Status</option>
                <option value="open">Open</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Maintenance Requests Table -->
        <div class="bg-white/60 backdrop-blur-sm rounded-xl border border-white/20 shadow-lg overflow-hidden">
          <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Maintenance Requests</h2>
          </div>
          
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50/50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="request in filteredRequests" :key="request.id" class="hover:bg-gray-50/50">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                          <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                          </svg>
                        </div>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">{{ request.title }}</div>
                        <div class="text-sm text-gray-500">{{ request.description.substring(0, 50) }}...</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ request.property_name }}</div>
                    <div class="text-sm text-gray-500">{{ request.unit_number }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="getPriorityClass(request.priority)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                      {{ request.priority }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="getStatusClass(request.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                      {{ request.status }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ formatDate(request.created_at) }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button
                      @click="viewRequest(request)"
                      class="text-blue-600 hover:text-blue-900 mr-3"
                    >
                      View
                    </button>
                    <button
                      @click="editRequest(request)"
                      class="text-indigo-600 hover:text-indigo-900 mr-3"
                    >
                      Edit
                    </button>
                    <button
                      @click="deleteRequest(request.id)"
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
                    Showing <span class="font-medium">{{ startIndex + 1 }}</span> to <span class="font-medium">{{ endIndex }}</span> of <span class="font-medium">{{ filteredRequests.length }}</span> results
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
    <Modal v-if="showCreateModal" @close="showCreateModal = false" :title="editingRequest ? 'Edit Maintenance Request' : 'New Maintenance Request'">
      <form @submit.prevent="saveRequest" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
            <input
              v-model="form.title"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="Request title"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Property</label>
            <select v-model="form.property_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <option value="">Select Property</option>
              <option v-for="property in properties" :key="property.id" :value="property.id">{{ property.name }}</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
            <select v-model="form.priority" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <option value="low">Low</option>
              <option value="medium">Medium</option>
              <option value="high">High</option>
              <option value="urgent">Urgent</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select v-model="form.status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <option value="open">Open</option>
              <option value="in_progress">In Progress</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
          <textarea
            v-model="form.description"
            rows="4"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="Describe the maintenance issue..."
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
            {{ editingRequest ? 'Update' : 'Create' }}
          </button>
        </div>
      </form>
    </Modal>
  </div>
</template>

<script>
import Modal from '../components/Modal.vue'

export default {
  name: 'Maintenance',
  components: {
    Modal
  },
  data() {
    return {
      loading: true,
      requests: [],
      properties: [],
      searchQuery: '',
      statusFilter: '',
      currentPage: 1,
      itemsPerPage: 10,
      showCreateModal: false,
      editingRequest: null,
      form: {
        title: '',
        description: '',
        property_id: '',
        priority: 'medium',
        status: 'open'
      }
    }
  },
  computed: {
    filteredRequests() {
      let filtered = this.requests
      
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase()
        filtered = filtered.filter(request => 
          request.title.toLowerCase().includes(query) ||
          request.description.toLowerCase().includes(query) ||
          request.property_name.toLowerCase().includes(query)
        )
      }
      
      if (this.statusFilter) {
        filtered = filtered.filter(request => request.status === this.statusFilter)
      }
      
      return filtered
    },
    paginatedRequests() {
      const start = (this.currentPage - 1) * this.itemsPerPage
      const end = start + this.itemsPerPage
      return this.filteredRequests.slice(start, end)
    },
    totalPages() {
      return Math.ceil(this.filteredRequests.length / this.itemsPerPage)
    },
    startIndex() {
      return (this.currentPage - 1) * this.itemsPerPage
    },
    endIndex() {
      return Math.min(this.startIndex + this.itemsPerPage, this.filteredRequests.length)
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
    totalRequests() {
      return this.requests.length
    },
    openRequests() {
      return this.requests.filter(r => r.status === 'open').length
    },
    inProgressRequests() {
      return this.requests.filter(r => r.status === 'in_progress').length
    },
    completedRequests() {
      return this.requests.filter(r => r.status === 'completed').length
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
          this.loadRequests(),
          this.loadProperties()
        ])
      } catch (error) {
        console.error('Error loading data:', error)
      } finally {
        this.loading = false
      }
    },
    
    async loadRequests() {
      try {
        const response = await this.$http.get('/maintenance-requests')
        this.requests = response.data?.data?.data || response.data?.data || response.data || []
      } catch (error) {
        console.error('Error loading maintenance requests:', error)
        // Mock data for development
        this.requests = [
          {
            id: 1,
            title: 'Leaky Faucet',
            description: 'Kitchen faucet is leaking and needs repair',
            property_name: 'Sunset Apartments',
            unit_number: 'A101',
            priority: 'medium',
            status: 'open',
            created_at: '2024-01-15'
          },
          {
            id: 2,
            title: 'Broken Window',
            description: 'Window in living room is cracked and needs replacement',
            property_name: 'Ocean View',
            unit_number: 'B205',
            priority: 'high',
            status: 'in_progress',
            created_at: '2024-01-20'
          }
        ]
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
    
    async saveRequest() {
      try {
        const url = this.editingRequest 
          ? `/maintenance-requests/${this.editingRequest.id}`
          : '/maintenance-requests'
        if (this.editingRequest) {
          await this.$http.put(url, this.form)
        } else {
          await this.$http.post(url, this.form)
        }
        await this.loadRequests()
        this.showCreateModal = false
        this.resetForm()
        alert(this.editingRequest ? 'Request updated successfully!' : 'Request created successfully!')
      } catch (error) {
        console.error('Error saving request:', error)
        alert('Error saving request')
      }
    },
    
    editRequest(request) {
      this.editingRequest = request
      this.form = {
        title: request.title,
        description: request.description,
        property_id: request.property_id,
        priority: request.priority,
        status: request.status
      }
      this.showCreateModal = true
    },
    
    async deleteRequest(id) {
      if (!confirm('Are you sure you want to delete this request?')) {
        return
      }
      
      try {
        await this.$http.delete(`/maintenance-requests/${id}`)
        await this.loadRequests()
        alert('Request deleted successfully!')
      } catch (error) {
        console.error('Error deleting request:', error)
        alert('Error deleting request')
      }
    },
    
    viewRequest(request) {
      // Implement view functionality
      console.log('View request:', request)
    },
    
    resetForm() {
      this.form = {
        title: '',
        description: '',
        property_id: '',
        priority: 'medium',
        status: 'open'
      }
      this.editingRequest = null
    },
    
    getPriorityClass(priority) {
      switch (priority) {
        case 'urgent':
          return 'bg-red-100 text-red-800'
        case 'high':
          return 'bg-orange-100 text-orange-800'
        case 'medium':
          return 'bg-yellow-100 text-yellow-800'
        case 'low':
          return 'bg-green-100 text-green-800'
        default:
          return 'bg-gray-100 text-gray-800'
      }
    },
    
    getStatusClass(status) {
      switch (status) {
        case 'completed':
          return 'bg-green-100 text-green-800'
        case 'in_progress':
          return 'bg-blue-100 text-blue-800'
        case 'open':
          return 'bg-yellow-100 text-yellow-800'
        case 'cancelled':
          return 'bg-red-100 text-red-800'
        default:
          return 'bg-gray-100 text-gray-800'
      }
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
