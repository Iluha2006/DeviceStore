<template>
    <div class="orders-container">
      <h1>Мои заказы</h1>
      <div v-if="loading" class="loading">Загрузка...</div>
      <div v-else-if="error" class="error">{{ error }}</div>

      <div v-else class="orders-list">
        <div v-for="order in orders" :key="order.id" class="order-card">
          <div class="order-header">
            <span class="order-date">{{ formatDate(order.created_at) }}</span>

          </div>


  <div class="order-products-container">
    <div class="order-products-scroll">
      <div v-for="product in order.products" :key="product.id" class="product-item">
        <img :src="product.imageUrl" :alt="product.name" class="product-image">
        <div class="product-info">
          <h3>{{ product.name }}</h3>
          <div class="product-meta">
            <span>{{ product.quantity }} × {{ product.price }} руб.</span>
            <span>{{ product.quantity * product.price }} руб.</span>
          </div>
        </div>
      </div>
    </div>
  </div>

          <div class="order-summary">
            <div class="summary-row">
              <span>Сумма заказа:</span>
              <span>{{ order.total_amount + order.discount }} руб.</span>
            </div>
            <div v-if="order.discount > 0" class="summary-row discount">
              <span>Скидка:</span>
              <span>-{{ order.discount }} руб.</span>
            </div>
            <div class="summary-row total">
              <span>Итого:</span>
              <span>{{ order.total_amount }} руб.</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useUserStore } from '@/Store/user';
import axios from 'axios';

const router = useRouter();
const userStore = useUserStore();

const loading = ref(false);
const error = ref(null);
const orders = ref([]);

const fetchOrders = async () => {
  try {
    loading.value = true;
    error.value = null;
    const response = await axios.get('/api/orders', {
      withCredentials: true,
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${userStore.token}`,
        'Content-Type': 'application/json'
      }
    });
    orders.value = response.data;
  } catch (err) {
    error.value = err.response?.data?.message || 'Ошибка загрузки заказов';
    console.error('Error fetching orders:', err);
  } finally {
    loading.value = false;
  }
};

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString();
};





onMounted(() => {
  fetchOrders();
});
</script>

  <style scoped>
  .order-products-container {
  overflow-x: auto;
  padding-bottom: 15px;
  margin-bottom: 15px;
}

.order-products-scroll {
  display: flex;
  gap: 15px;
  min-width: max-content;
}

.product-item {
  flex: 0 0 auto;
  width: 200px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.product-image {
  width: 100%;
  height: 120px;
  object-fit: cover;
  border-radius: 4px;
}

  h1{
    text-align: center;
    font-size:25px ;
  }
  .orders-container {

    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
  }

  .orders-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }


.product-item {
  flex: 0 0 auto;
  width: 200px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.product-image {
  width: 100%;
  height: 120px;
  object-fit: cover;
  border-radius: 4px;
}
  .order-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    background: white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  }

  .order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #f0f0f0;
  }

  .order-id {
    font-weight: bold;
  }

  .order-date {
    color: #666;
  }

  .order-status {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.85rem;
    font-weight: 500;
  }

  .status-pending {
    background-color: #fff3cd;
    color: #856404;
  }

  .status-processing {
    background-color: #cce5ff;
    color: #004085;
  }

  .status-completed {
    background-color: #d4edda;
    color: #155724;
  }

  .status-cancelled {
    background-color: #f8d7da;
    color: #721c24;
  }

  .order-products {
    display: flex;
    flex-direction: column;
    gap: 15px;
  }

  .product-item {
    display: flex;
    gap: 15px;
    align-items: center;
  }

  .product-image {
    width: 150px;
    height: auto;
    object-fit: cover;
    border-radius: 4px;
  }

  .product-info {
    flex: 1;
  }

  .product-info h3 {
    margin: 0 0 5px 0;
    font-size: 1rem;
  }

  .product-meta {
    display: flex;
    justify-content: space-between;
    color: #666;
    font-size: 0.9rem;
  }

  .order-summary {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #f0f0f0;
  }

  .summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
  }

  .summary-row.discount {
    color: #e74c3c;
  }

  .summary-row.total {
    font-weight: bold;
    font-size: 1.1rem;
    margin-top: 10px;
  }

  .loading, .no-orders {
    text-align: center;
    padding: 40px;
    color: #666;
  }

  .error {
    color: #e74c3c;
    padding: 15px;
    background-color: #fdecea;
    border-radius: 4px;
    margin: 20px 0;
    text-align: center;
  }
  </style>