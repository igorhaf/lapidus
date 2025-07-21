<template>
    <Head title="Página Inicial - Lapidus" />

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div class="flex items-center">
                        <h1 class="text-3xl font-bold text-gray-900">Lapidus</h1>
                        <span class="ml-3 px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                            Módulo Home
                        </span>
                    </div>
                    
                    <nav class="flex items-center space-x-4">
                        <div v-if="user" class="flex items-center space-x-4">
                            <span class="text-gray-700">Olá, {{ user.name }}</span>
                            <Link href="/dashboard"
                                  class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition">
                                Dashboard
                            </Link>
                        </div>
                        <div v-else class="flex items-center space-x-4">
                            <Link v-if="canLogin" href="/login"
                                  class="text-gray-600 hover:text-gray-900 font-medium">
                                Entrar
                            </Link>
                            <Link v-if="canRegister" href="/register"
                                  class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition">
                                Registrar
                            </Link>
                        </div>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            
            <!-- Welcome Section -->
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Bem-vindo ao Lapidus
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Sistema modular Laravel com Clean Architecture e Domain-Driven Design
                </p>
            </div>

            <!-- Stats Section -->
            <div v-if="homeData && homeData.data && homeData.data.metadata && homeData.data.metadata.stats" 
                 class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
                
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-3xl font-bold text-blue-600 mb-2">
                        {{ homeData.data.metadata.stats.total_views }}
                    </div>
                    <div class="text-gray-600">Total de Visualizações</div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-3xl font-bold text-green-600 mb-2">
                        {{ homeData.data.metadata.stats.unique_visitors }}
                    </div>
                    <div class="text-gray-600">Visitantes Únicos</div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-3xl font-bold text-purple-600 mb-2">
                        {{ homeData.data.metadata.stats.recent_views }}
                    </div>
                    <div class="text-gray-600">Visualizações Recentes</div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-3xl font-bold text-orange-600 mb-2">
                        {{ homeData.data.metadata.stats.bounce_rate }}
                    </div>
                    <div class="text-gray-600">Taxa de Rejeição</div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Clean Architecture</h3>
                    <p class="text-gray-600">Arquitetura limpa com separação de responsabilidades em camadas Domain, Application e Infrastructure.</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">API RESTful</h3>
                    <p class="text-gray-600">API completa com endpoints funcionais, validações e respostas padronizadas em JSON.</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Vue + Inertia.js</h3>
                    <p class="text-gray-600">Frontend moderno com Vue 3 e Inertia.js para uma experiência SPA fluida.</p>
                </div>
            </div>

            <!-- API Information -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Endpoints Disponíveis</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded mr-2">GET</span>
                            <code class="text-sm text-gray-600">/api/v1/pagina-inicial</code>
                        </div>
                        <p class="text-sm text-gray-500">Dados da página inicial com estatísticas</p>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded mr-2">POST</span>
                            <code class="text-sm text-gray-600">/api/v1/pagina-inicial/contact</code>
                        </div>
                        <p class="text-sm text-gray-500">Envio de formulário de contato</p>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-16">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="text-center text-gray-600">
                    <p>&copy; 2025 Lapidus - Sistema Laravel Modular com Clean Architecture</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'

// Props recebidas do controller
defineProps({
    homeData: Object,
    canLogin: Boolean,
    canRegister: Boolean,
    user: Object,
})
</script> 