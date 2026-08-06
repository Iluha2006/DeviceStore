<template>
    <header>
      <nav class="navbar-menu">

        <router-link to="/" class="home-link">

            <h1>Electronics </h1>
        </router-link>

        <svg  @click="toggleMenu" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
</svg>
        <svg

          xmlns="http://www.w3.org/2000/svg"
          width="25"
          height="25"
          fill="currentColor"
          class="bi bi-list menu-icon"
          viewBox="0 0 16 16"

        >
          <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
        </svg>
        <SearchInput />
        <div class="icons-container">
            <router-link to="/favorites" >

                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="25"
                  height="25"
                  fill="currentColor"
                  class="bi bi-heart"
                  viewBox="0 0 16 16"
                >
                  <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
                </svg>
            </router-link>

          <router-link to="/cartproduct" >

              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="25"
                height="25"
                fill="currentColor"
                class="bi bi-cart"
                viewBox="0 0 16 16"
              >
                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
              </svg>
          </router-link>




          <router-link
            v-if="userStore.user"
            :to="'/profile/' + userStore.user.id"
            class="profile-icon"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="25"
              height="25"
              fill="currentColor"
              class="bi bi-person"
              viewBox="0 0 16 16"
            >
              <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
            </svg>
          </router-link>

          <svg
            v-else
            @click="$emit('toggle-profile-modal')"
            xmlns="http://www.w3.org/2000/svg"
            width="25"
            height="25"
            fill="currentColor"
            class="bi bi-person"
            viewBox="0 0 16 16"
          >
            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
          </svg>
   <router-link
v-if="userStore.user && userStore.user.id === 1"
    to="/admin/create-product"
    class="admin-link"
  >
    <button class="create-product-btn">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
      </svg>
      Создать продукт
    </button>
  </router-link>
        </div>
      </nav>

    </header>

  </template>

  <script setup>
  import SearchInput from './SearchInput.vue'
  import { useUserStore} from '../Store/user'


  const userStore = useUserStore()
  const emit = defineEmits(['toggleMenu', 'toggle-profile-modal'])

const toggleMenu = () => {
  emit('toggleMenu')
}
  </script>

  <style scoped>
  header {
    background-color: #bbaaaa;
    padding: 1rem 2rem;
  }

  .navbar-menu {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    position: relative;
  }
  .navbar-menu svg{
    cursor: pointer;
  }
  h1{
    font-size: 25px;
  }
  .create-product-btn {
  display: flex;
  align-items: center;
  gap: 5px;
  padding: 8px 12px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
}

.create-product-btn:hover {
  background-color: #45a049;
}

.admin-link {
  text-decoration: none;
}
  .list-menu {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 2rem;
  }

  .list-menu li {
    font-size: 1.1rem;
    color: #333;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 0.5rem 1rem;
  }

  .list-menu li:hover {
    color: #ff6b6b;
    transform: translateY(-2px);
  }

  .icons-container {
    display: flex;
    gap: 30px;
  }

  .bi-heart,
  .bi-cart,
  .bi-person,
  .menu-icon {
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .bi-heart:hover,
  .bi-cart:hover,
  .bi-person:hover,
  .menu-icon:hover {
    transform: scale(1.2) rotate(5deg);
    fill: #ff6b6b;
  }


  @media (max-width: 768px) {
    .list-menu {
      display: none;
    }


  }

  @media (min-width: 769px) {
    .menu-icon {
      display: none;
    }
  }
  </style>