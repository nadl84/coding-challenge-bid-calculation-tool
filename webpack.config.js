const Encore = require('@symfony/webpack-encore');
const fs = require('fs');
const webpack = require('webpack');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.js')
    .enableVueLoader()
    .enableSingleRuntimeChunk()
    .enablePostCssLoader()
    .splitEntryChunks()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enableSassLoader()
    .configureDevServerOptions(options => {
        options.https = {
            key: fs.readFileSync('/app/infrastructure/ssl/key.pem'),
            cert: fs.readFileSync('/app/infrastructure/ssl/cert.pem'),
        };
        options.host = '0.0.0.0';
        options.port = process.env.FRONTEND_PORT || 3443;
    })
    .addPlugin(new webpack.DefinePlugin({
        __VUE_OPTIONS_API__: true,
        __VUE_PROD_DEVTOOLS__: false,
        __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: false
    }))
;

module.exports = Encore.getWebpackConfig(); 