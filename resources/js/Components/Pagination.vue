<template>
  <div v-if="shouldShowPagination" class="mt-6">
    <!-- Standard Laravel pagination links structure -->
    <div v-if="links && links.length > 3" class="flex flex-wrap justify-center md:justify-between items-center gap-2">
      <div v-for="(link, i) in links" :key="i" class="px-1">
        <component 
          :is="usesInertia ? Link : 'button'"
          v-if="link.url" 
          :href="usesInertia ? link.url : undefined"
          @click="usesInertia ? undefined : $emit('page-click', link.url)"
          v-html="link.label" 
          :class="{
            'px-4 py-2 bg-indigo-600 text-white rounded-md': link.active, 
            'px-4 py-2 bg-white text-gray-700 rounded-md hover:bg-gray-100': !link.active
          }"
          class="font-medium text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" 
        />
        <span v-else v-html="link.label" class="px-4 py-2 text-gray-400 font-medium text-sm"></span>
      </div>
    </div>

    <!-- Simple pagination for last_page structure -->
    <div v-else-if="lastPage > 1" class="flex flex-wrap justify-center space-x-2">
      <button 
        @click="$emit('page-click', 1)" 
        :disabled="currentPage === 1"
        :class="{'opacity-50 cursor-not-allowed': currentPage === 1}"
        class="px-4 py-2 bg-white text-gray-700 rounded-md hover:bg-gray-100 font-medium text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
        First
      </button>
      
      <button 
        @click="$emit('page-click', Math.max(1, currentPage - 1))" 
        :disabled="currentPage === 1"
        :class="{'opacity-50 cursor-not-allowed': currentPage === 1}"
        class="px-4 py-2 bg-white text-gray-700 rounded-md hover:bg-gray-100 font-medium text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
        Previous
      </button>
      
      <span class="px-4 py-2 bg-indigo-600 text-white rounded-md font-medium text-sm">
        {{ currentPage }}
      </span>
      
      <button 
        @click="$emit('page-click', Math.min(lastPage, currentPage + 1))" 
        :disabled="currentPage === lastPage"
        :class="{'opacity-50 cursor-not-allowed': currentPage === lastPage}"
        class="px-4 py-2 bg-white text-gray-700 rounded-md hover:bg-gray-100 font-medium text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
        Next
      </button>
      
      <button 
        @click="$emit('page-click', lastPage)" 
        :disabled="currentPage === lastPage"
        :class="{'opacity-50 cursor-not-allowed': currentPage === lastPage}"
        class="px-4 py-2 bg-white text-gray-700 rounded-md hover:bg-gray-100 font-medium text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
        Last
      </button>
    </div>

    <!-- Pagination info -->
    <div v-if="showInfo && hasItems" class="mt-3 text-center text-sm text-gray-600">
      Showing {{ displayFrom }} to {{ displayTo }} of {{ displayTotal }} {{ itemName }}
    </div>

    <!-- Error message when pagination fails -->
    <div v-if="error" class="mt-4 border-l-4 border-red-500 bg-red-50 p-4 rounded">
      <div class="flex items-start">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm text-red-700">
            {{ error }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
  // For standard Laravel pagination
  links: {
    type: Array,
    default: () => []
  },
  // For simple pagination
  currentPage: {
    type: Number,
    default: 1
  },
  lastPage: {
    type: Number,
    default: 1
  },
  // Common properties
  from: {
    type: Number,
    default: null
  },
  to: {
    type: Number,
    default: null
  },
  total: {
    type: Number,
    default: 0
  },
  perPage: {
    type: Number,
    default: 10
  },
  showInfo: {
    type: Boolean,
    default: true
  },
  itemName: {
    type: String,
    default: 'items'
  },
  usesInertia: {
    type: Boolean,
    default: true
  },
  error: {
    type: String,
    default: ''
  }
});

const emit = defineEmits(['page-click']);

// Computed properties for display
const shouldShowPagination = computed(() => {
  return (props.links && props.links.length > 3) || props.lastPage > 1 || props.error;
});

const hasItems = computed(() => props.total > 0);

const displayFrom = computed(() => {
  return props.from || ((props.currentPage - 1) * props.perPage + 1);
});

const displayTo = computed(() => {
  return props.to || Math.min(props.currentPage * props.perPage, props.total);
});

const displayTotal = computed(() => {
  return props.total;
});
</script>
