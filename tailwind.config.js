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
                /* Shadcn-inspired neutral SaaS tokens */
                primary: {
                    DEFAULT: '#18181B',
                    dark: '#3F3F46',
                    soft: '#F4F4F5',
                },
                secondary: '#FAFAFA',
                accent: {
                    DEFAULT: '#F4F4F5',
                    dark: '#E4E4E7',
                    soft: '#FAFAFA',
                },
                canvas: '#FAFAFA',
                foreground: '#09090B',
                card: '#FFFFFF',
                muted: '#F4F4F5',
                'muted-foreground': '#71717A',
                line: '#E4E4E7',
                destructive: '#DC2626',
                success: '#16A34A',
                warning: '#D97706',

                /* Backwards-compatible aliases used across the Blade views */
                paper: '#FAFAFA',
                surface: '#FFFFFF',
                ink: '#09090B',
                'ink-2': '#71717A',
                'ink-3': '#A1A1AA',
                'accent-soft': '#F4F4F5',
                positive: '#16A34A',
                caution: '#B45309',
                critical: '#DC2626',
            },

            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },

            boxShadow: {
                glass: '0 1px 2px 0 rgba(9, 9, 11, 0.04), 0 8px 24px -16px rgba(9, 9, 11, 0.18)',
                'glass-lg': '0 1px 2px 0 rgba(9, 9, 11, 0.05), 0 24px 48px -24px rgba(9, 9, 11, 0.25)',
                'blue-glow': '0 1px 2px 0 rgba(9, 9, 11, 0.06)',
                'accent-glow': '0 1px 2px 0 rgba(9, 9, 11, 0.06)',
            },

            backgroundImage: {
                'brand-gradient': 'linear-gradient(135deg, #18181B 0%, #27272A 55%, #3F3F46 100%)',
                'brand-sheen': 'radial-gradient(circle at 20% 10%, rgba(255, 255, 255, 0.1), transparent 45%)',
            },
        },
    },

    plugins: [forms],
};
