<template>
    <div class="auth-modal-overlay">
      <div class="auth-modal">
        <div class="auth-modal__icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
            <line x1="12" y1="9" x2="12" y2="13"></line>
            <line x1="12" y1="17" x2="12.01" y2="17"></line>
          </svg>
        </div>
        <h2 class="auth-modal__title">Требуется авторизация</h2>
        <p class="auth-modal__text">Для доступа к этой странице необходимо войти в систему.</p>
        <button class="auth-modal__button" @click="redirectToLogin">
          Главная
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
            <polyline points="10 17 15 12 10 7"></polyline>
            <line x1="15" y1="12" x2="3" y2="12"></line>
          </svg>
        </button>
      </div>
    </div>
  </template>

  <script setup>
  import { useRouter } from 'vue-router';

  const router = useRouter();

  const redirectToLogin = () => {
    router.push('/');
  };
  </script>

  <style scoped>
  .auth-modal-overlay {
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
    animation: fadeIn 0.3s ease-out;
  }

  .auth-modal {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    width: 90%;
    max-width: 400px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    text-align: center;
    transform: translateY(0);
    animation: slideUp 0.4s ease-out;
  }

  .auth-modal__icon {
    margin-bottom: 1.5rem;
    color: #f59e0b;
  }

  .auth-modal__icon svg {
    display: inline-block;
    vertical-align: middle;
  }

  .auth-modal__title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.75rem;
  }

  .auth-modal__text {
    color: #6b7280;
    margin-bottom: 1.5rem;
    line-height: 1.5;
  }

  .auth-modal__button {
    background-color: #3b82f6;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .auth-modal__button:hover {
    background-color: #2563eb;
    transform: translateY(-1px);
  }

  .auth-modal__button:active {
    transform: translateY(0);
  }

  .auth-modal__button svg {
    transition: transform 0.2s ease;
  }

  .auth-modal__button:hover svg {
    transform: translateX(3px);
  }

  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }

  @keyframes slideUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @media (max-width: 480px) {
    .auth-modal {
      padding: 1.5rem;
      width: 95%;
    }

    .auth-modal__title {
      font-size: 1.25rem;
    }
  }
  </style>