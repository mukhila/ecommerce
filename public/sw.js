const CACHE_NAME = 'jangokids-v1';

const PRECACHE = [
  '/',
  '/frontassets/css/vendors/bootstrap.css',
  '/frontassets/css/newui.css',
  '/frontassets/css/custom.css',
  '/frontassets/js/jquery-3.3.1.min.js',
  '/frontassets/js/bootstrap.bundle.min.js',
  '/frontassets/images/manifest.json',
];

// ── Install: pre-cache shell assets ──────────────────
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => cache.addAll(PRECACHE))
  );
  self.skipWaiting();
});

// ── Activate: purge old caches ────────────────────────
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys =>
      Promise.all(
        keys.filter(key => key !== CACHE_NAME).map(key => caches.delete(key))
      )
    )
  );
  self.clients.claim();
});

// ── Fetch strategy ─────────────────────────────────────
//   Static assets (CSS/JS/images/fonts): cache-first
//   Navigation/HTML:                      network-first with offline fallback
self.addEventListener('fetch', event => {
  const { request } = event;

  // Skip non-GET and cross-origin requests
  if (request.method !== 'GET') return;
  if (!request.url.startsWith(self.location.origin)) return;

  const url = new URL(request.url);

  // Cache-first for static assets
  const isStatic = /\.(css|js|woff2?|ttf|eot|png|jpg|jpeg|gif|svg|ico|webp)(\?.*)?$/.test(url.pathname);

  if (isStatic) {
    event.respondWith(
      caches.match(request).then(cached => {
        if (cached) return cached;
        return fetch(request).then(resp => {
          if (resp.ok) {
            const clone = resp.clone();
            caches.open(CACHE_NAME).then(cache => cache.put(request, clone));
          }
          return resp;
        });
      })
    );
    return;
  }

  // Network-first for pages
  event.respondWith(
    fetch(request)
      .then(resp => {
        if (resp.ok && request.headers.get('Accept')?.includes('text/html')) {
          const clone = resp.clone();
          caches.open(CACHE_NAME).then(cache => cache.put(request, clone));
        }
        return resp;
      })
      .catch(() => caches.match(request).then(cached => cached || caches.match('/')))
  );
});
