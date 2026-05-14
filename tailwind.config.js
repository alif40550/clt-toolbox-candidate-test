import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
                serif: ['Georgia', 'serif'],
                mono: ['ui-monospace', 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', 'monospace']
            },
            colors: {
                brand: {
                    50: '#f2f8f5',
                    100: '#e1efe6',
                    500: '#3e7c5b',
                    600: '#2f6348',
                    900: '#1a3b2b',
                },
                wood: {
                    light: '#e6d5c1', 
                    dark: '#c29e75',
                },
                surface: '#f8f9fa',
            }
        }
    },

    plugins: [forms],
};
