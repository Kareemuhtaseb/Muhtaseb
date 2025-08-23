<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const tenants = ref([])
const units = ref([])
const form = ref({name:'',phone:'',email:'',unit_id:'',start_date:'',end_date:''})
const loading = ref(false)

async function load(){
  try {
    loading.value = true
    const [tenantsResponse, unitsResponse] = await Promise.all([
      axios.get('/tenants.php'),
      axios.get('/units.php')
    ])
    tenants.value = tenantsResponse.data
    units.value = unitsResponse.data
  } catch (error) {
    console.error('Error loading data:', error)
  } finally {
    loading.value = false
  }
}

onMounted(load)

async function submit(){
  try {
    await axios.post('/tenants.php', form.value)
    form.value = {name:'',phone:'',email:'',unit_id:'',start_date:'',end_date:''}
    await load()
  } catch (error) {
    console.error('Error adding tenant:', error)
  }
}

async function remove(id){
  try {
    await axios.delete(`/tenants.php?id=${id}`)
    await load()
  } catch (error) {
    console.error('Error removing tenant:', error)
  }
}
</script>

<template>
  <div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-white">Tenants</h1>
        <p class="text-white/60 mt-2">Manage your property tenants and their information</p>
      </div>
      <button 
        @click="showAddModal = true"
        class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 hover-lift flex items-center space-x-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        <span>Add Tenant</span>
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Total Tenants</p>
            <p class="text-3xl font-bold text-white mt-2">{{ tenants.length }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Active Leases</p>
            <p class="text-3xl font-bold text-white mt-2">{{ activeTenants }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Expiring Soon</p>
            <p class="text-3xl font-bold text-white mt-2">{{ expiringSoon }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Vacant Units</p>
            <p class="text-3xl font-bold text-white mt-2">{{ vacantUnits }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center h-64">
      <div class="text-center">
        <div class="w-16 h-16 border-4 border-white/20 border-t-white rounded-full animate-spin mx-auto mb-4"></div>
        <p class="text-white/60">Loading tenants...</p>
      </div>
    </div>

    <!-- Tenants Table -->
    <div v-else class="glass rounded-2xl overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-white/5">
            <tr>
              <th class="px-6 py-4 text-left text-white/80 font-medium">Tenant</th>
              <th class="px-6 py-4 text-left text-white/80 font-medium">Contact</th>
              <th class="px-6 py-4 text-left text-white/80 font-medium">Unit</th>
              <th class="px-6 py-4 text-left text-white/80 font-medium">Lease Period</th>
              <th class="px-6 py-4 text-left text-white/80 font-medium">Status</th>
              <th class="px-6 py-4 text-left text-white/80 font-medium">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-white/10">
            <tr v-for="tenant in tenants" :key="tenant.id" class="hover:bg-white/5 transition-colors duration-200">
              <td class="px-6 py-4">
                <div>
                  <p class="text-white font-medium">{{ tenant.full_name }}</p>
                  <p class="text-white/60 text-sm">{{ tenant.email }}</p>
                </div>
              </td>
              <td class="px-6 py-4">
                <p class="text-white">{{ tenant.contact }}</p>
              </td>
              <td class="px-6 py-4">
                <p class="text-white">{{ tenant.unit_number || 'Not Assigned' }}</p>
              </td>
              <td class="px-6 py-4">
                <div>
                  <p class="text-white text-sm">{{ formatDate(tenant.lease_start) }}</p>
                  <p class="text-white/60 text-sm">to {{ tenant.lease_end ? formatDate(tenant.lease_end) : 'Ongoing' }}</p>
                </div>
              </td>
              <td class="px-6 py-4">
                <span :class="getStatusClass(tenant)" class="px-3 py-1 rounded-full text-xs font-medium">
                  {{ getStatusText(tenant) }}
                </span>
              </td>
              <td class="px-6 py-4">
                <div class="flex space-x-2">
                  <button 
                    @click="editTenant(tenant)"
                    class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center text-white/60 hover:text-white transition-all duration-200"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                  </button>
                  <button 
                    @click="remove(tenant.id)"
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
      <div v-if="tenants.length === 0" class="text-center py-16">
        <div class="w-24 h-24 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-6">
          <svg class="w-12 h-12 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-white mb-2">No Tenants Yet</h3>
        <p class="text-white/60 mb-6">Get started by adding your first tenant</p>
        <button 
          @click="showAddModal = true"
          class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 hover-lift"
        >
          Add Your First Tenant
        </button>
      </div>
    </div>

    <!-- Add/Edit Tenant Modal -->
    <div v-if="showAddModal || showEditModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
      <div class="glass rounded-2xl p-8 w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-white">
            {{ showEditModal ? 'Edit Tenant' : 'Add Tenant' }}
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

        <form @submit.prevent="submit" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-white/80 text-sm font-medium mb-2">Full Name</label>
              <input 
                v-model="form.full_name"
                type="text"
                required
                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                placeholder="Enter full name"
              />
            </div>

            <div>
              <label class="block text-white/80 text-sm font-medium mb-2">Contact</label>
              <input 
                v-model="form.contact"
                type="text"
                required
                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                placeholder="Enter phone number"
              />
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-white/80 text-sm font-medium mb-2">Lease Start Date</label>
              <input 
                v-model="form.lease_start"
                type="date"
                required
                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              />
            </div>

            <div>
              <label class="block text-white/80 text-sm font-medium mb-2">Lease End Date (Optional)</label>
              <input 
                v-model="form.lease_end"
                type="date"
                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              />
            </div>
          </div>

          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Deposit Amount</label>
            <input 
              v-model="form.deposit"
              type="number"
              step="0.01"
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              placeholder="0.00"
            />
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
  name: 'Tenants',
  data() {
    return {
      loading: true,
      saving: false,
      tenants: [],
      units: [],
      showAddModal: false,
      showEditModal: false,
      selectedTenant: null,
      form: {
        full_name: '',
        contact: '',
        lease_start: '',
        lease_end: '',
        deposit: ''
      }
    }
  },
  computed: {
    activeTenants() {
      return this.tenants.filter(tenant => {
        if (!tenant.lease_end) return true
        return new Date(tenant.lease_end) > new Date()
      }).length
    },
    expiringSoon() {
      const thirtyDaysFromNow = new Date()
      thirtyDaysFromNow.setDate(thirtyDaysFromNow.getDate() + 30)
      
      return this.tenants.filter(tenant => {
        if (!tenant.lease_end) return false
        const endDate = new Date(tenant.lease_end)
        return endDate <= thirtyDaysFromNow && endDate > new Date()
      }).length
    },
    vacantUnits() {
      const occupiedUnits = this.tenants.filter(t => t.unit_id).length
      return Math.max(0, this.units.length - occupiedUnits)
    }
  },
  async mounted() {
    await this.load()
  },
  methods: {
    async load() {
      try {
        this.loading = true
        const [tenantsResponse, unitsResponse] = await Promise.all([
          this.$http.get('/tenants.php'),
          this.$http.get('/units.php')
        ])
        this.tenants = tenantsResponse.data
        this.units = unitsResponse.data
      } catch (error) {
        console.error('Error loading data:', error)
      } finally {
        this.loading = false
      }
    },
    editTenant(tenant) {
      this.selectedTenant = tenant
      this.form = { ...tenant }
      this.showEditModal = true
    },
    async remove(id) {
      if (!confirm('Are you sure you want to delete this tenant?')) {
        return
      }

      try {
        await this.$http.delete(`/tenants.php?id=${id}`)
        await this.load()
      } catch (error) {
        console.error('Error removing tenant:', error)
        alert('Failed to delete tenant')
      }
    },
    async submit() {
      this.saving = true
      try {
        if (this.showEditModal) {
          await this.$http.put(`/tenants.php?id=${this.selectedTenant.id}`, this.form)
        } else {
          await this.$http.post('/tenants.php', this.form)
        }
        await this.load()
        this.closeModal()
      } catch (error) {
        console.error('Error saving tenant:', error)
        alert('Failed to save tenant')
      } finally {
        this.saving = false
      }
    },
    closeModal() {
      this.showAddModal = false
      this.showEditModal = false
      this.selectedTenant = null
      this.form = {
        full_name: '',
        contact: '',
        lease_start: '',
        lease_end: '',
        deposit: ''
      }
    },
    formatDate(date) {
      if (!date) return 'N/A'
      return new Date(date).toLocaleDateString()
    },
    getStatusClass(tenant) {
      if (!tenant.lease_end) return 'bg-green-500/20 text-green-400'
      const endDate = new Date(tenant.lease_end)
      if (endDate < new Date()) return 'bg-red-500/20 text-red-400'
      if (endDate <= new Date(Date.now() + 30 * 24 * 60 * 60 * 1000)) return 'bg-yellow-500/20 text-yellow-400'
      return 'bg-green-500/20 text-green-400'
    },
    getStatusText(tenant) {
      if (!tenant.lease_end) return 'Active'
      const endDate = new Date(tenant.lease_end)
      if (endDate < new Date()) return 'Expired'
      if (endDate <= new Date(Date.now() + 30 * 24 * 60 * 60 * 1000)) return 'Expiring Soon'
      return 'Active'
    }
  }
}
</script>
