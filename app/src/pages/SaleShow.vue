<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from '@/lib/axios'
import type { Sale } from '@/types/Sale'

const route = useRoute()
const router = useRouter()

const saleId = route.params.saleId
const sellerId = route.params.sellerId

const sale = ref<Sale | null>(null)
const isLoading = ref(true)

const formatCurrency = (value: number | string) =>
  new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(Number(value))

onMounted(async () => {
  try {
    const { data } = await axios.get(`/sales/${saleId}`)
    sale.value = data
  } catch (error) {
    console.error('Erro ao carregar a venda:', error)
    router.push(`/sellers/${sellerId}/sales`)
  } finally {
    isLoading.value = false
  }
})

const formatDate = (date: string) =>
  new Intl.DateTimeFormat('pt-BR').format(new Date(date))
</script>

<template>
  <div class="space-y-4">
    <h2 class="text-xl font-semibold">Detalhes da Venda</h2>

    <div v-if="isLoading" class="space-y-2">
      <div class="h-6 bg-muted animate-pulse rounded" />
    </div>

    <div v-else-if="sale">
      <p><strong>ID:</strong> {{ sale.id }}</p>
      <p><strong>Valor:</strong> {{ formatCurrency(sale.amount) }}</p>
      <p><strong>Comissão:</strong> {{ formatCurrency(sale.commission) }}</p>
      <p><strong>Data:</strong> {{ formatDate(sale.sale_date) }}</p>
      <p><strong>Status:</strong> {{ sale.status }}</p>

      <div class="mt-4">
        <router-link
          :to="`/sellers/${sellerId}/sales/${saleId}/edit`"
          class="text-yellow-600 hover:underline"
        >
          Editar Venda
        </router-link>
      </div>
    </div>

    <div v-else>
      <p class="text-red-500">Venda não encontrada.</p>
    </div>
  </div>
</template>
