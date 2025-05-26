<script setup lang="ts">
import axios from '@/lib/axios'
import { useRoute, useRouter } from 'vue-router'
import { ref, onMounted } from 'vue'
import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import { sellerSchema } from '@/schemas/sellerSchema'
import type { SellerFormData } from '@/schemas/sellerSchema'

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

const router = useRouter()
const route = useRoute()
const sellerId = route.params.id
const isEdit = !!sellerId

const { handleSubmit, setValues } = useForm<SellerFormData>({
  validationSchema: toTypedSchema(sellerSchema),
})

onMounted(async () => {
  if (isEdit) {
    try {
      const { data } = await axios.get(`/sellers/${sellerId}`)
      setValues({
        name: data.name,
        email: data.email,
      })
    } catch {
      toast.error('Erro ao carregar vendedor.')
      router.push('/sellers')
    }
  }
})

const onSubmit = handleSubmit(async (values) => {
  try {
    if (isEdit) {
      await axios.put(`/sellers/${sellerId}`, values)
      toast.success('Vendedor atualizado com sucesso!')
    } else {
      await axios.post('/sellers', values) // corrigido de `/sellersee` para `/sellers`
      toast.success('Vendedor cadastrado com sucesso!')
    }
    router.push('/sellers')
  } catch (err: any) {
    const msg = err?.response?.data?.message || 'Erro ao salvar vendedor.'
    toast.error(msg)
  }
})
</script>

<template>
  <div class="max-w-md space-y-6">
    <h2 class="text-xl font-semibold">
      {{ isEdit ? 'Editar Vendedor' : 'Novo Vendedor' }}
    </h2>
    <form @submit.prevent="onSubmit" class="space-y-4">
      <FormField v-slot="{ componentField }" name="name">
        <FormItem>
          <FormLabel>Nome</FormLabel>
          <FormControl>
            <Input type="text" placeholder="Digite o nome" v-bind="componentField" />
          </FormControl>
          <FormMessage />
        </FormItem>
      </FormField>

      <FormField v-slot="{ componentField }" name="email">
        <FormItem>
          <FormLabel>E-mail</FormLabel>
          <FormControl>
            <Input type="email" placeholder="Digite o e-mail" v-bind="componentField" />
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
