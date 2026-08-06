<template>
    <div class="payment-container">
      <div class="payment-status canceled">
        <h1>Оплата отменена</h1>
        <div >
        <p>Вы отменили оплату заказа </p>
        </div>
        <router-link to="/" class="back-link">Вернуться в корзину</router-link>
      </div>
    </div>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue';
  import { useRoute } from 'vue-router';
  import axios from 'axios';
  import { useUserStore } from '@/Store/user';
  const route = useRoute();
  const order = ref(null);
  const userStore = useUserStore();
  onMounted(async () => {
    try {
      const sessionId = route.query.session_id;
      const response = await axios.get(`/api/payment/cancel?session_id=${sessionId}` , {
        headers: {
          'Authorization': `Bearer ${userStore.token}`
        }
      });
      order.value = response.data.order;
    } catch (error) {
      console.error('Error fetching payment status:', error);
    }
  });
  </script>
  <style scoped>
  .payment-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: #f8f9fa;
    padding: 20px;
  }

  .payment-status {
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    padding: 40px;
    max-width: 600px;
    width: 100%;
    text-align: center;
  }

  .payment-status.canceled {
    border-top: 6px solid #e63946;
  }

  .payment-status h1 {
    color: #e63946;
    font-size: 28px;
    margin-bottom: 20px;
    font-weight: 600;
  }

  .payment-status p {
    color: #495057;
    font-size: 18px;
    margin-bottom: 30px;
    line-height: 1.6;
  }

  .back-link {
    display: inline-block;
    background-color: #e63946;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 2px solid #e63946;
  }

  .back-link:hover {
    background-color: white;
    color: #e63946;
  }

  @media (max-width: 768px) {
    .payment-status {
      padding: 30px 20px;
    }

    .payment-status h1 {
      font-size: 24px;
    }

    .payment-status p {
      font-size: 16px;
    }

    .back-link {
      padding: 10px 20px;
    }
  }
  </style>