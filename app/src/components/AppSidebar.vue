<script setup lang="ts">
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { GalleryVerticalEnd, LogOut } from 'lucide-vue-next'
import {
  Sidebar,
  SidebarContent,
  SidebarGroup,
  SidebarHeader,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem
} from '@/components/ui/sidebar'

const router = useRouter()
const route = useRoute()
const auth = useAuthStore()

const menuItems = [
  { label: 'Dashboard', path: '/dashboard' },
  { label: 'Vendedores', path: '/sellers' },
  { label: 'Vendas', path: '/sales' }
]

const logout = () => {
  auth.logout()
  router.push('/login')
}
</script>

<template>
  <Sidebar>
    <SidebarHeader>
      <SidebarMenu>
        <SidebarMenuItem>
          <SidebarMenuButton size="lg" as-child>
            <a href="#" class="flex items-center gap-3">
              <div class="flex aspect-square size-8 items-center justify-center rounded-lg bg-primary text-primary-foreground">
                <GalleryVerticalEnd class="size-4" />
              </div>
              <div class="flex flex-col leading-none">
                <span class="font-semibold">beeSHOP</span>
                <span class="text-xs">v1.0</span>
              </div>
            </a>
          </SidebarMenuButton>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarHeader>

    <SidebarContent>
      <SidebarGroup>
        <SidebarMenu>
          <SidebarMenuItem v-for="item in menuItems" :key="item.path">
            <SidebarMenuButton as-child :is-active="route.path === item.path">
              <RouterLink :to="item.path">
                {{ item.label }}
              </RouterLink>
            </SidebarMenuButton>
          </SidebarMenuItem>
          <SidebarMenuItem>
            <SidebarMenuButton @click="logout" class="text-red-500">
              <LogOut class="size-4 mr-2" /> Sair
            </SidebarMenuButton>
          </SidebarMenuItem>
        </SidebarMenu>
      </SidebarGroup>
    </SidebarContent>
  </Sidebar>
</template>
