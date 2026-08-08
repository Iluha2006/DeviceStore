<template>
  <footer class="dm-footer">
    <div class="dm-footer-inner">
      <div class="dm-footer-grid">
        <div class="dm-footer-brand">
          <div class="dm-logo dm-footer-logo">
            <span class="dm-logo-mark">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
              </svg>
            </span>
            <span>
              <span class="dm-logo-name dm-footer-logo-name">DeviceStore</span>
              <span class="dm-footer-tagline">Электроника со скидкой</span>
            </span>
          </div>
          <p class="dm-footer-about">
            Интернет-магазин электроники и цифровой техники. Только оригинальная продукция, честные цены и быстрая доставка.
          </p>
        </div>

        <div class="dm-footer-col">
          <h4 class="dm-footer-title">Каталог</h4>
          <ul class="dm-footer-links">
            <li><router-link to="/monitors">Мониторы</router-link></li>
            <li><router-link to="/laptops">Ноутбуки</router-link></li>
            <li><router-link to="/phones">Телефоны</router-link></li>
            <li><router-link to="/televison">Телевизоры</router-link></li>
            <li><router-link to="/audio">Аудио</router-link></li>
            <li><router-link to="/consoles">Консоли</router-link></li>
          </ul>
        </div>

        <div class="dm-footer-col">
          <h4 class="dm-footer-title">Покупателям</h4>
          <ul class="dm-footer-links">
            <li><router-link to="/favorites">Избранное</router-link></li>
            <li><router-link to="/cartproduct">Корзина</router-link></li>
            <li><router-link to="/checkout">Оформление заказа</router-link></li>
          </ul>
        </div>

        <div class="dm-footer-col">
          <h4 class="dm-footer-title">Контакты</h4>
          <ul class="dm-footer-contacts">
            <li>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M6.354 5.5H4a3.5 3.5 0 0 0 0 7h3a3.5 3.5 0 0 0 2-6.435V9.5c-.715.446-1.775.102-2.946-.062zM13.5 3h-3a3.5 3.5 0 0 0-2 6.435V6.623A3.5 3.5 0 0 1 10.5 4H13.5a1.5 1.5 0 0 1 1.5 1.5v7a1.5 1.5 0 0 1-1.5 1.5h-3a3.5 3.5 0 0 1-2-6.435V9.5c.715.446 1.775.102 2.946-.062z"/>
              </svg>
              support@devicestore.ru
            </li>
            <li>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.75 1.75 0 0 1-1.657-.459L5.482 8.062a1.75 1.75 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877Z"/>
              </svg>
              8 800 000-00-00
            </li>
            <li>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/>
              </svg>
              г. Москва, ул. Технологическая, 1
            </li>
          </ul>
        </div>
      </div>

      <div class="dm-footer-bottom">
        <span>© {{ currentYear }} DeviceStore. Все права защищены.</span>
        <div class="dm-footer-payments">
          <span>VISA</span>
          <span>MasterCard</span>
          <span>МИР</span>
          <span>СБП</span>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
const currentYear = new Date().getFullYear();
</script>

<style scoped>
.dm-footer {
  margin-top: auto;
  background: var(--dm-text);
  color: #c7ccd6;
}

.dm-footer-inner {
  max-width: var(--dm-content-width);
  margin: 0 auto;
  padding: var(--dm-space-12) var(--dm-space-4) var(--dm-space-6);
}

.dm-footer-grid {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1.4fr;
  gap: var(--dm-space-8);
}

.dm-footer-logo {
  color: #ffffff;
}

.dm-footer-logo-name {
  color: #ffffff;
}

.dm-footer-tagline {
  display: block;
  font-size: var(--dm-text-xs);
  color: #8b93a1;
}

.dm-footer-about {
  margin: var(--dm-space-4) 0 0;
  max-width: 300px;
  font-size: var(--dm-text-base);
  line-height: 1.6;
  color: #9aa3b0;
}

.dm-footer-title {
  margin: 0 0 var(--dm-space-4);
  font-size: var(--dm-text-md);
  font-weight: 700;
  color: #ffffff;
}

.dm-footer-links {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: var(--dm-space-2);
}

.dm-footer-links a {
  color: #9aa3b0;
  text-decoration: none;
  font-size: var(--dm-text-base);
  transition: color 0.15s ease;
}

.dm-footer-links a:hover {
  color: #ffffff;
}

.dm-footer-contacts {
  list-style: none;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: var(--dm-space-3);
}

.dm-footer-contacts li {
  display: flex;
  align-items: center;
  gap: var(--dm-space-2);
  font-size: var(--dm-text-base);
  color: #9aa3b0;
}

.dm-footer-contacts svg {
  flex-shrink: 0;
  color: var(--dm-primary-hover);
}

.dm-footer-bottom {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: var(--dm-space-4);
  flex-wrap: wrap;
  margin-top: var(--dm-space-8);
  padding-top: var(--dm-space-5);
  border-top: 1px solid rgba(255, 255, 255, 0.12);
  font-size: var(--dm-text-sm);
  color: #8b93a1;
}

.dm-footer-payments {
  display: flex;
  gap: var(--dm-space-2);
}

.dm-footer-payments span {
  padding: 4px 10px;
  font-size: var(--dm-text-xs);
  font-weight: 700;
  letter-spacing: 0.04em;
  color: #c7ccd6;
  background: rgba(255, 255, 255, 0.08);
  border-radius: var(--dm-radius-sm);
}

@media (max-width: 860px) {
  .dm-footer-grid {
    grid-template-columns: 1fr 1fr;
    gap: var(--dm-space-6);
  }
}

@media (max-width: 520px) {
  .dm-footer-grid {
    grid-template-columns: 1fr;
  }
}
</style>
