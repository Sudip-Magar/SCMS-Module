/** @type {import('tailwindcss').Config} */

module.exports = {
    darkMode: 'class', // ✅ enables class-based dark mode
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require('tailwind-scrollbar'),
    ],
};
