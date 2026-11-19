import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                /* Core semantic tokens — Skill SaaS default */
                primary: {
                    DEFAULT: '#2563EB',
                    dark: '#1D4ED8',
                    soft: '#EFF6FF',
                },
                secondary: '#3B82F6',
                accent: {
                    DEFAULT: '#EA580C',
                    dark: '#C2410C',
                    soft: '#FFF7ED',
                },
                canvas: '#F8FAFC',
                foreground: '#1E293B',
                card: '#FFFFFF',
                muted: '#E9EFF8',
                'muted-foreground': '#475569',
                line: '#E2E8F0',
                destructive: '#DC2626',
                success: '#059669',
                warning: '#D97706',

                /* Backwards-compatible aliases used across the Blade views */
                paper: '#F8FAFC',
                surface: '#FFFFFF',
                ink: '#1E293B',
                'ink-2': '#475569',
                'ink-3': '#5C6779',
                'accent-soft': '#FFF7ED',
                positive: '#059669',
                caution: '#B45309',
                critical: '#DC2626',
            },

            fontFamily: {
                sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
            },

            boxShadow: {
                glass: '0 8px 32px -8px rgba(15, 23, 42, 0.16)',
                'glass-lg': '0 24px 60px -20px rgba(15, 23, 42, 0.35)',
                'blue-glow': '0 12px 30px -10px rgba(37, 99, 235, 0.55)',
                'accent-glow': '0 12px 30px -10px rgba(234, 88, 12, 0.45)',
            },

            backgroundImage: {
                'brand-gradient': 'linear-gradient(135deg, #2563EB 0%, #4338CA 52%, #6D28D9 100%)',
                'brand-sheen': 'radial-gradient(circle at 20% 10%, rgba(255,255,255,.28), transparent 42%)',
            },
        },
    },

    plugins: [forms],
};
