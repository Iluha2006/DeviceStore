<template>
    <div class="cart-container">
        <div v-if="errorMessage" class="error-message">
          {{ errorMessage }}
        </div>
      <h1>Ваша корзина</h1>
      <div v-if="loading" class="loading">Загрузка...</div>
      <div v-else-if="!cartItems || cartItems.length === 0" class="empty-cart">
        <h1>Корзина пуста</h1>
      </div>
      <div v-else>
        <div class="cart-item" v-for="item in cartItems" :key="item.id">
          <img v-if="item.product?.imageUrl"
            :src="item.product.imageUrl"
            class="product-image"
            />
          <div class="cart-item-details">
            <h3>{{ item.product?.name  }}</h3>
            <div class="price">Цена: {{ item.product?.price || 0 }}₽</div>
            <div class="available">В наличии: {{ item.product?.quantity || 0 }} шт.</div>
            <div class="quantity-controls">
              <button @click="decrement(item.id)">-</button>
              <span>{{ item.quantity }}</span>
              <button @click="increment(item.id)">+</button>
            </div>
            <button class="remove-btn" @click="removeFromCart(item.id)">Удалить</button>
          </div>
        </div>
        <div class="cart-summary">
          <div class="cart-total">
            <h3>Итого: {{ totalItems }} товаров {{ totalPrice }}₽</h3>
          </div>
          <router-link to="/checkout" class="checkout-btn">
            <button class="but-buy">Перейти к оформлению</button>
          </router-link>
        </div>
      </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { loadStripe} from '@stripe/stripe-js';
import axios from 'axios';
import { useUserStore } from '@/Store/user';

const cartItems = ref([]);
const errorMessage = ref('');
const userStore = useUserStore();

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

    console.log('Cart response:', response.data);
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
     await axios.put(`/api/cart/increment/${itemId}` ,{}, {
      withCredentials: true,
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${userStore.token}`
      }
    });
    await fetchCart();
  } catch (error) {
    console.error('Ошибка при увеличении количества:', error);
    if (error.response?.status === 401) {
      userStore.clearUser();
      router.push('/login');
    }
  }
};

const decrement = async (itemId) => {
  try {
    const response = await axios.put(`/api/cart/decrement/${itemId}`, {}, {
      withCredentials: true,
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${userStore.token}`
      }
    });
    await fetchCart();
  } catch (error) {
    console.error('Ошибка при уменьшении количества:', error);
    if (error.response?.status === 401) {
      userStore.clearUser();
      router.push('/login');
    }
  }
};
const removeFromCart = async (itemId) => {
  try {
    const response = await axios.delete(`/api/cart/remove/${itemId}`, {
      withCredentials: true,
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${userStore.token}`
      }
    });
    await fetchCart();
  } catch (error) {
    console.error('Ошибка при удалении из корзины:', error);
    if (error.response?.status === 401) {
      userStore.clearUser();
      router.push('/login');
    }
  }
};




onMounted(async () => {
  fetchCart();
});
</script>

<style scoped>
.error-message {
  color: #ff4444;
  margin-bottom: 20px;
  padding: 10px;
  background: #ffeeee;
  border-radius: 4px;
}

.available {
  color: #666;
  font-size: 0.9rem;
  margin-bottom: 5px;
}

.but-buy {
    margin: 10px;
    background: #06ea28;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    font-size: 1rem;
    border-radius: 4px;
    width: 100%;
}

.cart-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

img {
    width: 200px;
    margin: 10px;
}

.cart-item {
    display: flex;
    margin-bottom: 20px;
    border-bottom: 1px solid #eee;
    padding-bottom: 20px;
}

.cart-item-details {
    flex-grow: 1;
}

.quantity-controls {
    display: flex;
    align-items: center;
    margin: 10px 0;
}

.quantity-controls button {
    width: 30px;
    height: 30px;
    border: 1px solid #ddd;
    background: #fff;
    cursor: pointer;
}

.quantity-controls span {
    margin: 0 10px;
}

h1 {
    text-align: center;
    font-size:25px;
}

.remove-btn {
    background: #ff4444;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
}

.cart-summary {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #eee;
}

.cart-total {
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 20px;
    text-align: right;
}

.empty-cart {
    text-align: center;
    padding: 40px;
    font-size:20px;
    color: #888;
}

.checkout-btn {
    text-decoration: none;
    display: block;
}
</style>