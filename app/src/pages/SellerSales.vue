<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import axios from '@/lib/axios'

const route = useRoute()
const sellerId = route.params.id
const sales = ref([])
const isLoading = ref(true)

onMounted(async () => {
  try {
    const { data } = await axios.get(`/sellers/${sellerId}/sales`)
    sales.value = data.sales || data
  } catch (error) {
    console.error('Erro ao buscar vendas:', error)
  } finally {
    isLoading.value = false
  }
})

const formatCurrency = (value: number | string) =>
  new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL'
  }).format(Number(value))

const formatDate = (date: string) =>
  new Intl.DateTimeFormat('pt-BR').format(new Date(date))
</script>

<template>
  <div class="space-y-4">
    <h2 class="text-xl font-semibold">Vendas do Vendedor #{{ sellerId }}</h2>

    <div v-if="isLoading" class="space-y-2">
      <div class="h-6 bg-muted animate-pulse rounded" v-for="i in 5" :key="i" />
    </div>

    <div v-else-if="sales.length === 0">
      <p class="text-muted">Nenhuma venda encontrada.</p>
    </div>

    <div v-else>
      <table class="w-full text-sm border border-muted rounded">
        <thead>
          <tr class="bg-muted text-left">
            <th class="p-2">#</th>
            <th class="p-2">Valor</th>
            <th class="p-2">Comissão</th>
            <th class="p-2">Data</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="sale in sales" :key="sale.id" class="hover:bg-accent">
            <td class="p-2">{{ sale.id }}</td>
            <td class="p-2">{{ formatCurrency(sale.amount) }}</td>
            <td class="p-2">{{ formatCurrency(sale.commission) }}</td>
            <td class="p-2">{{ formatDate(sale.sale_date) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
