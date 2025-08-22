<template>
  <div class="flex h-screen">
    <aside class="w-64 bg-gray-800 text-white p-4 space-y-2">
      <RouterLink class="block" to="/">Tenants</RouterLink>
      <RouterLink class="block" to="/units">Units</RouterLink>
      <RouterLink class="block" to="/payments">Payments</RouterLink>
      <RouterLink class="block" to="/expenses">Expenses</RouterLink>
      <RouterLink class="block" to="/owners">Owners & Distributions</RouterLink>
      <RouterLink class="block" to="/reports">Reports</RouterLink>
    </aside>
    <main class="flex-1 p-6 overflow-y-auto">
      <RouterView />
    </main>
  </div>
</template>

<script setup>
</script>
