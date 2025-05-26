import { defineStore } from 'pinia'
import api from '@/lib/axios'
import router from '@/router'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null as any,
    token: localStorage.getItem('token'),
  }),
  actions: {
    async login(email: string, password: string) {
      const { data } = await api.post('/login', { email, password })
      this.token = data.token
      localStorage.setItem('token', data.token)

      // você pode adicionar chamada ao perfil aqui
      router.push('/sellers')
    },
    logout() {
      this.token = null
      this.user = null
      localStorage.removeItem('token')
      router.push('/login')
    }
  }
})
