<template>
    <div class="not-found-page">
      <div class="stars"></div>
      <div class="twinkling"></div>

      <div class="not-found-content">
        <div class="astronaut">
          <img src="https://assets.codepen.io/1538474/astronaut.svg" >
        </div>

        <div class="message">
          <h1>404</h1>
          <h2>Страницы не существует</h2>
          <p>Похоже, страница, которую вы ищете, была перемещена, удалена или никогда не существовала.</p>
          <router-link to="/" class="home-button">
            <span>Вернуться на главную</span>
            <svg width="13px" height="10px" viewBox="0 0 13 10">
              <path d="M1,5 L11,5"></path>
              <polyline points="8 1 12 5 8 9"></polyline>
            </svg>
          </router-link>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  </script>

  <style scoped>
  .not-found-page {
    position: relative;
    height: 100vh;
    width: 100vw;
    overflow: hidden;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    color: white;
    font-family: 'Arial', sans-serif;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .stars {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><circle cx="10" cy="10" r="0.5" fill="white"/><circle cx="20" cy="30" r="0.5" fill="white"/><circle cx="30" cy="70" r="0.5" fill="white"/><circle cx="40" cy="40" r="0.5" fill="white"/><circle cx="50" cy="90" r="0.5" fill="white"/><circle cx="60" cy="10" r="0.5" fill="white"/><circle cx="70" cy="60" r="0.5" fill="white"/><circle cx="80" cy="20" r="0.5" fill="white"/><circle cx="90" cy="50" r="0.5" fill="white"/></svg>');
    animation: animStar 50s linear infinite;
  }

  .twinkling {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: transparent url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><circle cx="15" cy="15" r="0.3" fill="white" opacity="0.5"/><circle cx="25" cy="35" r="0.3" fill="white" opacity="0.5"/><circle cx="35" cy="75" r="0.3" fill="white" opacity="0.5"/><circle cx="45" cy="45" r="0.3" fill="white" opacity="0.5"/><circle cx="55" cy="95" r="0.3" fill="white" opacity="0.5"/><circle cx="65" cy="15" r="0.3" fill="white" opacity="0.5"/><circle cx="75" cy="65" r="0.3" fill="white" opacity="0.5"/><circle cx="85" cy="25" r="0.3" fill="white" opacity="0.5"/><circle cx="95" cy="55" r="0.3" fill="white" opacity="0.5"/></svg>');
    animation: animStar 100s linear infinite;
    opacity: 0.5;
  }

  /* Контент */
  .not-found-content {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    max-width: 1200px;
    padding: 0 20px;
  }

  .astronaut {
    flex: 1;
    animation: float 6s ease-in-out infinite;
  }

  .astronaut img {
    max-width: 100%;
    height: auto;
  }

  .message {
    flex: 1;
    padding-left: 50px;
  }

  .message h1 {
    font-size: 100px;
    margin: 0;
    color: #4cc9f0;
    text-shadow: 0 0 10px rgba(76, 201, 240, 0.5);
  }

  .message h2 {
    font-size: 36px;
    margin: 10px 0 20px;
    color: #f72585;
  }

  .message p {
    font-size: 18px;
    line-height: 1.6;
    margin-bottom: 30px;
    max-width: 500px;
  }

  .home-button {
    display: inline-flex;
    align-items: center;
    padding: 12px 24px;
    background: linear-gradient(45deg, #f72585 0%, #7209b7 100%);
    color: white;
    text-decoration: none;
    border-radius: 50px;
    font-weight: bold;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(114, 9, 183, 0.4);
  }

  .home-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(114, 9, 183, 0.6);
  }

  .home-button svg {
    margin-left: 10px;
    fill: none;
    stroke: white;
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
    transition: all 0.3s ease;
  }

  .home-button:hover svg {
    transform: translateX(5px);
  }


  @keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
    100% { transform: translateY(0px); }
  }

  @keyframes animStar {
    from { transform: translateY(0px); }
    to { transform: translateY(-1000px); }
  }
  @media (max-width: 768px) {
    .not-found-content {
      flex-direction: column;
      text-align: center;
    }

    .message {
      padding-left: 0;
      margin-top: 30px;
    }

    .message h1 {
      font-size: 80px;
    }

    .message h2 {
      font-size: 28px;
    }

    .astronaut {
      max-width: 300px;
    }
  }
  </style>