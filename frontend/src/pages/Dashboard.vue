<template>
  <div class="space-y-8">
    <!-- Enhanced Loading State with Premium Glass Skeleton -->
    <div v-if="loading" class="space-y-8">
      <!-- Premium Skeleton Header -->
      <div class="flex items-center justify-between">
        <div>
          <div class="h-8 bg-gradient-to-r from-white/10 via-white/20 to-white/10 rounded-lg w-48 mb-2 loading-shimmer"></div>
          <div class="h-4 bg-gradient-to-r from-white/10 via-white/20 to-white/10 rounded w-64 loading-shimmer"></div>
        </div>
      </div>

      <!-- Premium Skeleton Cards with Staggered Animation -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div v-for="i in 4" :key="i" class="glass-premium rounded-2xl p-6" :style="{ animationDelay: `${i * 0.1}s` }">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-r from-white/10 via-white/20 to-white/10 rounded-xl loading-shimmer"></div>
            <div class="w-16 h-6 bg-gradient-to-r from-white/10 via-white/20 to-white/10 rounded loading-shimmer"></div>
          </div>
          <div class="h-6 bg-gradient-to-r from-white/10 via-white/20 to-white/10 rounded w-24 mb-2 loading-shimmer"></div>
          <div class="h-8 bg-gradient-to-r from-white/10 via-white/20 to-white/10 rounded w-32 mb-2 loading-shimmer"></div>
          <div class="h-4 bg-gradient-to-r from-white/10 via-white/20 to-white/10 rounded w-20 loading-shimmer"></div>
        </div>
      </div>

      <!-- Premium Skeleton Charts -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="glass-premium rounded-2xl p-6">
          <div class="h-6 bg-gradient-to-r from-white/10 via-white/20 to-white/10 rounded w-48 mb-6 loading-shimmer"></div>
          <div class="h-64 bg-gradient-to-r from-white/10 via-white/20 to-white/10 rounded loading-shimmer"></div>
        </div>
        <div class="glass-premium rounded-2xl p-6">
          <div class="h-6 bg-gradient-to-r from-white/10 via-white/20 to-white/10 rounded w-48 mb-6 loading-shimmer"></div>
          <div class="h-64 bg-gradient-to-r from-white/10 via-white/20 to-white/10 rounded loading-shimmer"></div>
        </div>
      </div>
    </div>

    <!-- Enhanced Dashboard Content with Premium Glass Effects -->
    <div v-else class="space-y-8">
      <!-- Premium Quick Actions with Enhanced Glass Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Add Income Card with Premium Glass Effects -->
        <div 
          class="glass-premium rounded-2xl p-6 hover-lift transition-all duration-500 group cursor-pointer animate-float" 
          @click="navigateToIncome"
          style="animation-delay: 0.1s"
        >
          <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-xl group-hover:scale-110 transition-transform duration-500 hover-rotate shadow-glow">
              <svg class="w-6 h-6 text-green-400 group-hover:text-green-300 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
            </div>
            <div class="text-xs bg-gradient-to-r from-green-500/20 to-emerald-500/20 hover:from-green-500/30 hover:to-emerald-500/30 text-green-400 px-3 py-1 rounded-lg transition-all duration-300 group-hover:scale-105">
              Quick Add
            </div>
          </div>
          <h3 class="text-white font-semibold mb-2 group-hover:text-green-400 transition-colors duration-300 text-gradient">Add Income</h3>
          <p class="text-white/60 text-sm group-hover:text-white/80 transition-colors duration-300">Record new income transaction</p>
        </div>

        <!-- Total Income Card with Premium Glass Effects -->
        <div 
          class="glass-premium rounded-2xl p-6 hover-lift group animate-float"
          style="animation-delay: 0.2s"
        >
          <div class="flex items-center justify-between">
            <div>
              <p class="text-white/60 text-sm font-medium">Total Income</p>
              <p class="text-3xl font-bold text-white mt-2 group-hover:text-green-400 transition-colors duration-300 text-gradient-success">
                ${{ formatNumber(dashboard.overview?.total_income || 0) }}
              </p>
              <div class="flex items-center mt-2">
                <svg class="w-4 h-4 text-green-400 mr-1 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                <span class="text-green-400 text-sm font-medium">+12.5%</span>
              </div>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-500 hover-rotate shadow-glow">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
              </svg>
            </div>
          </div>
        </div>

        <!-- Total Expenses Card with Premium Glass Effects -->
        <div 
          class="glass-premium rounded-2xl p-6 hover-lift group animate-float"
          style="animation-delay: 0.3s"
        >
          <div class="flex items-center justify-between">
            <div>
              <p class="text-white/60 text-sm font-medium">Total Expenses</p>
              <p class="text-3xl font-bold text-white mt-2 group-hover:text-red-400 transition-colors duration-300">
                ${{ formatNumber(dashboard.overview?.total_expenses || 0) }}
              </p>
              <div class="flex items-center mt-2">
                <svg class="w-4 h-4 text-red-400 mr-1 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                </svg>
                <span class="text-red-400 text-sm font-medium">+8.2%</span>
              </div>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-500 hover-rotate shadow-glow">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
              </svg>
            </div>
          </div>
        </div>

        <!-- Net Income Card with Premium Glass Effects -->
        <div 
          class="glass-premium rounded-2xl p-6 hover-lift group animate-float"
          style="animation-delay: 0.4s"
        >
          <div class="flex items-center justify-between">
            <div>
              <p class="text-white/60 text-sm font-medium">Net Income</p>
              <p class="text-3xl font-bold text-white mt-2 group-hover:text-blue-400 transition-colors duration-300 text-gradient-primary">
                ${{ formatNumber(dashboard.overview?.net_income || 0) }}
              </p>
              <div class="flex items-center mt-2">
                <svg class="w-4 h-4 text-blue-400 mr-1 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                <span class="text-blue-400 text-sm font-medium">+15.3%</span>
              </div>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-500 hover-rotate shadow-glow">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Premium Charts Section with Enhanced Glass Effects -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Income vs Expenses Chart with Premium Glass -->
        <div class="glass-premium rounded-2xl p-6 hover-lift slide-in-left" style="animation-delay: 0.5s">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-white text-gradient">Income vs Expenses</h3>
            <div class="flex space-x-4">
              <div class="flex items-center">
                <div class="w-3 h-3 bg-green-400 rounded-full mr-2 animate-pulse shadow-glow"></div>
                <span class="text-white/60 text-sm">Income</span>
              </div>
              <div class="flex items-center">
                <div class="w-3 h-3 bg-red-400 rounded-full mr-2 animate-pulse shadow-glow"></div>
                <span class="text-white/60 text-sm">Expenses</span>
              </div>
              <button class="text-white/60 hover:text-white text-sm transition-colors duration-300 hover-scale">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                </svg>
              </button>
            </div>
          </div>
          <div class="chart-container">
            <canvas ref="incomeExpensesChart"></canvas>
          </div>
        </div>

        <!-- Owner Distribution Chart with Premium Glass -->
        <div class="glass-premium rounded-2xl p-6 hover-lift slide-in-right" style="animation-delay: 0.6s">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-white text-gradient">Owner Distribution</h3>
            <button class="text-white/60 hover:text-white text-sm transition-colors duration-300 hover-scale">View All</button>
          </div>
          <div class="chart-container">
            <canvas ref="ownerDistributionChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Property Statistics with Premium Glass -->
      <div class="glass-premium rounded-2xl p-6 hover-lift slide-in-up mb-8" style="animation-delay: 0.7s">
        <h3 class="text-xl font-bold text-white mb-6 text-gradient">Property Portfolio</h3>
        <div class="grid grid-cols-2 gap-4">
          <div class="flex items-center justify-between p-3 bg-gradient-to-r from-emerald-500/10 to-emerald-500/5 rounded-lg border border-emerald-500/20 hover:border-emerald-500/30 transition-all duration-300 hover-scale">
            <div class="flex items-center">
              <div class="w-8 h-8 bg-emerald-500/20 rounded-lg flex items-center justify-center mr-3 hover-rotate shadow-glow">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
              </div>
              <span class="text-white/60">Properties</span>
            </div>
            <span class="text-emerald-400 font-semibold">{{ dashboard.overview?.properties || 0 }}</span>
          </div>
          <div class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-500/10 to-blue-500/5 rounded-lg border border-blue-500/20 hover:border-blue-500/30 transition-all duration-300 hover-scale">
            <div class="flex items-center">
              <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center mr-3 hover-rotate shadow-glow">
                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5v4m8-4v4M8 11v4m8-4v4"></path>
                </svg>
              </div>
              <span class="text-white/60">Units</span>
            </div>
            <span class="text-blue-400 font-semibold">{{ dashboard.overview?.units || 0 }}</span>
          </div>
          <div class="flex items-center justify-between p-3 bg-gradient-to-r from-purple-500/10 to-purple-500/5 rounded-lg border border-purple-500/20 hover:border-purple-500/30 transition-all duration-300 hover-scale">
            <div class="flex items-center">
              <div class="w-8 h-8 bg-purple-500/20 rounded-lg flex items-center justify-center mr-3 hover-rotate shadow-glow">
                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
              </div>
              <span class="text-white/60">Occupancy</span>
            </div>
            <span class="text-purple-400 font-semibold">{{ dashboard.overview?.occupancy_rate || 0 }}%</span>
          </div>
          <div class="flex items-center justify-between p-3 bg-gradient-to-r from-orange-500/10 to-orange-500/5 rounded-lg border border-orange-500/20 hover:border-orange-500/30 transition-all duration-300 hover-scale">
            <div class="flex items-center">
              <div class="w-8 h-8 bg-orange-500/20 rounded-lg flex items-center justify-center mr-3 hover-rotate shadow-glow">
                <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
              </div>
              <span class="text-white/60">Contracts</span>
            </div>
            <span class="text-orange-400 font-semibold">{{ dashboard.overview?.active_contracts || 0 }}</span>
          </div>
        </div>
      </div>

      <!-- Premium Property Stats & Recent Activity with Enhanced Glass -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Financial Statistics with Premium Glass -->
        <div class="glass-premium rounded-2xl p-6 hover-lift slide-in-up" style="animation-delay: 0.7s">
          <h3 class="text-xl font-bold text-white mb-6 text-gradient">Financial Statistics</h3>
          <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-white/5 to-white/10 rounded-lg hover:bg-white/15 transition-all duration-300 hover-scale">
              <div class="flex items-center">
                <div class="w-8 h-8 bg-green-500/20 rounded-lg flex items-center justify-center mr-3 hover-rotate shadow-glow">
                  <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                  </svg>
                </div>
                <span class="text-white/60">Total Income</span>
              </div>
              <span class="text-white font-semibold text-gradient-success">${{ formatNumber(dashboard.overview?.total_income || 0) }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-white/5 to-white/10 rounded-lg hover:bg-white/15 transition-all duration-300 hover-scale">
              <div class="flex items-center">
                <div class="w-8 h-8 bg-red-500/20 rounded-lg flex items-center justify-center mr-3 hover-rotate shadow-glow">
                  <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                  </svg>
                </div>
                <span class="text-white/60">Total Expenses</span>
              </div>
              <span class="text-white font-semibold text-gradient-primary">${{ formatNumber(dashboard.overview?.total_expenses || 0) }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-white/5 to-white/10 rounded-lg hover:bg-white/15 transition-all duration-300 hover-scale">
              <div class="flex items-center">
                <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center mr-3 hover-rotate shadow-glow">
                  <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                  </svg>
                </div>
                <span class="text-white/60">Net Income</span>
              </div>
              <span class="text-blue-400 font-semibold">${{ formatNumber(dashboard.overview?.net_income || 0) }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-white/5 to-white/10 rounded-lg hover:bg-white/15 transition-all duration-300 hover-scale">
              <div class="flex items-center">
                <div class="w-8 h-8 bg-purple-500/20 rounded-lg flex items-center justify-center mr-3 hover-rotate shadow-glow">
                  <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                  </svg>
                </div>
                <span class="text-white/60">Active Owners</span>
              </div>
              <span class="text-purple-400 font-semibold">{{ dashboard.owner_distributions?.length || 0 }}</span>
            </div>
          </div>
        </div>

        <!-- Current Month Summary with Premium Glass -->
        <div class="glass-premium rounded-2xl p-6 hover-lift slide-in-up" style="animation-delay: 0.8s">
          <h3 class="text-xl font-bold text-white mb-6 text-gradient">Current Month</h3>
          <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-green-500/10 to-green-500/5 rounded-lg border border-green-500/20 hover:border-green-500/30 transition-all duration-300 hover-scale">
              <div class="flex items-center">
                <div class="w-8 h-8 bg-green-500/20 rounded-lg flex items-center justify-center mr-3 hover-rotate shadow-glow">
                  <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                  </svg>
                </div>
                <span class="text-white/60">Income</span>
              </div>
              <span class="text-green-400 font-semibold text-gradient-success">${{ formatNumber(dashboard.current_month?.income || 0) }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-red-500/10 to-red-500/5 rounded-lg border border-red-500/20 hover:border-red-500/30 transition-all duration-300 hover-scale">
              <div class="flex items-center">
                <div class="w-8 h-8 bg-red-500/20 rounded-lg flex items-center justify-center mr-3 hover-rotate shadow-glow">
                  <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                  </svg>
                </div>
                <span class="text-white/60">Expenses</span>
              </div>
              <span class="text-red-400 font-semibold">${{ formatNumber(dashboard.current_month?.expenses || 0) }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-500/10 to-blue-500/5 rounded-lg border border-blue-500/20 hover:border-blue-500/30 transition-all duration-300 hover-scale">
              <div class="flex items-center">
                <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center mr-3 hover-rotate shadow-glow">
                  <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                  </svg>
                </div>
                <span class="text-white/60">Net</span>
              </div>
              <span class="text-white font-semibold text-gradient-primary">${{ formatNumber(dashboard.current_month?.net || 0) }}</span>
            </div>
            <div class="pt-4 border-t border-white/10">
              <div class="flex items-center justify-between">
                <span class="text-white/60">vs Last Month</span>
                <span :class="getComparisonColor(dashboard.comparisons?.vs_previous_month?.net_change || 0)" class="font-medium">
                  {{ getComparisonText(dashboard.comparisons?.vs_previous_month?.net_change || 0) }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Owner Balances with Premium Glass -->
        <div class="glass-premium rounded-2xl p-6 hover-lift slide-in-up" style="animation-delay: 0.9s">
          <h3 class="text-xl font-bold text-white mb-6 text-gradient">Owner Balances</h3>
          <div class="space-y-3 max-h-48 overflow-y-auto">
            <div v-for="(owner, index) in dashboard.owner_distributions" :key="owner.owner_id" 
                 class="flex items-center justify-between p-3 bg-gradient-to-r from-white/5 to-white/10 rounded-lg hover:bg-white/15 transition-all duration-300 hover-scale"
                 :style="{ animationDelay: `${1.0 + index * 0.1}s` }">
              <div>
                <p class="text-white font-medium text-sm">{{ owner.name }}</p>
                <p class="text-white/60 text-xs">{{ owner.share_percentage }}% share</p>
              </div>
              <div class="text-right">
                <p class="text-white font-semibold text-sm">${{ formatNumber(owner.balance_owed) }}</p>
                <p :class="owner.balance_owed >= 0 ? 'text-green-400' : 'text-red-400'" class="text-xs font-medium">
                  {{ owner.balance_owed >= 0 ? 'Owed' : 'Overpaid' }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Premium Category Breakdown Section -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Income Categories with Premium Glass -->
        <div class="glass-premium rounded-2xl p-6 hover-lift slide-in-up" style="animation-delay: 1.0s">
          <h3 class="text-xl font-bold text-white mb-6 text-gradient">Income Categories</h3>
          <div class="space-y-3 max-h-48 overflow-y-auto">
            <div v-for="(category, index) in dashboard.category_breakdown?.income" :key="index" 
                 class="flex items-center justify-between p-3 bg-gradient-to-r from-green-500/10 to-green-500/5 rounded-lg border border-green-500/20 hover:border-green-500/30 transition-all duration-300 hover-scale"
                 :style="{ animationDelay: `${1.1 + index * 0.1}s` }">
              <div class="flex items-center">
                <div class="w-8 h-8 bg-green-500/20 rounded-lg flex items-center justify-center mr-3 hover-rotate shadow-glow">
                  <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                  </svg>
                </div>
                <span class="text-white/80 font-medium">{{ category.name }}</span>
              </div>
              <span class="text-green-400 font-semibold">${{ formatNumber(category.total) }}</span>
            </div>
          </div>
        </div>

        <!-- Expense Categories with Premium Glass -->
        <div class="glass-premium rounded-2xl p-6 hover-lift slide-in-up" style="animation-delay: 1.1s">
          <h3 class="text-xl font-bold text-white mb-6 text-gradient">Expense Categories</h3>
          <div class="space-y-3 max-h-48 overflow-y-auto">
            <div v-for="(category, index) in dashboard.category_breakdown?.expenses" :key="index" 
                 class="flex items-center justify-between p-3 bg-gradient-to-r from-red-500/10 to-red-500/5 rounded-lg border border-red-500/20 hover:border-red-500/30 transition-all duration-300 hover-scale"
                 :style="{ animationDelay: `${1.2 + index * 0.1}s` }">
              <div class="flex items-center">
                <div class="w-8 h-8 bg-red-500/20 rounded-lg flex items-center justify-center mr-3 hover-rotate shadow-glow">
                  <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                  </svg>
                </div>
                <span class="text-white/80 font-medium">{{ category.name }}</span>
              </div>
              <span class="text-red-400 font-semibold">${{ formatNumber(category.total) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Chart, registerables } from 'chart.js'

Chart.register(...registerables)

export default {
  name: 'Dashboard',
  data() {
    return {
      loading: true,
      dashboard: {},
      incomeExpensesChart: null,
      ownerDistributionChart: null
    }
  },
  async mounted() {
    await this.loadDashboardData()
    this.$nextTick(() => {
      setTimeout(() => {
        this.initializeCharts()
      }, 100)
    })
  },
  methods: {
    async loadDashboardData() {
      try {
        console.log('Loading dashboard data from backend...')
        // Use Laravel API only
        const response = await this.$http.get('/dashboard')
        const payload = response?.data
        // Laravel returns { success, data: {...} }
        this.dashboard = payload?.data || payload || {}

        // Fallbacks when Laravel payload lacks fields expected by UI
        if (!this.dashboard.current_month && this.dashboard.overview) {
          const mi = this.dashboard.overview.monthly_income || 0
          const me = this.dashboard.overview.monthly_expenses || 0
          this.dashboard.current_month = {
            income: mi,
            expenses: me,
            net: mi - me
          }
        }
      } catch (error) {
        console.error('Error loading dashboard data:', error)
        // Set default data for demo
        this.dashboard = {
          overview: {
            total_income: 125000,
            total_expenses: 45000,
            net_income: 80000
          },
          current_month: {
            income: 15000,
            expenses: 5000,
            net: 10000
          },
          comparisons: {
            vs_previous_month: {
              net_change: 2500
            }
          },
          owner_distributions: [
            { owner_id: 1, name: 'John Doe', share_percentage: 60, balance_owed: 6000 },
            { owner_id: 2, name: 'Jane Smith', share_percentage: 40, balance_owed: 4000 }
          ],
          monthly_chart_data: [
            { month: '2024-01', income: 12000, expenses: 4000 },
            { month: '2024-02', income: 13500, expenses: 4500 },
            { month: '2024-03', income: 15000, expenses: 5000 }
          ],
          category_breakdown: {
            income: [
              { name: 'Rental Income', total: 12000 },
              { name: 'Service Fees', total: 3000 }
            ],
            expenses: [
              { name: 'Maintenance', total: 3000 },
              { name: 'Utilities', total: 2000 }
            ]
          }
        }
      } finally {
        this.loading = false
      }
    },
    initializeCharts() {
      this.createIncomeExpensesChart()
      this.createOwnerDistributionChart()
    },
    createIncomeExpensesChart() {
      const ctx = this.$refs.incomeExpensesChart
      if (!ctx) return

      const data = this.dashboard.monthly_chart_data || []
      
      this.incomeExpensesChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: data.map(d => d.month),
          datasets: [
            {
              label: 'Income',
              data: data.map(d => d.income),
              borderColor: '#10B981',
              backgroundColor: 'rgba(16, 185, 129, 0.15)',
              tension: 0.4,
              fill: true,
              borderWidth: 4,
              pointBackgroundColor: '#10B981',
              pointBorderColor: '#ffffff',
              pointBorderWidth: 3,
              pointRadius: 8,
              pointHoverRadius: 12,
              pointHoverBorderWidth: 4,
              pointHoverBackgroundColor: '#10B981',
              pointHoverBorderColor: '#ffffff'
            },
            {
              label: 'Expenses',
              data: data.map(d => d.expenses),
              borderColor: '#EF4444',
              backgroundColor: 'rgba(239, 68, 68, 0.15)',
              tension: 0.4,
              fill: true,
              borderWidth: 4,
              pointBackgroundColor: '#EF4444',
              pointBorderColor: '#ffffff',
              pointBorderWidth: 3,
              pointRadius: 8,
              pointHoverRadius: 12,
              pointHoverBorderWidth: 4,
              pointHoverBackgroundColor: '#EF4444',
              pointHoverBorderColor: '#ffffff'
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          aspectRatio: 2,
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              backgroundColor: 'rgba(0, 0, 0, 0.9)',
              titleColor: '#ffffff',
              bodyColor: '#ffffff',
              borderColor: 'rgba(255, 255, 255, 0.3)',
              borderWidth: 2,
              cornerRadius: 12,
              displayColors: true,
              titleFont: {
                size: 14,
                weight: 'bold'
              },
              bodyFont: {
                size: 13
              },
              padding: 12,
              boxPadding: 6
            }
          },
          scales: {
            x: {
              grid: {
                color: 'rgba(255, 255, 255, 0.08)',
                drawBorder: false,
                lineWidth: 1
              },
              ticks: {
                color: 'rgba(255, 255, 255, 0.7)',
                font: {
                  size: 12,
                  weight: '500'
                },
                padding: 8
              }
            },
            y: {
              grid: {
                color: 'rgba(255, 255, 255, 0.08)',
                drawBorder: false,
                lineWidth: 1
              },
              ticks: {
                color: 'rgba(255, 255, 255, 0.7)',
                font: {
                  size: 12,
                  weight: '500'
                },
                padding: 8,
                callback: function(value) {
                  return '$' + value.toLocaleString()
                }
              }
            }
          },
          interaction: {
            intersect: false,
            mode: 'index'
          },
          animation: {
            duration: 2000,
            easing: 'easeInOutQuart'
          },
          elements: {
            point: {
              hoverRadius: 12,
              radius: 8
            }
          }
        }
      })
    },
    createOwnerDistributionChart() {
      const ctx = this.$refs.ownerDistributionChart
      if (!ctx) return

      const owners = this.dashboard.owner_distributions || []
      
      this.ownerDistributionChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: owners.map(o => o.name),
          datasets: [{
            data: owners.map(o => o.share_percentage),
            backgroundColor: [
              '#3B82F6',
              '#10B981',
              '#F59E0B',
              '#EF4444',
              '#8B5CF6',
              '#06B6D4',
              '#84CC16',
              '#F97316'
            ],
            borderWidth: 0,
            hoverOffset: 8,
            borderColor: 'rgba(255, 255, 255, 0.1)',
            borderWidth: 2
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          aspectRatio: 1.5,
          cutout: '60%',
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                color: 'rgba(255, 255, 255, 0.9)',
                padding: 20,
                usePointStyle: true,
                font: {
                  size: 13,
                  weight: '500'
                },
                generateLabels: function(chart) {
                  const data = chart.data;
                  if (data.labels.length && data.datasets.length) {
                    return data.labels.map((label, i) => {
                      const dataset = data.datasets[0];
                      const value = dataset.data[i];
                      const backgroundColor = dataset.backgroundColor[i];
                      return {
                        text: `${label}: ${value}%`,
                        fillStyle: backgroundColor,
                        strokeStyle: backgroundColor,
                        lineWidth: 0,
                        pointStyle: 'circle',
                        hidden: false,
                        index: i
                      };
                    });
                  }
                  return [];
                }
              }
            },
            tooltip: {
              backgroundColor: 'rgba(0, 0, 0, 0.9)',
              titleColor: '#ffffff',
              bodyColor: '#ffffff',
              borderColor: 'rgba(255, 255, 255, 0.3)',
              borderWidth: 2,
              cornerRadius: 12,
              titleFont: {
                size: 14,
                weight: 'bold'
              },
              bodyFont: {
                size: 13
              },
              padding: 12,
              callbacks: {
                label: function(context) {
                  return context.label + ': ' + context.parsed + '%'
                }
              }
            }
          },
          animation: {
            duration: 2000,
            easing: 'easeInOutQuart',
            animateRotate: true,
            animateScale: true
          },
          elements: {
            arc: {
              borderWidth: 2,
              borderColor: 'rgba(255, 255, 255, 0.1)'
            }
          }
        }
      })
    },
    formatNumber(num) {
      return new Intl.NumberFormat().format(num)
    },
    getComparisonColor(change) {
      return change >= 0 ? 'text-green-400' : 'text-red-400'
    },
    getComparisonText(change) {
      const sign = change >= 0 ? '+' : ''
      return `${sign}$${this.formatNumber(Math.abs(change))}`
    },
    navigateToIncome() {
      this.$router.push('/income')
    }
  },
  beforeUnmount() {
    if (this.incomeExpensesChart) {
      this.incomeExpensesChart.destroy()
    }
    if (this.ownerDistributionChart) {
      this.ownerDistributionChart.destroy()
    }
  }
}
</script>

<style scoped>
.chart-container {
  position: relative;
  width: 100%;
  height: 300px;
}

.chart-container canvas {
  max-height: 100% !important;
}

/* Enhanced animations */
@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.fade-in-up {
  animation: slideInUp 0.6s ease-out;
}

/* Skeleton animation */
@keyframes shimmer {
  0% {
    background-position: -200px 0;
  }
  100% {
    background-position: calc(200px + 100%) 0;
  }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Enhanced hover effects */
.hover-lift {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.hover-lift:hover {
  transform: translateY(-4px);
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
}
</style>
