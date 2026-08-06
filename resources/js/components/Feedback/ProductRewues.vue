<template>
    <div class="reviews-container" :class="{ 'modal-mode': isModal }">
      <div v-if="!isModal" class="add-review">
        <h3>Оставить комментарий</h3>
        <div class="rating-input" :class="{ 'error': errors.rating }">
        <span>Оценка:</span>
        <div class="stars-input">
          <span
            v-for="i in 5"
            :key="i"
            @click="newReview.rating = i; clearError('rating')"
            :class="{'filled': i <= newReview.rating}"
          >★</span>
        </div>
        <span class="error-message" v-if="errors.rating">{{ errors.rating }}</span>
      </div>


      <div class="form-group">
        <label>Достоинства:</label>
        <textarea
          v-model="newReview.pros"
          @input="clearError('pros')"
          :class="{ 'error': errors.pros }"
          placeholder="Что вам понравилось?"
        ></textarea>
        <span class="error-message" v-if="errors.pros">{{ errors.pros }}</span>
      </div>

      <div class="form-group">
        <label>Недостатки:</label>
        <textarea
          v-model="newReview.cons"
          @input="clearError('cons')"
          :class="{ 'error': errors.cons }"
          placeholder="Что можно улучшить?"
        ></textarea>
        <span class="error-message" v-if="errors.cons">{{ errors.cons }}</span>
      </div>


      <div class="form-group">
        <label>Комментарий:</label>
        <textarea
          v-model="newReview.comment"
          @input="clearError('comment')"
          :class="{ 'error': errors.comment }"
          placeholder="Ваш комментарий"
        ></textarea>
        <span class="error-message" v-if="errors.comment">{{ errors.comment }}</span>
      </div>

      <button @click="submitReview" class="submit-btn">Отправить отзыв</button>
    </div>

      <div class="reviews-list">
        <button

          @click="isModal = true"
          class="view-all-comments-btn"
        >
          Посмотреть все комментарии
        </button>
        <div v-for="review in  reviews" :key="review.id" class="review-item">
            <div class="review-author">
            {{ review.user.name }}
          </div>
          <div class="review-content">
            <div v-if="review.pros " class="review-pros">
              <strong>Достоинства:</strong> {{ review.pros }}

            </div>

            <div v-if="review.cons " class="review-cons">
              <strong>Недостатки:</strong> {{ review.cons }}
              <span class="error-message" v-if="errors.cons">{{ errors.cons }}</span>
            </div>

            <div v-if="review.comment" class="review-comment">
              {{ review.comment }}
            </div>
          </div>


        </div>
      </div>
    </div>
  </template>

<script setup>
import { ref, onMounted, defineProps } from 'vue';
import axios from 'axios';
import { useUserStore } from '@/Store/user';

const userStore = useUserStore();
const isModal = ref(false);
const isLoading = ref(false);
const error = ref(null);
const errors = ref({});

const newReview = ref({
  rating: 0,
  pros: '',
  cons: '',
  comment: ''
});

const props = defineProps({
  productId: {
    type: Number,
    required: true
  }
});

const reviews = ref([]);

const clearError = (field) => {
  if (errors.value[field]) {
    errors.value[field] = '';
  }
};

const validateForm = () => {
  let isValid = true;
  errors.value = {};

  if (!newReview.value.rating) {
    errors.value.rating = 'Поставьте оценку';
    isValid = false;
  }

  if (newReview.value.pros.length > 500) {
    errors.value.pros = 'Не более 500 символов';
    isValid = false;
  }

  if (newReview.value.cons.length > 500) {
    errors.value.cons = 'Не более 500 символов';
    isValid = false;
  }

  if (newReview.value.comment.length > 1000) {
    errors.value.comment = 'Не более 1000 символов';
    isValid = false;
  }

  return isValid;
};

const fetchReviews = async () => {
  try {
    isLoading.value = true;
    error.value = null;
    const response = await axios.get(`/api/products/${props.productId}/reviews`, {
      withCredentials: true,
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${userStore.token}`,
        'Content-Type': 'application/json'
      }
    });
    reviews.value = response.data;
  } catch (err) {
    console.error('Error fetching reviews:', err);
    error.value = 'Не удалось загрузить комментарии';
    reviews.value = [];
  } finally {
    isLoading.value = false;
  }
};

const submitReview = async () => {
  if (!validateForm()) {
    return;
  }
  try {
    if (!userStore.isAuthenticated) {
      alert('Для добавления комментария необходимо авторизоваться');
      return;
    }

    await axios.post(
      `/api/products/${props.productId}/reviews`,
      newReview.value,
      {
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${userStore.token}`
        },
        withCredentials: true
      }
    );

    await fetchReviews();
    newReview.value = { rating: 0, pros: '', cons: '', comment: '' };
    errors.value = {};
  } catch (err) {
    if (err.response?.data?.errors) {
      errors.value = err.response.data.errors;
    } else {
      alert('Ошибка при отправке отзыва: ' + (err.response?.data?.message || err.message));
    }
  }
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString('ru-RU', options);
};

