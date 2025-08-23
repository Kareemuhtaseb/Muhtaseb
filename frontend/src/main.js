import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import './style.css'
import axios from 'axios'

// Import components
import DataTable from './components/DataTable.vue'
import Modal from './components/Modal.vue'
import FormField from './components/FormField.vue'

// Configure axios
axios.defaults.baseURL = 'http://localhost:8000/api'

// Attach auth token if present
const token = localStorage.getItem('auth_token') || sessionStorage.getItem('auth_token')
if (token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
}

// Add request interceptor for loading states
axios.interceptors.request.use(
  config => {
    // Add loading state if needed
    return config
  },
  error => {
    return Promise.reject(error)
  }
)

// Add response interceptor for error handling
axios.interceptors.response.use(
  response => {
    return response
  },
  error => {
    console.error('API Error:', error)
    
    // Handle different error types
    if (error.response) {
      // Server responded with error status
      const { status, data } = error.response
      
      switch (status) {
        case 401:
          // Unauthorized - redirect to login
          console.error('Unauthorized access')
          break
        case 403:
          // Forbidden
          console.error('Access forbidden')
          break
        case 404:
          // Not found
          console.error('Resource not found')
          break
        case 422:
          // Validation error
          console.error('Validation error:', data)
          break
        case 500:
          // Server error
          console.error('Server error')
          break
        default:
          console.error(`HTTP ${status}: ${data?.message || 'Unknown error'}`)
      }
    } else if (error.request) {
      // Network error
      console.error('Network error - no response received')
    } else {
      // Other error
      console.error('Error:', error.message)
    }
    
    return Promise.reject(error)
  }
)

const app = createApp(App)

// Register components globally
app.component('DataTable', DataTable)
app.component('Modal', Modal)
app.component('FormField', FormField)

// Add axios to app properties
app.config.globalProperties.$http = axios

// Add global error handler
app.config.errorHandler = (err, vm, info) => {
  console.error('Vue Error:', err)
  console.error('Component:', vm)
  console.error('Info:', info)
}

// Add global properties
app.config.globalProperties.$formatNumber = (num) => {
  return new Intl.NumberFormat().format(num || 0)
}

app.config.globalProperties.$formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString()
}

app.config.globalProperties.$formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(amount || 0)
}

app.use(router)
app.mount('#app')
