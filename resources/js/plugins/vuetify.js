import "@mdi/font/css/materialdesignicons.css"; // Ensure you are using css-loader
import 'vuetify/dist/vuetify.min.css';
import Vue from "vue";
import Vuetify from "vuetify/lib";

Vue.use(Vuetify);

const vuetify = new Vuetify({
    icons: {
        iconfont: "mdi", // 'mdi' || 'mdiSvg' || 'md' || 'fa' || 'fa4' || 'faSvg'
    },
    rtl: localStorage.getItem("current_local") == "en" ? false : true,
    theme: {
        themes: {
            light: {
                primary: localStorage.getItem("app_color") || "#005a94",
                secondary: "#b0bec5",
                anchor: "#8c9eff",
                background: "#e5e7eb",
            },
        },
    },
});

export default vuetify;