onMounted(() => {
  fetchReviews();
});
</script>
<style scoped>
.error-message {
  color: #ff4444;
  font-size: 20px;
  margin-top: 5px;
  display: block;
}


.reviews-container {
  margin-top: 40px;
  padding: 20px;
  border-top: 1px solid #eee;
}

.reviews-container.modal-mode {
  padding: 0;
  border: none;
  margin-top: 0;
}

.rating-summary {
  display: flex;
  gap: 40px;
  margin-bottom: 30px;
  padding: 20px;
  background: #f9f9f9;
  border-radius: 8px;
}

.average-rating {
  display: flex;
  flex-direction: column;
  align-items: center;
  min-width: 150px;
}

.rating-value {
  font-size: 2.5rem;
  font-weight: bold;
}

.stars {
  font-size: 1.5rem;
  color: #ccc;
  margin: 5px 0;
}

.stars .filled,
.stars-input .filled {
  color: #ffc107;
}

.total-reviews {
  color: #666;
}

.rating-distribution {
  flex-grow: 1;
}

.rating-bar {
  display: flex;
  align-items: center;
  margin: 5px 0;
}

.star-count {
  width: 40px;
}

.bar-container {
  flex-grow: 1;
  height: 10px;
  background: #eee;
  margin: 0 10px;
  border-radius: 5px;
  overflow: hidden;
}

.bar {
  height: 100%;
  background: #ffc107;
}

.percentage {
  width: 40px;
  text-align: right;
}

.add-review {
  padding: 20px;
  margin-bottom: 30px;
  background: #f9f9f9;
  border-radius: 8px;
}

.rating-input {
  display: flex;
  align-items: center;
  margin-bottom: 15px;
}

.stars-input {
  font-size: 1.5rem;
  color: #ccc;
  margin-left: 10px;
  cursor: pointer;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

.form-group textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  min-height: 80px;
}

.submit-btn {
  padding: 10px 20px;
  background: #000;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.reviews-list {
  margin-top: 20px;
  max-height: calc(100vh - 200px);
  overflow-y: auto;
}

.review-item {
  padding: 20px;
  margin-bottom: 20px;
  border: 1px solid #eee;
  border-radius: 8px;
}

.review-header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
}

.review-rating {
  font-size: 1.2rem;
  color: #ffc107;
}

.review-date {
  color: #666;
}

.review-pros,
.review-cons {
  margin-bottom: 10px;
}

.review-comment {
  margin: 10px 0;
  line-height: 1.5;
}

.review-author {
  margin-top: 10px;
  font-style: italic;
  color: #666;
}

.view-all-comments-btn {
  display: block;
  width: 100%;
  padding: 10px;
  margin-bottom: 20px;
  background: #f5f5f5;
  border: 1px solid #ddd;
  border-radius: 4px;
  text-align: center;
  cursor: pointer;
  color: #333;
  font-weight: bold;
}

.view-all-comments-btn:hover {
  background: #eee;
}

.close-modal-btn {
  position: fixed;
  bottom: 20px;
  right: 20px;
  padding: 10px 20px;
  background: #000;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  z-index: 1000;
}
.modal-mode .reviews-list {
  max-height: calc(100vh - 60px);
}

.modal-mode .review-item {
  padding: 15px;
  margin-bottom: 15px;
}

.modal-mode .review-header {
  flex-direction: column;
  gap: 5px;
}

.modal-mode .review-date {
  font-size: 0.9rem;
}

.modal-mode .review-content {
  margin-top: 10px;
}

.modal-mode .review-comment {
  font-size: 1rem;
  margin: 5px 0;
}

.modal-mode .review-author {
  font-size: 0.9rem;
  margin-top: 5px;
}

@media (max-width: 480px) {
  .reviews-container {
    padding: 8px;
  }

  .form-group textarea {
    min-height: 60px;
    padding: 8px;
  }

  .submit-btn {
    font-size: 0.9rem;
  }

  .review-content div {
    font-size: 0.95rem;
  }

  .review-author {
    font-size: 0.85rem;
  }
}

@media (max-width: 360px) {
  .stars, .stars-input {
    font-size: 1.1rem;
  }

  .review-rating {
    font-size: 1rem;
  }

  .review-date {
    font-size: 0.8rem;
  }
}
</style>