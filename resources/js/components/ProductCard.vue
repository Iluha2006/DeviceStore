<template>
  <div class="products-container">
    <transition name="fade">
      <div v-if="ModalMessage" class="dm-toast">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14m.75-10.25v5.5a.75.75 0 0 1-1.5 0v-5.5a.75.75 0 0 1 1.5 0M8 11.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5"/>
        </svg>
        {{ message }}
      </div>
    </transition>

    <button class="scroll-button" @click="scrollLeft">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
      </svg>
    </button>

    <div class="products-wrapper" ref="productsWrapper">
      <div class="dm-card card-product" v-for="product in products" :key="product.id">
        <div class="card-content">
          <div class="card-header">
            <span class="dm-badge dm-badge-danger" v-if="product.discount && product.discount !== '0%'">
              −{{ product.discount }}
            </span>
            <span class="dm-badge dm-badge-success" v-else>В наличии</span>
            <button class="favorite" @click="addToFavorites(product)" title="В избранное">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
              </svg>
            </button>
          </div>

          <router-link :to="'/product/' + product.id" class="product-image-link">
            <div class="product-image-wrap">
              <img :src="product.imageUrl" class="dm-img-contain" :alt="product.name" />
            </div>
          </router-link>

          <h3 class="product-title">
            <router-link :to="'/product/' + product.id">{{ product.name }}</router-link>
          </h3>

          <div class="product-rating">
            <div class="stars">
              <span v-for="i in 5" :key="i" :class="{ 'filled': i <= Math.round(product.average_rating || 0) }">★</span>
            </div>
            <span class="reviews-count" v-if="product.reviews_count">({{ product.reviews_count }})</span>
          </div>

          <div class="price-row">
            <span class="price">{{ formatPrice(product.price) }}</span>
            <span class="price-old" v-if="product.oldPrice">{{ formatPrice(product.oldPrice) }}</span>
          </div>

          <button class="dm-btn dm-btn-primary dm-btn-block" @click="addToCart(product)" :disabled="product.quantity <= 0">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
              <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
            </svg>
            {{ product.quantity > 0 ? 'В корзину' : 'Нет в наличии' }}
          </button>
        </div>
      </div>
    </div>

    <button class="scroll-button" @click="scrollRight">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
      </svg>
    </button>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router';
import { useUserStore } from '../Store/user';

const products = ref([])
const ModalMessage = ref(false);
const message = ref("");
const router = useRouter();
const userStore = useUserStore();
const productsWrapper = ref(null)

const formatPrice = (value) => {
  const num = Number(value || 0);
  return num.toLocaleString('ru-RU') + ' ₽';
};

const fetchProductsWithRatings = async () => {
  try {
    const response = await axios.get('/api/products/ratings');
    products.value = response.data.slice(0, 20);
    console.log('Products with ratings:', products.value);
  } catch (error) {
    console.error('Error fetching products with ratings:', error);
  }
};

const fetchProducts = async () => {
  try {
    const response = await axios.get('/api/products');
  } catch (error) {
    console.error('Error fetching products:', error);
  }
};

const scrollLeft = () => {
  if (productsWrapper.value) {
    productsWrapper.value.scrollBy({ left: -300, behavior: 'smooth' });
  }
};

const scrollRight = () => {
  if (productsWrapper.value) {
    productsWrapper.value.scrollBy({ left: 300, behavior: 'smooth' });
  }
};

