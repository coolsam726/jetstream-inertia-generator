const path = require('path');
require('dotenv').config();

module.exports = {
    resolve: {
        alias: {
            '@': path.resolve('resources/js'),
        },
    },
    output: {
        chunkFilename: `js/chunks/[name].js?id=[chunkhash]`,
        filename: "[name].js?id=[chunkhash]",
        publicPath: `/${process.env.MIX_APP_URI ? process.env.MIX_APP_URI+'/' : ''}`
    }
};
