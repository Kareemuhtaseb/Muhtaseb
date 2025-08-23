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
          <h1 class="text-3xl font-bold text-gray-900 mb-2">Notifications</h1>
          <p class="text-gray-600">Manage system notifications and alerts</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/20 shadow-lg">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Total Notifications</p>
                <p class="text-2xl font-bold text-gray-900">{{ totalNotifications }}</p>
              </div>
              <div class="p-3 bg-blue-100 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.19 4.19A2 2 0 006 3h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2z"></path>
                </svg>
              </div>
            </div>
          </div>

          <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/20 shadow-lg">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Unread</p>
                <p class="text-2xl font-bold text-gray-900">{{ unreadNotifications }}</p>
              </div>
              <div class="p-3 bg-red-100 rounded-full">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
            </div>
          </div>

          <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/20 shadow-lg">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">Today</p>
                <p class="text-2xl font-bold text-gray-900">{{ todayNotifications }}</p>
              </div>
              <div class="p-3 bg-green-100 rounded-full">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
              </div>
            </div>
          </div>

          <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/20 shadow-lg">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-600">This Week</p>
                <p class="text-2xl font-bold text-gray-900">{{ weeklyNotifications }}</p>
              </div>
              <div class="p-3 bg-purple-100 rounded-full">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
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
                @click="markAllAsRead"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Mark All as Read
              </button>
              
              <button
                @click="clearAll"
                class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Clear All
              </button>
            </div>

            <div class="flex items-center gap-4">
              <div class="relative">
                <input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Search notifications..."
                  class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </div>
              
              <select
                v-model="typeFilter"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="">All Types</option>
                <option value="payment">Payment</option>
                <option value="maintenance">Maintenance</option>
                <option value="system">System</option>
                <option value="alert">Alert</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Notifications List -->
        <div class="bg-white/60 backdrop-blur-sm rounded-xl border border-white/20 shadow-lg overflow-hidden">
          <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Notifications</h2>
          </div>
          
          <div class="divide-y divide-gray-200">
            <div 
              v-for="notification in filteredNotifications" 
              :key="notification.id" 
              :class="[
                'p-6 hover:bg-gray-50/50 transition-colors duration-200 cursor-pointer',
                !notification.read_at ? 'bg-blue-50/50' : ''
              ]"
              @click="markAsRead(notification)"
            >
              <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                  <div :class="getNotificationIconClass(notification.type)" class="h-10 w-10 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path v-if="notification.type === 'payment'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                      <path v-else-if="notification.type === 'maintenance'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                      <path v-else-if="notification.type === 'system'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                      <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                </div>
                
                <div class="flex-1 min-w-0">
                  <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-900">
                      {{ notification.title }}
                    </p>
                    <div class="flex items-center space-x-2">
                      <span class="text-xs text-gray-500">{{ formatTime(notification.created_at) }}</span>
                      <button
                        @click.stop="deleteNotification(notification.id)"
                        class="text-gray-400 hover:text-red-500 transition-colors duration-200"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                      </button>
                    </div>
                  </div>
                  <p class="text-sm text-gray-600 mt-1">{{ notification.message }}</p>
                  <div class="flex items-center mt-2 space-x-4">
                    <span :class="getTypeClass(notification.type)" class="px-2 py-1 text-xs font-medium rounded-full">
                      {{ notification.type }}
                    </span>
                    <span v-if="!notification.read_at" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                      New
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-if="filteredNotifications.length === 0" class="p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.19 4.19A2 2 0 006 3h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No notifications</h3>
            <p class="mt-1 text-sm text-gray-500">You're all caught up!</p>
          </div>

          <!-- Pagination -->
          <div v-if="filteredNotifications.length > 0" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
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
                    Showing <span class="font-medium">{{ startIndex + 1 }}</span> to <span class="font-medium">{{ endIndex }}</span> of <span class="font-medium">{{ filteredNotifications.length }}</span> results
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
  </div>
</template>

