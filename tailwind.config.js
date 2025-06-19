import defaultTheme from 'tailwindcss/defaultTheme';
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                primary: '#635000',
                secondary: {
                    light: '#2B5894',
                    default: '#012E6B'
                }
            },
        },
    },
};
