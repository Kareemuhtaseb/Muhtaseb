<template>
  <div class="space-y-2">
    <!-- Label -->
    <label v-if="label" :for="id" class="block text-white/80 text-sm font-medium">
      {{ label }}
      <span v-if="required" class="text-red-400 ml-1">*</span>
    </label>

    <!-- Input Container -->
    <div class="relative">
      <!-- Icon Prefix -->
      <div v-if="prefixIcon" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-white/40">
        <component :is="prefixIcon" class="w-5 h-5" />
      </div>

      <!-- Input Field -->
      <input
        v-if="type !== 'textarea' && type !== 'select'"
        :id="id"
        :type="type"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        @blur="handleBlur"
        @focus="handleFocus"
        :placeholder="placeholder"
        :required="required"
        :disabled="disabled"
        :readonly="readonly"
        :min="min"
        :max="max"
        :step="step"
        :class="[
          'w-full bg-white/10 border rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200',
          prefixIcon ? 'pl-10' : '',
          suffixIcon ? 'pr-10' : '',
          error ? 'border-red-400 focus:ring-red-500' : 'border-white/20',
          disabled ? 'opacity-50 cursor-not-allowed' : '',
          readonly ? 'bg-white/5' : ''
        ]"
      />

      <!-- Textarea -->
      <textarea
        v-else-if="type === 'textarea'"
        :id="id"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        @blur="handleBlur"
        @focus="handleFocus"
        :placeholder="placeholder"
        :required="required"
        :disabled="disabled"
        :readonly="readonly"
        :rows="rows"
        :class="[
          'w-full bg-white/10 border rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none',
          prefixIcon ? 'pl-10' : '',
          suffixIcon ? 'pr-10' : '',
          error ? 'border-red-400 focus:ring-red-500' : 'border-white/20',
          disabled ? 'opacity-50 cursor-not-allowed' : '',
          readonly ? 'bg-white/5' : ''
        ]"
      ></textarea>

      <!-- Select -->
      <select
        v-else-if="type === 'select'"
        :id="id"
        :value="modelValue"
        @change="$emit('update:modelValue', $event.target.value)"
        @blur="handleBlur"
        @focus="handleFocus"
        :required="required"
        :disabled="disabled"
        :class="[
          'w-full bg-white/10 border rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 appearance-none',
          prefixIcon ? 'pl-10' : '',
          suffixIcon ? 'pr-10' : '',
          error ? 'border-red-400 focus:ring-red-500' : 'border-white/20',
          disabled ? 'opacity-50 cursor-not-allowed' : ''
        ]"
      >
        <option v-if="placeholder" value="" disabled selected>{{ placeholder }}</option>
        <option 
          v-for="option in options" 
          :key="option.value" 
          :value="option.value"
          class="bg-gray-800 text-white"
        >
          {{ option.label }}
        </option>
      </select>

      <!-- Icon Suffix -->
      <div v-if="suffixIcon" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-white/40">
        <component :is="suffixIcon" class="w-5 h-5" />
      </div>

      <!-- Error Icon -->
      <div v-if="error" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-red-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </div>

      <!-- Success Icon -->
      <div v-else-if="success" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-green-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
      </div>
    </div>

    <!-- Helper Text -->
    <div v-if="helperText || error" class="flex items-start space-x-2">
      <div v-if="error" class="flex-shrink-0 mt-0.5">
        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </div>
      <div v-else-if="helperText" class="flex-shrink-0 mt-0.5">
        <svg class="w-4 h-4 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </div>
      <p 
        :class="[
          'text-sm',
          error ? 'text-red-400' : 'text-white/60'
        ]"
      >
        {{ error || helperText }}
      </p>
    </div>

    <!-- Character Counter -->
    <div v-if="showCounter && maxLength" class="flex justify-end">
      <span class="text-xs text-white/40">
        {{ (modelValue || '').length }} / {{ maxLength }}
      </span>
    </div>
  </div>
</template>

<script>
export default {
  name: 'FormField',
  props: {
    modelValue: {
      type: [String, Number],
      default: ''
    },
    label: {
      type: String,
      default: ''
    },
    type: {
      type: String,
      default: 'text',
      validator: value => ['text', 'email', 'password', 'number', 'tel', 'url', 'textarea', 'select'].includes(value)
    },
    placeholder: {
      type: String,
      default: ''
    },
    required: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    },
    readonly: {
      type: Boolean,
      default: false
    },
    error: {
      type: String,
      default: ''
    },
    success: {
      type: Boolean,
      default: false
    },
    helperText: {
      type: String,
      default: ''
    },
    prefixIcon: {
      type: String,
      default: ''
    },
    suffixIcon: {
      type: String,
      default: ''
    },
    options: {
      type: Array,
      default: () => []
    },
    rows: {
      type: Number,
      default: 3
    },
    min: {
      type: Number,
      default: null
    },
    max: {
      type: Number,
      default: null
    },
    step: {
      type: Number,
      default: null
    },
    maxLength: {
      type: Number,
      default: null
    },
    showCounter: {
      type: Boolean,
      default: false
    }
  },
  emits: ['update:modelValue', 'blur', 'focus'],
  computed: {
    id() {
      return `field-${Math.random().toString(36).substr(2, 9)}`
    }
  },
  methods: {
    handleBlur(event) {
      this.$emit('blur', event)
    },
    handleFocus(event) {
      this.$emit('focus', event)
    }
  }
}
</script>

<style scoped>
/* Enhanced focus states */
input:focus,
textarea:focus,
select:focus {
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Select arrow styling */
select {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
  background-position: right 0.5rem center;
  background-repeat: no-repeat;
  background-size: 1.5em 1.5em;
  padding-right: 2.5rem;
}

/* Disabled state */
input:disabled,
textarea:disabled,
select:disabled {
  cursor: not-allowed;
  opacity: 0.5;
}

/* Readonly state */
input[readonly],
textarea[readonly] {
  background-color: rgba(255, 255, 255, 0.05);
  cursor: default;
}

/* Error state */
input:invalid,
textarea:invalid,
select:invalid {
  border-color: #ef4444;
}

/* Success state */
input:valid:not(:placeholder-shown),
textarea:valid:not(:placeholder-shown),
select:valid:not([value=""]) {
  border-color: #10b981;
}

/* Animation for focus */
input,
textarea,
select {
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Enhanced hover effects */
input:hover:not(:disabled):not(:readonly),
textarea:hover:not(:disabled):not(:readonly),
select:hover:not(:disabled) {
  border-color: rgba(255, 255, 255, 0.3);
}

/* Character counter animation */
.text-xs {
  transition: color 0.2s ease;
}

/* Icon animations */
svg {
  transition: all 0.2s ease;
}

/* Error icon shake animation */
@keyframes shake {
  0%, 100% { transform: translateX(0); }
  25% { transform: translateX(-2px); }
  75% { transform: translateX(2px); }
}

.error svg {
  animation: shake 0.5s ease-in-out;
}
</style>
