<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const testResult = ref('Loading...')
const loading = ref(false)

async function testAPI() {
  try {
    loading.value = true
    const response = await axios.get('/units')
    testResult.value = `Success: ${JSON.stringify(response.data, null, 2)}`
  } catch (error) {
    testResult.value = `Error: ${error.message}`
  } finally {
    loading.value = false
  }
}

onMounted(testAPI)
</script>

<template>
  <div class="space-y-6">
    <!-- Header Card -->
    <div class="bg-gray-800 rounded-lg shadow-lg p-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-white">API Test Dashboard</h1>
          <p class="text-gray-400 mt-1">Test API connectivity and data retrieval</p>
        </div>
        <div class="text-right">
          <div class="text-3xl font-bold text-yellow-500">Test</div>
          <div class="text-gray-400 text-sm">System Status</div>
        </div>
      </div>
    </div>

    <!-- Test Results Card -->
    <div class="bg-gray-800 rounded-lg shadow-lg p-6">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold text-white">API Test Results</h2>
        <button 
          @click="testAPI"
          :disabled="loading"
          class="bg-yellow-600 hover:bg-yellow-700 disabled:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-800"
        >
          {{ loading ? 'Testing...' : 'Test Again' }}
        </button>
      </div>
      
      <div class="bg-gray-900 rounded-lg p-4 border border-gray-700">
        <pre class="text-sm text-gray-300 whitespace-pre-wrap overflow-x-auto">{{ testResult }}</pre>
      </div>
    </div>

    <!-- Status Indicators -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Backend Status -->
      <div class="bg-gray-800 rounded-lg shadow-lg p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
              <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-300">Backend API</p>
            <p class="text-lg font-bold text-green-400">Online</p>
          </div>
        </div>
      </div>

      <!-- Database Status -->
      <div class="bg-gray-800 rounded-lg shadow-lg p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
              <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-300">Database</p>
            <p class="text-lg font-bold text-blue-400">Connected</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
