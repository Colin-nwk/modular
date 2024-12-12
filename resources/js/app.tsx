import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/react';
// import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot, hydrateRoot } from 'react-dom/client';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    // resolve: (name) =>
    //     resolvePageComponent(
    //         `./Pages/${name}.tsx`,
    //         import.meta.glob('./Pages/**/*.tsx'),
    //     ),
    resolve: (name) => {
        const pages = import.meta.glob([
            "./Pages/**/*.tsx",
            "../../app-modules/*/resources/js/Pages/**/*.tsx",
        ]);
        const regex = /([^:]+)::(.+)/;
        const matches = regex.exec(name);

        if (matches && matches.length > 2) {
            // hyphenate the Pascal case name as is done
            // for directory names by the internachi/modular package
            const module = matches[1].replace(
                /[A-Z]/g,
                (m, offset) => (offset > 0 ? '-' : '') + m.toLowerCase()
            );

            const pageName = matches[2];

            return pages[`../../app-modules/${module}/resources/js/Pages/${pageName}.tsx`]();
        } else {
            return pages[`./Pages/${name}.tsx`]();
        }
    },
    setup({ el, App, props }) {
        if (import.meta.env.SSR) {
            hydrateRoot(el, <App {...props} />);
            return;
        }

        createRoot(el).render(<App {...props} />);
    },
    progress: {
        color: '#4B5563',
    },
});
