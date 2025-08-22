<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const payments = ref([])
const tenants = ref([])
const form = ref({tenant_id:'',payment_date:'',amount:'',method:'cash',notes:''})

async function load(){
  payments.value = (await axios.get('/api/payments')).data
  tenants.value = (await axios.get('/api/tenants')).data
}

onMounted(load)

async function submit(){
  await axios.post('/api/payments', form.value)
  form.value = {tenant_id:'',payment_date:'',amount:'',method:'cash',notes:''}
  load()
}
</script>

<template>
  <div>
    <h1 class="text-2xl mb-4">Payments</h1>
    <form @submit.prevent="submit" class="space-y-2 mb-4">
      <select v-model="form.tenant_id" class="border p-1">
        <option disabled value="">Tenant</option>
        <option v-for="t in tenants" :value="t.id" :key="t.id">{{t.name}}</option>
      </select>
      <input v-model="form.payment_date" type="date" class="border p-1" />
      <input v-model="form.amount" placeholder="Amount" class="border p-1" />
      <select v-model="form.method" class="border p-1">
        <option value="cash">Cash</option>
        <option value="bank_transfer">Bank Transfer</option>
        <option value="credit_card">Credit Card</option>
      </select>
      <input v-model="form.notes" placeholder="Notes" class="border p-1" />
      <button class="bg-blue-500 text-white px-2">Add</button>
    </form>
    <ul>
      <li v-for="p in payments" :key="p.id" class="border-b py-1">
        {{p.payment_date}} - {{p.amount}}
      </li>
    </ul>
  </div>
</template>
