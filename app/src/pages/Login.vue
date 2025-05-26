<script lang="ts" setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Button } from '@/components/ui/button'

const email = ref('')
const password = ref('')
const auth = useAuthStore()

const onSubmit = () => {
  auth.login(email.value, password.value)
}
</script>

<template>
  <div class="container relative h-screen flex-col items-center justify-center grid lg:max-w-none lg:grid-cols-2 lg:px-0">
    <div class="relative hidden h-full flex-col bg-muted p-10 text-white lg:flex dark:border-r">
      <div class="absolute inset-0 bg-zinc-900" />
      <div class="relative z-20 flex items-center text-lg font-medium">
        Sistema de Vendas
      </div>
      <div class="relative z-20 mt-auto">
        <blockquote class="space-y-2">
          <p class="text-lg">
            &ldquo;A melhor maneira de prever o futuro é criá-lo.&rdquo;
          </p>
          <footer class="text-sm">Peter Drucker</footer>
        </blockquote>
      </div>
    </div>

    <div class="lg:p-8">
      <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
        <div class="flex flex-col space-y-2 text-center">
          <h1 class="text-2xl font-semibold tracking-tight">Acessar conta</h1>
          <p class="text-sm text-muted-foreground">Digite seu e-mail e senha</p>
        </div>
        <form @submit.prevent="onSubmit" class="space-y-4">
          <div class="space-y-2">
            <Label for="email">E-mail</Label>
            <Input id="email" type="email" v-model="email" required autocomplete="email" />
          </div>
          <div class="space-y-2">
            <Label for="password">Senha</Label>
            <Input id="password" type="password" v-model="password" required autocomplete="current-password" />
          </div>
          <Button type="submit" class="w-full">Entrar</Button>
        </form>
      </div>
    </div>
  </div>
</template>
