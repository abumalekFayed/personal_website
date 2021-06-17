import Vue from "vue";
import vuetify from "./plugins/vuetify";

import axios from "axios";
import VueAxios from "vue-axios";
import router from "./plugins/router";
import store from "./store";

//components
import Index from "./Index.vue"; //root
import vPage from "./components/VPage";
import DBlock from "./components/DBlock";
import PrintLayout from "./components/PrintLayout";
import DDialog from "./components/DDialog";
import DPanel from "./components/DPanel";
import LTable from "./components/LTable";
import auth from "@websanova/vue-auth/dist/v2/vue-auth.esm.js";
import driverAuthBearer from "@websanova/vue-auth/dist/drivers/auth/bearer.esm.js";
import driverHttpAxios from "@websanova/vue-auth/dist/drivers/http/axios.1.x.esm.js";
import driverRouterVueRouter from "@websanova/vue-auth/dist/drivers/router/vue-router.2.x.esm.js";
import VueMoment from "vue-moment";

const moment = require("moment");
require("moment/locale/ar");
Vue.use(VueMoment, {
    moment
});

//import Spotlight from "spotlight";
//var spotlight = require('spotlight');
//
//Vue.use(Spotlight)

window.jsonToFormData = require("./plugins/jsonToFormData.min.js");

Vue.router = router;
Vue.config.performance = false;

Vue.use(VueAxios, axios);

axios.defaults.baseURL =
    window.location.protocol +
    "//" +
    window.location.hostname +
    ":" +
    window.location.port +
    "/api";
//axios.defaults.withCredentials = true;
let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
    //axios.defaults.headers.common["Authorization"] = token.content;
}
window.axios = axios;

//Vue.use(require("vue-moment"));

Vue.use(auth, {
    plugins: {
        http: Vue.axios, // Axios
        // http: Vue.http, // Vue Resource
        router: Vue.router
    },
    drivers: {
        auth: driverAuthBearer,
        http: driverHttpAxios,
        router: driverRouterVueRouter
    },
    options: {
        rolesKey: "permissions",
        notFoundRedirect: { path: "/" },
        refreshData: { enabled: false }
    }
});
Vue.component("index", Index);
Vue.component("vPage", vPage);
Vue.component("dBlock", DBlock);
Vue.component("printLayout", PrintLayout);
Vue.component("dDialog", DDialog);
Vue.component("dPanel", DPanel);
Vue.component("lTable", LTable);

Vue.config.productionTip = false;
export const bus = new Vue();

export default new Vue({
    el: "#app",
    vuetify,
    router,
    store
});
