import i18next from "i18next";
import { createI18nStore } from "svelte-i18next";

i18next.init({
    lng: 'sr',
    resources: LARAVEL_TRANSLATIONS, /* could be merged with additional resources */
    interpolation: {
        escapeValue: false, // not needed for svelte as it escapes by default
    }
});
const i18n = createI18nStore(i18next);
export default i18n;
