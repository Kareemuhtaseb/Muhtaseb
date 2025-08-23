<template>
  <div id="app" class="min-h-screen bg-gradient-to-br from-gray-900 via-slate-900 to-gray-900">
    <!-- Premium Sidebar with Enhanced Effects -->
    <div class="fixed inset-y-0 left-0 z-50 w-72">
      <div class="h-full bg-gradient-to-b from-slate-900/95 via-slate-800/90 to-slate-900/95 backdrop-blur-2xl border-r border-white/20 shadow-2xl relative overflow-hidden">
        <!-- Animated Background Pattern -->
        <div class="absolute inset-0 opacity-5">
          <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, rgba(59, 130, 246, 0.3) 0%, transparent 50%), radial-gradient(circle at 75% 75%, rgba(139, 92, 246, 0.3) 0%, transparent 50%);"></div>
        </div>
        <!-- Premium Logo Area -->
        <div class="p-8 border-b border-white/10 bg-gradient-to-r from-slate-800/50 to-slate-700/30">
          <div class="flex items-center space-x-4">
            <div class="relative">
              <div class="w-12 h-12 bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
              </div>
              <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-slate-900"></div>
            </div>
            <div>
              <h1 class="text-2xl font-bold bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">Muhtaseb</h1>
              <p class="text-slate-400 text-sm font-medium">Enterprise Property Management</p>
            </div>
          </div>
        </div>

        <!-- Premium Navigation -->
        <nav class="p-6 space-y-10 overflow-y-auto h-full pb-32 sidebar-scroll">
          <!-- Overview Section -->
          <div>
            <div class="flex items-center space-x-3 mb-6">
              <div class="w-1 h-8 bg-gradient-to-b from-blue-500 to-purple-600 rounded-full shadow-lg"></div>
              <h3 class="text-slate-400 text-xs font-bold uppercase tracking-widest">Overview</h3>
            </div>
            <div class="space-y-3">
              <router-link 
                to="/"
                class="group relative flex items-center space-x-4 px-5 py-4 rounded-2xl text-slate-300 hover:text-white transition-all duration-500 hover-lift"
                :class="{ 
                  'bg-gradient-to-r from-blue-600/20 to-purple-600/20 text-white shadow-lg border border-blue-500/30 transform scale-105 animate-glow': $route.path === '/',
                  'hover:bg-gradient-to-r hover:from-white/10 hover:to-white/5 hover:shadow-md hover:scale-105': $route.path !== '/'
                }"
              >
                <div class="relative">
                                     <div class="w-11 h-11 bg-gradient-to-br from-blue-500/20 to-purple-500/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                     <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5v4m8-4v4M8 11v4m8-4v4"></path>
                     </svg>
                   </div>
                  <div v-if="$route.path === '/'" class="absolute -top-1 -right-1 w-3 h-3 bg-blue-500 rounded-full animate-pulse shadow-lg"></div>
                </div>
                <div class="flex-1">
                  <span class="font-semibold text-sm">Dashboard</span>
                  <p class="text-xs text-slate-500 mt-1">Overview & Analytics</p>
                </div>
                <div v-if="$route.path === '/'" class="w-2 h-2 bg-blue-500 rounded-full shadow-lg"></div>
              </router-link>
            </div>
          </div>

          <!-- Property Management Section -->
          <div>
            <div class="flex items-center space-x-3 mb-6">
              <div class="w-1 h-8 bg-gradient-to-b from-emerald-500 to-teal-600 rounded-full shadow-lg"></div>
              <h3 class="text-slate-400 text-xs font-bold uppercase tracking-widest">Property Management</h3>
            </div>
            <div class="space-y-3">
              <router-link
                to="/properties"
                class="group relative flex items-center space-x-4 px-5 py-4 rounded-2xl text-slate-300 hover:text-white transition-all duration-300"
                :class="{
                  'bg-gradient-to-r from-emerald-600/20 to-teal-600/20 text-white shadow-lg border border-emerald-500/30 transform scale-105': $route.path === '/properties',
                  'hover:bg-white/5 hover:shadow-md hover:scale-105': $route.path !== '/properties'
                }"
              >
                <div class="relative">
                  <div class="w-11 h-11 bg-gradient-to-br from-emerald-500/20 to-teal-500/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                  </div>
                  <div v-if="$route.path === '/properties'" class="absolute -top-1 -right-1 w-3 h-3 bg-emerald-500 rounded-full animate-pulse shadow-lg"></div>
                </div>
                <div class="flex-1">
                  <span class="font-semibold text-sm">Properties</span>
                  <p class="text-xs text-slate-500 mt-1">Manage Properties</p>
                </div>
                <div v-if="$route.path === '/properties'" class="w-2 h-2 bg-emerald-500 rounded-full shadow-lg"></div>
              </router-link>

              <router-link
                to="/units"
                class="group relative flex items-center space-x-4 px-5 py-4 rounded-2xl text-slate-300 hover:text-white transition-all duration-300"
                :class="{
                  'bg-gradient-to-r from-emerald-600/20 to-teal-600/20 text-white shadow-lg border border-emerald-500/30 transform scale-105': $route.path === '/units',
                  'hover:bg-white/5 hover:shadow-md hover:scale-105': $route.path !== '/units'
                }"
              >
                <div class="relative">
                  <div class="w-11 h-11 bg-gradient-to-br from-emerald-500/20 to-teal-500/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5v4m8-4v4M8 11v4m8-4v4"></path>
                    </svg>
                  </div>
                  <div v-if="$route.path === '/units'" class="absolute -top-1 -right-1 w-3 h-3 bg-emerald-500 rounded-full animate-pulse shadow-lg"></div>
                </div>
                <div class="flex-1">
                  <span class="font-semibold text-sm">Units</span>
                  <p class="text-xs text-slate-500 mt-1">Manage Units</p>
                </div>
                <div v-if="$route.path === '/units'" class="w-2 h-2 bg-emerald-500 rounded-full shadow-lg"></div>
              </router-link>

              <router-link
                to="/contracts"
                class="group relative flex items-center space-x-4 px-5 py-4 rounded-2xl text-slate-300 hover:text-white transition-all duration-300"
                :class="{
                  'bg-gradient-to-r from-emerald-600/20 to-teal-600/20 text-white shadow-lg border border-emerald-500/30 transform scale-105': $route.path === '/contracts',
                  'hover:bg-white/5 hover:shadow-md hover:scale-105': $route.path !== '/contracts'
                }"
              >
                <div class="relative">
                  <div class="w-11 h-11 bg-gradient-to-br from-emerald-500/20 to-teal-500/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                  </div>
                  <div v-if="$route.path === '/contracts'" class="absolute -top-1 -right-1 w-3 h-3 bg-emerald-500 rounded-full animate-pulse shadow-lg"></div>
                </div>
                <div class="flex-1">
                  <span class="font-semibold text-sm">Contracts</span>
                  <p class="text-xs text-slate-500 mt-1">Manage Contracts</p>
                </div>
                <div v-if="$route.path === '/contracts'" class="w-2 h-2 bg-emerald-500 rounded-full shadow-lg"></div>
              </router-link>
            </div>
          </div>

          <!-- Financial Management Section -->
          <div>
            <div class="flex items-center space-x-3 mb-6">
              <div class="w-1 h-8 bg-gradient-to-b from-green-500 to-emerald-600 rounded-full shadow-lg"></div>
              <h3 class="text-slate-400 text-xs font-bold uppercase tracking-widest">Financial</h3>
            </div>
            <div class="space-y-3">
              <router-link 
                to="/income"
                class="group relative flex items-center space-x-4 px-5 py-4 rounded-2xl text-slate-300 hover:text-white transition-all duration-300"
                :class="{ 
                  'bg-gradient-to-r from-green-600/20 to-emerald-600/20 text-white shadow-lg border border-green-500/30 transform scale-105': $route.path === '/income',
                  'hover:bg-white/5 hover:shadow-md hover:scale-105': $route.path !== '/income'
                }"
              >
                <div class="relative">
                                     <div class="w-11 h-11 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                     <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                     </svg>
                   </div>
                  <div v-if="$route.path === '/income'" class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 rounded-full animate-pulse shadow-lg"></div>
                </div>
                <div class="flex-1">
                  <span class="font-semibold text-sm">Income</span>
                  <p class="text-xs text-slate-500 mt-1">Rental Income</p>
                </div>
                <div v-if="$route.path === '/income'" class="w-2 h-2 bg-green-500 rounded-full shadow-lg"></div>
              </router-link>
              
              <router-link 
                to="/expenses"
                class="group relative flex items-center space-x-4 px-5 py-4 rounded-2xl text-slate-300 hover:text-white transition-all duration-300"
                :class="{ 
                  'bg-gradient-to-r from-green-600/20 to-emerald-600/20 text-white shadow-lg border border-green-500/30 transform scale-105': $route.path === '/expenses',
                  'hover:bg-white/5 hover:shadow-md hover:scale-105': $route.path !== '/expenses'
                }"
              >
                <div class="relative">
                                     <div class="w-11 h-11 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                     <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                     </svg>
                   </div>
                  <div v-if="$route.path === '/expenses'" class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 rounded-full animate-pulse shadow-lg"></div>
                </div>
                <div class="flex-1">
                  <span class="font-semibold text-sm">Expenses</span>
                  <p class="text-xs text-slate-500 mt-1">Cost Management</p>
                </div>
                <div v-if="$route.path === '/expenses'" class="w-2 h-2 bg-green-500 rounded-full shadow-lg"></div>
              </router-link>
              
              <router-link 
                to="/owners"
                class="group relative flex items-center space-x-4 px-5 py-4 rounded-2xl text-slate-300 hover:text-white transition-all duration-300"
                :class="{ 
                  'bg-gradient-to-r from-green-600/20 to-emerald-600/20 text-white shadow-lg border border-green-500/30 transform scale-105': $route.path === '/owners',
                  'hover:bg-white/5 hover:shadow-md hover:scale-105': $route.path !== '/owners'
                }"
              >
                <div class="relative">
                                     <div class="w-11 h-11 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                     <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                     </svg>
                   </div>
                  <div v-if="$route.path === '/owners'" class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 rounded-full animate-pulse shadow-lg"></div>
                </div>
                <div class="flex-1">
                  <span class="font-semibold text-sm">Owners</span>
                  <p class="text-xs text-slate-500 mt-1">Share & Distributions</p>
                </div>
                <div v-if="$route.path === '/owners'" class="w-2 h-2 bg-green-500 rounded-full shadow-lg"></div>
              </router-link>
              
              <router-link 
                to="/payments"
                class="group relative flex items-center space-x-4 px-5 py-4 rounded-2xl text-slate-300 hover:text-white transition-all duration-300"
                :class="{ 
                  'bg-gradient-to-r from-green-600/20 to-emerald-600/20 text-white shadow-lg border border-green-500/30 transform scale-105': $route.path === '/payments',
                  'hover:bg-white/5 hover:shadow-md hover:scale-105': $route.path !== '/payments'
                }"
              >
                <div class="relative">
                                     <div class="w-11 h-11 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                     <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                     </svg>
                   </div>
                  <div v-if="$route.path === '/payments'" class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 rounded-full animate-pulse shadow-lg"></div>
                </div>
                <div class="flex-1">
                  <span class="font-semibold text-sm">Payments</span>
                  <p class="text-xs text-slate-500 mt-1">Payment Tracking</p>
                </div>
                <div v-if="$route.path === '/payments'" class="w-2 h-2 bg-green-500 rounded-full shadow-lg"></div>
              </router-link>
              
              <router-link 
                to="/invoices"
                class="group relative flex items-center space-x-4 px-5 py-4 rounded-2xl text-slate-300 hover:text-white transition-all duration-300"
                :class="{ 
                  'bg-gradient-to-r from-green-600/20 to-emerald-600/20 text-white shadow-lg border border-green-500/30 transform scale-105': $route.path === '/invoices',
                  'hover:bg-white/5 hover:shadow-md hover:scale-105': $route.path !== '/invoices'
                }"
              >
                <div class="relative">
                                     <div class="w-11 h-11 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                     <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                     </svg>
                   </div>
                  <div v-if="$route.path === '/invoices'" class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 rounded-full animate-pulse shadow-lg"></div>
                </div>
                <div class="flex-1">
                  <span class="font-semibold text-sm">Invoices</span>
                  <p class="text-xs text-slate-500 mt-1">Invoice Management</p>
                </div>
                <div v-if="$route.path === '/invoices'" class="w-2 h-2 bg-green-500 rounded-full shadow-lg"></div>
              </router-link>
              
              <router-link 
                to="/monthly-statements"
                class="group relative flex items-center space-x-4 px-5 py-4 rounded-2xl text-slate-300 hover:text-white transition-all duration-300"
                :class="{ 
                  'bg-gradient-to-r from-green-600/20 to-emerald-600/20 text-white shadow-lg border border-green-500/30 transform scale-105': $route.path === '/monthly-statements',
                  'hover:bg-white/5 hover:shadow-md hover:scale-105': $route.path !== '/monthly-statements'
                }"
              >
                <div class="relative">
                                     <div class="w-11 h-11 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                     <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6"></path>
                     </svg>
                   </div>
                  <div v-if="$route.path === '/monthly-statements'" class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 rounded-full animate-pulse shadow-lg"></div>
                </div>
                <div class="flex-1">
                  <span class="font-semibold text-sm">Monthly Statements</span>
                  <p class="text-xs text-slate-500 mt-1">Owner Reports</p>
                </div>
                <div v-if="$route.path === '/monthly-statements'" class="w-2 h-2 bg-green-500 rounded-full shadow-lg"></div>
              </router-link>
            </div>
          </div>



          <!-- Administration Section -->
          <div>
            <div class="flex items-center space-x-3 mb-6">
              <div class="w-1 h-8 bg-gradient-to-b from-purple-500 to-pink-600 rounded-full shadow-lg"></div>
              <h3 class="text-slate-400 text-xs font-bold uppercase tracking-widest">Administration</h3>
            </div>
            <div class="space-y-3">
              <router-link 
                to="/categories"
                class="group relative flex items-center space-x-4 px-5 py-4 rounded-2xl text-slate-300 hover:text-white transition-all duration-300"
                :class="{ 
                  'bg-gradient-to-r from-purple-600/20 to-pink-600/20 text-white shadow-lg border border-purple-500/30 transform scale-105': $route.path === '/categories',
                  'hover:bg-white/5 hover:shadow-md hover:scale-105': $route.path !== '/categories'
                }"
              >
                <div class="relative">
                                     <div class="w-11 h-11 bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                     <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                     </svg>
                   </div>
                  <div v-if="$route.path === '/categories'" class="absolute -top-1 -right-1 w-3 h-3 bg-purple-500 rounded-full animate-pulse shadow-lg"></div>
                </div>
                <div class="flex-1">
                  <span class="font-semibold text-sm">Categories</span>
                  <p class="text-xs text-slate-500 mt-1">Classification</p>
                </div>
                <div v-if="$route.path === '/categories'" class="w-2 h-2 bg-purple-500 rounded-full shadow-lg"></div>
              </router-link>
              
              <router-link 
                to="/users"
                class="group relative flex items-center space-x-4 px-5 py-4 rounded-2xl text-slate-300 hover:text-white transition-all duration-300"
                :class="{ 
                  'bg-gradient-to-r from-purple-600/20 to-pink-600/20 text-white shadow-lg border border-purple-500/30 transform scale-105': $route.path === '/users',
                  'hover:bg-white/5 hover:shadow-md hover:scale-105': $route.path !== '/users'
                }"
              >
                <div class="relative">
                                     <div class="w-11 h-11 bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                     <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                     </svg>
                   </div>
                  <div v-if="$route.path === '/users'" class="absolute -top-1 -right-1 w-3 h-3 bg-purple-500 rounded-full animate-pulse shadow-lg"></div>
                </div>
                <div class="flex-1">
                  <span class="font-semibold text-sm">Users</span>
                  <p class="text-xs text-slate-500 mt-1">User Management</p>
                </div>
                <div v-if="$route.path === '/users'" class="w-2 h-2 bg-purple-500 rounded-full shadow-lg"></div>
              </router-link>
              
              <router-link 
                to="/reports"
                class="group relative flex items-center space-x-4 px-5 py-4 rounded-2xl text-slate-300 hover:text-white transition-all duration-300"
                :class="{ 
                  'bg-gradient-to-r from-purple-600/20 to-pink-600/20 text-white shadow-lg border border-purple-500/30 transform scale-105': $route.path === '/reports',
                  'hover:bg-white/5 hover:shadow-md hover:scale-105': $route.path !== '/reports'
                }"
              >
                <div class="relative">
                                     <div class="w-11 h-11 bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                     <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6"></path>
                     </svg>
                   </div>
                  <div v-if="$route.path === '/reports'" class="absolute -top-1 -right-1 w-3 h-3 bg-purple-500 rounded-full animate-pulse shadow-lg"></div>
                </div>
                <div class="flex-1">
                  <span class="font-semibold text-sm">Reports</span>
                  <p class="text-xs text-slate-500 mt-1">Analytics & Insights</p>
                </div>
                <div v-if="$route.path === '/reports'" class="w-2 h-2 bg-purple-500 rounded-full shadow-lg"></div>
              </router-link>
            </div>
          </div>
        </nav>
      </div>
    </div>

    <!-- Main Content -->
    <div class="ml-72 min-h-screen">
      <!-- Enhanced Header with Better Visual Effects -->
      <header class="bg-gradient-to-r from-slate-900/95 to-slate-800/90 backdrop-blur-2xl border-b border-white/20 sticky top-0 z-40 relative overflow-hidden">
        <!-- Animated Background Pattern -->
        <div class="absolute inset-0 opacity-5">
          <div class="absolute inset-0" style="background-image: linear-gradient(45deg, rgba(59, 130, 246, 0.1) 25%, transparent 25%), linear-gradient(-45deg, rgba(139, 92, 246, 0.1) 25%, transparent 25%), linear-gradient(45deg, transparent 75%, rgba(59, 130, 246, 0.1) 75%), linear-gradient(-45deg, transparent 75%, rgba(139, 92, 246, 0.1) 75%); background-size: 20px 20px; background-position: 0 0, 0 10px, 10px -10px, -10px 0px;"></div>
        </div>
        <div class="px-8 py-6">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-3xl font-bold bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">{{ currentPageTitle }}</h2>
              <p class="text-slate-400 text-base mt-1">{{ currentPageDescription }}</p>
            </div>
            <div class="flex items-center space-x-4">
              <!-- Notifications -->
              <button 
                @click="showNotifications = !showNotifications"
                data-notifications-button
                class="relative p-3 text-slate-300 hover:text-white hover:bg-white/10 rounded-2xl transition-all duration-300 group"
              >
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <svg class="w-6 h-6 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.19 4H20a2 2 0 012 2v6a10 10 0 01-10 10H4.19a2 2 0 01-1.7-3.12L6.5 12.5a2 2 0 01-.3-1.5V6a2 2 0 012-2z"></path>
                </svg>
                <div v-if="notifications.length > 0" class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full border-2 border-slate-900 animate-pulse notification-badge"></div>
              </button>
            
              <!-- Settings -->
              <button 
                @click="showSettings = true"
                class="relative p-3 text-slate-300 hover:text-white hover:bg-white/10 rounded-2xl transition-all duration-300 group"
              >
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <svg class="w-6 h-6 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
              </button>

              <!-- Premium Admin User Profile -->
              <div class="relative group">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-purple-600/20 rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative bg-gradient-to-r from-slate-800/90 to-slate-700/90 backdrop-blur-xl border border-white/20 rounded-2xl p-4 hover:border-white/30 transition-all duration-300 cursor-pointer user-profile-card" @click="toggleUserMenu">
                  <div class="flex items-center space-x-3">
                    <div class="relative">
                      <div class="w-10 h-10 bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                      </div>
                      <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-slate-800 animate-pulse"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-white font-semibold text-sm truncate">Admin User</p>
                      <div class="flex items-center mt-1">
                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                        <span class="text-green-400 text-xs font-medium">Online</span>
                      </div>
                    </div>
                    <button class="p-1 text-slate-400 hover:text-white hover:bg-white/10 rounded-lg transition-all duration-300">
                      <svg class="w-4 h-4 transform transition-transform duration-300" :class="{ 'rotate-180': showUserMenu }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                      </svg>
                    </button>
                  </div>
                </div>

                <!-- Premium User Menu Dropdown -->
                <div v-if="showUserMenu" class="absolute right-0 mt-3 w-80 z-50">
                  <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/10 to-purple-600/10 rounded-2xl blur-xl"></div>
                    <div class="relative bg-gradient-to-r from-slate-800/95 to-slate-700/95 backdrop-blur-2xl border border-white/20 rounded-2xl p-4 animate-fade-in user-menu-dropdown">
                      <div class="space-y-3">
                        <!-- Premium Profile Section -->
                        <div class="p-4 bg-gradient-to-r from-blue-600/10 to-purple-600/10 rounded-xl border border-blue-500/20 profile-section">
                          <div class="flex items-center space-x-4 mb-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 rounded-2xl flex items-center justify-center shadow-lg">
                              <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                              </svg>
                            </div>
                            <div>
                              <p class="text-white font-bold text-sm">Admin User</p>
                              <p class="text-slate-400 text-xs">admin@muhtaseb.com</p>
                              <div class="flex items-center mt-2">
                                <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                                <span class="text-green-400 text-xs font-medium">Online</span>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- Premium Menu Items -->
                        <button 
                          @click="showProfileModal = true"
                          class="w-full flex items-center space-x-4 px-4 py-3 text-slate-300 hover:text-white hover:bg-gradient-to-r hover:from-blue-600/20 hover:to-purple-600/20 rounded-xl transition-all duration-300 group"
                        >
                          <div class="w-10 h-10 bg-gradient-to-br from-blue-500/20 to-purple-500/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 text-blue-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                          </div>
                          <div class="flex-1 text-left">
                            <span class="text-sm font-semibold">My Profile</span>
                            <p class="text-xs text-slate-500 mt-1">Manage your account</p>
                          </div>
                        </button>

                        <button 
                          @click="showSettings = true"
                          class="w-full flex items-center space-x-4 px-4 py-3 text-slate-300 hover:text-white hover:bg-gradient-to-r hover:from-blue-600/20 hover:to-purple-600/20 rounded-xl transition-all duration-300 group"
                        >
                          <div class="w-10 h-10 bg-gradient-to-br from-emerald-500/20 to-teal-500/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 text-emerald-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                          </div>
                          <div class="flex-1 text-left">
                            <span class="text-sm font-semibold">Settings</span>
                            <p class="text-xs text-slate-500 mt-1">Configure preferences</p>
                          </div>
                        </button>

                        <button 
                          @click="showHelpModal = true"
                          class="w-full flex items-center space-x-4 px-4 py-3 text-slate-300 hover:text-white hover:bg-gradient-to-r hover:from-blue-600/20 hover:to-purple-600/20 rounded-xl transition-all duration-300 group"
                        >
                          <div class="w-10 h-10 bg-gradient-to-br from-orange-500/20 to-red-500/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 text-orange-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                          </div>
                          <div class="flex-1 text-left">
                            <span class="text-sm font-semibold">Help & Support</span>
                            <p class="text-xs text-slate-500 mt-1">Get assistance</p>
                          </div>
                        </button>

                        <div class="border-t border-slate-600/50 my-3"></div>

                        <button 
                          @click="logout"
                          class="w-full flex items-center space-x-4 px-4 py-3 text-red-400 hover:text-red-300 hover:bg-gradient-to-r hover:from-red-600/20 hover:to-pink-600/20 rounded-xl transition-all duration-300 group"
                        >
                          <div class="w-10 h-10 bg-gradient-to-br from-red-500/20 to-pink-500/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 text-red-400 group-hover:text-red-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                          </div>
                          <div class="flex-1 text-left">
                            <span class="text-sm font-semibold">Sign Out</span>
                            <p class="text-xs text-slate-500 mt-1">Logout from system</p>
                          </div>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="p-8">
        <router-view />
      </main>
    </div>

    <!-- Notifications Dropdown -->
    <div v-if="showNotifications" class="fixed top-20 right-8 z-50">
      <div class="glass rounded-2xl p-6 w-80 max-h-96 overflow-y-auto notifications-dropdown">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-bold text-white">Notifications</h3>
          <button 
            @click="showNotifications = false"
            class="p-1 text-white/60 hover:text-white hover:bg-white/10 rounded-lg transition-colors"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        
        <div v-if="notifications.length === 0" class="text-center py-8">
          <svg class="w-12 h-12 text-white/40 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.19 4H20a2 2 0 012 2v6a10 10 0 01-10 10H4.19a2 2 0 01-1.7-3.12L6.5 12.5a2 2 0 01-.3-1.5V6a2 2 0 012-2z"></path>
          </svg>
          <p class="text-white/60">No notifications</p>
        </div>
        
        <div v-else class="space-y-3">
          <div 
            v-for="notification in notifications" 
            :key="notification.id"
            class="p-3 bg-white/5 rounded-lg border-l-4"
            :class="{
              'border-green-400': notification.type === 'success',
              'border-red-400': notification.type === 'error',
              'border-blue-400': notification.type === 'info',
              'border-yellow-400': notification.type === 'warning'
            }"
          >
            <div class="flex items-start justify-between">
              <div class="flex-1">
                <p class="text-white font-medium text-sm">{{ notification.title }}</p>
                <p class="text-white/60 text-xs mt-1">{{ notification.message }}</p>
                <p class="text-white/40 text-xs mt-2">{{ formatTime(notification.time) }}</p>
              </div>
              <button 
                @click="removeNotification(notification.id)"
                class="p-1 text-white/40 hover:text-white hover:bg-white/10 rounded transition-colors ml-2"
              >
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Settings Modal -->
    <div v-if="showSettings" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 modal-backdrop" @click="showSettings = false">
      <div class="glass rounded-2xl p-8 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto" @click.stop>
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-white">Settings</h2>
          <button 
            @click="showSettings = false"
            class="p-2 text-white/60 hover:text-white hover:bg-white/10 rounded-lg transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <div class="space-y-6">
          <!-- Theme Settings -->
          <div>
            <h3 class="text-lg font-semibold text-white mb-4">Theme Settings</h3>
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <span class="text-white/80">Dark Mode</span>
                <button 
                  @click="toggleTheme"
                  class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
                  :class="isDarkMode ? 'bg-blue-600' : 'bg-gray-600'"
                >
                  <span 
                    class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                    :class="isDarkMode ? 'translate-x-6' : 'translate-x-1'"
                  ></span>
                </button>
              </div>
            </div>
          </div>

          <!-- Notification Settings -->
          <div>
            <h3 class="text-lg font-semibold text-white mb-4">Notification Settings</h3>
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <span class="text-white/80">Email Notifications</span>
                <input 
                  v-model="settings.emailNotifications" 
                  type="checkbox" 
                  class="rounded border-white/20 bg-white/10 text-blue-600 focus:ring-blue-500"
                />
              </div>
              <div class="flex items-center justify-between">
                <span class="text-white/80">System Notifications</span>
                <input 
                  v-model="settings.systemNotifications" 
                  type="checkbox" 
                  class="rounded border-white/20 bg-white/10 text-blue-600 focus:ring-blue-500"
                />
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex space-x-4 pt-6 border-t border-white/10">
            <button 
              @click="showSettings = false"
              class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200"
            >
              Cancel
            </button>
            <button 
              @click="saveSettings"
              class="flex-1 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200"
            >
              Save Settings
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Profile Modal -->
    <div v-if="showProfileModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 modal-backdrop" @click="showProfileModal = false">
      <div class="glass rounded-2xl p-8 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto" @click.stop>
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-white">My Profile</h2>
          <button 
            @click="showProfileModal = false"
            class="p-2 text-white/60 hover:text-white hover:bg-white/10 rounded-lg transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <div class="space-y-6">
          <!-- Profile Information -->
          <div>
            <h3 class="text-lg font-semibold text-white mb-4">Profile Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-white/80 text-sm font-medium mb-2">Full Name</label>
                <input 
                  v-model="profile.fullName"
                  type="text" 
                  class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Enter your full name"
                />
              </div>
              <div>
                <label class="block text-white/80 text-sm font-medium mb-2">Email</label>
                <input 
                  v-model="profile.email"
                  type="email" 
                  class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Enter your email"
                />
              </div>
              <div>
                <label class="block text-white/80 text-sm font-medium mb-2">Phone</label>
                <input 
                  v-model="profile.phone"
                  type="tel" 
                  class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Enter your phone number"
                />
              </div>
              <div>
                <label class="block text-white/80 text-sm font-medium mb-2">Role</label>
                <input 
                  v-model="profile.role"
                  type="text" 
                  class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Enter your role"
                />
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex space-x-4 pt-6 border-t border-white/10">
            <button 
              @click="showProfileModal = false"
              class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200"
            >
              Cancel
            </button>
            <button 
              @click="saveProfile"
              class="flex-1 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200"
            >
              Save Profile
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Help Modal -->
    <div v-if="showHelpModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 modal-backdrop" @click="showHelpModal = false">
      <div class="glass rounded-2xl p-8 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto" @click.stop>
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-2xl font-bold text-white">Help & Support</h2>
          <button 
            @click="showHelpModal = false"
            class="p-2 text-white/60 hover:text-white hover:bg-white/10 rounded-lg transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <div class="space-y-6">
          <!-- Getting Started -->
          <div class="p-4 bg-white/5 rounded-xl border border-white/10">
            <h3 class="text-lg font-semibold text-white mb-4">Getting Started</h3>
            <div class="space-y-3 text-white/80">
              <p>Welcome to Muhtaseb! Here's how to get started:</p>
              <ul class="list-disc list-inside space-y-2 ml-4">
                <li>Navigate through the sidebar to access different modules</li>
                <li>Use the Dashboard to get an overview of your properties</li>
                <li>Manage your properties, units, and tenants</li>
                <li>Track income, expenses, and generate reports</li>
              </ul>
            </div>
          </div>

          <!-- Contact Information -->
          <div class="p-4 bg-white/5 rounded-xl border border-white/10">
            <h3 class="text-lg font-semibold text-white mb-4">Contact Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="flex items-center space-x-3">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <span class="text-white/80">support@muhtaseb.com</span>
              </div>
              <div class="flex items-center space-x-3">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
                <span class="text-white/80">+1 (555) 123-4567</span>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex space-x-4 pt-6 border-t border-white/10">
            <button 
              @click="showHelpModal = false"
              class="flex-1 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200"
            >
              Got it
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Dashboard from './pages/Dashboard.vue'
import Income from './pages/Income.vue'
import Expenses from './pages/Expenses.vue'
import Owners from './pages/Owners.vue'
import Invoices from './pages/Invoices.vue'
import Categories from './pages/Categories.vue'
import Users from './pages/Users.vue'
import Reports from './pages/Reports.vue'