const addToCart = async (product) => {
  try {
    if (!userStore.isAuthenticated) {
      router.push('/erorsauth');
      return;
    }

    const response = await axios.post('/api/AddCart', {
      product_id: product.id,
      quantity: 1
    }, {
      withCredentials: true,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${userStore.token}`
      }
    });
    if (!ModalMessage.value) {
      message.value = "Товар добавлен в корзину!";
      ModalMessage.value = true;
      setTimeout(() => {
        ModalMessage.value = false;
      }, 1300);
    } else {
      message.value = "Товар уже добавлен в корзину!";
      ModalMessage.value = true;
      setTimeout(() => {
        ModalMessage.value = false;
      }, 1300);
    }
  } catch (error) {
    console.error('Ошибка добавления:', error);
  }
};

const addToFavorites = async (product) => {
  try {
    if (!userStore.isAuthenticated) {
      router.push('/erorsauth');
      return;
    }

    await axios.post('/api/favorites/add', {
      product_id: product.id
    }, {
      withCredentials: true,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${userStore.token}`
      }
    });

    if (!ModalMessage == true) {
      message.value = "Товар добавлен в избранное!";
      ModalMessage.value = true;
      setTimeout(() => {
        ModalMessage.value = false;
      }, 1300);
    }
    else {
      message.value = "Товар уже добавлен в избранное";
      ModalMessage.value = true;
      setTimeout(() => {
        ModalMessage.value = false;
      }, 1300);
    }
  } catch (error) {
    console.error('Error adding to favorites:', error);
  }
}

onMounted(() => {
  fetchProducts(),
    fetchProductsWithRatings()
})
</script>

<style scoped>
.products-container {
  display: flex;
  align-items: center;
  width: 100%;
  max-width: 100%;
  gap: var(--dm-space-3);
  position: relative;
}

.products-wrapper {
  display: flex;
  gap: var(--dm-space-4);
  overflow-x: auto;
  scroll-behavior: smooth;
  scrollbar-width: none;
  -ms-overflow-style: none;
  padding: var(--dm-space-2) var(--dm-space-1) var(--dm-space-4);
}

.products-wrapper::-webkit-scrollbar {
  display: none;
}

.scroll-button {
  flex-shrink: 0;
  width: 44px;
  height: 44px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--dm-surface);
  border: 1px solid var(--dm-border-strong);
  border-radius: 50%;
  color: var(--dm-text);
  cursor: pointer;
  box-shadow: var(--dm-shadow-sm);
  transition: all 0.15s ease;
}

.scroll-button:hover {
  color: var(--dm-primary);
  border-color: var(--dm-primary);
  background: var(--dm-primary-soft);
}

.scroll-button svg {
  width: 20px;
  height: 20px;
}

.card-product {
  flex: 0 0 auto;
  width: 268px;
  padding: var(--dm-space-4);
  transition: box-shadow 0.2s ease, transform 0.2s ease;
}

.card-product:hover {
  box-shadow: var(--dm-shadow-md);
  transform: translateY(-3px);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: var(--dm-space-3);
}

.favorite {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border: none;
  background: transparent;
  color: var(--dm-text-muted);
  cursor: pointer;
  border-radius: 50%;
  transition: all 0.15s ease;
}

.favorite:hover {
  color: var(--dm-danger);
  background: var(--dm-danger-soft);
}

.product-image-link {
  display: block;
}

.product-image-wrap {
  width: 100%;
  height: 180px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--dm-surface-muted);
  border: 1px solid var(--dm-border);
  border-radius: var(--dm-radius-md);
  margin-bottom: var(--dm-space-3);
  overflow: hidden;
}

.product-title {
  margin: 0 0 var(--dm-space-2);
  height: 40px;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}

.product-title a {
  font-size: var(--dm-text-md);
  font-weight: 600;
  color: var(--dm-text);
  text-decoration: none;
  line-height: 1.35;
  transition: color 0.15s ease;
}

.product-title a:hover {
  color: var(--dm-primary);
}

.product-rating {
  display: flex;
  align-items: center;
  gap: var(--dm-space-2);
  margin-bottom: var(--dm-space-3);
}

.stars {
  font-size: var(--dm-text-sm);
  color: var(--dm-border-strong);
  letter-spacing: 2px;
}

.stars .filled {
  color: var(--dm-warning);
}

.reviews-count {
  font-size: var(--dm-text-xs);
  color: var(--dm-text-muted);
}

.price-row {
  display: flex;
  align-items: baseline;
  gap: var(--dm-space-2);
  margin-bottom: var(--dm-space-3);
}

.price {
  font-size: var(--dm-text-xl);
  font-weight: 800;
  color: var(--dm-text);
}

.price-old {
  font-size: var(--dm-text-sm);
  color: var(--dm-text-muted);
  text-decoration: line-through;
}
</style>
