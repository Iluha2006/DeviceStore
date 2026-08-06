<template>
    <div class="checkout-container">
      <h1>Оформление заказа</h1>
      <div class="checkout-grid">
        <div class="checkout-form">
          <h2>Ваши данные</h2>
          <form @submit.prevent="submitOrder">
            <div class="form-group">
              <label for="name">Имя</label>
              <input
                type="text"
                id="name"
                v-model="form.name"
                required
              >
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input
                type="email"
                id="email"
                v-model="form.email"
                required
              >
            </div>

            <div class="form-group">
              <label for="comment">Комментарий к заказу (необязательно)</label>
              <textarea
                id="comment"
                v-model="form.comment"
                rows="3"
              ></textarea>
            </div>

            <div class="form-agreement">
              <input
                type="checkbox"
                id="agreement"
                v-model="form.agreement"
                required
              >
              <label for="agreement">Я согласен с условиями Политики конфиденциальности</label>
            </div>

            <button
              type="submit"
              class="submit-btn"
              :disabled="loading"
            >
              <span v-if="loading">Обработка...</span>
              <span v-else>Оформить заказ</span>
            </button>
          </form>
        </div>

        <div class="order-summary">
          <h2>Ваш заказ</h2>

          <div class="order-items">
            <div
              v-for="item in cartItems"
              :key="item.id"
              class="order-item"
            >
              <div class="item-image">
                <img :src="item.product.imageUrl" >
              </div>
              <div class="item-details">
                <h3>{{ item.product.name }}</h3>
                <div class="item-meta">
                 <span>{{ item.quantity }} шт.</span>

                  <span>{{ item.quantity * item.product.price }} руб.</span>

                  <button class="delete-products" @click="deleteCartProducts(item.id)">Удалить</button>
                </div>
              </div>
            </div>
          </div>

          <div class="summary-totals">
            <div class="summary-row">

              <span>Итого {{ total }} руб.</span>
            </div>
          </div>
        </div>
      </div>

      <div v-if="error" class="error-message">
        {{ error }}
      </div>
    </div>
  </template>

  <script setup>
  import { ref, computed, onMounted } from 'vue';
  import { useRouter } from 'vue-router';
 import { useUserStore } from '@/Store/user'
  import axios from 'axios';

  const router = useRouter();
  const userStore = useUserStore();

  const cartItems = ref([]);
  const loading = ref(false);
  const error = ref(null);

  const form = ref(
    {
    name: userStore.user?.name || '',
    email: userStore.user?.email || '',
    comment: '',
    agreement: false
  }
);

  const total = computed(() => {
    return cartItems.value.reduce((sum, item) => {
      return sum + (item.quantity * item.product.price);
    }, 0);
  });

  const fetchCartItems = async () => {
    try {
      loading.value = true;
      const response = await axios.get('/api/cart', {
      withCredentials: true,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${userStore.token}`
      }
    });
      cartItems.value = response.data;
      if (cartItems.value.length === 0) {
        router.push('/cart');
      }
    } catch (err) {
      error.value = err.response?.data?.message || 'Ошибка загрузки корзины';
    } finally {
      loading.value = false;
    }
  };

  const submitOrder = async () => {
  try {
    loading.value = true;
    error.value = null;


    if (cartItems.value.length === 0) {
      error.value = 'Ваша корзина пуста';
      return;
    }


    const orderResponse = await axios.post('/api/orders', {
      comment: form.value.comment,
    }, {
      withCredentials: true,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${userStore.token}`
      }
    });


    const paymentUrl = orderResponse.data.order.payment_url;

    if (paymentUrl) {

      window.location.href = paymentUrl;
    }
    else{
        router.push('/payment/success')
    }
  } catch (err) {
    console.error('Order error:', err);
    error.value = err.response?.data?.message || 'Ошибка оформления заказа';
    if (err.response?.status === 419) {
      window.location.reload();
    }
  } finally {
    loading.value = false;
  }
};

const deleteCartProducts = async (itemId) => {
  try {
    const response= await axios.delete(`/api/cart/remove/${itemId}`, {
      withCredentials: true,
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${userStore.token}`
      }
    });
    await fetchCartItems();
  } catch (error) {
    console.error('Ошибка при удалении из корзины:', error);
    if (error.response?.status === 401) {
      userStore.clearUser();
      router.push('/login');
    }
  }
};
  onMounted(() => {
    if (!userStore.user) {
      router.push('/login');
    } else {
      fetchCartItems();
      form.value.name = userStore.user.name;
      form.value.email = userStore.user.email;
    }
  });
  </script>

  <style scoped>

  .delete-products{
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
    transition: background-color 0.2s;
  }

  .delete-products:hover{
    background-color: #f8f8f8;
    color: #e74c3c;
    border: 1px solid #e74c3c;
  }
  .checkout-container {
    max-width: 1300px;
    margin: 0 auto;
    padding: 30px 20px;
  }

  .checkout-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-top: 30px;
  }
  h2,h1{
    font-size: 25px;
    text-align: center;
  }
  .checkout-form {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  }

  .order-summary {
    background: white;
    padding: 30px;
    max-width: 700px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    position: sticky;
    top: 20px;
  }

  .form-group {
    margin-bottom: 20px;
  }

  .form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
  }

  .form-group input,
  .form-group textarea {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
  }

  .form-group textarea {
    min-height: 100px;
  }

  .form-agreement {
    margin: 25px 0;
    display: flex;
    align-items: center;
  }

  .form-agreement input {
    margin-right: 10px;
  }

  .submit-btn {
    width: 100%;
    padding: 15px;
    background: #3498db;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 1.1rem;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s;
  }

  .submit-btn:hover:not(:disabled) {
    background: #2980b9;
  }

  .submit-btn:disabled {
    background: #95a5a6;
    cursor: not-allowed;
  }

  .order-items {
    margin: 20px 0;
  }

  .order-item {
    display: flex;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px solid #f0f0f0;
  }

  .order-item:last-child {
    border-bottom: none;
  }

  .item-image img {
    width: 200px;
    height: auto;
    object-fit: cover;
    border-radius: 4px;
  }

  .item-details {
    flex: 1;
  }

  .item-details h3 {
    margin: 0 0 5px 0;
    font-size: 1rem;
  }

  .item-meta {
    display: flex;
    justify-content: space-between;
    color: #666;
  }

  .summary-totals {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #f0f0f0;
  }

  .summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
  }

  .summary-row.total {
    font-weight: bold;
    font-size: 1.1rem;
    margin-top: 15px;
  }

  .error-message {
    margin-top: 20px;
    padding: 15px;
    background-color: #fdecea;
    color: #e74c3c;
    border-radius: 4px;
    text-align: center;
  }

  @media (max-width: 768px) {
    .checkout-grid {
      grid-template-columns: 1fr;
    }

    .order-summary {
      position: static;
    }
  }
  </style>