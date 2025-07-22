import type { Config } from "tailwindcss"

const config: Config = {
  darkMode: ["class"],
  content: [
    "./pages/**/*.{js,ts,jsx,tsx,mdx}",
    "./components/**/*.{js,ts,jsx,tsx,mdx}",
    "./app/**/*.{js,ts,jsx,tsx,mdx}",
    "*.{js,ts,jsx,tsx,mdx}",
  ],
  prefix: "",
  theme: {
    container: {
      center: true,
      padding: "2rem",
      screens: {
        "2xl": "1400px",
      },
    },
    extend: {
      colors: {
        border: "hsl(var(--border))",
        input: "hsl(var(--input))",
        ring: "hsl(var(--ring))",
        background: "hsl(var(--background))",
        foreground: "hsl(var(--foreground))",
        primary: {
          DEFAULT: "#8B4513", // saddle brown
          foreground: "#ffffff",
          50: "#fdf8f6",
          100: "#f2e8e5",
          200: "#eaddd7",
          300: "#e0cfc5",
          400: "#d2bab0",
          500: "#bfa094",
          600: "#a18072",
          700: "#8B4513",
          800: "#723710",
          900: "#5d2f0e",
        },
        secondary: {
          DEFAULT: "#f5f5dc", // beige
          foreground: "#3c2414",
        },
        destructive: {
          DEFAULT: "#dc2626",
          foreground: "#ffffff",
        },
        muted: {
          DEFAULT: "#f5f5dc",
          foreground: "#6b5b4d",
        },
        accent: {
          DEFAULT: "#f5deb3", // wheat
          foreground: "#3c2414",
        },
        popover: {
          DEFAULT: "#ffffff",
          foreground: "#3c2414",
        },
        card: {
          DEFAULT: "#ffffff",
          foreground: "#3c2414",
        },
        brown: {
          50: "#fdf8f6",
          100: "#f2e8e5",
          200: "#eaddd7",
          300: "#e0cfc5",
          400: "#d2bab0",
          500: "#bfa094",
          600: "#a18072",
          700: "#8B4513",
          800: "#723710",
          900: "#5d2f0e",
          950: "#3c2414",
        },
        beige: {
          50: "#fefdfb",
          100: "#fef7ed",
          200: "#fef2e2",
          300: "#fde8cc",
          400: "#f5deb3",
          500: "#f5f5dc",
          600: "#deb887",
          700: "#d2b48c",
          800: "#bc9a6a",
          900: "#a0845c",
        },
        tan: {
          50: "#faf9f7",
          100: "#f5f1eb",
          200: "#ebe4d6",
          300: "#ddd2bd",
          400: "#d2b48c",
          500: "#cd853f",
          600: "#b8860b",
          700: "#a0522d",
          800: "#8b4513",
          900: "#654321",
        },
      },
      borderRadius: {
        lg: "var(--radius)",
        md: "calc(var(--radius) - 2px)",
        sm: "calc(var(--radius) - 4px)",
      },
      keyframes: {
        "accordion-down": {
          from: { height: "0" },
          to: { height: "var(--radix-accordion-content-height)" },
        },
        "accordion-up": {
          from: { height: "var(--radix-accordion-content-height)" },
          to: { height: "0" },
        },
        fadeIn: {
          "0%": { opacity: "0", transform: "translateY(20px)" },
          "100%": { opacity: "1", transform: "translateY(0)" },
        },
        slideIn: {
          "0%": { opacity: "0", transform: "translateX(20px)" },
          "100%": { opacity: "1", transform: "translateX(0)" },
        },
      },
      animation: {
        "accordion-down": "accordion-down 0.2s ease-out",
        "accordion-up": "accordion-up 0.2s ease-out",
        fadeIn: "fadeIn 0.6s ease-out",
        slideIn: "slideIn 0.6s ease-out",
      },
    },
  },
  plugins: [require("tailwindcss-animate")],
} satisfies Config

export default config
