// Atualização do SellerSales.vue para incluir o botão de nova venda
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import TableSales from '@/components/TableSales.vue'
import { Button } from '@/components/ui/button'

const route = useRoute()
const router = useRouter()
const sellerId = route.params.id

const goToCreateSale = () => {
  router.push(`/sellers/${sellerId}/sales/create`)
}
</script>

<template>
  <div class="space-y-4">
    <div class="flex items-center justify-between">
      <h2 class="text-xl font-semibold">Vendas do Vendedor #{{ sellerId }}</h2>
      <Button @click="goToCreateSale">Nova Venda</Button>
    </div>

    <TableSales :url="`/sellers/${sellerId}/sales`" />
  </div>
</template>
