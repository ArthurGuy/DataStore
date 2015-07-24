require('serviceworker-cache-polyfill');

console.log("SW startup");

var versions = require('./../versions');

var CACHE_NAME = 'dashboard-' + versions['dashboard'];

// The files we want to cache
var urlsToCache = [
    '/dashboard/1',
    '/js/dashboard.js',
    '/css/dashboard.css',
    '/fonts/glyphicons-regular.woff2',
    '/fonts/glyphicons-halflings-regular.woff2',
    '/dashboard/manifest.json'
];

// Set the callback for the install step
self.oninstall = function(event) {

    console.log('SW Installing');

    //https://developer.mozilla.org/en-US/docs/Web/API/ServiceWorkerGlobalScope/skipWaiting
    self.skipWaiting();

    //Turn all the urls into requests
    var requests = urlsToCache.map(function(url) {
        return new Request(url);
    });


    //Fetch all the requests and when that's done add the items to the cache
    Promise.all(
        requests.map(function(request) {
            console.log("Looking up ", request);
            return fetch(request.clone(), {credentials: 'include'});
        })
    ).then(function(responses) {
            console.log("Saving data to cache", CACHE_NAME);
            caches.open(CACHE_NAME).then(function(cache) {
                return Promise.all(
                        responses.map(function (response, i) {
                            return cache.put(requests[i], response);
                        })
                ).then(function() {
                        console.log("Data cached");
                        return true;
                    })
            });
    });

};


self.onactivate = function(event) {

    console.log("SW activated");

    //https://developer.mozilla.org/en-US/docs/Web/API/Clients/claim
    if (self.clients && clients.claim) {
        clients.claim();
    }

    var cacheWhitelist = [CACHE_NAME];

    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames.map(function(cacheName) {
                    if (cacheWhitelist.indexOf(cacheName) == -1) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
};


self.addEventListener('fetch', function(event) {

    var requestURL = new URL(event.request.url);

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
                                    //console.log("Saving to cache", event.request.url);
                                    //cache.put(event.request, cacheResponse);
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

/*
    Listen and respond to server push notifications
 */
self.addEventListener('push', function(event) {
    console.log('Received a push message', event);

    event.waitUntil(
        fetch('/api/notification').then(function(response) {
            if (response.status !== 200) {
                // Either show a message to the user explaining the error
                // or enter a generic message and handle the
                // onnotificationclick event to direct the user to a web page
                console.log('Looks like there was a problem. Status Code: ' + response.status);
                throw new Error();
            }

            // Examine the text in the response
            return response.json().then(function(data) {

                var title           = data.title;
                var message         = data.message;
                var icon            = data.icon;
                var notificationTag = data.tag;

                return self.registration.showNotification(title, {
                    body: message,
                    icon: icon,
                    tag: notificationTag,
                    data: {
                        path: '/dashboard/1',
                        foo:'bar'
                    }
                });
            });
        }).catch(function(err) {
            console.error('Unable to retrieve data', err);

            var title = 'An error occurred';
            var message = 'We were unable to get the information for this push message';
            var icon = '/images/icon-192x192.png';
            var notificationTag = 'notification-error';
            return self.registration.showNotification(title, {
                body: message,
                icon: icon,
                tag: notificationTag,
                data: {
                    path: '/dashboard/1',
                    foo:'bar'
                }
            });
        })
    );

    /*
    var title = 'Yay a message.';
    var body = 'We have received a push message.';
    var icon = '/images/icon-192x192.png';
    var tag = 'simple-push-demo-notification-tag';

    event.waitUntil(
        self.registration.showNotification(title, {
            body: body,
            icon: icon,
            tag: tag,
            data: {
                path: '/dashboard/1',
                foo:'bar'
            }
        })
    );
    */
});

/*
    The user has clicked the notification
 */
self.addEventListener('notificationclick', function(event) {
    console.log('On notification click: ', event.notification.tag);
    // Android doesn't close the notification when you click on it
    // See: http://crbug.com/463146
    event.notification.close();

    // This looks to see if the current window is already open and
    // focuses if it is
    event.waitUntil(
        clients.matchAll({
            type: "window"
        })
            .then(function(clientList) {
                for (var i = 0; i < clientList.length; i++) {
                    var client = clientList[i];
                    if (client.url == event.notification.data.path && 'focus' in client)
                        return client.focus();
                }
                if (clients.openWindow) {
                    return clients.openWindow(event.notification.data.path);
                }
            })
    );
});

function apiResponse(request) {
    console.log('Caught an API Call', request.url);
    return fetch(request, {credentials: 'include'}).then(function(response) {

        //cache.put(request, response.clone());

        return response;
    }).catch(function(error) {
        console.log("Service Worker: API Fetch error");
    });
}

