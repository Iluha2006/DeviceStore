<template>
    <div class="products-container">
        <transition name="fade">
            <div v-if="ModalMessage" class="message-success">
                {{message}}
            </div>
        </transition>
      <button class="scroll-button left" @click="scrollLeft">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
</svg>
      </button>
      <div class="products-wrapper" ref="productsWrapper">
        <div class="card-product" v-for="product in products" :key="product.id">
          <div class="card-content">
            <div class="card-header">
              <span class="discount" >{{ product.discount }}</span>
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

                <router-link :to="'/product/' + product.id" >

                    <svg  xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
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

            <button class="add-to-cart" @click="addToCart(product)" >Добавить в корзину</button>
            <div class="product-rating">
  <div class="stars">
    <span v-for="i in 5" :key="i"
    :class="{'filled': i <= (product.average_rating )}">★</span>
  </div>
  <span class="reviews-count" v-if="product.reviews_count">
    ({{ product.reviews_count }})
  </span>
</div>
          </div>
        </div>
      </div>

      <button class="scroll-button right" @click="scrollRight">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
</svg>
      </button>
    </div>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue'
   import axios from 'axios'
   import { useRouter } from 'vue-router';

   import { useUserStore } from '../Store/user';

  const products = ref([])
  const ModalMessage = ref(false);
const message = ref("");
const router = useRouter();
const userStore = useUserStore();
  const productsWrapper = ref(null)


  const fetchProductsWithRatings = async () => {
  try {

    const response = await axios.get('/api/products/ratings');
    products.value = response.data.filter(product =>
      [ 1, 2 , 3, 4, 5,6,7,8].includes(product.id)
    );


    console.log('Products with ratings:', products.value);
  } catch (error) {
    console.error('Error fetching products with ratings:', error);
  }
};

  const fetchProducts = async () => {
  try {
    const response = await axios.get('/api/products');

  } catch (error) {
    console.error('Error fetching products:', error);
  }
};
  const scrollLeft = () => {
    if (productsWrapper.value) {
      productsWrapper.value.scrollBy
      (
    {
      left: -300, behavior: 'smooth'
    }
    )
    }
  }

  const scrollRight = () => {
    if (productsWrapper.value) {
      productsWrapper.value.scrollBy(
   {
      left: 300, behavior: 'smooth'

     }
      )
    }
  }


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
    if (!ModalMessage.value) {
      message.value = "Товар добавлен в корзину!";
      ModalMessage.value = true;
      setTimeout(() => {
        ModalMessage.value = false;
      }, 1300);
    } else {
      message.value = "Товар уже добавлен в корзину!";
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

    if(!ModalMessage==true){
        message.value = "Товар добавлен в избранное!";
        ModalMessage.value = true;
        setTimeout(() => {
            ModalMessage.value = false;
        }, 1300);
    }
      else {
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
    fetchProducts(),
    fetchProductsWithRatings()
  })
  </script>

  <style scoped>

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
.products-container {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  max-width: 1200px;
  margin: 20px auto;
  gap: 15px;
  position: relative;
  padding: 0 15px;
}

  .products-wrapper {
    display: flex;
    gap: 20px;
    overflow-x: auto;
    scroll-behavior: smooth;
    scrollbar-width: none;
    -ms-overflow-style: none;
    padding: 10px 0;
  }

  .products-wrapper::-webkit-scrollbar {
    display: none;
  }

  .scroll-button {
    background: white;
    border: 1px solid #ddd;
    border-radius: 50%;
    width: 120px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    transition: all 0.3s;
    z-index: 1;
  }

  .scroll-button:hover {
    background: #f5f5f5;
  }

  .scroll-button svg {
    width: 20px;
    height: 20px;
  }

  .card-product {
    flex: 0 0 auto;
    width: 280px;
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
  </style>