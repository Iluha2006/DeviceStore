<template>
    <div class="payment-container">
      <div v-if="!order && !error" class="payment-status loading">
        <div class="spinner"></div>
        <p>Проверка статуса оплаты...</p>
      </div>
      <div v-else-if="order" class="payment-status success">
        <div class="success-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
        </div>
        <h1>Оплата прошла успешно!</h1>
        <div v-if="order.products && order.products.length" class="order-products">
          <h3>Товары в заказе:</h3>
          <ul class="product-list">
            <li v-for="product in order.products" :key="product.id" class="product-item">
              <span class="product-name">{{ product.name }}</span>
              <span class="product-quantity">{{ product.pivot.quantity }} шт.</span>
              <span class="product-price">{{ product.pivot.price * product.pivot.quantity }} руб.</span>
            </li>
          </ul>
          <div class="order-total">
            <span>Итого:</span>
            <span class="total-amount">
              {{ calculateTotal() }} руб.
            </span>
          </div>
        </div>
        <router-link to="/" class="back-link">Главная</router-link>
      </div>
    </div>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue';
  import { useRoute } from 'vue-router';
  import axios from 'axios';
  import { useUserStore } from '@/Store/user';

  const route = useRoute();
  const userStore = useUserStore();
  const order = ref(null);
  const error = ref(null);

  const calculateTotal = () => {
    if (!order.value?.products) return 0;
    return order.value.products.reduce(
      (sum, product) => sum + (product.pivot.price * product.pivot.quantity),
      0
    );
  };

  async function checkPaymentSuccess() {
    try {
      const sessionId = route.query.session_id;
      if (!sessionId) {
        error.value = 'Неверная сессия оплаты';
        return;
      }

      const response = await axios.get(`/api/payment/success?session_id=${sessionId}`, {
        headers: {
          'Authorization': `Bearer ${userStore.token}`
        }
      });

      order.value = response.data.order;

      if (order.value?.id) {
        const orderDetails = await axios.get(`/api/orders/${order.value.id}`, {
          headers: {
            'Authorization': `Bearer ${userStore.token}`
          }
        });
        order.value = orderDetails.data;
      }
    } catch (err) {
      error.value = err.response?.data?.message || 'Ошибка при проверке статуса оплаты';
      console.error('Payment error:', err);
    }
  }

  onMounted(() => {
    checkPaymentSuccess();
  });
  </script>
  <style scoped>
  .payment-container {
    max-width: 600px;
    margin: 2rem auto;
    padding: 2rem;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .payment-status {
    background: white;
    border-radius: 12px;
    padding: 2.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    text-align: center;
  }

  .loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
  }

  .spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }

  .success-icon, .error-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .success-icon {
    background-color: #e8f5e9;
    color: #4caf50;
  }

  .error-icon {
    background-color: #ffebee;
    color: #f44336;
  }

  .success-icon svg, .error-icon svg {
    width: 48px;
    height: 48px;
    fill: currentColor;
  }

  h1 {
    font-size: 1.8rem;
    margin-bottom: 1rem;
    color: #2c3e50;
  }

  .order-number {
    font-size: 1.1rem;
    color: #555;
    margin-bottom: 2rem;
  }

  .order-products {
    margin: 2rem 0;
    text-align: left;
  }

  .order-products h3 {
    font-size: 1.2rem;
    color: #333;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #eee;
  }

  .product-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .product-item {
    display: flex;
    justify-content: space-between;
    padding: 0.8rem 0;
    border-bottom: 1px solid #f5f5f5;
  }

  .product-name {
    flex: 2;
    color: #333;
  }

  .product-quantity {
    flex: 1;
    text-align: center;
    color: #666;
  }

  .product-price {
    flex: 1;
    text-align: right;
    font-weight: 500;
  }

  .order-total {
    display: flex;
    justify-content: space-between;
    margin-top: 1.5rem;
    padding-top: 1rem;
    border-top: 2px solid #eee;
    font-size: 1.1rem;
    font-weight: 600;
  }

  .total-amount {
    color: #2e7d32;
  }

  .error-message {
    color: #d32f2f;
    margin: 1.5rem 0;
  }

  .back-link {

    display: inline-block;
    margin-top: 1.5rem;
    padding: 0.8rem 1.5rem;
    background-color: #3498db;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 500;
    transition: background-color 0.2s;
  }

  .back-link:hover {
    background-color: #2980b9;
  }

  @media (max-width: 640px) {
    .payment-container {
      padding: 1rem;
    }

    .payment-status {
      padding: 1.5rem;
    }

    .product-item {
      flex-direction: column;
      gap: 0.3rem;
    }

    .product-quantity, .product-price {
      text-align: left;
    }
  }
  </style>