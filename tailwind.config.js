const defaultTheme = require("tailwindcss/defaultTheme");

/** @type {import("tailwindcss").Config} */
module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            animation: {
                "bounce-delay": "bounce-delay 1.4s infinite ease-in-out both",
                "shake-horizontal": "shake-horizontal 0.82s ease-in-out both",
            },
            fontFamily: {
                sans: ["Nunito", ...defaultTheme.fontFamily.sans],
            },
            keyframes: {
                "bounce-delay": {
                    "0%, 80%, 100%": { transform: "scale(0)" },
                    "40%": { transform: "scale(1.0)" },
                },
                "shake-horizontal": {
                    "0%, 100%": { transform: "translateX(0)" },
                    "10%, 30%, 50%, 70%, 90%": {
                        transform: "translateX(-10px)",
                    },
                    "20%, 40%, 60%, 80%": { transform: "translateX(10px)" },
                },
            },
        },
    },

    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
    ],
};
