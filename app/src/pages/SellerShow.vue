<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from '@/lib/axios'
import type { Seller } from '@/types/Seller'

const route = useRoute()
const sellerId = route.params.id
const seller = ref<Seller | null>(null)
const isLoading = ref(true)

onMounted(async () => {
  try {
    const { data } = await axios.get(`/sellers/${sellerId}`)
    seller.value = data
  } catch (error) {
    console.error('Erro ao carregar vendedor:', error)
  } finally {
    isLoading.value = false
  }
})
</script>

<template>
  <div class="space-y-4">
    <h2 class="text-xl font-semibold">Dados do Vendedor</h2>

    <div v-if="isLoading" class="space-y-2">
      <div class="h-6 bg-muted animate-pulse rounded" />
    </div>

    <div v-else-if="seller">
      <p><strong>Nome:</strong> {{ seller.name }}</p>
      <p><strong>E-mail:</strong> {{ seller.email }}</p>
    </div>

    <div v-else>
      <p class="text-red-500">Vendedor não encontrado.</p>
    </div>
  </div>
</template>
