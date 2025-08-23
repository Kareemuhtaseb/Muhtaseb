<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import { Bar } from 'vue-chartjs'
import { Chart, BarElement, CategoryScale, LinearScale, Legend, Tooltip } from 'chart.js'
Chart.register(BarElement, CategoryScale, LinearScale, Legend, Tooltip)

const data = ref({payments:[], expenses:[]})
const loading = ref(false)

onMounted(async () => {
  try {
    loading.value = true
    data.value = (await axios.get('/reports.php')).data
  } catch (error) {
    console.error('Error loading reports:', error)
  } finally {
    loading.value = false
  }
})

const chartData = computed(() => {
  const months = data.value.payments.map(p => p.month)
  return {
    labels: months,
    datasets: [
      { 
        label: 'Income', 
        data: data.value.payments.map(p=>p.total), 
        backgroundColor: 'rgba(34,197,94,0.5)',
        borderColor: 'rgba(34,197,94,1)',
        borderWidth: 1
      },
      { 
        label: 'Expenses', 
        data: data.value.expenses.map(e=>e.total), 
        backgroundColor: 'rgba(239,68,68,0.5)',
        borderColor: 'rgba(239,68,68,1)',
        borderWidth: 1
      }
    ]
  }
})

const chartOptions = { 
  responsive: true, 
  maintainAspectRatio: false,
  plugins: {
    legend: {
      labels: {
        color: '#9CA3AF'
      }
    }
  },
  scales: {
    x: {
      ticks: {
        color: '#9CA3AF'
      },
      grid: {
        color: '#374151'
      }
    },
    y: {
      ticks: {
        color: '#9CA3AF'
      },
      grid: {
        color: '#374151'
      }
    }
  }
}
</script>

<template>
  <div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-white">Financial Reports</h1>
        <p class="text-white/60 mt-2">Track income, expenses, and financial performance</p>
      </div>
      <div class="flex items-center space-x-4">
        <button 
          @click="refreshData"
          class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 hover-lift flex items-center space-x-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
          </svg>
          <span>Refresh</span>
        </button>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
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
            <p class="text-white/60 text-sm font-medium">Total Expenses</p>
            <p class="text-3xl font-bold text-white mt-2">${{ formatNumber(totalExpenses) }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Net Profit</p>
            <p class="text-3xl font-bold text-white mt-2">${{ formatNumber(netProfit) }}</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
          </div>
        </div>
      </div>

      <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-white/60 text-sm font-medium">Profit Margin</p>
            <p class="text-3xl font-bold text-white mt-2">{{ profitMargin }}%</p>
          </div>
          <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center h-64">
      <div class="text-center">
        <div class="w-16 h-16 border-4 border-white/20 border-t-white rounded-full animate-spin mx-auto mb-4"></div>
        <p class="text-white/60">Loading financial reports...</p>
      </div>
    </div>

    <!-- Reports Content -->
    <div v-else>
      <!-- Monthly Financial Overview -->
      <div class="glass rounded-2xl p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-bold text-white">Monthly Financial Overview</h2>
          <div class="flex space-x-2">
            <div class="flex items-center">
              <div class="w-3 h-3 bg-green-400 rounded-full mr-2"></div>
              <span class="text-white/60 text-sm">Income</span>
            </div>
            <div class="flex items-center">
              <div class="w-3 h-3 bg-red-400 rounded-full mr-2"></div>
              <span class="text-white/60 text-sm">Expenses</span>
            </div>
          </div>
        </div>
        
        <div v-if="!data.payments || data.payments.length === 0" class="text-center py-16">
          <div class="w-24 h-24 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold text-white mb-2">No Data Available</h3>
          <p class="text-white/60">Start adding income and expenses to see financial reports</p>
        </div>
        
        <div v-else class="h-96">
          <Bar :data="chartData" :options="chartOptions" />
        </div>
      </div>

      <!-- Summary Tables -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Income Summary -->
        <div class="glass rounded-2xl p-6">
          <h3 class="text-lg font-bold text-white mb-4">Income Summary</h3>
          <div class="space-y-3">
            <div v-for="payment in data.payments" :key="payment.month" class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
              <div>
                <p class="text-white font-medium">{{ payment.month }}</p>
                <p class="text-white/60 text-sm">{{ payment.count }} transactions</p>
              </div>
              <p class="text-green-400 font-semibold">${{ formatNumber(payment.total) }}</p>
            </div>
            <div v-if="!data.payments || data.payments.length === 0" class="text-center py-8">
              <p class="text-white/60">No income data available</p>
            </div>
          </div>
        </div>

        <!-- Expenses Summary -->
        <div class="glass rounded-2xl p-6">
          <h3 class="text-lg font-bold text-white mb-4">Expenses Summary</h3>
          <div class="space-y-3">
            <div v-for="expense in data.expenses" :key="expense.month" class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
              <div>
                <p class="text-white font-medium">{{ expense.month }}</p>
                <p class="text-white/60 text-sm">{{ expense.count }} transactions</p>
              </div>
              <p class="text-red-400 font-semibold">${{ formatNumber(expense.total) }}</p>
            </div>
            <div v-if="!data.expenses || data.expenses.length === 0" class="text-center py-8">
              <p class="text-white/60">No expense data available</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Bar } from 'vue-chartjs'
