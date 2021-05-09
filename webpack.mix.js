const mix = require("laravel-mix");
const resolve = require("path").resolve;

const ImageminPlugin = require("imagemin-webpack-plugin").default;
require("laravel-mix-purgecss");

const jspath = resolve(__dirname, "resources/js");
const componentPath = resolve(jspath, "components");

mix.webpackConfig({
    resolve: {
        alias: {
            "@": resolve(__dirname, "resources"),
            "@admin": resolve(componentPath, "admin"),
            "@business": resolve(componentPath, "business"),
            "@callcenter": resolve(componentPath, "callcenter"),
            "@components": componentPath,
            "@customer": resolve(componentPath, "customer"),
            "@experts": resolve(componentPath, "experts"),
            "@general": resolve(componentPath, "general"),
            "@js": jspath,
            "@LLI": resolve(componentPath, "LLI"),
            "@utils": resolve(jspath, "utils"),
            "@metadata": resolve(jspath, "metadata"),
        },
    },
    plugins: [
        new ImageminPlugin({
            //            disable: process.env.NODE_ENV !== 'production',  Disable during development
            pngquant: {
                quality: "70-80",
            },
            test: /\.(jpe?g|png|gif|svg)$/i,
        }),
    ],
})
    .copy("resources/img", "public/img", false)
    .copy("resources/js/manifest.json", "public/js")
    .js("resources/js/app.js", "public/js")
    .js("resources/js/init_worker.js", "public/js")
    .js("resources/js/sw.js", "public")
    .sass("resources/sass/app.scss", "public/css")
    .sourceMaps(false)
    .vue();

if (mix.inProduction() || process.env.npm_lifecycle_event !== "test") {
    mix.version();
}
