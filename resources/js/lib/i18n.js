import i18n from 'i18next';
import I18nextBrowserLanguageDetector from 'i18next-browser-languagedetector';
import { initReactI18next } from 'react-i18next';
import I18NextHttpBackend from 'i18next-http-backend';

console.log(import.meta);
const hash = import.meta.env.DEV ? Date.now().toString(16) : '';

i18n.use(I18NextHttpBackend)
    .use(I18nextBrowserLanguageDetector)
    .use(initReactI18next)
    .init({
        load: 'languageOnly',
        preload: ['en'],
        ns: [],
        react: {
            //useSuspense: false,
        },
        detection: {
            order: ['htmlTag'],
            htmlTag: document.documentElement,
        },
        debug: import.meta.env.DEV,
        //lng: 'en',
        fallbackLng: 'en',
        keySeparator: '.',
        backend: {
            loadPath: '/locales/{{lng}}/{{ns}}.json',
            queryStringParams: import.meta.env.DEV ? { hash } : null, // only add cache buster in dev mode
            allowMultiLoading: true,
        },
        interpolation: {
            // Per i18n-react documentation: this is not needed since React is already
            // handling escapes for us.
            escapeValue: false,
        },
    });

export default i18n;
