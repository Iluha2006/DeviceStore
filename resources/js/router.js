import Home from './components/Home.vue';
import { createRouter, createWebHistory } from 'vue-router';
import Profile from './components/Profile.vue';
import Cart from './components/AddProduct/Cart.vue';
import Favorites from './components/AddProduct/Favorites.vue';
import ProductDetail from './components/ProductDetail.vue';
import { useUserStore } from './Store/user';
import AuthModals from './ErorPage/AuthModal.vue'
import NotFound from './ErorPage/NotFound.vue';
import Monitors from './components/Category/Monitors.vue';
import Consoles from './components/Category/Consoles.vue';
import Laptops from './components/Category/Laptops.vue';
import NetworkEquipment from './components/Category/NetworkEquipment.vue';
import Phones from './components/Category/Phones.vue';
import Checkout from './components/Order/Checkout.vue';
import Televisions from './components/Category/Televisions.vue';
import Audio from './components/Category/Audio.vue';
import CreateProduct from './components/Admin/CreateProduct.vue';
import PaymentCancel from './components/Pyaments/PaymentCancel.vue';
import PaymentSuccess from './components/Pyaments/PaymentSuccess.vue';
import OrderList from './components/Order/OrderList.vue';

const routes =
[
  {
        path: '/product/:id',
        component: ProductDetail,
        props: true
  },


  {
    path: '/payment/success',
    component: PaymentSuccess
  },
  {
    path: '/payment/cancel',
    name: 'payment-cancel',
    component: PaymentCancel
  },

  {
    path: '/cartproduct',
    component:Cart,

  },
  {
    path: '/admin/create-product',
    component: CreateProduct,


  },
  {
    path: '/monitors',
    component:Monitors,

  },
  {
    path: '/consoles',
    component:Consoles,

  },
  {
    path: '/laptops',
    component:Laptops,

  },
  {
    path: '/networkequipment',
    component:NetworkEquipment,

  },
  {
    path: '/phones',
    component:Phones,

  },
  {
    path: '/televison',
    component:Televisions,

  },
  {
    path: '/audio',
    component:Audio,

  },
  {
    path: '/favorites',
    component:Favorites,
  },
  {
    path: '/erorsauth',
    component:AuthModals,
  },

  {
    path: '/',
    name:'/home',
    component: Home,
  },
  {
    path: '/orederlist',
    component: OrderList,
  },
  {
    path: '/erorsauth',
    component: AuthModals,
  },

  {
    path: '/checkout',
    component:Checkout
  },
  { path: '/profile/:id',
    component: Profile,
     },
     {
        path: '/:pathMatch(.*)*',
        component: NotFound
      }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach(async (to) => {
    const userStore = useUserStore();



      if (to.meta.requiresAdmin && (!userStore.isAuthenticated || !userStore.isAdmin)) {
        return '/';
      }

    if (to.path === '/cartproduct' && !userStore.isAuthenticated) {
      return '/erorsauth';
    }
    else if(to.path==='/product/:id' && !userStore.isAuthenticated){
          return '/erorsauth'
    }
    else if(to.path==='/favorites' && !userStore.isAuthenticated){
        return '/erorsauth'
  }
  });
export default router;
