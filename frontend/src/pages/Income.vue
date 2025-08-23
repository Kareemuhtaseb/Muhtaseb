<template>
  <div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-white">Income</h1>
        <p class="text-white/60 mt-2">Track and manage income records</p>
      </div>
      <button 
        @click="showAddModal = true"
        class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 hover-lift flex items-center space-x-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        <span>Add Income</span>
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Total Income</p>
            <p class="text-3xl font-bold text-white mt-2">${{ formatNumber(totalIncome) }}</p>
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
            <p class="text-white/60 text-sm font-medium">This Month</p>
            <p class="text-3xl font-bold text-white mt-2">${{ formatNumber(currentMonthIncome) }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Records</p>
            <p class="text-3xl font-bold text-white mt-2">{{ incomeRecords.length }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center h-64">
      <div class="text-center">
        <div class="w-16 h-16 border-4 border-white/20 border-t-white rounded-full animate-spin mx-auto mb-4"></div>
        <p class="text-white/60">Loading income records...</p>
      </div>
    </div>

    <!-- Income Records Table -->
    <div v-else class="glass rounded-2xl overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-white/5">
            <tr>
              <th class="px-6 py-4 text-left text-white/80 font-medium">Date</th>
              <th class="px-6 py-4 text-left text-white/80 font-medium">Property</th>
              <th class="px-6 py-4 text-left text-white/80 font-medium">Unit</th>
              <th class="px-6 py-4 text-left text-white/80 font-medium">Tenant</th>
              <th class="px-6 py-4 text-left text-white/80 font-medium">Category</th>
              <th class="px-6 py-4 text-left text-white/80 font-medium">Payee</th>
              <th class="px-6 py-4 text-left text-white/80 font-medium">Amount</th>
              <th class="px-6 py-4 text-left text-white/80 font-medium">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-white/10">
            <tr v-for="record in incomeRecords" :key="record.id" class="hover:bg-white/5 transition-colors duration-200">
              <td class="px-6 py-4">
                <p class="text-white">{{ formatDate(record.date) }}</p>
              </td>
              <td class="px-6 py-4">
                <p class="text-white font-medium">{{ record.property_name }}</p>
              </td>
              <td class="px-6 py-4">
                <p class="text-white">{{ record.unit_number || '-' }}</p>
              </td>
              <td class="px-6 py-4">
                <p class="text-white">{{ record.tenant_name || '-' }}</p>
              </td>
              <td class="px-6 py-4">
                <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-medium">
                  {{ record.category_name }}
                </span>
              </td>
              <td class="px-6 py-4">
                <p class="text-white">{{ record.payee }}</p>
              </td>
              <td class="px-6 py-4">
                <p class="text-green-400 font-semibold">${{ formatNumber(record.amount) }}</p>
              </td>
              <td class="px-6 py-4">
                <div class="flex space-x-2">
                  <button 
                    @click="editRecord(record)"
                    class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center text-white/60 hover:text-white transition-all duration-200"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                  </button>
                  <button 
                    @click="deleteRecord(record.id)"
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
      <div v-if="incomeRecords.length === 0" class="text-center py-16">
        <div class="w-24 h-24 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-6">
          <svg class="w-12 h-12 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-white mb-2">No Income Records Yet</h3>
        <p class="text-white/60 mb-6">Start tracking your income by adding your first record</p>
        <button 
          @click="showAddModal = true"
          class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 hover-lift"
        >
          Add Your First Income Record
        </button>
      </div>
    </div>

    <!-- Add/Edit Income Modal -->
    <div v-if="showAddModal || showEditModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
      <div class="glass rounded-2xl p-8 w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-white">
            {{ showEditModal ? 'Edit Income Record' : 'Add Income Record' }}
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

        <form @submit.prevent="saveRecord" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-white/80 text-sm font-medium mb-2">Property</label>
              <select 
                v-model="form.property_id"
                required
                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              >
                <option value="">Select Property</option>
                <option v-for="property in properties" :key="property.id" :value="property.id">
                  {{ property.name }}
                </option>
              </select>
            </div>

            <div>
              <label class="block text-white/80 text-sm font-medium mb-2">Unit (Optional)</label>
              <select 
                v-model="form.unit_id"
                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              >
                <option value="">Select Unit</option>
                <option v-for="unit in filteredUnits" :key="unit.id" :value="unit.id">
                  {{ unit.unit_number }}
                </option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-white/80 text-sm font-medium mb-2">Tenant (Optional)</label>
              <select 
                v-model="form.tenant_id"
                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              >
                <option value="">Select Tenant</option>
                <option v-for="tenant in tenants" :key="tenant.id" :value="tenant.id">
                  {{ tenant.full_name }}
                </option>
              </select>
            </div>

            <div>
              <label class="block text-white/80 text-sm font-medium mb-2">Category</label>
              <select 
                v-model="form.category_id"
                required
                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              >
                <option value="">Select Category</option>
                <option v-for="category in incomeCategories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-white/80 text-sm font-medium mb-2">Date</label>
              <input 
                v-model="form.date"
                type="date"
                required
                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              />
            </div>

            <div>
              <label class="block text-white/80 text-sm font-medium mb-2">Amount</label>
              <input 
                v-model="form.amount"
                type="number"
                step="0.01"
                required
                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                placeholder="0.00"
              />
            </div>
          </div>

          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Payee</label>
            <input 
              v-model="form.payee"
              type="text"
              required
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              placeholder="Enter payee name"
            />
          </div>

          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Receipt Number (Optional)</label>
            <input 
              v-model="form.receipt_number"
              type="text"
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              placeholder="Enter receipt number"
            />
          </div>

          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Memo (Optional)</label>
            <textarea 
              v-model="form.memo"
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
              class="flex-1 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 disabled:opacity-50 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 flex items-center justify-center"
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
  name: 'Income',
  data() {
    return {
      loading: true,
      saving: false,
      incomeRecords: [],
      properties: [],
      units: [],
      tenants: [],
      incomeCategories: [],
      showAddModal: false,
      showEditModal: false,
      selectedRecord: null,
      form: {
        property_id: '',
        unit_id: '',
        tenant_id: '',
        category_id: '',
        date: '',
        amount: '',
        payee: '',
        receipt_number: '',
        memo: ''
      }
    }
  },
  computed: {
    totalIncome() {
      return this.incomeRecords.reduce((sum, record) => sum + parseFloat(record.amount), 0)
    },
    currentMonthIncome() {
      const currentMonth = new Date().getMonth()
      const currentYear = new Date().getFullYear()
      return this.incomeRecords
        .filter(record => {
          const recordDate = new Date(record.date)
          return recordDate.getMonth() === currentMonth && recordDate.getFullYear() === currentYear
        })
        .reduce((sum, record) => sum + parseFloat(record.amount), 0)
    },
    filteredUnits() {
      if (!this.form.property_id) return this.units
      return this.units.filter(unit => unit.property_id == this.form.property_id)
    }
  },
  async mounted() {
    await Promise.all([
      this.loadIncomeRecords(),
      this.loadProperties(),
      this.loadUnits(),
      this.loadTenants(),
      this.loadCategories()
    ])
  },
  methods: {
    async loadIncomeRecords() {
      try {
        const response = await this.$http.get('/income.php')
        this.incomeRecords = response.data
      } catch (error) {
        console.error('Error loading income records:', error)
        this.incomeRecords = []
      } finally {
        this.loading = false
      }
    },
    async loadProperties() {
      try {
        const response = await this.$http.get('/properties.php')
        this.properties = response.data
      } catch (error) {
        console.error('Error loading properties:', error)
        this.properties = []
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
    async loadCategories() {
      try {
        const response = await this.$http.get('/categories.php?type=income')
        this.incomeCategories = response.data
      } catch (error) {
        console.error('Error loading categories:', error)
        this.incomeCategories = []
      }
    },
    editRecord(record) {
      this.selectedRecord = record
      this.form = { ...record }
      this.showEditModal = true
    },
    async deleteRecord(id) {
      if (!confirm('Are you sure you want to delete this income record?')) {
        return
      }

      try {
        await this.$http.delete(`/income.php?id=${id}`)
        await this.loadIncomeRecords()
      } catch (error) {
        console.error('Error deleting income record:', error)
        alert('Failed to delete income record')
      }
    },
    async saveRecord() {
      this.saving = true
      try {
        if (this.showEditModal) {
          await this.$http.put(`/income.php?id=${this.selectedRecord.id}`, this.form)
        } else {
          await this.$http.post('/income.php', this.form)
        }
        await this.loadIncomeRecords()
        this.closeModal()
      } catch (error) {
        console.error('Error saving income record:', error)
        alert('Failed to save income record')
      } finally {
        this.saving = false
      }
    },
    closeModal() {
      this.showAddModal = false
      this.showEditModal = false
      this.selectedRecord = null
      this.form = {
        property_id: '',
        unit_id: '',
        tenant_id: '',
        category_id: '',
        date: '',
        amount: '',
        payee: '',
        receipt_number: '',
        memo: ''
      }
    },
    formatNumber(num) {
      return new Intl.NumberFormat().format(num)
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString()
    }
  }
}
</script>
