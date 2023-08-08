const cacheName = 'pwa-btbox-v1';
const staticAssets = [
	'./',
	'./app.js'
];
//'./login.html',

//self.addEventListener('install', async event => {
//	console.log('install event');
//	const cache = await caches.open(cacheName); 
//	await cache.addAll(staticAssets);
//});

self.addEventListener('fetch', event => {
	// console.log('fetch event');
	const req = event.request;
	event.respondWith(cacheFirst(req));
});

async function cacheFirst(req) {
  const cache = await caches.open(cacheName); 
  const cachedResponse = await cache.match(req); 
  return cachedResponse || fetch(req); 
}