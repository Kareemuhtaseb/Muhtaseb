<template>
  <div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-white">Leases</h1>
        <p class="text-white/60 mt-2">Manage tenant leases and agreements</p>
      </div>
      <button 
        @click="showAddModal = true"
        class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 hover-lift flex items-center space-x-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        <span>Add Lease</span>
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center h-64">
      <div class="text-center">
        <div class="w-16 h-16 border-4 border-white/20 border-t-white rounded-full animate-spin mx-auto mb-4"></div>
        <p class="text-white/60">Loading leases...</p>
      </div>
    </div>

    <!-- Leases Table -->
    <div v-else class="glass rounded-2xl overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-white/5">
            <tr>
              <th class="px-6 py-4 text-left text-white/80 font-medium">Unit</th>
              <th class="px-6 py-4 text-left text-white/80 font-medium">Tenant</th>
              <th class="px-6 py-4 text-left text-white/80 font-medium">Monthly Rent</th>
              <th class="px-6 py-4 text-left text-white/80 font-medium">Start Date</th>
              <th class="px-6 py-4 text-left text-white/80 font-medium">End Date</th>
              <th class="px-6 py-4 text-left text-white/80 font-medium">Status</th>
              <th class="px-6 py-4 text-left text-white/80 font-medium">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-white/10">
            <tr v-for="lease in leases" :key="lease.id" class="hover:bg-white/5 transition-colors duration-200">
              <td class="px-6 py-4">
                <div>
                  <p class="text-white font-medium">{{ lease.unit_number }}</p>
                  <p class="text-white/60 text-sm">{{ lease.property_name }}</p>
                </div>
              </td>
              <td class="px-6 py-4">
                <p class="text-white font-medium">{{ lease.tenant_name }}</p>
              </td>
              <td class="px-6 py-4">
                <p class="text-white font-semibold">${{ formatNumber(lease.monthly_rent) }}</p>
              </td>
              <td class="px-6 py-4">
                <p class="text-white">{{ formatDate(lease.start_date) }}</p>
              </td>
              <td class="px-6 py-4">
                <p class="text-white">{{ lease.end_date ? formatDate(lease.end_date) : 'Ongoing' }}</p>
              </td>
              <td class="px-6 py-4">
                <span :class="getStatusClass(lease.status)" class="px-3 py-1 rounded-full text-xs font-medium">
                  {{ lease.status }}
                </span>
              </td>
              <td class="px-6 py-4">
                <div class="flex space-x-2">
                  <button 
                    @click="editLease(lease)"
                    class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center text-white/60 hover:text-white transition-all duration-200"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                  </button>
                  <button 
                    @click="deleteLease(lease.id)"
                    class="w-8 h-8 bg-red-500/10 hover:bg-red-500/20 rounded-lg flex items-center justify-center text-red-400 hover:text-red-300 transition-all duration-200"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Empty State -->
      <div v-if="leases.length === 0" class="text-center py-16">
        <div class="w-24 h-24 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-6">
          <svg class="w-12 h-12 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-white mb-2">No Leases Yet</h3>
        <p class="text-white/60 mb-6">Get started by creating your first lease agreement</p>
        <button 
          @click="showAddModal = true"
          class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 hover-lift"
        >
          Create Your First Lease
        </button>
      </div>
    </div>

    <!-- Add/Edit Lease Modal -->
    <div v-if="showAddModal || showEditModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
      <div class="glass rounded-2xl p-8 w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-white">
            {{ showEditModal ? 'Edit Lease' : 'Add Lease' }}
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

        <form @submit.prevent="saveLease" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-white/80 text-sm font-medium mb-2">Unit</label>
              <select 
                v-model="form.unit_id"
                required
                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              >
                <option value="">Select Unit</option>
                <option v-for="unit in units" :key="unit.id" :value="unit.id">
                  {{ unit.unit_number }} - {{ unit.property_name }}
                </option>
              </select>
            </div>

            <div>
              <label class="block text-white/80 text-sm font-medium mb-2">Tenant</label>
              <select 
                v-model="form.tenant_id"
                required
                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              >
                <option value="">Select Tenant</option>
                <option v-for="tenant in tenants" :key="tenant.id" :value="tenant.id">
                  {{ tenant.full_name }}
                </option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-white/80 text-sm font-medium mb-2">Monthly Rent</label>
              <input 
                v-model="form.monthly_rent"
                type="number"
                step="0.01"
                required
                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                placeholder="0.00"
              />
            </div>

            <div>
              <label class="block text-white/80 text-sm font-medium mb-2">Status</label>
              <select 
                v-model="form.status"
                required
                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              >
                <option value="active">Active</option>
                <option value="pending">Pending</option>
                <option value="expired">Expired</option>
                <option value="terminated">Terminated</option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-white/80 text-sm font-medium mb-2">Start Date</label>
              <input 
                v-model="form.start_date"
                type="date"
                required
                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              />
            </div>

            <div>
              <label class="block text-white/80 text-sm font-medium mb-2">End Date (Optional)</label>
              <input 
                v-model="form.end_date"
                type="date"
                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              />
            </div>
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
  name: 'Leases',
  data() {
    return {
      loading: true,
      saving: false,
      leases: [],
      units: [],
      tenants: [],
      showAddModal: false,
      showEditModal: false,
      selectedLease: null,
      form: {
        unit_id: '',
        tenant_id: '',
        monthly_rent: '',
        start_date: '',
        end_date: '',
        status: 'active'
      }
    }
  },
  async mounted() {
    await Promise.all([
      this.loadLeases(),
      this.loadUnits(),
      this.loadTenants()
    ])
  },
  methods: {
    async loadLeases() {
      try {
        const response = await this.$http.get('/leases.php')
        this.leases = response.data
      } catch (error) {
        console.error('Error loading leases:', error)
        this.leases = []
      } finally {
        this.loading = false
      }
    },
    async loadUnits() {
      try {
        const response = await this.$http.get('/units.php')
        this.units = response.data
      } catch (error) {
        console.error('Error loading units:', error)
        this.units = []
      }
    },
    async loadTenants() {
      try {
        const response = await this.$http.get('/tenants.php')
        this.tenants = response.data
      } catch (error) {
        console.error('Error loading tenants:', error)
        this.tenants = []
      }
    },
    editLease(lease) {
      this.selectedLease = lease
      this.form = { ...lease }
      this.showEditModal = true
    },
    async deleteLease(id) {
      if (!confirm('Are you sure you want to delete this lease?')) {
        return
      }

      try {
        await this.$http.delete(`/leases.php?id=${id}`)
        await this.loadLeases()
      } catch (error) {
        console.error('Error deleting lease:', error)
        alert('Failed to delete lease')
      }
    },
    async saveLease() {
      this.saving = true
      try {
        if (this.showEditModal) {
          await this.$http.put(`/leases.php?id=${this.selectedLease.id}`, this.form)
        } else {
          await this.$http.post('/leases.php', this.form)
        }
        await this.loadLeases()
        this.closeModal()
      } catch (error) {
        console.error('Error saving lease:', error)
        alert('Failed to save lease')
      } finally {
        this.saving = false
      }
    },
    closeModal() {
      this.showAddModal = false
      this.showEditModal = false
      this.selectedLease = null
      this.form = {
        unit_id: '',
        tenant_id: '',
        monthly_rent: '',
        start_date: '',
        end_date: '',
        status: 'active'
      }
    },
    formatNumber(num) {
      return new Intl.NumberFormat().format(num)
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString()
    },
    getStatusClass(status) {
      const classes = {
        active: 'bg-green-500/20 text-green-400',
        pending: 'bg-yellow-500/20 text-yellow-400',
        expired: 'bg-red-500/20 text-red-400',
        terminated: 'bg-gray-500/20 text-gray-400'
      }
      return classes[status] || classes.pending
    }
  }
}
</script>
