import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            colors: {
                primary: "#2D9CDB",
                success: "#27AE60",
                warning: "#F2994A",
                light: "#F9FAFB",
                dark: "#1E293B",
                important: "#111827",
                accent: "#6B7280",
            },
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                montserrat: ["Montserrat", "sans-serif"],
                nunito: ["Nunito", "sans-serif"],
            },
        },
    },

    plugins: [forms, typography],
};
