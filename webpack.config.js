var Encore = require('@symfony/webpack-encore');
var ExtractTextPlugin = require("extract-text-webpack-plugin");


Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
    .addEntry('js/app', './assets/js/app.js')
    .addStyleEntry('css/app', './assets/scss/app.scss')

    // uncomment if you use Sass/SCSS files
    .enableSassLoader()

    // uncomment for legacy applications that require $/jQuery as a global variable
    .autoProvidejQuery()
;

const firsetConfig = Encore.getWebpackConfig();

Encore.reset();

Encore
// the project directory where compiled assets will be stored
    .setOutputPath('public/build/frontend')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build/frontend')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
    .addEntry('app', './assets/frontend/ui-dev/app.js')
    .addPlugin(new ExtractTextPlugin('bundle.css'))

    // uncomment if you use Sass/SCSS files
    .enableSassLoader()

    // uncomment for legacy applications that require $/jQuery as a global variable
    .autoProvidejQuery()
;

const secondConfig = Encore.getWebpackConfig();

module.exports = [firsetConfig, secondConfig];