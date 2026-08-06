
<template>
    <div class="modal-overlay" @click.self="close">
      <div class="auth-container">
        <div class="auth-card">
    
          <svg @click="close" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
          </svg>

    
          <h2 class="auth-title">{{ isLoginMode ? 'Вход в систему' : 'Регистрация' }}</h2>

          <!-- Форма аутентификации -->
          <form @submit.prevent="handleSubmit" class="auth-form">
          
            <div class="input-group" v-if="!isLoginMode">
              <label for="name" class="input-label">Имя</label>
              <input
                type="text"
                id="name"
                v-model="form.name"
                required
                class="input-field"
                placeholder="Ваше имя"
              />
            </div>

          
            <div class="input-group">
              <label for="email" class="input-label">Email</label>
              <input
                type="email"
                id="email"
                v-model="form.email"
                required
                class="input-field"
                :placeholder="isLoginMode ? 'Почта' : 'Ваш email'"
              />
            </div>

         
            <div class="input-group">
              <label for="password" class="input-label">Пароль</label>
              <input
                type="password"
                id="password"
                v-model="form.password"
                required
                class="input-field"
                placeholder="Пароль"
              />
            </div>

         
            <div class="input-group" v-if="!isLoginMode">
              <label for="password_confirmation" class="input-label">Подтверждение пароля</label>
              <input
                type="password"
                id="password_confirmation"
                v-model="form.password_confirmation"
                required
                class="input-field"
                placeholder="Повторите пароль"
              />
            </div>

           
            <button type="submit" class="auth-button" :disabled="loading">
              <span v-if="!loading">{{ isLoginMode ? 'Войти' : 'Зарегистрироваться' }}</span>
              <span v-else>{{ isLoginMode ? 'Вход...' : 'Регистрация...' }}</span>
            </button>

         
            <div v-if="error" class="error-message">
              {{ error }}
            </div>
          </form>

         
          <p class="auth-switch">
            {{ isLoginMode ? 'Нет аккаунта?' : 'Уже зарегистрированы?' }}
            <a href="#" @click.prevent="toggleAuthMode()">{{ isLoginMode ? 'Зарегистрируйтесь!' : 'Войдите!' }}</a>
          </p>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  import { useRouter } from 'vue-router';
  import { useUserStore } from '../Store/user';


  const router = useRouter();
  const userStore = useUserStore();
  const emit = defineEmits(['close']);


  const isLoginMode = ref(true); 
  const form = ref({
    name: '',       
    email: '',      
    password: '', 
    password_confirmation: '' 
  });


  const loading = ref(false);
  const error = ref('');

  
  const toggleAuthMode = () => {
    isLoginMode.value = !isLoginMode.value;
    error.value = ''; 
  };


  const close = () => {
    emit('close');
  };

  
  const handleSubmit = async () => {
    try {
      loading.value = true;
      error.value = '';

    
      const url = isLoginMode.value ? '/api/login' : '/api/register';
      const response = await axios.post(url, form.value);

    
      userStore.token = response.data.token;
      userStore.setUser(response.data.user);

     
      close(); 
      router.push(`/profile/${response.data.user.id}`); 

    } catch (err) {
      if (err.response?.status === 422) {
        error.value = err.response.data.message || 'Пожалуйста, исправьте ошибки в форме';
      } else {
        error.value = err.response?.data?.message || (isLoginMode.value ? 'Неверные учетные данные' : 'Ошибка регистрации');
      }
    } finally {
      loading.value = false;
    }
  };
  </script>

<style scoped>
.field-error {
color: #e53e3e;
font-size: 12px;
margin-top: 4px;
}

.input-field.error {
border-color: #e53e3e;
}
.message-success {
position: fixed;
top: 20px;
left: 50%;
transform: translateX(-50%);
background-color: #4CAF50;
color: white;
padding: 15px;
border-radius: 4px;
z-index: 1001;
animation: fadeIn 0.3s;
}

@keyframes fadeIn {
from { opacity: 0; }
to { opacity: 1; }
}

.fade-enter-active, .fade-leave-active {
transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to {
opacity: 0;
}
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.auth-container {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px;
}

.auth-card {
  position: relative;
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  padding: 40px;
  width: 100%;
  width: 450px;
}

.bi-x-circle {
  position: absolute;
  top: 15px;
  right: 15px;
  cursor: pointer;
  z-index: 10;
}

.auth-title {
  font-size: 28px;
  font-weight: 600;
  color: #2d3748;
  margin-bottom: 24px;
  text-align: center;
}

.auth-form {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.input-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.input-label {
  font-size: 14px;
  font-weight: 500;
  color: #4a5568;
}

.input-field {
  padding: 12px 16px;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  font-size: 16px;
  transition: border-color 0.2s;
}

.input-field:focus {
  outline: none;
  border-color: #4299e1;
}

.auth-button {
  background-color: #4299e1;
  color: white;
  padding: 14px;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s;
  margin-top: 12px;
}

.auth-button:hover {
  background-color: #3182ce;
}

.auth-button:disabled {
  background-color: #bee3f8;
  cursor: not-allowed;
}

.error-message {
  color: #e53e3e;
  font-size: 14px;
  text-align: center;
  padding: 12px;
  background-color: #fff5f5;
  border-radius: 8px;
  margin-top: 8px;
}

.auth-switch {
  text-align: center;
  margin-top: 16px;
  font-size: 14px;
  color: #718096;
}

.auth-switch a {
  color: #4299e1;
  text-decoration: none;
  font-weight: 500;
  margin-left: 5px;
}

.auth-switch a:hover {
  text-decoration: underline;
}
</style>