<script setup lang="ts">
import TableSales from '@/components/TableSales.vue'
</script>

<template>
  <div class="space-y-4">
    <h2 class="text-xl font-semibold">Vendas</h2>

    <TableSales url="/sales" :show-seller="true" />
  </div>
</template>
