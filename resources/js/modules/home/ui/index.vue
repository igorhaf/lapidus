<template>
  <div class="min-h-screen bg-gray-100">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Dados da página -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
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

        <!-- Formulário de Contato -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <h2 class="text-xl font-semibold mb-4">Entre em Contato</h2>
            
            <form @submit.prevent="submitContact" class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Nome *</label>
                  <input 
                    v-model="form.name" 
                    type="text" 
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  >
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700">E-mail *</label>
                  <input 
                    v-model="form.email" 
                    type="email" 
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  >
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Telefone</label>
                  <input 
                    v-model="form.phone" 
                    type="tel"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  >
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700">Preferência de Contato</label>
                  <select 
                    v-model="form.preferred_contact"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  >
                    <option value="">Selecione...</option>
                    <option value="email">E-mail</option>
                    <option value="phone">Telefone</option>
                    <option value="whatsapp">WhatsApp</option>
                  </select>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700">Assunto *</label>
                <input 
                  v-model="form.subject" 
                  type="text" 
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700">Mensagem *</label>
                <textarea 
                  v-model="form.message" 
                  rows="4" 
                  required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                ></textarea>
              </div>

              <div class="flex items-center">
                <input 
                  v-model="form.newsletter" 
                  type="checkbox" 
                  id="newsletter"
                  class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                >
                <label for="newsletter" class="ml-2 block text-sm text-gray-900">
                  Quero receber novidades por e-mail
                </label>
              </div>

              <div class="flex justify-end">
                <button 
                  type="submit" 
                  :disabled="loading"
                  class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 disabled:opacity-50"
                >
                  {{ loading ? 'Enviando...' : 'Enviar Mensagem' }}
                </button>
              </div>
            </form>

            <!-- Mensagem de sucesso -->
            <div v-if="successMessage" class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
              {{ successMessage }}
            </div>

            <!-- Mensagem de erro -->
            <div v-if="errorMessage" class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
              {{ errorMessage }}
            </div>
          </div>
        </div>
      </div>
    </div>
  
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const message = ref('')
const module = ref('')
const timestamp = ref('')
const user = ref(null)

const form = ref({
  name: '',
  email: '',
  phone: '',
  subject: '',
  message: '',
  preferred_contact: '',
  newsletter: false,
})

const loading = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

onMounted(async () => {
  const { data } = await axios.get('/api/v1/pagina-inicial')
  message.value = data.data.message
  module.value = data.data.module
  timestamp.value = data.data.timestamp
  user.value = data.data.user
})

async function submitContact() {
  loading.value = true
  successMessage.value = ''
  errorMessage.value = ''
  try {
    const { data } = await axios.post('/api/v1/pagina-inicial/contact', form.value)
    successMessage.value = data.data.message
    // Limpar formulário após sucesso
    form.value = {
      name: '',
      email: '',
      phone: '',
      subject: '',
      message: '',
      preferred_contact: '',
      newsletter: false,
    }
  } catch (error) {
    errorMessage.value = error.response?.data?.error?.message || 'Erro ao enviar mensagem.'
  } finally {
    loading.value = false
  }
}
</script> 