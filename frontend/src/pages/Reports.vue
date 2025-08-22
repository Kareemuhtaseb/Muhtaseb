<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import { Bar } from 'vue-chartjs'
import { Chart, BarElement, CategoryScale, LinearScale, Legend, Tooltip } from 'chart.js'
Chart.register(BarElement, CategoryScale, LinearScale, Legend, Tooltip)

const data = ref({payments:[], expenses:[]})

onMounted(async () => {
  data.value = (await axios.get('/api/reports/monthly')).data
})

const chartData = computed(() => {
  const months = data.value.payments.map(p => p.month)
  return {
    labels: months,
    datasets: [
      { label: 'Income', data: data.value.payments.map(p=>p.total), backgroundColor: 'rgba(34,197,94,0.5)' },
      { label: 'Expenses', data: data.value.expenses.map(e=>e.total), backgroundColor: 'rgba(239,68,68,0.5)' }
    ]
  }
})

const chartOptions = { responsive: true, maintainAspectRatio:false }
</script>

<template>
  <div>
    <h1 class="text-2xl mb-4">Monthly Report</h1>
    <div class="h-64">
      <Bar :data="chartData" :options="chartOptions" />
    </div>
  </div>
</template>
