<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const owners = ref([])
const distributions = ref([])
const calc = ref([])
const ownerForm = ref({name:'',share_ratio:'',email:''})
const distForm = ref({owner_id:'',date:'',amount:''})

async function load(){
  owners.value = (await axios.get('/api/owners')).data
  distributions.value = (await axios.get('/api/distributions')).data
}

onMounted(load)

async function addOwner(){
  await axios.post('/api/owners', ownerForm.value)
  ownerForm.value = {name:'',share_ratio:'',email:''}
  load()
}

async function addDistribution(){
  await axios.post('/api/distributions', distForm.value)
  distForm.value = {owner_id:'',date:'',amount:''}
  load()
}

async function calculate(){
  calc.value = (await axios.get('/api/distributions/calculate')).data
}
</script>

<template>
  <div class="space-y-6">
    <section>
      <h1 class="text-2xl mb-4">Owners</h1>
      <form @submit.prevent="addOwner" class="space-y-2 mb-4">
        <input v-model="ownerForm.name" placeholder="Name" class="border p-1" />
        <input v-model="ownerForm.share_ratio" placeholder="Share %" class="border p-1" />
        <input v-model="ownerForm.email" placeholder="Email" class="border p-1" />
        <button class="bg-blue-500 text-white px-2">Add</button>
      </form>
      <ul>
        <li v-for="o in owners" :key="o.id">{{o.name}} - {{o.share_ratio}}%</li>
      </ul>
    </section>

    <section>
      <h2 class="text-xl mb-2">Distributions</h2>
      <form @submit.prevent="addDistribution" class="space-y-2 mb-4">
        <select v-model="distForm.owner_id" class="border p-1">
          <option disabled value="">Owner</option>
          <option v-for="o in owners" :value="o.id" :key="o.id">{{o.name}}</option>
        </select>
        <input v-model="distForm.date" type="date" class="border p-1" />
        <input v-model="distForm.amount" placeholder="Amount" class="border p-1" />
        <button class="bg-blue-500 text-white px-2">Add</button>
      </form>
      <ul>
        <li v-for="d in distributions" :key="d.id">{{d.date}} - {{d.amount}}</li>
      </ul>
    </section>

    <section>
      <h2 class="text-xl mb-2">Calculate</h2>
      <button @click="calculate" class="bg-green-500 text-white px-2 mb-2">Calculate</button>
      <ul>
        <li v-for="c in calc" :key="c.owner_id">{{c.name}}: {{c.amount}}</li>
      </ul>
    </section>
  </div>
</template>
