<template>
  <div class="dm-page cart-page">
    <transition name="dm-fade">
      <div v-if="toast" class="dm-toast">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14m.75-10.25v5.5a.75.75 0 0 1-1.5 0v-5.5a.75.75 0 0 1 1.5 0M8 11.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5"/>
        </svg>
        {{ toast }}
      </div>
    </transition>

    <h1 class="dm-page-title">
      Корзина
      <span class="dm-count-badge" v-if="cartItems.length > 0">{{ totalItems }}</span>
    </h1>

    <div v-if="errorMessage" class="dm-alert">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14M8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4m.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2"/>
      </svg>
      {{ errorMessage }}
    </div>

    <div v-if="loading" class="dm-loading">
      <span class="dm-spinner"></span>
      Загрузка корзины...
    </div>

    <div v-else-if="!cartItems || cartItems.length === 0" class="dm-card dm-empty">
      <div class="dm-empty-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" fill="currentColor" viewBox="0 0 16 16">
          <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
        </svg>
      </div>
      <h2>В корзине пока пусто</h2>
      <p>Добавьте товары из каталога — они появятся здесь и будут ждать оформления заказа.</p>
      <router-link to="/" class="dm-btn dm-btn-primary dm-btn-lg">
        Перейти в каталог
      </router-link>
    </div>

    <div v-else class="cart-layout">
      <div class="cart-items">
        <transition-group name="dm-list" tag="div">
          <div class="dm-card cart-item" v-for="item in cartItems" :key="item.id">
            <div class="cart-item-img">
              <img v-if="item.product?.imageUrl" :src="item.product.imageUrl" class="dm-img-contain" :alt="item.product.name" />
              <span v-else class="dm-muted">Нет фото</span>
            </div>

            <div class="cart-item-body">
              <div class="cart-item-head">
                <div class="cart-item-info">
                  <h3 class="cart-item-name">{{ item.product?.name }}</h3>
                  <div class="cart-item-meta">
                    <span class="dm-badge dm-badge-primary" v-if="item.product?.discount && item.product.discount !== '0%'">
                      Скидка {{ item.product.discount }}
                    </span>
                    <span class="dm-badge dm-badge-success" v-if="(item.product?.quantity || 0) > 0">В наличии</span>
                    <span class="dm-badge dm-badge-danger" v-else>Нет в наличии</span>
                  </div>
                </div>
                <button
                  class="dm-btn dm-btn-ghost dm-btn-sm remove-btn"
                  @click="removeFromCart(item.id)"
                  title="Удалить из корзины"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                  </svg>
                  Удалить
                </button>
              </div>

              <div class="cart-item-foot">
                <div class="dm-stepper">
                  <button @click="decrement(item.id)" :disabled="item.quantity <= 1">−</button>
                  <span>{{ item.quantity }}</span>
                  <button @click="increment(item.id)" :disabled="item.quantity >= (item.product?.quantity || 1)">+</button>
                </div>

                <div class="cart-item-price">
                  <div class="cart-item-price-unit">Цена за шт. — {{ formatPrice(item.product?.price) }}</div>
                  <div class="cart-item-price-total">{{ formatPrice(itemTotal(item)) }}</div>
                </div>
              </div>
            </div>
          </div>
        </transition-group>
      </div>

      <aside class="cart-summary dm-card">
        <h2 class="cart-summary-title">Ваш заказ</h2>

        <div class="cart-summary-row">
          <span>Товары ({{ totalItems }})</span>
          <span>{{ formatPrice(totalPrice) }}</span>
        </div>
        <div class="cart-summary-row">
          <span>Доставка</span>
          <span class="dm-badge dm-badge-success">Бесплатно</span>
        </div>

        <div class="cart-summary-total">
          <span>Итого</span>
          <span>{{ formatPrice(totalPrice) }}</span>
        </div>

        <router-link to="/checkout" class="dm-btn dm-btn-primary dm-btn-lg dm-btn-block">
          Оформить заказ
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
          </svg>
        </router-link>
        <router-link to="/" class="cart-continue">Продолжить покупки</router-link>
      </aside>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { useUserStore } from '../../Store/user';

const cartItems = ref([]);
const errorMessage = ref('');
const toast = ref('');
const userStore = useUserStore();
const router = useRouter();

const showToast = (msg) => {
  toast.value = msg;
  setTimeout(() => (toast.value = ''), 1800);
};

const fetchCart = async () => {
  try {
    if (!userStore.isAuthenticated || !userStore.token) {
      throw new Error('Not authenticated');
    }

    const response = await axios.get('/api/cart', {
      withCredentials: true,
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${userStore.token}`,
        'Content-Type': 'application/json'
      }
    });

    cartItems.value = response.data.map(item => ({
      ...item,
      product: item.product || {}
    }));

    errorMessage.value = '';
  } catch (error) {
    console.error('Cart fetch error:', error);
    if (error.response?.status === 401) {
      userStore.clearUser();
      router.push('/erorsauth');
    }
    errorMessage.value = error.response?.data?.message || 'Ошибка загрузки корзины';
  }
};

const formatPrice = (value) => {
  const num = Number(value || 0);
  return num.toLocaleString('ru-RU') + ' ₽';
};

const itemTotal = (item) => (item?.product?.price || 0) * (item?.quantity || 0);

const totalItems = computed(() => {
  if (!Array.isArray(cartItems.value)) {
    return 0;
  }
  return cartItems.value.reduce((total, item) => total + (item?.quantity || 0), 0);
});

const totalPrice = computed(() => {
  if (!Array.isArray(cartItems.value)) {
    return 0;
  }
  return cartItems.value.reduce((total, item) => {
    const price = item?.product?.price || 0;
    const quantity = item?.quantity || 0;
    return total + (price * quantity);
  }, 0);
});

const increment = async (itemId) => {
  try {
    await axios.put(`/api/cart/increment/${itemId}`, {}, {
      withCredentials: true,
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${userStore.token}`
      }
    });
    await fetchCart();
  } catch (error) {
    console.error('Ошибка при увеличении количества:', error);
    if (error.response?.status === 422) {
      showToast('Достигнут максимум на складе');
    } else if (error.response?.status === 401) {
      userStore.clearUser();
      router.push('/login');
    }
  }
};

