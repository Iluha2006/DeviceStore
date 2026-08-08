<template>
  <div class="menu-modal" :class="{ 'active': isOpen }">
    <div class="menu-overlay" @click="$emit('close')"></div>
    <div class="menu-content" id="menu-content">
      <div class="close-menu" @click="$emit('close')">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
          <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
        </svg>
      </div>

      <div class="menu-brand">Каталог</div>

      <ul class="list-unstyled">
        <li>
          <span class="menu-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
              <path d="M8 1a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1zm1 13.5a.5.5 0 1 0 1 0 .5.5 0 0 0-1 0m2 0a.5.5 0 1 0 1 0 .5.5 0 0 0-1 0M9.5 1a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM9 3.5a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 0-1h-5a.5.5 0 0 0-.5.5M1.5 2A1.5 1.5 0 0 0 0 3.5v7A1.5 1.5 0 0 0 1.5 12H6v2h-.5a.5.5 0 0 0 0 1H7v-4H1.5a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .5-.5H7V2z"/>
            </svg>
          </span>
          <router-link to="/monitors">Мониторы</router-link>
        </li>
        <li>
          <span class="menu-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
              <path d="M2.5 1A1.5 1.5 0 0 0 1 2.5V11h14V2.5A1.5 1.5 0 0 0 13.5 1h-11zM14 12H2v-.5A1.5 1.5 0 0 0 .5 10V2.5A2.5 2.5 0 0 1 3 0h10a2.5 2.5 0 0 1 2.5 2.5V10a1.5 1.5 0 0 0-1.5 1.5V12zm-3 1.5v-.5H5v.5a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 .5-.5z"/>
            </svg>
          </span>
          <router-link to="/laptops">Ноутбуки</router-link>
        </li>
        <li>
          <span class="menu-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
              <path d="M2.5 13.5A.5.5 0 0 1 3 13h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5M13.991 3l.024.001a1.5 1.5 0 0 1 .538.143.76.76 0 0 1 .302.254c.067.1.145.277.145.602v5.991l-.001.024a1.5 1.5 0 0 1-.143.538.76.76 0 0 1-.254.302c-.1.067-.277.145-.602.145H2.009l-.024-.001a1.5 1.5 0 0 1-.538-.143.76.76 0 0 1-.302-.254C1.078 10.502 1 10.325 1 10V4.009l.001-.024a1.5 1.5 0 0 1 .143-.538.76.76 0 0 1 .254-.302C1.498 3.078 1.675 3 2 3zM14 2H2C0 2 0 4 0 4v6c0 2 2 2 2 2h12c2 0 2-2 2-2V4c0-2-2-2-2-2"/>
            </svg>
          </span>
          <router-link to="/televison">Телевизоры</router-link>
        </li>
        <li>
          <span class="menu-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
              <path d="M9 13c0 1.105-1.12 2-2.5 2S4 14.105 4 13s1.12-2 2.5-2 2.5.895 2.5 2m0-9.75V13H8V3.25zM8 3.25a.75.75 0 0 1 .74-.75h.01c.2 0 .4.07.54.2l3 2.4c.13.1.21.25.21.4v1.5a.75.75 0 0 1-1.5 0V6l-2.5-2V13H8z"/>
            </svg>
          </span>
          <router-link to="/audio">Аудио</router-link>
        </li>
        <li>
          <span class="menu-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
              <path d="M8.296 2.033a.75.75 0 0 0-.496.6 4 4 0 0 1-1.768 2.567l-.775 1.253a2.5 2.5 0 0 1-2.036 1.11H2a1 1 0 0 0-1 1v1.02c0 .41.25.777.632.93l.184.06c.682.222 1.18.825 1.318 1.552l.09.5c.1.552.4 1.045.847 1.37l.617.44c.42.3.716.736.85 1.23l.254.93a1.5 1.5 0 0 0 2.448.728l1.557-1.32a2.8 2.8 0 0 1 3.312-.29l.414.258c.91.567 2.064.42 2.806-.36l.34-.357a1 1 0 0 0-.04-1.43l-1.396-1.283a4 4 0 0 0-3.75-.93 2.5 2.5 0 0 1-1.382-2.198 4 4 0 0 0-.826-2.438l-.688-.84a1.25 1.25 0 0 0-1.842-.11z"/>
            </svg>
          </span>
          <router-link to="/consoles">Консоли</router-link>
        </li>
        <li>
          <span class="menu-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
              <path d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
            </svg>
          </span>
          <router-link to="/phones">Телефоны</router-link>
        </li>
        <li>
          <span class="menu-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
              <path d="M15.384 6.115a.485.485 0 0 0-.047-.736A12.44 12.44 0 0 0 8 3C5.259 3 2.723 3.882.663 5.379a.485.485 0 0 0-.048.736.52.52 0 0 0 .668.05A11.45 11.45 0 0 1 8 4c2.507 0 4.827.802 6.716 2.164.205.148.49.13.668-.049m-2.155 2.156a.482.482 0 0 0-.063-.745A9.46 9.46 0 0 0 8 6c-1.905 0-3.68.56-5.166 1.526a.48.48 0 0 0-.063.745.525.525 0 0 0 .652.065A8.46 8.46 0 0 1 8 7a8.46 8.46 0 0 1 4.576 1.336c.206.132.48.108.653-.065m-2.183 2.183c.226-.226.185-.605-.1-.75A6.5 6.5 0 0 0 8 9c-1.06 0-2.062.254-2.946.704-.285.145-.326.524-.1.75l.015.015c.16.16.407.19.611.09A5.5 5.5 0 0 1 8 10c.868 0 1.69.201 2.42.56.203.1.45.07.61-.091zM9.06 12.44c.196-.196.198-.52-.04-.66A2 2 0 0 0 8 11.5a2 2 0 0 0-1.02.28c-.238.14-.236.464-.04.66l.706.706a.5.5 0 0 0 .707 0l.707-.707z"/>
            </svg>
          </span>
          <router-link to="/networkequipment">Сетевое оборудование</router-link>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
