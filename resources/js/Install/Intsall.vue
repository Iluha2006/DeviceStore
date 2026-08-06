<template>

    <div v-if="showInstallButton" class="install-prompt">
      <div class="install-content">
        <h3>Установить приложение!</h3>
        <div class="install-buttons">

          <button @click="installPWA" class="button">Установить</button>
        </div>
      </div>
    </div>
  </template>

  <script>
  import { ref, onMounted, onBeforeUnmount } from 'vue';

  export default {
    setup() {
      const installPrompt = ref(null);
      const showInstallButton = ref(false);


      function savePrompt(event) {
        event.preventDefault();
        installPrompt.value = event;
        showInstallButton.value = true;

      }


      async function installPWA() {
        if (installPrompt.value) {
          try {
            await installPrompt.value.prompt();
            await installPrompt.value.userChoice;
          } catch (error) {
            console.error('Ошибка при вызове prompt:', error);
          } finally {

            showInstallButton.value = false;
            installPrompt.value = null;
          }
        }
      }


      function handleAppInstalled() {

        showInstallButton.value = false;
      }

      onMounted(() => {

        if (window.matchMedia('(display-mode: standalone)').matches || navigator.standalone) {
          showInstallButton.value = false;
          return;
        }


        window.addEventListener('beforeinstallprompt', savePrompt);
        window.addEventListener('appinstalled', handleAppInstalled);
      });

      onBeforeUnmount(() => {
        window.removeEventListener('beforeinstallprompt', savePrompt);
        window.removeEventListener('appinstalled', handleAppInstalled);
      });

      return {
        showInstallButton,
        installPWA,
      };
    },
  };
  </script>

  <style scoped>
  .install-prompt {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: white;
    border: 2px solid #000;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    max-width: 300px;
  }

  .install-content {
    text-align: center;
  }

  .install-buttons {
    margin-top: 10px;
  }

  .button {
    background-color: #000;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
  }

  .button:hover {
    background-color: #333;
  }
  </style>
