<template>
    <transition name="fade">
      <div v-if="visible" class="message-success">
        {{ message }}
      </div>
    </transition>
  </template>

  <script>
  import { ref } from 'vue'

  export default {
    setup() {
      const visible = ref(false)
      const message = ref('')

      const showMessage = (msg) => {
        message.value = msg
        visible.value = true
        setTimeout(() => {
          visible.value = false
        }, 1300)
      }

      return {
        visible,
        message,
        showMessage
      }
    }
  }
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

  .fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s;
  }
  .fade-enter-from, .fade-leave-to {
    opacity: 0;
  }
  </style>