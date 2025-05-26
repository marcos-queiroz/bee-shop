<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from '@/lib/axios'
import { useAuthStore } from '@/stores/auth'
import type { Seller } from '@/types/Seller'

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
import PaginationWrapper from '@/components/PaginationWrapper.vue'

// Router
const route = useRoute()
const router = useRouter()
const auth = useAuthStore()

// States
const page = ref(Number(route.query.page) || 1)
const sellers = ref<Seller[]>([])
const isLoading = ref(true)
const meta = ref({
  currentPage: 1,
  lastPage: 1,
  perPage: 10,
  total: 0,
})

const fetchSellers = async () => {
  try {
    isLoading.value = true
    const { data } = await axios.get(`/sellers?page=${page.value}`)
    sellers.value = data.data
    meta.value = {
      currentPage: data.current_page,
      lastPage: data.last_page,
      perPage: data.per_page,
      total: data.total,
    }

    sellers.value.forEach((seller) => {
      if (!emailDateMap.value[seller.id]) {
        emailDateMap.value[seller.id] = getYesterday()
      }
    })
  } catch (error) {
    console.error('Erro ao carregar vendedores:', error)
  } finally {
    isLoading.value = false
  }
}

onMounted(fetchSellers)

// Atualiza ao mudar de página
watch(page, (newPage) => {
  router.replace({ query: { ...route.query, page: newPage } })
  fetchSellers()
})

// Ações
const goToSales = (sellerId: number) => {
  router.push(`/sellers/${sellerId}/sales`)
}

const editSeller = (sellerId: number) => {
  router.push(`/sellers/${sellerId}/edit`)
}

const confirmDelete = async (seller: Seller) => {
  try {
    await axios.delete(`/sellers/${seller.id}`)
    toast.success('Vendedor excluído com sucesso!')
    fetchSellers()
  } catch (err) {
    console.error(err)
    toast.error('Erro ao excluir vendedor.')
  }
}

const emailDateMap = ref<{ [key: number]: string }>({})

const getYesterday = () => {
  const date = new Date()
  date.setDate(date.getDate() - 1)
  return date.toISOString().split('T')[0]
}

const resendEmail = async (seller: Seller) => {
  const date = emailDateMap.value[seller.id] || getYesterday()

  try {
    await axios.post(`/sellers/${seller.id}/resend-email`, { date })
    toast.success(`E-mail reenviado para ${seller.name} (${date})`)
  } catch (err) {
    console.error(err)
    toast.error(`Erro ao reenviar e-mail para ${seller.name}`)
  }
}
</script>

<template>
  <div class="space-y-4">
    <h2 class="text-xl font-semibold">Vendedores</h2>

    <div class="flex justify-end">
      <Button @click="router.push('/sellers/create')" class="mb-4">
        Novo Vendedor
      </Button>
    </div>

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
            <th class="p-2 text-center">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="seller in sellers" :key="seller.id" class="hover:bg-accent">
            <td class="p-2">{{ seller.id }}</td>
            <td class="p-2">{{ seller.name }}</td>
            <td class="p-2">{{ seller.email }}</td>
            <td class="p-2 text-center space-x-2">
              <Button variant="link" size="sm" @click="goToSales(seller.id)">Ver vendas</Button>
              <Button variant="link" size="sm" class="text-yellow-600" @click="editSeller(seller.id)">
                Editar
              </Button>

              <AlertDialog>
                <AlertDialogTrigger as-child>
                  <Button variant="link" size="sm" class="text-blue-600">
                    Reenviar E-mail
                  </Button>
                </AlertDialogTrigger>

                <AlertDialogContent>
                  <AlertDialogHeader>
                    <AlertDialogTitle>Reenviar e-mail de resumo?</AlertDialogTitle>
                    <AlertDialogDescription>
                      Selecione a data de referência para reenvio.
                    </AlertDialogDescription>
                  </AlertDialogHeader>

                  <div class="mt-4">
                    <input v-model="emailDateMap[seller.id]" type="date"
                      class="w-full border border-input rounded px-3 py-2 text-sm" />
                  </div>

                  <AlertDialogFooter>
                    <AlertDialogCancel>Cancelar</AlertDialogCancel>
                    <AlertDialogAction @click="resendEmail(seller)">Enviar</AlertDialogAction>
                  </AlertDialogFooter>
                </AlertDialogContent>
              </AlertDialog>

              <AlertDialog>
                <AlertDialogTrigger as-child>
                  <Button variant="link" size="sm" class="text-red-600">
                    Excluir
                  </Button>
                </AlertDialogTrigger>
                <AlertDialogContent>
                  <AlertDialogHeader>
                    <AlertDialogTitle>Deseja mesmo excluir?</AlertDialogTitle>
                    <AlertDialogDescription>
                      Esta ação não poderá ser desfeita.
                    </AlertDialogDescription>
                  </AlertDialogHeader>
                  <AlertDialogFooter>
                    <AlertDialogCancel>Cancelar</AlertDialogCancel>
                    <AlertDialogAction @click="confirmDelete(seller)">
                      Sim, excluir
                    </AlertDialogAction>
                  </AlertDialogFooter>
                </AlertDialogContent>
              </AlertDialog>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="mt-4" v-if="meta.total > meta.perPage">
        <PaginationWrapper v-model="page" :perPage="meta.perPage" :total="meta.total" />
      </div>
    </div>
  </div>
</template>