export default {
  name: 'App',
  components: {
    Dashboard,
    Income,
    Expenses,
    Owners,
    Invoices,
    Categories,
    Users,
    Reports
  },
  data() {
    return {
      sidebarOpen: false,
      showNotifications: false,
      showSettings: false,
      showProfileModal: false,
      showHelpModal: false,
      showUserMenu: false,
      isDarkMode: true,
      notifications: [],
      settings: {
        emailNotifications: true,
        systemNotifications: true
      },
      profile: {
        fullName: 'Admin User',
        email: 'admin@muhtaseb.com',
        phone: '+1 (555) 123-4567',
        role: 'Administrator'
      }
    }
  },
  computed: {
    currentPageTitle() {
      const currentRoute = this.$route.path
      const pageTitles = {
        '/': 'Dashboard',
        '/properties': 'Properties',
        '/units': 'Units',
        '/contracts': 'Contracts',
        '/income': 'Income',
        '/expenses': 'Expenses',
        '/owners': 'Owners & Distributions',
        '/payments': 'Payments',
        '/invoices': 'Invoices',
        '/monthly-statements': 'Monthly Statements',
        '/categories': 'Categories',
        '/users': 'User Management',
        '/reports': 'Reports'
      }
      return pageTitles[currentRoute] || 'Dashboard'
    },
    currentPageDescription() {
      const descriptions = {
        '/': 'Overview of your property portfolio',
        '/properties': 'Manage your properties',
        '/units': 'Manage property units',
        '/contracts': 'Manage contracts and leases',
        '/income': 'Track rental income and payments',
        '/expenses': 'Monitor property expenses',
        '/owners': 'Manage property owners and profit distributions',
        '/payments': 'Track payment transactions',
        '/invoices': 'Generate and manage invoices',
        '/monthly-statements': 'Generate and send monthly owner statements',
        '/categories': 'Manage expense and income categories',
        '/users': 'Manage system users and permissions',
        '/reports': 'Financial reports and analytics'
      }
              return descriptions[this.$route.path] || 'Property management system'
    }
  },
  mounted() {
    // Close user menu when clicking outside
    document.addEventListener('click', this.handleClickOutside);
    // Close notifications when clicking outside
    document.addEventListener('click', this.handleNotificationsClickOutside);
    // Close modals when pressing Escape key
    document.addEventListener('keydown', this.handleEscapeKey);
  },
  beforeUnmount() {
    document.removeEventListener('click', this.handleClickOutside);
    document.removeEventListener('click', this.handleNotificationsClickOutside);
    document.removeEventListener('keydown', this.handleEscapeKey);
  },
  methods: {
    addNotification(type, title, message) {
      const id = Date.now();
      this.notifications.push({
        id,
        type,
        title,
        message,
        time: new Date()
      });
      setTimeout(() => this.removeNotification(id), 5000); // Auto-remove after 5 seconds
    },
    removeNotification(id) {
      this.notifications = this.notifications.filter(n => n.id !== id);
    },
    toggleTheme() {
      this.isDarkMode = !this.isDarkMode;
      document.documentElement.classList.toggle('dark', this.isDarkMode);
    },
    saveSettings() {
      console.log('Settings saved:', this.settings);
      this.showSettings = false;
      this.addNotification('success', 'Settings Saved', 'Your settings have been updated successfully.');
    },
    saveProfile() {
      console.log('Profile updated:', this.profile);
      this.showProfileModal = false;
      this.addNotification('success', 'Profile Updated', 'Your profile has been updated successfully.');
    },
    formatTime(date) {
      return date.toLocaleTimeString();
    },
    toggleUserMenu(event) {
      event.stopPropagation(); // Prevent event bubbling
      this.showUserMenu = !this.showUserMenu;
    },
    logout() {
      this.showUserMenu = false; // Close menu on logout
      this.addNotification('info', 'Logged Out', 'You have been logged out successfully.');
      // In a real app, you would clear authentication tokens here
      setTimeout(() => {
        this.$router.push('/login'); // Redirect to login page
      }, 1000);
    },
    handleClickOutside(event) {
      const userMenu = event.target.closest('.user-profile-card');
      const userDropdown = event.target.closest('.user-menu-dropdown');
      
      if (!userMenu && !userDropdown && this.showUserMenu) {
        this.showUserMenu = false;
      }
    },
    handleNotificationsClickOutside(event) {
      const notificationsDropdown = event.target.closest('.notifications-dropdown');
      const notificationsButton = event.target.closest('[data-notifications-button]');
      
      if (!notificationsDropdown && !notificationsButton && this.showNotifications) {
        this.showNotifications = false;
      }
    },
    handleEscapeKey(event) {
      if (event.key === 'Escape') {
        this.showUserMenu = false;
        this.showNotifications = false;
        this.showSettings = false;
        this.showProfileModal = false;
        this.showHelpModal = false;
      }
    },
    closeUserMenu() {
      this.showUserMenu = false;
    },
    closeAllModals() {
      this.showUserMenu = false;
      this.showNotifications = false;
      this.showSettings = false;
      this.showProfileModal = false;
      this.showHelpModal = false;
    }
  }
}
</script>

