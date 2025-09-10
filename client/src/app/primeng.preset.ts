import { definePreset } from '@primeuix/themes'
import Aura from '@primeuix/themes/aura'

export const primengPreset = definePreset(Aura, {
    semantic: {
        primary: {
            50: '{purple.50}',
            100: '{purple.100}',
            200: '{purple.200}',
            300: '{purple.300}',
            400: '{purple.400}',
            500: '{purple.500}',
            600: '{purple.600}',
            700: '{purple.700}',
            800: '{purple.800}',
            900: '{purple.900}',
            950: '{purple.950}',
        },
    },
    components: {
        inputtext: {
            root: {
                borderRadius: '{borderRadius.xl}',
                paddingY: '10px',
                paddingX: '16px',
            },
        },
    },
})
