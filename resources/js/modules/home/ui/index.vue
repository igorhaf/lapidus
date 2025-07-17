<template>
  <div class="min-h-screen bg-gray-100">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold mb-4">Página Inicial</h1>
            <p class="mb-4">{{ message }}</p>
            <div class="text-sm text-gray-600">
              <p><strong>Módulo:</strong> {{ module }}</p>
              <p><strong>Timestamp:</strong> {{ timestamp }}</p>
              <p v-if="user"><strong>Usuário:</strong> {{ user.name }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { onMounted } from 'vue'
import axios from 'axios'

const message = ref('')
const module = ref('')
const timestamp = ref('')
const user = ref(null)

onMounted(async () => {
  const { data } = await axios.get('/api/v1/pagina-inicial')
  message.value = data.data.message
  module.value = data.data.module
  timestamp.value = data.data.timestamp
  user.value = data.data.user
})
</script> 