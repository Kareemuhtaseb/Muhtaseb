<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const expenses = ref([])
const form = ref({category:'',date:'',amount:'',notes:''})

async function load(){
  expenses.value = (await axios.get('/api/expenses')).data
}

onMounted(load)

async function submit(){
  await axios.post('/api/expenses', form.value)
  form.value = {category:'',date:'',amount:'',notes:''}
  load()
}
</script>

<template>
  <div>
    <h1 class="text-2xl mb-4">Expenses</h1>
    <form @submit.prevent="submit" class="space-y-2 mb-4">
      <input v-model="form.category" placeholder="Category" class="border p-1" />
      <input v-model="form.date" type="date" class="border p-1" />
      <input v-model="form.amount" placeholder="Amount" class="border p-1" />
      <input v-model="form.notes" placeholder="Notes" class="border p-1" />
      <button class="bg-blue-500 text-white px-2">Add</button>
    </form>
    <ul>
      <li v-for="e in expenses" :key="e.id" class="border-b py-1">
        {{e.date}} - {{e.category}} - {{e.amount}}
      </li>
    </ul>
  </div>
</template>
