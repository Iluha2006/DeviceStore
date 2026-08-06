const cacheName = 'electronics-shop-v3';

const urlsToCache = [
   '/',
   '/css/app.css',
    '/js/app.js',
    '/store.png',
    '/manifest.json'
];


self.addEventListener('install', async event => {
   const cash = await caches.open(cacheName);
   await cash.addAll(urlsToCache)
});


self.addEventListener('activate', async event => {
    event.waitUntil(
      caches.keys().then(cacheNames => {
        return Promise.all(
          cacheNames.map(cache => {
            if (cache !== cacheName) {
              return caches.delete(cache);
            }
          })
        );
      })
    );
  });





  self.addEventListener('fetch', event => {
    console.log('Fetching:', event.request.url);
    event.respondWith(
      caches.match(event.request)
        .then(response => {
          if (response) {
            console.log('Кеш найден:', event.request.url);
            return response;
          }
          console.log('Нет кеша, fetch:', event.request.url);
          return fetch(event.request);
        })
    );
  });