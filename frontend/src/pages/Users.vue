<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

const users = ref([])
const loading = ref(false)
const saving = ref(false)
const showAddModal = ref(false)
const showEditModal = ref(false)
const selectedUser = ref(null)

const userForm = ref({
  name: '',
  email: '',
  role: 'user',
  phone: '',
  is_active: true
})

// Computed properties for statistics
const stats = computed(() => {
  const total = users.value.length
  const active = users.value.filter(u => u.is_active).length
  const inactive = users.value.filter(u => !u.is_active).length
  const admins = users.value.filter(u => u.role === 'admin').length
  
  return { total, active, inactive, admins }
})

async function loadUsers() {
  try {
    loading.value = true
    const response = await axios.get('/users.php')
    users.value = response.data
  } catch (error) {
    console.error('Error loading users:', error)
  } finally {
    loading.value = false
  }
}

onMounted(loadUsers)

function editUser(user) {
  selectedUser.value = user
  userForm.value = {
    name: user.name,
    email: user.email,
    role: user.role,
    phone: user.phone || '',
    is_active: user.is_active
  }
  showEditModal.value = true
}

async function submitUser() {
  try {
    saving.value = true
    if (showEditModal.value) {
      await axios.put(`/users.php?id=${selectedUser.value.id}`, userForm.value)
    } else {
      await axios.post('/users.php', userForm.value)
    }
    await loadUsers()
    closeModal()
  } catch (error) {
    console.error('Error saving user:', error)
    alert('Failed to save user')
  } finally {
    saving.value = false
  }
}

async function deleteUser(id) {
  if (!confirm('Are you sure you want to delete this user?')) {
    return
  }
  
  try {
    await axios.delete(`/users.php?id=${id}`)
    await loadUsers()
  } catch (error) {
    console.error('Error deleting user:', error)
    alert('Failed to delete user')
  }
}

function closeModal() {
  showAddModal.value = false
  showEditModal.value = false
  selectedUser.value = null
  resetForm()
}

function resetForm() {
  userForm.value = {
    name: '',
    email: '',
    role: 'user',
    phone: '',
    is_active: true
  }
}

function formatDate(date) {
  return new Date(date).toLocaleDateString()
}
</script>

<template>
  <div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-white">User Management</h1>
        <p class="text-white/60 mt-2">Manage system users and permissions</p>
      </div>
      <button 
        @click="showAddModal = true"
        class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 hover-lift flex items-center space-x-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        <span>Add User</span>
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Total Users</p>
            <p class="text-3xl font-bold text-white mt-2">{{ stats.total }}</p>
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
            <p class="text-white/60 text-sm font-medium">Active Users</p>
            <p class="text-3xl font-bold text-white mt-2">{{ stats.active }}</p>
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
            <p class="text-white/60 text-sm font-medium">Administrators</p>
            <p class="text-3xl font-bold text-white mt-2">{{ stats.admins }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Inactive Users</p>
            <p class="text-3xl font-bold text-white mt-2">{{ stats.inactive }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center h-64">
      <div class="text-center">
        <div class="w-16 h-16 border-4 border-white/20 border-t-white rounded-full animate-spin mx-auto mb-4"></div>
        <p class="text-white/60">Loading users...</p>
      </div>
    </div>

    <!-- Users Table -->
    <div v-else class="glass rounded-2xl p-6">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-white">System Users</h2>
        <button 
          @click="showAddModal = true"
          class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center text-white/60 hover:text-white transition-all duration-200"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
        </button>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-white/10">
              <th class="text-left py-3 px-4 text-white/60 font-medium">Name</th>
              <th class="text-left py-3 px-4 text-white/60 font-medium">Email</th>
              <th class="text-left py-3 px-4 text-white/60 font-medium">Role</th>
              <th class="text-left py-3 px-4 text-white/60 font-medium">Status</th>
              <th class="text-left py-3 px-4 text-white/60 font-medium">Created</th>
              <th class="text-right py-3 px-4 text-white/60 font-medium">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.id" class="border-b border-white/5 hover:bg-white/5 transition-colors">
              <td class="py-4 px-4">
                <div class="flex items-center space-x-3">
                  <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                    <span class="text-white font-semibold text-sm">{{ user.name.charAt(0).toUpperCase() }}</span>
                  </div>
                  <div>
                    <p class="text-white font-medium">{{ user.name }}</p>
                    <p v-if="user.phone" class="text-white/60 text-sm">{{ user.phone }}</p>
                  </div>
                </div>
              </td>
              <td class="py-4 px-4">
                <p class="text-white">{{ user.email }}</p>
              </td>
              <td class="py-4 px-4">
                <span 
                  :class="{
                    'bg-purple-400/20 text-purple-400': user.role === 'admin',
                    'bg-blue-400/20 text-blue-400': user.role === 'user',
                    'bg-green-400/20 text-green-400': user.role === 'manager'
                  }"
                  class="px-3 py-1 rounded-full text-xs font-medium"
                >
                  {{ user.role.charAt(0).toUpperCase() + user.role.slice(1) }}
                </span>
              </td>
              <td class="py-4 px-4">
                <span 
                  :class="{
                    'bg-green-400/20 text-green-400': user.is_active,
                    'bg-red-400/20 text-red-400': !user.is_active
                  }"
                  class="px-3 py-1 rounded-full text-xs font-medium"
                >
                  {{ user.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="py-4 px-4">
                <p class="text-white/60 text-sm">{{ formatDate(user.created_at) }}</p>
              </td>
              <td class="py-4 px-4">
                <div class="flex items-center justify-end space-x-2">
                  <button 
                    @click="editUser(user)"
                    class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center text-white/60 hover:text-white transition-all duration-200"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                  </button>
                  <button 
                    @click="deleteUser(user.id)"
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

      <div v-if="users.length === 0" class="text-center py-16">
        <div class="w-24 h-24 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-12 h-12 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">No users found</h3>
        <p class="text-white/60 mb-6">Get started by adding your first system user</p>
        <button 
          @click="showAddModal = true"
          class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 hover-lift"
        >
          Add First User
        </button>
      </div>
    </div>

    <!-- Add/Edit User Modal -->
    <div v-if="showAddModal || showEditModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
      <div class="glass rounded-2xl p-8 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-white">
            {{ showEditModal ? 'Edit User' : 'Add User' }}
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

        <form @submit.prevent="submitUser" class="space-y-6">
          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Full Name *</label>
            <input 
              v-model="userForm.name"
              type="text"
              required
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              placeholder="Enter full name"
            />
          </div>

          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Email Address *</label>
            <input 
              v-model="userForm.email"
              type="email"
              required
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              placeholder="Enter email address"
            />
          </div>

          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Phone Number</label>
            <input 
              v-model="userForm.phone"
              type="tel"
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              placeholder="Enter phone number"
            />
          </div>

          <div>
            <label class="block text-white/80 text-sm font-medium mb-2">Role *</label>
            <select 
              v-model="userForm.role"
              required
              class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
            >
              <option value="user">User</option>
              <option value="manager">Manager</option>
              <option value="admin">Administrator</option>
            </select>
          </div>

          <div class="flex items-center space-x-3">
            <input 
              v-model="userForm.is_active"
              type="checkbox"
              id="is_active"
              class="rounded border-white/20 bg-white/10 text-blue-600 focus:ring-blue-500"
            />
            <label for="is_active" class="text-white/80 text-sm font-medium">Active User</label>
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
