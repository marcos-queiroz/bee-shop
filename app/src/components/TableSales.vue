<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import axios from '@/lib/axios'
import { useRoute, useRouter } from 'vue-router'
import type { Sale } from '@/types/Sale';
import { toast } from 'vue-sonner'
import {
  AlertDialog,
  AlertDialogTrigger,
  AlertDialogContent,
  AlertDialogHeader,
  AlertDialogFooter,
  AlertDialogTitle,
  AlertDialogDescription,
  AlertDialogCancel,
  AlertDialogAction,
} from '@/components/ui/alert-dialog'
import { Button } from '@/components/ui/button'
import PaginationWrapper from './PaginationWrapper.vue';

// Props
const props = defineProps<{
  url: string
  showSeller?: boolean
  initialPage?: number
}>()

const emit = defineEmits<{
  (e: 'loaded', payload: { sales: any[]; meta: object }): void
}>()

// Router
const route = useRoute()
const router = useRouter()

// States
const page = ref(Number(route.query.page) || props.initialPage || 1)
const sales = ref<Sale[]>([])
const meta = ref()
const isLoading = ref(true)

// Currency and Date formatters
const formatCurrency = (value: number | string) =>
  new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(Number(value))

const formatDate = (date: string) =>
  new Intl.DateTimeFormat('pt-BR').format(new Date(date))

// Fetch data
const fetchSales = async () => {
  isLoading.value = true
  try {
    const { data } = await axios.get(`${props.url}?page=${page.value}`)
    sales.value = data.data
    meta.value = {
      currentPage: data.current_page,
      lastPage: data.last_page,
      perPage: data.per_page,
      total: data.total,
      totalAmount: Number(data.total_amount),
      totalCommission: Number(data.total_commission),
    }
    emit('loaded', { sales: sales.value, meta: meta.value })
  } finally {
    isLoading.value = false
  }
}

onMounted(fetchSales)

watch(page, (newPage) => {
  router.replace({ query: { ...route.query, page: newPage } })
  fetchSales()
})

const editSale = (saleId: number) => {
  const sellerId = route.params.id
  router.push(`/sellers/${sellerId}/sales/${saleId}/edit`)
}

const deleteSale = async (saleId: number) => {
  try {
    await axios.delete(`/sales/${saleId}`)
    toast.success('Venda excluída com sucesso!')
    await fetchSales()
  } catch (err) {
    console.error(err)
    toast.error('Erro ao excluir venda.')
  }
}
</script>

<template>
  <div class="space-y-4">
    <div v-if="isLoading" class="space-y-2">
      <div class="h-6 bg-muted animate-pulse rounded" v-for="i in 5" :key="i" />
    </div>

    <div v-else>
      <div class="text-sm text-right my-3">
        <p><strong>Total de Vendas:</strong> {{ formatCurrency(meta.totalAmount) }}</p>
        <p><strong>Total de Comissões:</strong> {{ formatCurrency(meta.totalCommission) }}</p>
      </div>

      <table class="w-full text-sm border border-muted rounded">
        <thead>
          <tr class="bg-muted text-left">
            <th class="p-2">#</th>
            <th v-if="showSeller" class="p-2">Vendedor</th>
            <th class="p-2">Valor</th>
            <th class="p-2">Comissão</th>
            <th class="p-2">Data</th>
            <th class="p-2 text-center">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="sale in sales" :key="sale.id" class="hover:bg-accent">
            <td class="p-2">{{ sale.id }}</td>
            <td v-if="showSeller" class="p-2">{{ sale.seller?.name || '-' }}</td>
            <td class="p-2">{{ formatCurrency(sale.amount) }}</td>
            <td class="p-2">{{ formatCurrency(sale.commission) }}</td>
            <td class="p-2">{{ formatDate(sale.sale_date) }}</td>
            <td class="p-2 text-center space-x-2">
              <Button variant="link" size="sm" class="text-yellow-600" @click="editSale(sale.id)">
                Editar
              </Button>

              <AlertDialog>
                <AlertDialogTrigger as-child>
                  <Button variant="link" size="sm" class="text-red-600">
                    Excluir
                  </Button>
                </AlertDialogTrigger>
                <AlertDialogContent>
                  <AlertDialogHeader>
                    <AlertDialogTitle>Deseja excluir esta venda?</AlertDialogTitle>
                    <AlertDialogDescription>Esta ção não pode ser desfeita.</AlertDialogDescription>
                  </AlertDialogHeader>
                  <AlertDialogFooter>
                    <AlertDialogCancel>Cancelar</AlertDialogCancel>
                    <AlertDialogAction @click="deleteSale(sale.id)">Sim, excluir</AlertDialogAction>
                  </AlertDialogFooter>
                </AlertDialogContent>
              </AlertDialog>
            </td>

          </tr>
        </tbody>
      </table>

      <div class="mt-4" v-if="meta">
        <PaginationWrapper v-model="page" :perPage="meta.perPage" :total="meta.total" />
      </div>
    </div>
  </div>
</template>
