<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import AppSidebar from '@/components/AppSidebar.vue'
import {
  SidebarInset,
  SidebarProvider,
  SidebarTrigger,
} from '@/components/ui/sidebar'
import { Separator } from '@/components/ui/separator'
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator
} from '@/components/ui/breadcrumb'

const route = useRoute()
const crumbs = computed(() => {
  const segments = route.path.split('/').filter(Boolean)
  return segments.map((seg, i) => ({
    label: seg.charAt(0).toUpperCase() + seg.slice(1),
    path: '/' + segments.slice(0, i + 1).join('/')
  }))
})
</script>

<template>
  <SidebarProvider :style="{ '--sidebar-width': '19rem' }">
    <AppSidebar />
    
    <SidebarInset>
      <header class="flex h-16 items-center gap-2 px-4 border-b">
        <SidebarTrigger class="-ml-1" />
        <Separator orientation="vertical" class="mr-2 h-4" />
        <Breadcrumb>
          <BreadcrumbList>
            <BreadcrumbItem class="hidden md:block">
              <BreadcrumbLink href="/">beeSHOP</BreadcrumbLink>
            </BreadcrumbItem>
            <template v-for="(crumb, index) in crumbs" :key="crumb.path">
              <BreadcrumbSeparator />
              <BreadcrumbItem>
                <BreadcrumbPage v-if="index === crumbs.length - 1">
                  {{ crumb.label }}
                </BreadcrumbPage>
                <BreadcrumbLink v-else :href="crumb.path">
                  {{ crumb.label }}
                </BreadcrumbLink>
              </BreadcrumbItem>
            </template>
          </BreadcrumbList>
        </Breadcrumb>
      </header>

      <main class="flex-1 p-4">
        <RouterView />
      </main>
    </SidebarInset>

  </SidebarProvider>
</template>
