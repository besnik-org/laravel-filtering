/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/js/vue/**/*.{vue,js,ts,jsx,tsx}'
    ],
    theme: {
        extend: {},
    },
    plugins: [require('@tailwindcss/forms')],
}

