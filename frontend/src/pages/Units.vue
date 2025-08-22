<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const units = ref([])
const form = ref({unit_number:'',size:'',monthly_rent:'',status:'available'})

async function load(){
  units.value = (await axios.get('/api/units')).data
}

onMounted(load)

async function submit(){
  await axios.post('/api/units', form.value)
  form.value = {unit_number:'',size:'',monthly_rent:'',status:'available'}
  load()
}

async function remove(id){
  await axios.delete(`/api/units/${id}`)
  load()
}
</script>

<template>
  <div>
    <h1 class="text-2xl mb-4">Units</h1>
    <form @submit.prevent="submit" class="space-y-2 mb-4">
      <input v-model="form.unit_number" placeholder="Number" class="border p-1" />
      <input v-model="form.size" placeholder="Size" class="border p-1" />
      <input v-model="form.monthly_rent" placeholder="Rent" class="border p-1" />
      <select v-model="form.status" class="border p-1">
        <option value="available">Available</option>
        <option value="occupied">Occupied</option>
        <option value="maintenance">Maintenance</option>
      </select>
      <button class="bg-blue-500 text-white px-2">Add</button>
    </form>
    <ul>
      <li v-for="u in units" :key="u.id" class="border-b py-1 flex justify-between">
        <span>{{u.unit_number}} - {{u.status}}</span>
        <button @click="remove(u.id)" class="text-red-600">Delete</button>
      </li>
    </ul>
  </div>
</template>
