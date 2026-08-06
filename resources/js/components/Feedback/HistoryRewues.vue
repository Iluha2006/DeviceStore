<template>
    <div class="user-reviews">
      <h2>Мои отзывы</h2>

      <div v-if="loading" class="loading">Загрузка отзывов...</div>
      <div v-else-if="error" class="error">{{ error }}</div>
      <div v-else-if="reviews.length === 0" class="no-reviews">
        У вас пока нет отзывов
      </div>
      <div v-else class="reviews-list">
        <div v-for="review in reviews" :key="review.id" class="review-item">

          <div class="product-info" v-if="review.product">
            <img :src="review.product.imageUrl" :alt="review.product.name" class="product-image">
            <router-link :to="`/product/${review.product.id}`" class="product-link">
              {{ review.product.name }}
            </router-link>
          </div>

          <div class="review-header">
            <div class="review-rating">
              <span v-for="i in 5" :key="i" :class="{ 'filled': i <= review.rating }">★</span>
            </div>
            <span class="review-date">{{ formatDate(review.created_at) }}</span>
          </div>

          <div class="review-content">
            <div v-if="review.pros" class="pros">
              <strong>Достоинства:</strong> {{ review.pros }}
            </div>
            <div v-if="review.cons" class="cons">
              <strong>Недостатки:</strong> {{ review.cons }}
            </div>
            <div v-if="review.comment" class="comment">
              {{ review.comment }}
            </div>
          </div>

          <button
            @click="deleteReview(review.id)"
            class="delete-btn"
          >
            Удалить
          </button>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import { useUserStore } from '@/Store/user';

  const userStore = useUserStore();
  const reviews = ref([]);
  const loading = ref(false);
  const error = ref(null);

  const fetchUserReviews = async () => {
    try {
      loading.value = true;
      error.value = null;
      const response = await axios.get('/api/user/reviews', {
        withCredentials: true,
        headers: {
          'Accept': 'application/json',
          'Authorization': `Bearer ${userStore.token}`,
          'Content-Type': 'application/json'
        }
      });
      reviews.value = response.data;
    } catch (err) {
      error.value = 'Ошибка загрузки отзывов';
      console.error('Error fetching reviews:', err);
    } finally {
      loading.value = false;
    }
  };

  const deleteReview = async (reviewId) => {
  if (!confirm('Вы уверены, что хотите удалить этот отзыв?')) return;

  try {
    await axios.delete(`/api/reviews/${reviewId}`, {
      withCredentials: true,
      headers: {
        'Authorization': `Bearer ${userStore.token}`,
        'Accept': 'application/json'
      }
    });
    await fetchUserReviews();
  } catch (err) {
    error.value = err.response?.data?.message || 'Ошибка удаления отзыва';
    console.error('Error deleting review:', err);
  }
};

  const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('ru-RU', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  };

  onMounted(() => {
    if (userStore.user?.id) {
      fetchUserReviews();
    }
  });
  </script>

  <style scoped>
  .user-reviews {
    padding: 20px;
  }

  .reviews-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  .review-item {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    background: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  }

  .product-info {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
  }


  h2{
    font-size:25px ;
    text-align: center;
  }
  .product-image {
    width: 150px;
    height:auto;
    object-fit: cover;
    border-radius: 4px;
  }

  .product-link {
    font-weight: bold;
    color: #3498db;
    text-decoration: none;
  }

  .product-link:hover {
    text-decoration: underline;
  }

  .review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
  }

  .review-rating {
    color: #675200;
    font-size: 1.2rem;
  }

  .review-rating .filled {
    color: #f39c12;
  }

  .review-date {
    color: #777;
    font-size: 0.9rem;
  }

  .review-content {
    margin-bottom: 15px;
  }

  .pros, .cons, .comment {
    margin-bottom: 8px;
    line-height: 1.5;
  }

  .delete-btn {
    background: none;
    border: 1px solid #e74c3c;
    color: #e74c3c;
    padding: 8px 15px;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
  }

  .delete-btn:hover {
    background: #fdecea;
  }

  .loading, .no-reviews {
    text-align: center;
    padding: 40px;
    color: #777;
  }

  .error {
    color: #e74c3c;
    padding: 15px;
    background-color: #fdecea;
    border-radius: 4px;
    margin: 20px 0;
  }
  </style>