const decrement = async (itemId) => {
  try {
    await axios.put(`/api/cart/decrement/${itemId}`, {}, {
      withCredentials: true,
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${userStore.token}`
      }
    });
    await fetchCart();
  } catch (error) {
    console.error('Ошибка при уменьшении количества:', error);
    if (error.response?.status === 422) {
      showToast('Минимальное количество — 1 шт.');
    } else if (error.response?.status === 401) {
      userStore.clearUser();
      router.push('/login');
    }
  }
};

const removeFromCart = async (itemId) => {
  try {
    await axios.delete(`/api/cart/remove/${itemId}`, {
      withCredentials: true,
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${userStore.token}`
      }
    });
    showToast('Товар удалён из корзины');
    await fetchCart();
  } catch (error) {
    console.error('Ошибка при удалении из корзины:', error);
    if (error.response?.status === 401) {
      userStore.clearUser();
      router.push('/login');
    }
  }
};

onMounted(() => {
  fetchCart();
});
</script>

<style scoped>
.cart-page {
  max-width: 1160px;
}

.cart-layout {
  display: grid;
  grid-template-columns: 1fr 340px;
  gap: var(--dm-space-6);
  align-items: start;
}

.cart-items {
  display: flex;
  flex-direction: column;
  gap: var(--dm-space-4);
}

.cart-item {
  display: flex;
  gap: var(--dm-space-5);
  padding: var(--dm-space-4);
  transition: box-shadow 0.2s ease, transform 0.2s ease;
}

.cart-item:hover {
  box-shadow: var(--dm-shadow-md);
}

.cart-item-img {
  flex-shrink: 0;
  width: 140px;
  height: 140px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: var(--dm-radius-md);
  background: var(--dm-surface-muted);
  border: 1px solid var(--dm-border);
  overflow: hidden;
}

.cart-item-body {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: var(--dm-space-4);
  min-width: 0;
}

.cart-item-head {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: var(--dm-space-4);
}

.cart-item-info {
  display: flex;
  flex-direction: column;
  gap: var(--dm-space-2);
  min-width: 0;
}

.cart-item-name {
  margin: 0;
  font-size: var(--dm-text-lg);
  font-weight: 700;
  line-height: 1.3;
  color: var(--dm-text);
}

.cart-item-meta {
  display: flex;
  gap: var(--dm-space-2);
  flex-wrap: wrap;
}

.remove-btn {
  color: var(--dm-text-muted);
}

.remove-btn:hover {
  color: var(--dm-danger);
  background: var(--dm-danger-soft);
}

.cart-item-foot {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: var(--dm-space-4);
  flex-wrap: wrap;
  margin-top: auto;
}

.cart-item-price {
  text-align: right;
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.cart-item-price-unit {
  font-size: var(--dm-text-sm);
  color: var(--dm-text-muted);
}

.cart-item-price-total {
  font-size: var(--dm-text-xl);
  font-weight: 800;
  color: var(--dm-text);
}

/* ---- Сводка заказа ---- */
.cart-summary {
  position: sticky;
  top: 24px;
  padding: var(--dm-space-6);
  display: flex;
  flex-direction: column;
  gap: var(--dm-space-4);
}

.cart-summary-title {
  margin: 0 0 var(--dm-space-2);
  font-size: var(--dm-text-xl);
  font-weight: 800;
}

.cart-summary-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: var(--dm-text-base);
  color: var(--dm-text-secondary);
}

.cart-summary-total {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: var(--dm-space-4);
  border-top: 1px solid var(--dm-border);
  font-size: var(--dm-text-lg);
  font-weight: 800;
  color: var(--dm-text);
}

.cart-continue {
  text-align: center;
  font-size: var(--dm-text-base);
  font-weight: 600;
  color: var(--dm-primary);
  text-decoration: none;
}

.cart-continue:hover {
  color: var(--dm-primary-hover);
  text-decoration: underline;
}

@media (max-width: 860px) {
  .cart-layout {
    grid-template-columns: 1fr;
  }

  .cart-summary {
    position: static;
  }
}

@media (max-width: 560px) {
  .cart-item {
    flex-direction: column;
    align-items: stretch;
  }

  .cart-item-img {
    width: 100%;
    height: 200px;
  }

  .cart-item-price {
    text-align: left;
  }
}

.dm-fade-enter-active,
.dm-fade-leave-active {
  transition: opacity 0.25s ease, transform 0.25s ease;
}

.dm-fade-enter-from,
.dm-fade-leave-to {
  opacity: 0;
  transform: translate(-50%, -8px);
}

.dm-list-enter-active,
.dm-list-leave-active {
  transition: all 0.3s ease;
}

.dm-list-enter-from,
.dm-list-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}
</style>
