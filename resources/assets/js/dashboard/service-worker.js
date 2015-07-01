require('serviceworker-cache-polyfill');

console.log("SW startup");

var CACHE_NAME = 'my-site-cache-v1';

// The files we want to cache
var urlsToCache = [
    //'/dashboard/1',   //cant fetch protected assets at this stage
    paths.css,
    '/js/dashboard.js',
    '/css/dashboard.css',
    '/fonts/glyphicons-regular.woff2'
];

// Set the callback for the install step
self.addEventListener('install', function(event) {
    console.log('SW Installing');

    caches.delete(CACHE_NAME);

    // Perform install steps
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function(cache) {
                console.log('Opened cache, caching files', urlsToCache);
                return cache.addAll(urlsToCache);
            })
    );
});


self.addEventListener('activate', function(event) {
    console.log("SW activated");

    //delete the old file cache
    caches.delete(CACHE_NAME);

    //if assets have changed send a message to the ui saying a refresh is needed

    //Go through all our caches and delete caches not in the whitelist
    var cacheWhitelist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames.map(function(cacheName) {
                    if (cacheWhitelist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});


self.addEventListener('fetch', function(event) {
    console.log("Caught a fetch!", event.request.url);

    var requestURL = new URL(event.request.url);
    //console.log(requestURL);

    if (requestURL.pathname.indexOf('/api/') === 0) {
        event.respondWith(apiResponse(event.request));
    } else {

        event.respondWith(
            caches.match(event.request, {ignoreVary: true})
                .then(function (response) {
                    // Cache hit - return response
                    if (response) {
                        console.log("Cache Hit", event.request.url);
                        return response;
                    }

                    // IMPORTANT: Clone the request. A request is a stream and
                    // can only be consumed once. Since we are consuming this
                    // once by cache and once by the browser for fetch, we need
                    // to clone the response
                    var fetchRequest = event.request.clone();

                    return fetch(fetchRequest, {credentials: 'include'}).then(
                        function (response) {
                            // Check if we received a valid response
                            if (!response || response.status !== 200 || response.type !== 'basic') {
                                return response;
                            }

                            var cacheResponse = response.clone();
                            caches.open(CACHE_NAME)
                                .then(function (cache) {
                                    console.log("Saving to cache", event.request.url);
                                    cache.put(event.request, cacheResponse);
                                });

                            return response;
                        }
                    );

                    //return fetch(event.request, {credentials: 'include'});
                }
            )
        );
    }
});

function apiResponse(request) {
    console.log('API Call');
    return fetch(request, {credentials: 'include'}).then(function(response) {

        //cache.put(request, response.clone());

        return response;
    }).catch(function(error) {
        console.log("Service Worker: API Fetch error");
    });
}