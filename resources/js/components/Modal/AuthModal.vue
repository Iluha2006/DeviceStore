<template>
    <div class="auth-modal" v-if="show">
      <div class="auth-modal-content">
        <h3>Требуется авторизация</h3>
        <p>Для доступа к корзине необходимо войти в систему</p>
        <div class="auth-modal-buttons">
          <button @click="closeModal">Закрыть</button>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { defineProps, defineEmits } from 'vue';
  import { useRouter } from 'vue-router';

  const router = useRouter();
  const props = defineProps(['show']);
  const emit = defineEmits(['close']);

  const closeModal = () => {
    emit('close');
  };
  </script>

  <style scoped>
  .auth-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
  }

  .auth-modal-content {
    background: white;
    padding: 20px;
    border-radius: 8px;
    max-width: 400px;
    width: 100%;
    text-align: center;
  }

  .auth-modal-buttons {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 20px;
  }

  .auth-modal-buttons button {
    padding: 8px 16px;
    cursor: pointer;
  }
  </style>