<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import TableSales from '@/components/TableSales.vue'
import { Button } from '@/components/ui/button'
import axios from '@/lib/axios'
import type { Seller } from '@/types/Seller'

const route = useRoute()
const router = useRouter()
const sellerId = route.params.id
const seller = ref<Seller | null>(null)

const goToCreateSale = () => {
  router.push(`/sellers/${sellerId}/sales/create`)
}

onMounted(async () => {
  try {
    const { data } = await axios.get(`/sellers/${sellerId}`)
    seller.value = data
  } catch (error) {
    console.error('Erro ao carregar vendedor:', error)
  }
})
</script>

<template>
  <div class="space-y-4">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-xl font-semibold">
          Vendas de: {{ seller?.name || 'Vendedor' }}
        </h2>
        <p v-if="seller" class="text-md text-sm">
          E-mail: {{ seller.email }}
        </p>
      </div>
      <Button @click="goToCreateSale">Nova Venda</Button>
    </div>

    <TableSales :url="`/sellers/${sellerId}/sales`" />
  </div>
</template>
