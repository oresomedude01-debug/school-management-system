// i18n.js - Internationalization System
class I18n {
    constructor() {
        this.locale = this.getStoredLocale() || 'en';
        this.fallbackLocale = 'en';
        this.translations = {};
        this.rtlLocales = ['ar', 'he', 'fa', 'ur'];
    }

    // Get stored locale from localStorage
    getStoredLocale() {
        return localStorage.getItem('locale');
    }

    // Set and store locale
    async setLocale(locale) {
        this.locale = locale;
        localStorage.setItem('locale', locale);

        // Load translations for this locale
        await this.loadTranslations(locale);

        // Update document direction
        this.updateDirection();

        // Trigger locale change event
        document.dispatchEvent(new CustomEvent('localeChanged', { detail: { locale } }));

        return this.translations;
    }

    // Load translations from server
    async loadTranslations(locale) {
        try {
            const response = await fetch(`/api/translations/${locale}`);
            if (response.ok) {
                this.translations = await response.json();
            } else {
                console.error('Failed to load translations for', locale);
                if (locale !== this.fallbackLocale) {
                    await this.loadTranslations(this.fallbackLocale);
                }
            }
        } catch (error) {
            console.error('Error loading translations:', error);
        }
    }

    // Get translation by key (supports dot notation)
    t(key, params = {}) {
        const keys = key.split('.');
        let value = this.translations;

        for (const k of keys) {
            if (value && typeof value === 'object' && k in value) {
                value = value[k];
            } else {
                console.warn(`Translation key not found: ${key}`);
                return key;
            }
        }

        // Replace parameters in translation
        if (typeof value === 'string' && Object.keys(params).length > 0) {
            return value.replace(/:(\w+)/g, (match, param) => {
                return params[param] !== undefined ? params[param] : match;
            });
        }

        return value;
    }

    // Check if current locale is RTL
    isRTL() {
        return this.rtlLocales.includes(this.locale);
    }

    // Update document direction
    updateDirection() {
        const dir = this.isRTL() ? 'rtl' : 'ltr';
        document.documentElement.setAttribute('dir', dir);
        document.documentElement.setAttribute('lang', this.locale);

        // Update body class for easier styling
        document.body.classList.toggle('rtl', this.isRTL());
        document.body.classList.toggle('ltr', !this.isRTL());
    }

    // Get current locale
    getLocale() {
        return this.locale;
    }

    // Get all available locales
    getAvailableLocales() {
        return [
            { code: 'en', name: 'English', nativeName: 'English', flag: '🇬🇧' },
            { code: 'ar', name: 'Arabic', nativeName: 'العربية', flag: '🇸🇦' }
        ];
    }

    // Initialize i18n
    async init() {
        await this.loadTranslations(this.locale);
        this.updateDirection();
        return this;
    }

    // Translate all elements with data-i18n attribute
    translatePage() {
        document.querySelectorAll('[data-i18n]').forEach(element => {
            const key = element.getAttribute('data-i18n');
            const translation = this.t(key);

            if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
                element.placeholder = translation;
            } else {
                element.textContent = translation;
            }
        });

        // Translate placeholders
        document.querySelectorAll('[data-i18n-placeholder]').forEach(element => {
            const key = element.getAttribute('data-i18n-placeholder');
            element.placeholder = this.t(key);
        });

        // Translate titles
        document.querySelectorAll('[data-i18n-title]').forEach(element => {
            const key = element.getAttribute('data-i18n-title');
            element.title = this.t(key);
        });
    }
}

// Create global i18n instance
const i18n = new I18n();

// Auto-translate page when locale changes
document.addEventListener('localeChanged', () => {
    i18n.translatePage();
});

// Helper function for easier access
window.__ = (key, params) => i18n.t(key, params);
window.i18n = i18n;
