<template>
  <Transition name="modal" appear>
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
      <!-- Enhanced Backdrop -->
      <div 
        class="fixed inset-0 bg-black/60 backdrop-blur-md transition-all duration-500 modal-backdrop"
        @click="handleBackdropClick"
      ></div>

      <!-- Enhanced Modal Container -->
      <div class="flex min-h-full items-center justify-center p-4">
        <div 
          class="relative w-full mx-auto"
          :class="size === 'sm' ? 'max-w-md' : size === 'lg' ? 'max-w-4xl' : 'max-w-2xl'"
        >
          <!-- Enhanced Modal Content -->
          <div 
            class="glass-premium rounded-3xl shadow-2xl transform transition-all duration-500 relative overflow-hidden"
            :class="[
              size === 'sm' ? 'p-6' : size === 'lg' ? 'p-8' : 'p-8',
              fullHeight ? 'h-[90vh]' : ''
            ]"
          >
            <!-- Animated Background Pattern -->
            <div class="absolute inset-0 opacity-5">
              <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, rgba(59, 130, 246, 0.3) 0%, transparent 50%), radial-gradient(circle at 75% 75%, rgba(139, 92, 246, 0.3) 0%, transparent 50%);"></div>
            </div>

            <!-- Enhanced Header -->
            <div v-if="title || showClose" class="flex items-center justify-between mb-6 relative z-10">
              <h2 v-if="title" class="text-2xl font-bold text-white text-gradient">{{ title }}</h2>
              <div v-else></div>
              
              <button 
                v-if="showClose"
                @click="close"
                class="w-10 h-10 bg-gradient-to-r from-white/10 to-white/5 hover:from-white/20 hover:to-white/10 rounded-xl flex items-center justify-center text-white/60 hover:text-white transition-all duration-300 hover-lift hover-rotate"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>

            <!-- Enhanced Content -->
            <div :class="fullHeight ? 'h-full overflow-y-auto relative z-10' : 'relative z-10'">
              <slot></slot>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script>
export default {
  name: 'Modal',
  props: {
    show: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: ''
    },
    showClose: {
      type: Boolean,
      default: true
    },
    size: {
      type: String,
      default: 'md',
      validator: value => ['sm', 'md', 'lg'].includes(value)
    },
    fullHeight: {
      type: Boolean,
      default: false
    },
    closeOnBackdrop: {
      type: Boolean,
      default: true
    }
  },
  emits: ['close'],
  mounted() {
    if (this.show) {
      this.addBodyClass()
    }
  },
  beforeUnmount() {
    this.removeBodyClass()
  },
  watch: {
    show(newVal) {
      if (newVal) {
        this.addBodyClass()
      } else {
        this.removeBodyClass()
      }
    }
  },
  methods: {
    close() {
      this.$emit('close')
    },
    handleBackdropClick() {
      if (this.closeOnBackdrop) {
        this.close()
      }
    },
    addBodyClass() {
      document.body.classList.add('overflow-hidden')
    },
    removeBodyClass() {
      document.body.classList.remove('overflow-hidden')
    }
  }
}
</script>

<style scoped>
/* Enhanced Modal transitions */
.modal-enter-active,
.modal-leave-active {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal-enter-from {
  opacity: 0;
  transform: scale(0.8) translateY(-20px) rotate(-2deg);
}

.modal-leave-to {
  opacity: 0;
  transform: scale(0.8) translateY(-20px) rotate(2deg);
}

.modal-enter-to,
.modal-leave-from {
  opacity: 1;
  transform: scale(1) translateY(0) rotate(0deg);
}

/* Enhanced Backdrop transitions */
.modal-enter-active .modal-backdrop,
.modal-leave-active .modal-backdrop {
  transition: all 0.5s ease;
}

.modal-enter-from .modal-backdrop,
.modal-leave-to .modal-backdrop {
  opacity: 0;
  backdrop-filter: blur(0px);
}

.modal-enter-to .modal-backdrop,
.modal-leave-from .modal-backdrop {
  opacity: 1;
  backdrop-filter: blur(8px);
}

/* Enhanced glass effect */
.glass-premium {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
  backdrop-filter: blur(40px) saturate(200%);
  border: 1px solid rgba(255, 255, 255, 0.2);
  box-shadow: 
    0 25px 50px rgba(0, 0, 0, 0.5),
    inset 0 1px 0 rgba(255, 255, 255, 0.2),
    0 0 0 1px rgba(255, 255, 255, 0.05);
}

/* Enhanced Custom scrollbar for modal content */
.overflow-y-auto::-webkit-scrollbar {
  width: 8px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.05);
  border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: linear-gradient(180deg, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.2));
  border-radius: 4px;
  transition: all 0.3s ease;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(180deg, rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.4));
  transform: scaleX(1.2);
}

/* Enhanced hover effects */
.hover-lift {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  will-change: transform, box-shadow;
}

.hover-lift:hover {
  transform: translateY(-2px) scale(1.05);
  box-shadow: 
    0 10px 25px rgba(0, 0, 0, 0.3),
    0 0 0 1px rgba(255, 255, 255, 0.1);
}

.hover-rotate {
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  will-change: transform;
}

.hover-rotate:hover {
  transform: rotate(90deg) scale(1.1);
}

/* Text gradient effect */
.text-gradient {
  background: linear-gradient(135deg, #3b82f6, #8b5cf6);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* Enhanced backdrop */
.modal-backdrop {
  backdrop-filter: blur(8px);
  background: rgba(0, 0, 0, 0.6);
}
</style>
