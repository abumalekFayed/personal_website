const mix = require('laravel-mix');
const VuetifyLoaderPlugin = require("vuetify-loader/lib/plugin");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.options({
   
    hmrOptions: {
        host: "naeb.test",
        port: 3000,
    },
});
if (mix.inProduction()) mix.version();

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css');

mix.webpackConfig({ 
        // module: {
        //     rules: [{
        //         test: /\.js?$/,
        //         use: [{
        //             loader: "babel-loader",
        //             options: mix.config.babel(),
        //         }, ],
        //         // enforce: 'pre',
        //         // exclude: /node_modules/,
        //         // loader: 'eslint-loader',
        //         // test: /\.(js|vue)?$/
        //     }, ],
        // },
        output: {
            chunkFilename: mix.inProduction() ?
                "js/chunks/[name].js?id=[chunkhash]" : "js/chunks/[name].js",
        },
        plugins: [
            new VuetifyLoaderPlugin(),
            // new webpack.DefinePlugin({
            //     "process.env.NODE_ENV": JSON.stringify("production")
            // }),
           // 
            // new webpack.IgnorePlugin({
            //     resourceRegExp: /^\.\/locale$/,
            //     contextRegExp: /moment$/,
            // }),
    
            // new CompressionPlugin(),
        ],
    });
