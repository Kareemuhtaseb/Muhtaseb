<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const tenants = ref([])
const units = ref([])
const form = ref({name:'',phone:'',email:'',unit_id:'',start_date:'',end_date:''})

async function load(){
  tenants.value = (await axios.get('/api/tenants')).data
  units.value = (await axios.get('/api/units')).data
}

onMounted(load)

async function submit(){
  await axios.post('/api/tenants', form.value)
  form.value = {name:'',phone:'',email:'',unit_id:'',start_date:'',end_date:''}
  load()
}

async function remove(id){
  await axios.delete(`/api/tenants/${id}`)
  load()
}
</script>

<template>
  <div>
    <h1 class="text-2xl mb-4">Tenants</h1>
    <form @submit.prevent="submit" class="space-y-2 mb-4">
      <input v-model="form.name" placeholder="Name" class="border p-1" />
      <input v-model="form.phone" placeholder="Phone" class="border p-1" />
      <input v-model="form.email" placeholder="Email" class="border p-1" />
      <select v-model="form.unit_id" class="border p-1">
        <option disabled value="">Select Unit</option>
        <option v-for="u in units" :value="u.id" :key="u.id">{{u.unit_number}}</option>
      </select>
      <input v-model="form.start_date" type="date" class="border p-1" />
      <button class="bg-blue-500 text-white px-2">Add</button>
    </form>
    <ul>
      <li v-for="t in tenants" :key="t.id" class="border-b py-1 flex justify-between">
        <span>{{t.name}} - {{t.email}}</span>
        <button @click="remove(t.id)" class="text-red-600">Delete</button>
      </li>
    </ul>
  </div>
</template>
