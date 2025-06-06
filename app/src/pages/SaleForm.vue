<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import { z } from 'zod'
import axios from '@/lib/axios'

import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import {
  FormField,
  FormItem,
  FormLabel,
  FormControl,
  FormMessage
} from '@/components/ui/form'
import { toast } from 'vue-sonner'
import type { Seller } from '@/types/Seller'

const router = useRouter()
const route = useRoute()

const sellerId = Number(route.params.id || route.params.sellerId)
const saleId = route.params.saleId
const isEdit = !!saleId

const seller = ref<Seller | null>(null)

const formSchema = toTypedSchema(z.object({
  amount: z.coerce.number({ invalid_type_error: 'Informe um valor numérico' }).min(0.01, 'Valor obrigatório'),
  sale_date: z.string().min(1, 'Data obrigatória'),
}))

const { handleSubmit, setValues } = useForm({
  validationSchema: formSchema,
})

onMounted(async () => {
  if (sellerId) {
    try {
      const { data } = await axios.get(`/sellers/${sellerId}`)
      seller.value = data
    } catch {
      toast.error('Erro ao carregar dados do vendedor.')
    }
  }

  if (isEdit) {
    try {
      const { data } = await axios.get(`/sales/${saleId}`)
      setValues({
        amount: data.amount,
        sale_date: data.sale_date?.substring(0, 10), // yyyy-mm-dd
      })
    } catch {
      toast.error('Erro ao carregar dados da venda.')
      router.push(sellerId ? `/sellers/${sellerId}/sales` : '/sales')
    }
  }
})

const onSubmit = handleSubmit(async (values) => {
  try {
    if (isEdit) {
      await axios.put(`/sales/${saleId}`, values)
      toast.success('Venda atualizada com sucesso!')
    } else {
      await axios.post('/sales', { ...values, seller_id: sellerId })
      toast.success('Venda cadastrada com sucesso!')
    }

    router.push(sellerId ? `/sellers/${sellerId}/sales` : '/sales')
  } catch (err: any) {
    const msg = err?.response?.data?.message || 'Erro ao salvar venda.'
    toast.error(msg)
  }
})
</script>

<template>
  <div class="max-w-md space-y-6">
    <div>
      <h2 class="text-xl font-semibold">
        {{ isEdit ? 'Editar Venda' : 'Nova Venda' }}
      </h2>
      <p v-if="seller" class="text-sm">
        <strong>{{ seller.name }}</strong> | {{ seller.email }}
      </p>
    </div>

    <form @submit.prevent="onSubmit" class="space-y-4">
      <FormField v-slot="{ componentField }" name="amount">
        <FormItem>
          <FormLabel>Valor</FormLabel>
          <FormControl>
            <Input type="number" step="0.01" placeholder="Informe o valor" v-bind="componentField" />
          </FormControl>
          <FormMessage />
        </FormItem>
      </FormField>

      <FormField v-slot="{ componentField }" name="sale_date">
        <FormItem>
          <FormLabel>Data da Venda</FormLabel>
          <FormControl>
            <Input type="date" v-bind="componentField" />
          </FormControl>
          <FormMessage />
        </FormItem>
      </FormField>

      <Button type="submit" class="w-full">
        {{ isEdit ? 'Atualizar' : 'Cadastrar' }}
      </Button>
    </form>
  </div>
</template>
