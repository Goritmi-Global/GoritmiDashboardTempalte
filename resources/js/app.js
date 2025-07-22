import "../css/app.css";
import "./bootstrap";
import "vue-multiselect/dist/vue-multiselect.css";

import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";

// Import and style Toastify
import Vue3Toastify, { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

// Global components
import ConfirmModal from "@/Components/ConfirmModal.vue";
import PrimaryLinkButton from "@/Components/PrimaryLinkButton.vue";
import PrimaryModalButton from "@/Components/PrimaryModalButton.vue";
import ImageCropperModal from "@/Components/ImageCropperModal.vue";
import ImageZoomModal from "@/Components/ImageZoomModal.vue";
 




const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({
            render: () => h(App, props),
        });

        //  Global components
        vueApp.component("ConfirmModal", ConfirmModal);
        vueApp.component("PrimaryLinkButton", PrimaryLinkButton);
        vueApp.component("PrimaryModalButton", PrimaryModalButton);
        vueApp.component("ImageCropperModal", ImageCropperModal);
        vueApp.component("ImageZoomModal", ImageZoomModal);

        vueApp.use(plugin);
        vueApp.use(ZiggyVue);
        vueApp.use(Vue3Toastify, {
            autoClose: 3000,
            position: "bottom-right",
        });

        vueApp.mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
