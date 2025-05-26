import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import Login from "@/pages/Login.vue";
import SidebarLayout from "@/layouts/SidebarLayout.vue";

const routes = [
  { path: "/", redirect: "/dashboard" },
  { path: "/login", component: Login },
  {
    path: "/",
    component: SidebarLayout,
    children: [
      { path: "dashboard", component: () => import("@/pages/Dashboard.vue") },
      { path: "sellers", component: () => import("@/pages/Sellers.vue") },
      { path: "sellers/create", component: () => import("@/pages/SellerForm.vue") },
      { path: "sellers/:id", component: () => import("@/pages/SellerShow.vue") },
      { path: "sellers/:id/edit", component: () => import("@/pages/SellerForm.vue") },
      { path: "sales", component: () => import("@/pages/Sales.vue") },
      { path: "sellers/:id/sales", component: () => import("@/pages/SellerSales.vue") },
      { path: "sellers/:id/sales/create", component: () => import("@/pages/SaleForm.vue") },
      { path: "sellers/:sellerId/sales/:saleId", component: () => import("@/pages/SaleShow.vue")},
      { path: "sellers/:sellerId/sales/:saleId/edit", component: () => import("@/pages/SaleForm.vue") },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const auth = useAuthStore();
  const isLoggedIn = !!auth.token;
  if (to.path !== "/login" && !isLoggedIn) {
    return next("/login");
  }
  next();
});

export default router;