defineProps({
  isOpen: Boolean
})
</script>

<style scoped>
.menu-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: var(--dm-z-modal);
  display: none;
}

.menu-modal.active {
  display: block;
}

.menu-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(16, 24, 40, 0.55);
  backdrop-filter: blur(2px);
}

.menu-content {
  position: absolute;
  top: 0;
  left: 0;
  width: 300px;
  height: 100%;
  background-color: var(--dm-surface);
  padding: var(--dm-space-5);
  transform: translateX(-100%);
  transition: transform 0.3s ease;
  box-shadow: var(--dm-shadow-lg);
  overflow-y: auto;
}

.menu-modal.active .menu-content {
  transform: translateX(0);
}

.close-menu {
  position: absolute;
  top: var(--dm-space-4);
  right: var(--dm-space-4);
  cursor: pointer;
  color: var(--dm-text-muted);
  transition: color 0.15s ease;
}

.close-menu:hover {
  color: var(--dm-text);
}

.menu-brand {
  margin: 0 0 var(--dm-space-4);
  padding: var(--dm-space-2) 0 var(--dm-space-3);
  border-bottom: 1px solid var(--dm-border);
  font-size: var(--dm-text-xl);
  font-weight: 800;
  letter-spacing: -0.01em;
  color: var(--dm-text);
}

.list-unstyled {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
}

.list-unstyled li {
  display: flex;
  align-items: center;
  gap: var(--dm-space-3);
  cursor: pointer;
  padding: var(--dm-space-3) var(--dm-space-2);
  border-bottom: 1px solid var(--dm-border);
  border-radius: var(--dm-radius-md);
  transition: background-color 0.15s ease;
}

.list-unstyled li:hover {
  background: var(--dm-surface-muted);
}

.menu-icon {
  flex-shrink: 0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: var(--dm-radius-sm);
  background: var(--dm-primary-soft);
  color: var(--dm-primary);
}

.list-unstyled li a {
  text-decoration: none;
  color: var(--dm-text);
  font-size: var(--dm-text-md);
  font-weight: 600;
  white-space: nowrap;
  transition: color 0.15s ease;
}

.list-unstyled li:hover a {
  color: var(--dm-primary);
}
</style>
