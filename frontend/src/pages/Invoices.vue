<template>
  <div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-white">Owner Invoices</h1>
        <p class="text-white/60 mt-2">Manage invoices and payments for property owners</p>
      </div>
      <button 
        @click="showAddModal = true"
        class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 hover-lift flex items-center space-x-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        <span>Create Invoice</span>
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Total Invoices</p>
            <p class="text-3xl font-bold text-white mt-2">{{ stats.total }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Paid</p>
            <p class="text-3xl font-bold text-white mt-2">{{ stats.paid }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Pending</p>
            <p class="text-3xl font-bold text-white mt-2">{{ stats.pending }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Overdue</p>
            <p class="text-3xl font-bold text-white mt-2">{{ stats.overdue }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center h-64">
      <div class="text-center">
        <div class="w-16 h-16 border-4 border-white/20 border-t-white rounded-full animate-spin mx-auto mb-4"></div>
        <p class="text-white/60">Loading invoices...</p>
      </div>
    </div>

    <!-- Invoices Table -->
    <div v-else class="glass rounded-2xl overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-white/5">
            <tr>
              <th class="px-6 py-4 text-left text-white/60 font-medium">Invoice #</th>
              <th class="px-6 py-4 text-left text-white/60 font-medium">Owner</th>
              <th class="px-6 py-4 text-left text-white/60 font-medium">Date</th>
              <th class="px-6 py-4 text-left text-white/60 font-medium">Due Date</th>
              <th class="px-6 py-4 text-left text-white/60 font-medium">Amount</th>
              <th class="px-6 py-4 text-left text-white/60 font-medium">Status</th>
              <th class="px-6 py-4 text-left text-white/60 font-medium">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-white/10">
            <tr v-for="invoice in invoices" :key="invoice.id" class="hover:bg-white/5 transition-colors">
              <td class="px-6 py-4">
                <div class="flex items-center">
                  <span class="text-white font-medium">{{ invoice.invoice_number }}</span>
                </div>
              </td>
              <td class="px-6 py-4">
                <span class="text-white">{{ invoice.owner_name }}</span>
              </td>
              <td class="px-6 py-4">
                <span class="text-white/80">{{ formatDate(invoice.invoice_date) }}</span>
              </td>
              <td class="px-6 py-4">
                <span class="text-white/80">{{ formatDate(invoice.due_date) }}</span>
              </td>
              <td class="px-6 py-4">
                <span class="text-green-400 font-semibold">${{ formatNumber(invoice.total_amount) }}</span>
              </td>
              <td class="px-6 py-4">
                <span 
                  :class="{
                    'bg-green-400/20 text-green-400': invoice.status === 'paid',
                    'bg-blue-400/20 text-blue-400': invoice.status === 'sent',
                    'bg-yellow-400/20 text-yellow-400': invoice.status === 'draft',
                    'bg-red-400/20 text-red-400': invoice.status === 'overdue',
                    'bg-gray-400/20 text-gray-400': invoice.status === 'cancelled'
                  }"
                  class="px-3 py-1 rounded-full text-xs font-medium"
                >
                  {{ invoice.status.charAt(0).toUpperCase() + invoice.status.slice(1) }}
                </span>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center space-x-2">
                  <button 
                    @click="viewInvoice(invoice)"
                    class="p-2 text-white/60 hover:text-white hover:bg-white/10 rounded-lg transition-colors"
                    title="View Invoice"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                  </button>
                  <button 
                    @click="editInvoice(invoice)"
                    class="p-2 text-white/60 hover:text-white hover:bg-white/10 rounded-lg transition-colors"
                    title="Edit Invoice"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                  </button>
                  <button 
                    @click="deleteInvoice(invoice.id)"
                    class="p-2 text-red-400 hover:text-red-300 hover:bg-red-400/10 rounded-lg transition-colors"
                    title="Delete Invoice"
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
      <div v-if="invoices.length === 0" class="text-center py-16">
        <div class="w-24 h-24 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-12 h-12 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">No invoices found</h3>
        <p class="text-white/60 mb-6">Get started by creating your first invoice</p>
        <button 
          @click="showAddModal = true"
          class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 hover-lift"
        >
          Create First Invoice
        </button>
      </div>
    </div>

    <!-- Add/Edit Invoice Modal -->
    <div v-if="showAddModal || showEditModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
      <div class="glass rounded-2xl p-8 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-white">
            {{ showEditModal ? 'Edit Invoice' : 'Create New Invoice' }}
          </h2>
          <button 
            @click="closeModal"
            class="p-2 text-white/60 hover:text-white hover:bg-white/10 rounded-lg transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <form @submit.prevent="showEditModal ? updateInvoice() : addInvoice()" class="space-y-6">
          <!-- Owner Selection -->
          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Owner *</label>
            <select 
              v-model="form.owner_id" 
              required
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
            >
              <option value="" disabled>Select an owner</option>
              <option v-for="owner in owners" :key="owner.id" :value="owner.id">
                {{ owner.name }}
              </option>
            </select>
          </div>

          <!-- Invoice Details -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-white/80 text-sm font-medium mb-2">Invoice Date *</label>
              <input 
                v-model="form.invoice_date" 
                type="date" 
                required
                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
              />
            </div>

            <div>
              <label class="block text-white/80 text-sm font-medium mb-2">Due Date *</label>
              <input 
                v-model="form.due_date" 
                type="date" 
                required
                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
              />
            </div>
          </div>

          <!-- Amount Details -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-white/80 text-sm font-medium mb-2">Amount *</label>
              <input 
                v-model="form.amount" 
                type="number" 
                step="0.01"
                required
                placeholder="0.00"
                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
              />
            </div>

            <div>
              <label class="block text-white/80 text-sm font-medium mb-2">Tax Amount</label>
              <input 
                v-model="form.tax_amount" 
                type="number" 
                step="0.01"
                placeholder="0.00"
                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
              />
            </div>
          </div>

          <!-- Status -->
          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Status</label>
            <select 
              v-model="form.status" 
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
            >
              <option value="draft">Draft</option>
              <option value="sent">Sent</option>
              <option value="paid">Paid</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>

          <!-- Description -->
          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Description</label>
            <textarea 
              v-model="form.description" 
              rows="3"
              placeholder="Invoice description..."
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none"
            ></textarea>
          </div>

          <!-- Notes -->
          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Notes</label>
            <textarea 
              v-model="form.notes" 
              rows="2"
              placeholder="Additional notes..."
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none"
            ></textarea>
          </div>

          <!-- Form Actions -->
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
              :disabled="submitting"
              class="flex-1 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="submitting">Saving...</span>
              <span v-else>{{ showEditModal ? 'Update Invoice' : 'Create Invoice' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

const invoices = ref([])
const owners = ref([])
const loading = ref(false)
const submitting = ref(false)
const showAddModal = ref(false)
const showEditModal = ref(false)
const editingInvoice = ref(null)

const form = ref({
  owner_id: '',
  invoice_date: '',
  due_date: '',
  amount: '',
  tax_amount: '0',
  status: 'draft',
  description: '',
  notes: ''
})

// Computed properties for statistics
const stats = computed(() => {
  const total = invoices.value.length
  const paid = invoices.value.filter(i => i.status === 'paid').length
  const pending = invoices.value.filter(i => ['draft', 'sent'].includes(i.status)).length
  const overdue = invoices.value.filter(i => i.status === 'overdue').length
  
  return { total, paid, pending, overdue }
})

async function loadData() {
  try {
    loading.value = true
    const [invoicesResponse, ownersResponse] = await Promise.all([
      axios.get('/invoices'),
      axios.get('/owners')
    ])
    invoices.value = invoicesResponse.data?.data?.data || invoicesResponse.data?.data || invoicesResponse.data || []
    owners.value = ownersResponse.data?.data?.data || ownersResponse.data?.data || ownersResponse.data || []
  } catch (error) {
    console.error('Error loading data:', error)
  } finally {
    loading.value = false
  }
}

onMounted(loadData)

function editInvoice(invoice) {
  editingInvoice.value = invoice
  form.value = {
    owner_id: invoice.owner_id,
    invoice_date: invoice.invoice_date,
    due_date: invoice.due_date,
    amount: invoice.amount,
    tax_amount: invoice.tax_amount || '0',
    status: invoice.status,
    description: invoice.description || '',
    notes: invoice.notes || ''
  }
  showEditModal.value = true
}

async function addInvoice() {
  try {
    submitting.value = true
    await axios.post('/invoices', form.value)
    closeModal()
    await loadData()
  } catch (error) {
    console.error('Error creating invoice:', error)
    alert('Failed to create invoice')
  } finally {
    submitting.value = false
  }
}

async function updateInvoice() {
  try {
    submitting.value = true
    await axios.put(`/invoices/${editingInvoice.value.id}`, form.value)
    closeModal()
    await loadData()
  } catch (error) {
    console.error('Error updating invoice:', error)
    alert('Failed to update invoice')
  } finally {
    submitting.value = false
  }
}

async function deleteInvoice(id) {
  if (!confirm('Are you sure you want to delete this invoice?')) {
    return
  }
  
  try {
    await axios.delete(`/invoices/${id}`)
    await loadData()
  } catch (error) {
    console.error('Error deleting invoice:', error)
    alert('Failed to delete invoice')
  }
}

function viewInvoice(invoice) {
  // TODO: Implement invoice preview/modal
  console.log('View invoice:', invoice)
}

function closeModal() {
  showAddModal.value = false
  showEditModal.value = false
  editingInvoice.value = null
  resetForm()
}

function resetForm() {
  form.value = {
    owner_id: '',
    invoice_date: '',
    due_date: '',
    amount: '',
    tax_amount: '0',
    status: 'draft',
    description: '',
    notes: ''
  }
}

function formatNumber(num) {
  return new Intl.NumberFormat().format(num || 0)
}

function formatDate(date) {
  return new Date(date).toLocaleDateString()
}
</script>
