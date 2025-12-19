import { defineConfig } from 'unocss'
import presetUno from '@unocss/preset-uno'
import presetAttributify from '@unocss/preset-attributify'
import presetIcons from '@unocss/preset-icons'

export default defineConfig({
  presets: [
    presetUno({
      dark: 'class',
    }),
    presetAttributify(),
    presetIcons({
      scale: 1.2,
      extraProperties: {
        'display': 'inline-block',
        'vertical-align': 'middle',
      },
    }),
  ],
  // Добавьте свои правила если нужно
  rules: [],
  // Добавьте шорткаты если нужно
  shortcuts: {
    btn: 'px-4 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition-colors',
  },
})