import { Chart, BarElement, CategoryScale, LinearScale, Legend, Tooltip } from 'chart.js'

Chart.register(BarElement, CategoryScale, LinearScale, Legend, Tooltip)

export default {
  name: 'Reports',
  components: { Bar },
  data() {
    return {
      data: { payments: [], expenses: [] },
      loading: false
    }
  },
  computed: {
    totalIncome() {
      return this.data.payments?.reduce((sum, p) => sum + parseFloat(p.total), 0) || 0
    },
    totalExpenses() {
      return this.data.expenses?.reduce((sum, e) => sum + parseFloat(e.total), 0) || 0
    },
    netProfit() {
      return this.totalIncome - this.totalExpenses
    },
    profitMargin() {
      if (this.totalIncome === 0) return 0
      return ((this.netProfit / this.totalIncome) * 100).toFixed(1)
    },
    chartData() {
      const months = this.data.payments?.map(p => p.month) || []
      return {
        labels: months,
        datasets: [
          { 
            label: 'Income', 
            data: this.data.payments?.map(p => p.total) || [], 
            backgroundColor: 'rgba(34, 197, 94, 0.5)',
            borderColor: 'rgba(34, 197, 94, 1)',
            borderWidth: 1
          },
          { 
            label: 'Expenses', 
            data: this.data.expenses?.map(e => e.total) || [], 
            backgroundColor: 'rgba(239, 68, 68, 0.5)',
            borderColor: 'rgba(239, 68, 68, 1)',
            borderWidth: 1
          }
        ]
      }
    },
    chartOptions() {
      return { 
        responsive: true, 
        maintainAspectRatio: false,
        plugins: {
          legend: {
            labels: {
              color: 'rgba(255, 255, 255, 0.8)'
            }
          }
        },
        scales: {
          x: {
            ticks: {
              color: 'rgba(255, 255, 255, 0.6)'
            },
            grid: {
              color: 'rgba(255, 255, 255, 0.1)'
            }
          },
          y: {
            ticks: {
              color: 'rgba(255, 255, 255, 0.6)',
              callback: function(value) {
                return '$' + value.toLocaleString()
              }
            },
            grid: {
              color: 'rgba(255, 255, 255, 0.1)'
            }
          }
        }
      }
    }
  },
  async mounted() {
    await this.loadData()
  },
  methods: {
    async loadData() {
      try {
        this.loading = true
        const response = await this.$http.get('/reports.php')
        this.data = response.data
      } catch (error) {
        console.error('Error loading reports:', error)
        this.data = { payments: [], expenses: [] }
      } finally {
        this.loading = false
      }
    },
    async refreshData() {
      await this.loadData()
    },
    formatNumber(num) {
      return new Intl.NumberFormat().format(num)
    }
  }
}
</script>