<script>
export default {
  name: 'Notifications',
  data() {
    return {
      loading: true,
      notifications: [],
      searchQuery: '',
      typeFilter: '',
      currentPage: 1,
      itemsPerPage: 10
    }
  },
  computed: {
    filteredNotifications() {
      let filtered = this.notifications
      
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase()
        filtered = filtered.filter(notification => 
          notification.title.toLowerCase().includes(query) ||
          notification.message.toLowerCase().includes(query)
        )
      }
      
      if (this.typeFilter) {
        filtered = filtered.filter(notification => notification.type === this.typeFilter)
      }
      
      return filtered
    },
    paginatedNotifications() {
      const start = (this.currentPage - 1) * this.itemsPerPage
      const end = start + this.itemsPerPage
      return this.filteredNotifications.slice(start, end)
    },
    totalPages() {
      return Math.ceil(this.filteredNotifications.length / this.itemsPerPage)
    },
    startIndex() {
      return (this.currentPage - 1) * this.itemsPerPage
    },
    endIndex() {
      return Math.min(this.startIndex + this.itemsPerPage, this.filteredNotifications.length)
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
    totalNotifications() {
      return this.notifications.length
    },
    unreadNotifications() {
      return this.notifications.filter(n => !n.read_at).length
    },
    todayNotifications() {
      const today = new Date().toDateString()
      return this.notifications.filter(n => 
        new Date(n.created_at).toDateString() === today
      ).length
    },
    weeklyNotifications() {
      const weekAgo = new Date()
      weekAgo.setDate(weekAgo.getDate() - 7)
      return this.notifications.filter(n => 
        new Date(n.created_at) >= weekAgo
      ).length
    }
  },
  async mounted() {
    await this.loadNotifications()
  },
  methods: {
    async loadNotifications() {
      try {
        this.loading = true
        const response = await this.$http.get('/notifications')
        this.notifications = response.data?.data?.data || response.data?.data || response.data || []
      } catch (error) {
        console.error('Error loading notifications:', error)
        // Mock data for development
        this.notifications = [
          {
            id: 1,
            title: 'Payment Received',
            message: 'Payment of $1,200 has been received for Unit A101',
            type: 'payment',
            read_at: null,
            created_at: '2024-01-15T10:30:00Z'
          },
          {
            id: 2,
            title: 'Maintenance Request',
            message: 'New maintenance request submitted for Unit B205',
            type: 'maintenance',
            read_at: '2024-01-15T09:15:00Z',
            created_at: '2024-01-15T09:00:00Z'
          },
          {
            id: 3,
            title: 'System Update',
            message: 'System maintenance scheduled for tonight at 2 AM',
            type: 'system',
            read_at: null,
            created_at: '2024-01-14T16:45:00Z'
          }
        ]
      } finally {
        this.loading = false
      }
    },
    
    async markAsRead(notification) {
      if (notification.read_at) return
      
      try {
        await this.$http.post(`/notifications/${notification.id}/read`)
        notification.read_at = new Date().toISOString()
      } catch (error) {
        console.error('Error marking notification as read:', error)
        // For development, just mark as read locally
        notification.read_at = new Date().toISOString()
      }
    },
    
    async markAllAsRead() {
      try {
        await this.$http.post('/notifications/mark-all-read')
        this.notifications.forEach(n => {
          if (!n.read_at) {
            n.read_at = new Date().toISOString()
          }
        })
        alert('All notifications marked as read!')
      } catch (error) {
        console.error('Error marking all notifications as read:', error)
        // For development, just mark all as read locally
        this.notifications.forEach(n => {
          if (!n.read_at) {
            n.read_at = new Date().toISOString()
          }
        })
        alert('All notifications marked as read!')
      }
    },
    
    async clearAll() {
      if (!confirm('Are you sure you want to clear all notifications?')) {
        return
      }
      
      try {
        await this.$http.delete('/notifications/clear-all')
        this.notifications = []
        alert('All notifications cleared!')
      } catch (error) {
        console.error('Error clearing notifications:', error)
        // For development, just clear locally
        this.notifications = []
        alert('All notifications cleared!')
      }
    },
    
    async deleteNotification(id) {
      try {
        await this.$http.delete(`/notifications/${id}`)
        this.notifications = this.notifications.filter(n => n.id !== id)
      } catch (error) {
        console.error('Error deleting notification:', error)
        // For development, just remove locally
        this.notifications = this.notifications.filter(n => n.id !== id)
      }
    },
    
    getNotificationIconClass(type) {
      switch (type) {
        case 'payment':
          return 'bg-green-100 text-green-600'
        case 'maintenance':
          return 'bg-orange-100 text-orange-600'
        case 'system':
          return 'bg-blue-100 text-blue-600'
        case 'alert':
          return 'bg-red-100 text-red-600'
        default:
          return 'bg-gray-100 text-gray-600'
      }
    },
    
    getTypeClass(type) {
      switch (type) {
        case 'payment':
          return 'bg-green-100 text-green-800'
        case 'maintenance':
          return 'bg-orange-100 text-orange-800'
        case 'system':
          return 'bg-blue-100 text-blue-800'
        case 'alert':
          return 'bg-red-100 text-red-800'
        default:
          return 'bg-gray-100 text-gray-800'
      }
    },
    
    formatTime(dateString) {
      const date = new Date(dateString)
      const now = new Date()
      const diffInHours = (now - date) / (1000 * 60 * 60)
      
      if (diffInHours < 1) {
        const diffInMinutes = Math.floor((now - date) / (1000 * 60))
        return `${diffInMinutes}m ago`
      } else if (diffInHours < 24) {
        return `${Math.floor(diffInHours)}h ago`
      } else {
        return date.toLocaleDateString('en-US', {
          month: 'short',
          day: 'numeric'
        })
      }
    }
  }
}
</script>
