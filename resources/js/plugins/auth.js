import bearer from "@websanova/vue-auth/drivers/auth/bearer";
import waxios from "@websanova/vue-auth/drivers/http/axios.1.x";
import wrouter from "@websanova/vue-auth/drivers/router/vue-router.2.x";


const config = {
    auth: bearer,
    http: waxios,
    router: wrouter,
    tokenDefaultName: "laravel-vue-spa",
    tokenStore: ["localStorage"],
    rolesKey: "permissions",
    refreshData: true,
};

export default config;