import { createRouter, createWebHistory } from 'vue-router'
import IndexPage from "../components/pages/IndexPage.vue"
import IndexPageLogged from "../components/pages/IndexPageLogged.vue"
import ShopPage from "../components/pages/ShopPage.vue"
import LogInPage from "../components/pages/LogInPage.vue"
import SignUpPage from "../components/pages/SignUpPage.vue"
import AccountPage from "../components/pages/AccountPage.vue"
import MyOrders from "../components/MyOrders.vue"
import ProductDescPage from "../components/pages/ProductDescPage.vue"
import CartPage from "../components/pages/CartPage.vue"


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: IndexPage
    },

    {
      path: '/indexlogged',
      name: 'home_logged',
      component: IndexPageLogged
    },

    {
      path: '/shop',
      name: 'shop',
      component: ShopPage
    },

    {
      path: '/login',
      name: 'login',
      component: LogInPage
    },

    {
      path: '/signup',
      name: 'signup',
      component: SignUpPage
    },

    {
      path: '/account',
      name: 'account',
      component: AccountPage
    },

    {
      path: '/cart',
      name: 'cart',
      component: CartPage
    },

    {
      path: '/myorder',
      name: 'myorder',
      component: MyOrders
    },

    {
      path: '/shop_detail',
      name: 'shop_detail',
      component: ProductDescPage
    }
  ]
})

export default router
