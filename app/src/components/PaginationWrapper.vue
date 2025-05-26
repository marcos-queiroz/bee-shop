<!-- src/components/PaginationWrapper.vue -->
<script setup lang="ts">
import { computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import {
  Pagination,
  PaginationContent,
  PaginationEllipsis,
  PaginationItem,
  PaginationNext,
  PaginationPrevious,
} from '@/components/ui/pagination'

const props = defineProps<{
  modelValue: number
  total: number
  perPage: number
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: number): void
}>()

const route = useRoute()
const router = useRouter()

const currentPage = computed({
  get: () => props.modelValue,
  set: (val) => emit('update:modelValue', val),
})

watch(currentPage, (newPage) => {
  router.replace({ query: { ...route.query, page: newPage } })
})
</script>

<template>
  <Pagination v-model:page="currentPage" :items-per-page="perPage" :total="total">
    <PaginationContent v-slot="{ items }">
      <PaginationPrevious />
      <template v-for="(item, index) in items" :key="index">
        <PaginationItem
          v-if="item.type === 'page'"
          :value="item.value"
          :is-active="item.value === currentPage"
        >
          {{ item.value }}
        </PaginationItem>
        <PaginationEllipsis v-else-if="item.type === 'ellipsis'" />
      </template>
      <PaginationNext />
    </PaginationContent>
  </Pagination>
</template>
