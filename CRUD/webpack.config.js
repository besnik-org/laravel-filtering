let builder = require("bundle-master")

module.exports = builder
    .vue('resources/js/vue/app.js', 'public/assets/app.js')
    .postCss('resources/js/vue/index.css', 'public/assets/app.css')
    .init();