<style>
/* Custom scrollbar */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.1);
  border-radius: 3px;
}

::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.3);
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: rgba(255, 255, 255, 0.5);
}

/* Enhanced Glass effect */
.glass {
  background: rgba(255, 255, 255, 0.08);
  backdrop-filter: blur(24px);
  border: 1px solid rgba(255, 255, 255, 0.12);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

/* Hover lift effect */
.hover-lift:hover {
  transform: translateY(-2px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
}

/* Form styling fixes */
select option {
  background-color: #1f2937;
  color: white;
}

/* Dropdown fixes */
select {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
  background-position: right 0.5rem center;
  background-repeat: no-repeat;
  background-size: 1.5em 1.5em;
  padding-right: 2.5rem;
}

/* Animation classes */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.fade-in {
  animation: fadeIn 0.3s ease-out;
}

/* User menu dropdown animations */
.user-menu-dropdown {
  animation: slideInUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(-10px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

/* Enhanced user profile hover effects */
.user-profile-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
}

/* Profile section styling */
.profile-section {
  background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.1));
  border: 1px solid rgba(59, 130, 246, 0.2);
}

/* Focus states */
.focus-ring:focus {
  outline: none;
  box-shadow: 0 0 0 2px #3b82f6;
}

/* Enhanced glass morphism */
.glass-morphism {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(20px) saturate(180%);
  border: 1px solid rgba(255, 255, 255, 0.15);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

/* Smooth transitions */
* {
  transition: all 0.2s ease-in-out;
}

/* Custom button styles */
.btn-glass {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  transition: all 0.3s ease;
}

.btn-glass:hover {
  background: rgba(255, 255, 255, 0.2);
  border-color: rgba(255, 255, 255, 0.3);
  transform: translateY(-1px);
}

/* Enhanced sidebar navigation effects */
.sidebar-nav-item {
  position: relative;
  overflow: hidden;
}

.sidebar-nav-item::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
  transition: left 0.5s;
}

.sidebar-nav-item:hover::before {
  left: 100%;
}

/* Active navigation item glow effect */
.nav-item-active {
  box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
}

/* Enhanced section dividers */
.section-divider {
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
  margin: 2rem 0;
}

/* Improved scrollbar for sidebar */
.sidebar-scroll::-webkit-scrollbar {
  width: 4px;
}

.sidebar-scroll::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.05);
  border-radius: 2px;
}

