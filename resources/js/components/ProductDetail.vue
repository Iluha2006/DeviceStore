<template>
    <div class="product-detail-container">
      <transition name="fade">
        <div v-if="ModalMessage" class="message-success">
          {{message}}
        </div>
      </transition>

      <div class="product-detail">
        <div class="product-image-wrapper">
          <img :src="product.imageUrl" :alt="product.name" class="product-image">
        </div>
        <div class="product-info">
          <h1>{{ product.name }}</h1>
          <div class="price-section">
            <span class="price">{{ product.price }} ₽</span>
            <span v-if="product.discount" class="discount">{{ product.discount }}</span>
          </div>

          <div class="product-description">
            <h3>Описание</h3>
            <p>{{ product.description }}</p>
          </div>
          <div class="product-actions">

              <button class="add-to-cart" @click="addToCart(product)">
                  Добавить в корзину
                </button>
                <button class="favorite-button" @click="addToFavorites(product)">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="25"
                    height="25"
                    viewBox="0 0 16 16"
                    :fill="product.is_favorite ? 'red' : 'currentColor'"
                  >
                    <path
                      d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"
                    />
                  </svg>
                </button>
              <button class="view-reviews-btn" @click="showReviewsModal = true">
                  Добавить отзыв
                </button>
</div>


          </div>


        </div>
      </div>
      <transition name="modal">
        <div v-if="showReviewsModal" class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header">
                <h3>Отзывы о товаре</h3>
                <button class="modal-close" @click="showReviewsModal = false">
                  &times;
                </button>
              </div>
              <div class="modal-body">
                <ProductReviews :product-id="product.id" />
              </div>
            </div>
          </div>
        </div>
      </transition>

  </template>

  <script setup>
  import { ref, onMounted } from 'vue';
  import { useRoute, useRouter } from 'vue-router';
  import axios from 'axios';
  import { useUserStore } from '../Store/user';
  import ProductReviews from './Feedback/ProductRewues.vue';

  const route = useRoute();
  const router = useRouter();
  const product = ref({});
  const ModalMessage = ref(false);
  const message = ref("");
  const showReviewsModal = ref(false);
  const userStore = useUserStore();

  const fetchProduct = async () => {
    try {
      const response = await axios.get(`/api/products/${route.params.id}`);
      product.value = response.data;
    } catch (error) {
      console.error('Error fetching product:', error);
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

      if(!ModalMessage.value){
        message.value = "Товар добавлен в корзину!";
        ModalMessage.value = true;
        setTimeout(() => {
          ModalMessage.value = false;
        }, 1300);
      } else {
        message.value = "Товар уже добавлен в корзину";
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

      if(!ModalMessage.value){
        message.value = "Товар добавлен в избранное!";
        ModalMessage.value = true;
        setTimeout(() => {
          ModalMessage.value = false;
        }, 1300);
      } else {
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
    fetchProduct();
  });
  </script>

  <style scoped>
  .product-detail-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    position: relative;
  }

  .product-detail {
    display: flex;
    gap: 40px;
  }

  .product-image-wrapper {
    flex: 1;
  }

  .product-image {
    width: 100%;
    max-height: 500px;
    object-fit: contain;
    border-radius: 8px;
  }

  h1{
    font-size: 25px;
  }
  .product-info {
    flex: 1;
  }

  .price-section {
    display: flex;
    align-items: center;
    gap: 15px;
    margin: 20px 0;
  }

  .price {
    font-size: 1.8rem;
    font-weight: bold;
  }

  .discount {
    background: #ff4444;
    color: white;
    padding: 0.3rem 0.6rem;
    border-radius: 4px;
    font-size: 1rem;
  }

  .product-description {
    margin: 30px 0;
  }

  .product-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
  }

  .add-to-cart {
    padding: 12px 24px;

    background: #000;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s;
  }

  .add-to-cart:hover {
    background: #333;
  }

  .favorite-button {
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
    display: flex;
    align-items: center;
  }

  .message-success {
    position: fixed;
    top: 80px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #000000;
    color: rgb(219, 219, 219);
    padding: 1rem;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    font-size: 1.1em;
    text-align: center;
  }

  /* Стили для рейтинга */
  .product-rating {
    margin: 20px 0;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .product-rating .stars {
    font-size: 1.2rem;
    color: #ccc;
  }

  .product-rating .stars .filled {
    color: #ffc107;
  }

  .view-reviews-btn {
    background: none;
    border: none;
    color: #0066cc;
    cursor: pointer;
    font-size: 0.9rem;
    text-decoration: underline;
    padding: 0;
  }

  .view-reviews-btn:hover {
    color: #004499;
  }

  /* Стили для модального окна */
  .modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    transition: opacity 0.3s ease;
  }

  .modal-wrapper {
    width: 90%;
    max-width: 800px;
    max-height: 90vh;
  }

  .modal-container {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
    transition: all 0.3s ease;
    padding: 20px;
    max-height: 80vh;
    overflow-y: auto;
  }

  .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
  }

  .modal-header h3 {
    margin: 0;
  }

  .modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0 10px;
  }

  .modal-body {
    margin: 20px 0;
  }

  /* Анимации */
  .modal-enter-from, .modal-leave-to {
    opacity: 0;
  }

  .modal-enter-active, .modal-leave-active {
    transition: opacity 0.3s ease;
  }

  @media (max-width: 768px) {
    .product-detail {
      flex-direction: column;
    }

    .modal-wrapper {
      width: 95%;
    }
  }
  </style>