export default {
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                green: {
                    500: '#47af98'
                },
                dark_bg: '#1c1c16',
                gray: {
                    100: '#e3e3e3',
                    300: '#4f4f4f'
                },
                dark: {
                    400: '#4c4b46',
                    500: '#363531',
                    600: '#1c1c16'
                },
                brown: {
                    300: '#ECBA57'
                },
                blue: {
                    500: '#66a2e5'
                }
            }
        }
    }

}
