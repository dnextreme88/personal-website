import './bootstrap';

window.darkModeSwitcher = function() {
    return {
        switchOn: JSON.parse(localStorage.getItem('kevinPortfolioIsDarkMode')) || false,
        switchTheme() {
            if (this.switchOn) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            localStorage.setItem('kevinPortfolioIsDarkMode', this.switchOn);

            console.log('Dark mode:', this.switchOn);
        },
        init() {
            this.switchTheme();
        }
    }
}
