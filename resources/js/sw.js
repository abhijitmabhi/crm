import "workbox-sw/build/workbox-sw";

// Not used right now
const staticCache = new workbox.strategies.CacheFirst({
    cacheName: "static-cache",
    plugins: [
        // Safe only 20 Entries to avoid flooding the cache and remember it for a week
        new workbox.expiration.ExpirationPlugin({
            maxEntries: 20,
            maxAgeSeconds: 7 * 24 * 60 * 60,
        }),
    ],
});

const syncLeads = new workbox.strategies.NetworkOnly({
    plugins: [new workbox.backgroundSync.BackgroundSyncPlugin("leads", {})],
});

function registerRoutes() {
    workbox.routing.registerRoute(
        /\/css\/app\.(js|css)/,
        new workbox.strategies.NetworkFirst("assets")
    );

    workbox.routing.registerRoute(
        /\/(img|storage)\//,
        new workbox.strategies.NetworkFirst("userAssets")
    );

    workbox.routing.registerRoute(/\/api\//, syncLeads, "POST");

    workbox.routing.registerRoute(/\/experts\//, new workbox.strategies.NetworkFirst("experts"));
}

// registerRoutes();
