import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import { vTooltip, vClosePopper, Dropdown, Tooltip, Menu } from "floating-vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import numbro from "numbro";
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";
import "floating-vue/dist/style.css";
import "toastify-js/src/toastify.css";
import '@vuepic/vue-datepicker/dist/main.css';

const appName = import.meta.env.VITE_APP_NAME || "EduNova";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        app.config.globalProperties.$numbro = numbro;
        app.directive("tooltip", vTooltip);
        app.directive("close-popper", vClosePopper);

        app.component("VDropdown", Dropdown);
        app.component("VTooltip", Tooltip);
        app.component("VMenu", Menu);
        app.component("Multiselect", Multiselect);

        return app
            .component("VueDatePicker", VueDatePicker)
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
