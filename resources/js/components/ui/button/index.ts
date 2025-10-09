import { cva, type VariantProps } from 'class-variance-authority'

export { default as Button } from './Button.vue'

export const buttonVariants = cva(
  'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-semibold transition-all duration-200 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*=\'size-\'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:ring-2 focus-visible:ring-offset-2 active:scale-95',
  {
    variants: {
      variant: {
        default:
          'bg-gradient-to-r from-orange-500 to-amber-600 text-white shadow-lg shadow-orange-500/30 hover:shadow-xl hover:shadow-orange-500/40 hover:from-orange-600 hover:to-amber-700 focus-visible:ring-orange-500/50',
        destructive:
          'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg shadow-red-500/30 hover:shadow-xl hover:shadow-red-500/40 hover:from-red-600 hover:to-red-700 focus-visible:ring-red-500/50',
        outline:
          'border-2 border-orange-200 dark:border-orange-800 bg-white dark:bg-gray-900 shadow-sm hover:bg-orange-50 dark:hover:bg-orange-950/30 hover:border-orange-300 dark:hover:border-orange-700 focus-visible:ring-orange-500/50',
        secondary:
          'bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 text-gray-900 dark:text-gray-100 shadow-sm hover:from-gray-200 hover:to-gray-300 dark:hover:from-gray-700 dark:hover:to-gray-600 focus-visible:ring-gray-500/50',
        ghost:
          'hover:bg-orange-50 dark:hover:bg-orange-950/30 hover:text-orange-600 focus-visible:ring-orange-500/50',
        link:
          'text-orange-600 underline-offset-4 hover:underline hover:text-orange-700 focus-visible:ring-orange-500/50',
      },
      size: {
        default: 'h-10 px-5 py-2.5 has-[>svg]:px-4',
        sm: 'h-9 rounded-lg gap-1.5 px-4 text-xs has-[>svg]:px-3',
        lg: 'h-12 rounded-lg px-7 text-base has-[>svg]:px-5',
        icon: 'size-10',
      },
    },
    defaultVariants: {
      variant: 'default',
      size: 'default',
    },
  },
)

export type ButtonVariants = VariantProps<typeof buttonVariants>
