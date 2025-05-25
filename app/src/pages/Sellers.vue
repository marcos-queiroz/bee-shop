<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from '@/lib/axios'
import { useAuthStore } from '@/stores/auth'
import router from '@/router'
import type { Seller } from '@/types/Seller'

const sellers = ref<Seller[]>([])
const isLoading = ref(true)

const auth = useAuthStore()

onMounted(async () => {
  try {
    isLoading.value = true
    const { data } = await axios.get('/sellers')

    sellers.value = data
  } catch (error) {
    console.error('Erro ao carregar vendedores:', error)
  } finally {
    isLoading.value = false
  }
})

const goToSales = (sellerId: number) => {
  router.push(`/sellers/${sellerId}/sales`)
}
</script>

<template>
  <div class="space-y-4">
    <h2 class="text-xl font-semibold">Vendedores</h2>

    <div v-if="isLoading" class="space-y-2">
      <div class="h-6 bg-muted animate-pulse rounded" v-for="i in 5" :key="i" />
    </div>

    <div v-else>
      <table class="w-full text-sm border border-muted rounded">
        <thead>
          <tr class="bg-muted text-left">
            <th class="p-2">#</th>
            <th class="p-2">Nome</th>
            <th class="p-2">E-mail</th>
            <th class="p-2 text-right">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="seller in sellers" :key="seller.id" class="hover:bg-accent">
            <td class="p-2">{{ seller.id }}</td>
            <td class="p-2">{{ seller.name }}</td>
            <td class="p-2">{{ seller.email }}</td>
            <td class="p-2 text-right">
              <button class="text-primary underline hover:opacity-80" @click="goToSales(seller.id)">
                Ver vendas
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
