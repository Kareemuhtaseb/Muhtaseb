<template>
  <div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-white">Categories</h1>
        <p class="text-white/60 mt-2">Manage income and expense categories</p>
      </div>
      <button 
        @click="showAddModal = true"
        class="bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 hover-lift flex items-center space-x-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        <span>Add Category</span>
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Total Categories</p>
            <p class="text-3xl font-bold text-white mt-2">{{ stats.total }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Income Categories</p>
            <p class="text-3xl font-bold text-white mt-2">{{ stats.income }}</p>
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
            <p class="text-white/60 text-sm font-medium">Expense Categories</p>
            <p class="text-3xl font-bold text-white mt-2">{{ stats.expense }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex space-x-2">
      <button 
        @click="filter = null"
        :class="[
          'px-4 py-2 rounded-lg font-medium transition-all duration-200',
          filter === null 
            ? 'bg-white/20 text-white' 
            : 'bg-white/10 text-white/60 hover:text-white hover:bg-white/15'
        ]"
      >
        All
      </button>
      <button 
        @click="filter = 'income'"
        :class="[
          'px-4 py-2 rounded-lg font-medium transition-all duration-200',
          filter === 'income' 
            ? 'bg-green-400/20 text-green-400' 
            : 'bg-white/10 text-white/60 hover:text-white hover:bg-white/15'
        ]"
      >
        Income
      </button>
      <button 
        @click="filter = 'expense'"
        :class="[
          'px-4 py-2 rounded-lg font-medium transition-all duration-200',
          filter === 'expense' 
            ? 'bg-red-400/20 text-red-400' 
            : 'bg-white/10 text-white/60 hover:text-white hover:bg-white/15'
        ]"
      >
        Expense
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center h-64">
      <div class="text-center">
        <div class="w-16 h-16 border-4 border-white/20 border-t-white rounded-full animate-spin mx-auto mb-4"></div>
        <p class="text-white/60">Loading categories...</p>
      </div>
    </div>

    <!-- Categories Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div 
        v-for="category in filteredCategories" 
        :key="category.id"
        class="glass rounded-2xl p-6 hover-lift transition-all duration-200"
      >
        <div class="flex items-start justify-between mb-4">
          <div>
            <h3 class="text-xl font-bold text-white">{{ category.name }}</h3>
            <p class="text-white/60 text-sm">{{ category.type.charAt(0).toUpperCase() + category.type.slice(1) }}</p>
          </div>
          <div class="flex space-x-2">
            <button 
              @click="editCategory(category)"
              class="p-2 text-white/60 hover:text-white hover:bg-white/10 rounded-lg transition-colors"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
              </svg>
            </button>
            <button 
              @click="deleteCategory(category.id)"
              class="p-2 text-red-400 hover:text-red-300 hover:bg-red-400/10 rounded-lg transition-colors"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
              </svg>
            </button>
          </div>
        </div>

        <!-- Type Badge -->
        <div class="mb-4">
          <span 
            :class="{
              'bg-green-400/20 text-green-400': category.type === 'income',
              'bg-red-400/20 text-red-400': category.type === 'expense'
            }"
            class="px-3 py-1 rounded-full text-xs font-medium"
          >
            {{ category.type.charAt(0).toUpperCase() + category.type.slice(1) }}
          </span>
        </div>

        <!-- Category Details -->
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-white/60 text-sm">ID:</span>
            <span class="text-white font-medium">#{{ category.id }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-white/60 text-sm">Type:</span>
            <span class="text-white font-medium">{{ category.type.charAt(0).toUpperCase() + category.type.slice(1) }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="!loading && filteredCategories.length === 0" class="text-center py-16">
      <div class="w-24 h-24 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-12 h-12 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
        </svg>
      </div>
      <h3 class="text-xl font-bold text-white mb-2">No categories found</h3>
      <p class="text-white/60 mb-6">Get started by adding your first category</p>
      <button 
        @click="showAddModal = true"
        class="bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 hover-lift"
      >
        Add First Category
      </button>
    </div>

    <!-- Add/Edit Category Modal -->
    <div v-if="showAddModal || showEditModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
      <div class="glass rounded-2xl p-8 w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-white">
            {{ showEditModal ? 'Edit Category' : 'Add New Category' }}
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

        <form @submit.prevent="showEditModal ? updateCategory() : addCategory()" class="space-y-6">
          <!-- Category Name -->
          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Category Name *</label>
            <input 
              v-model="form.name" 
              type="text" 
              required
              placeholder="e.g., Rent, Electricity, Maintenance"
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
            />
          </div>

          <!-- Category Type -->
          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Type *</label>
            <select 
              v-model="form.type" 
              required
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
            >
              <option value="" disabled>Select type</option>
              <option value="income">Income</option>
              <option value="expense">Expense</option>
            </select>
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
              class="flex-1 bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="submitting">Saving...</span>
              <span v-else>{{ showEditModal ? 'Update Category' : 'Add Category' }}</span>
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

const categories = ref([])
const loading = ref(false)
const submitting = ref(false)
const filter = ref(null)
const showAddModal = ref(false)
const showEditModal = ref(false)
const editingCategory = ref(null)

const form = ref({
  name: '',
  type: ''
})

// Computed properties for statistics
const stats = computed(() => {
  const total = categories.value.length
  const income = categories.value.filter(c => c.type === 'income').length
  const expense = categories.value.filter(c => c.type === 'expense').length
  
  return { total, income, expense }
})

const filteredCategories = computed(() => {
  if (!filter.value) return categories.value
  return categories.value.filter(c => c.type === filter.value)
})

async function loadData() {
  try {
    loading.value = true
    const response = await axios.get('/categories.php')
    categories.value = response.data
  } catch (error) {
    console.error('Error loading data:', error)
  } finally {
    loading.value = false
  }
}

onMounted(loadData)

function editCategory(category) {
  editingCategory.value = category
  form.value = {
    name: category.name,
    type: category.type
  }
  showEditModal.value = true
}

async function addCategory() {
  try {
    submitting.value = true
    await axios.post('/categories.php', form.value)
    closeModal()
    await loadData()
  } catch (error) {
    console.error('Error adding category:', error)
    alert('Failed to add category')
  } finally {
    submitting.value = false
  }
}

async function updateCategory() {
  try {
    submitting.value = true
    await axios.put(`/categories.php?id=${editingCategory.value.id}`, form.value)
    closeModal()
    await loadData()
  } catch (error) {
    console.error('Error updating category:', error)
    alert('Failed to update category')
  } finally {
    submitting.value = false
  }
}

async function deleteCategory(id) {
  if (!confirm('Are you sure you want to delete this category?')) {
    return
  }
  
  try {
    await axios.delete(`/categories.php?id=${id}`)
    await loadData()
  } catch (error) {
    console.error('Error deleting category:', error)
    alert('Failed to delete category')
  }
}

function closeModal() {
  showAddModal.value = false
  showEditModal.value = false
  editingCategory.value = null
  resetForm()
}

function resetForm() {
  form.value = {
    name: '',
    type: ''
  }
}
</script>
