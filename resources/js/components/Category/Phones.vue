<template>
    <div class="products-container">
      <transition name="fade">
        <div v-if="ModalMessage" class="message-success">
          {{ message }}
        </div>
      </transition>
      <h1>Телефоны</h1>
      <div class="products-wrapper">
        <div class="card-product" v-for="product in  phones" :key="product.id">
          <div class="card-content">
            <div class="card-header">
              <span class="discount" v-if="product.discount">{{ product.discount }}</span>
              <button class="favorite">
                <svg @click="addToFavorites(product)"
                  xmlns="http://www.w3.org/2000/svg"
                  width="25"
                  height="25"
                  viewBox="0 0 16 16"
                >
                  <path
                    d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"
                  />
                </svg>

                <router-link :to="'/product/' + product.id">
                  <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                  </svg>
                </router-link>
              </button>
            </div>

            <img :src="product.imageUrl"
              class="product-image"
              :alt="product.name"/>

            <h3 class="product-title">{{ product.name }}</h3>
            <div class="price">
              <span>{{ product.price }} ₽</span>
            </div>

            <button class="add-to-cart" @click="addToCart(product)">Добавить в корзину</button>
            <div class="product-rating">
              <div class="stars">
                <span v-for="i in 5" :key="i"
                :class="{'filled': i <= (product.average_rating || 0)}">★</span>
              </div>
              <span class="reviews-count" v-if="product.reviews_count">
                ({{ product.reviews_count }})
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script>
  import { ref, onMounted } from 'vue'
  import axios from 'axios'
  import { useRouter } from 'vue-router'
  import { useUserStore } from '@/Store/user';


export default {
  setup() {
    const  phones = ref([])
    const ModalMessage = ref(false)
    const message = ref("")
    const router = useRouter()
    const userStore = useUserStore()

    const fetchPhones = async () => {
      try {
        const response = await axios.get('/api/categories/8/products')
        phones.value = response.data
      } catch (error) {
        console.error('Ошибка при загрузке мониторов:', error)
      }
    }

    const addToCart = async (product) => {
      try {
        if (!userStore.isAuthenticated) {
          router.push('/erorsauth')
          return
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
        })

        if (!ModalMessage.value) {
          message.value = "Товар добавлен в корзину!"
          ModalMessage.value = true
          setTimeout(() => {
            ModalMessage.value = false
          }, 1300)
        } 
        else
         {
          message.value = "Товар уже добавлен в корзину!"
          ModalMessage.value = true
          setTimeout(() => {
            ModalMessage.value = false
          }, 1300)
        }
      } 
      catch (error) 
      {
        console.error('Ошибка добавления:', error)
      }
    }
    const fetchProductsWithRatings = async () => {
  try {
    const response = await axios.get('/api/products/ratings');
    const ratingsMap = Object.fromEntries(response.data.map(p => [p.id, p]));
    phones.value = phones.value.map(product => ({
      ...product,
      average_rating: ratingsMap[product.id]?.average_rating,
      reviews_count: ratingsMap[product.id]?.reviews_count
    }));
  } catch (error) {
    console.error('Error fetching products with ratings:', error);
  }
};
    const addToFavorites = async (product) => {
      try {
        if (!userStore.isAuthenticated) {
          router.push('/erorsauth')
          return
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
        })

        if(!ModalMessage.value) {
          message.value = "Товар добавлен в избранное!"
          ModalMessage.value = true
          setTimeout(() => {
            ModalMessage.value = false
          }, 1300)
        } else {
          message.value = "Товар уже добавлен в избранное"
          ModalMessage.value = true
          setTimeout(() => {
            ModalMessage.value = false
          }, 1300)
        }
      } catch (error) {
        console.error('Error adding to favorites:', error)
      }
    }

    onMounted(() => {
        fetchPhones(),
     fetchProductsWithRatings()
    })

    return {
        phones,
      ModalMessage,
      message,
      addToCart,
      addToFavorites
    }
  }
}
  </script>

  <style scoped>
   h1{
    font-size: 25px;
  }
  .products-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
    max-width: 1200px;
    margin: 20px auto;
    gap: 15px;
    position: relative;
    padding: 0 15px;
  }

  h1 {
    text-align: center;
    margin-bottom: 20px;
  }

  .products-wrapper {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    width: 100%;
  }

  .card-product {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    transition: transform 0.3s;
  }

  .card-product:hover {
    transform: translateY(-5px);
  }

  .card-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
  }

  .discount {
    background: #ff4444;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 13px;
  }

  .favorite {
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
    display: flex;
    gap: 10px;
  }

  .product-image {
    width: 100%;
    height: 180px;
    object-fit: contain;
    margin-bottom: 1rem;
  }

  .product-title {
    font-size: 1rem;
    margin: 0 0 1rem 0;
    color: #333;
    height: 40px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;

    -webkit-box-orient: vertical;
  }

  .price {
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 1rem;
    color: #000;
  }

  .add-to-cart {
    width: 100%;
    padding: 0.75rem;
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

  .product-rating {
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
  }

  .product-rating .stars {
    font-size: 1rem;
    color: #ccc;
    margin-right: 0.5rem;
  }

  .product-rating .stars .filled {
    color: #ffc107;
  }

  .product-rating .reviews-count {
    font-size: 0.8rem;
    color: #666;
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
  </style>