.sidebar-scroll::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.2);
  border-radius: 2px;
}

.sidebar-scroll::-webkit-scrollbar-thumb:hover {
  background: rgba(255, 255, 255, 0.3);
}

/* Enhanced notification badge */
.notification-badge {
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
    transform: scale(1);
  }
  50% {
    opacity: 0.7;
    transform: scale(1.1);
  }
}

/* Professional loading states */
.loading-shimmer {
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
  background-size: 200% 100%;
  animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
  0% { background-position: -200% 0; }
  100% { background-position: 200% 0; }
}

/* Enhanced modal backdrop */
.modal-backdrop {
  backdrop-filter: blur(8px);
  background: rgba(0, 0, 0, 0.6);
}

/* Professional button hover effects */
.btn-hover-lift:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

/* Enhanced card shadows */
.card-shadow {
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  transition: box-shadow 0.3s ease;
}

.card-shadow:hover {
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
}

/* Professional text gradients */
.text-gradient-primary {
  background: linear-gradient(135deg, #3b82f6, #8b5cf6);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.text-gradient-success {
  background: linear-gradient(135deg, #10b981, #059669);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* Enhanced focus states */
.focus-ring {
  outline: none;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
}

/* Professional spacing utilities */
.space-y-6 > * + * {
  margin-top: 1.5rem;
}

.space-y-8 > * + * {
  margin-top: 2rem;
}

/* Enhanced responsive design */
@media (max-width: 1024px) {
  .sidebar-responsive {
    transform: translateX(-100%);
    transition: transform 0.3s ease;
  }
  
  .sidebar-responsive.open {
    transform: translateX(0);
  }
}

@media (max-width: 768px) {
  .header-responsive {
    padding: 1rem;
  }
  
  .header-responsive h2 {
    font-size: 1.5rem;
  }
  
  .header-responsive p {
    font-size: 0.875rem;
  }
  
  .user-profile-card {
    padding: 0.75rem;
  }
  
  .user-profile-card .flex-1 {
    display: none;
  }
}

@media (max-width: 640px) {
  .modal-responsive {
    margin: 1rem;
    padding: 1.5rem;
  }
  
  .notifications-dropdown {
    width: calc(100vw - 2rem);
    right: 1rem;
  }
}

/* Professional status indicators */
.status-online {
  background: linear-gradient(135deg, #10b981, #059669);
  box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);
}

.status-offline {
  background: linear-gradient(135deg, #6b7280, #4b5563);
}

.status-busy {
  background: linear-gradient(135deg, #f59e0b, #d97706);
  box-shadow: 0 0 10px rgba(245, 158, 11, 0.5);
}

/* Performance optimizations */
.will-change-transform {
  will-change: transform;
}

.will-change-opacity {
  will-change: opacity;
}

/* Improved focus management */
.focus-visible:focus {
  outline: 2px solid #3b82f6;
  outline-offset: 2px;
}

/* Enhanced accessibility */
@media (prefers-reduced-motion: reduce) {
  *,
  *::before,
  *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}

/* Better scrollbar for webkit browsers */
.sidebar-scroll::-webkit-scrollbar {
  width: 4px;
}

.sidebar-scroll::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.05);
  border-radius: 2px;
}

.sidebar-scroll::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.2);
  border-radius: 2px;
}

.sidebar-scroll::-webkit-scrollbar-thumb:hover {
  background: rgba(255, 255, 255, 0.3);
}

/* Firefox scrollbar */
.sidebar-scroll {
  scrollbar-width: thin;
  scrollbar-color: rgba(255, 255, 255, 0.2) rgba(255, 255, 255, 0.05);
}

/* Enhanced modal backdrop */
.modal-backdrop {
  backdrop-filter: blur(8px);
  background: rgba(0, 0, 0, 0.6);
}

/* Professional button hover effects */
.btn-hover-lift:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

/* Enhanced card shadows */
.card-shadow {
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  transition: box-shadow 0.3s ease;
}

.card-shadow:hover {
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
}

/* Professional text gradients */
.text-gradient-primary {
  background: linear-gradient(135deg, #3b82f6, #8b5cf6);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.text-gradient-success {
  background: linear-gradient(135deg, #10b981, #059669);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* Enhanced focus states */
.focus-ring {
  outline: none;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
}

/* Professional spacing utilities */
.space-y-6 > * + * {
  margin-top: 1.5rem;
}

.space-y-8 > * + * {
  margin-top: 2rem;
}

/* Z-index management */
.z-dropdown {
  z-index: 50;
}

.z-modal {
  z-index: 100;
}

.z-tooltip {
  z-index: 150;
}

/* Loading states */
.loading-shimmer {
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
  background-size: 200% 100%;
  animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
  0% { background-position: -200% 0; }
  100% { background-position: 200% 0; }
}

/* Enhanced notification badge */
.notification-badge {
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
    transform: scale(1);
  }
  50% {
    opacity: 0.7;
    transform: scale(1.1);
  }
}
</style>
