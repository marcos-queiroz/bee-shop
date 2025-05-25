<script setup lang="ts">
import { useRoute } from 'vue-router'
import TableSales from '@/components/TableSales.vue'

const route = useRoute()
const sellerId = route.params.id
</script>

<template>
  <div class="space-y-4">
    <h2 class="text-xl font-semibold">Vendas do Vendedor #{{ sellerId }}</h2>

    <TableSales :url="`/sellers/${sellerId}/sales`" />
  </div>
</template>
