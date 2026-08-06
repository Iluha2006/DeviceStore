<template>
    <div class="home-page">
      <div v-if="userStore.loading" class="loading">Загрузка данных...</div>
      <div v-else-if="userStore.user" class="user-info">
        <div class="profile-card">
            <svg @click="$emit('close')" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
  <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
</svg>
          <h3>Ваш профиль</h3>
          <h1 v-if="userStore.profile">Здрувствуйте {{ userStore.profile.name }}</h1>
          <p v-if="userStore.profile">Почта: {{ userStore.profile.email }}</p>
          <router-link :to="`/profile/${userStore.user.id}`"
            class="profile-link"
          >
            <button class="profile-link">Перейти в профиль</button>
          </router-link>
        </div>
      </div>
    </div>
  </template>
  <script setup>
  import { onMounted } from 'vue'
  import { useUserStore } from '@/Store/user'
  const userStore = useUserStore()
  const emit = defineEmits(['close'])
  const close = () => {
  emit('close')
  }
  onMounted(
    () => {
    if (userStore.user && !userStore.profile)
    {
      userStore.fetchProfile(userStore.user.id)
    }
  })
  </script>

  <style scoped>


.profile-card svg{
    position: absolute;
    right: 10px;
    cursor: pointer;
}
h3{
    font-size:25px ;
  }
  h1{
    font-size:25px ;
  }
 p{
    font-size:20px ;
   }


  .user-info {
    margin-top: 30px;
  }


  .profile-card {
    position: absolute;
    top:30px;
    right: 300px;
    z-index: 9999;
    width: 300px;
    background: #8d9298;
    padding: 20px;
    border-radius: 8px;
    margin-top: 20px;
    box-shadow: 0 10px 10px rgb(0, 0, 0, .3);
  }

  .profile-link {
    display: inline-block;
    margin-top: 15px;
    color: #0f0b73;
    text-decoration: none;
    cursor: pointer;
  }
.profile-link:hover{
    color: red;
}
  .not-authorized {
    text-align: center;
    margin-top: 50px;
  }

  </style>
