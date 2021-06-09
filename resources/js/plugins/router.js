/* eslint-disable no-undef */
import Vue from "vue";
import VueRouter from "vue-router";
Vue.use(VueRouter);

function loadView(view) {
    return () =>
        import(/* webpackChunkName: "vuejs7-[request]" */ `./../${view}.vue`);
}

/*v important

-to check multiple roles with each others use auth:{ roles('role1','role2',....)}
-to check if multiple roles includes one or more roles use auth:{ roles['role1','role2',...}

*/

const routes = [
    {
        path: "/403",
        hide: true,
        name: "Error",
        component: loadView("components/Error")
    },

    {
        path: "/",
        hide: true,
        name: "home",
        component: loadView("pages/home/Home")
    },

    {
        path: "/departments",
        name: "الاقسام",
        component: loadView("pages/departments/Departments"),
        meta: { auth: { roles: ["admin"] } }
    },

    {
        path: "/departments/:id",
        hide: true,
        name: "department",
        component: loadView("pages/departments/Department")
    },

    {
        path: "/departments/:id/:item_id",
        hide: true,
        name: "department item",
        component: loadView("pages/departments/DepartmentItem")
    },

    {
        path: "/contact-us",
        hide: true,
        name: "contact us",
        component: loadView("pages/about/ContactUs")
    },

    {
        path: "/settings",
        name: "الاعدادات العامة",
        icon: "mdi-cog",
        component: loadView("pages/settings/Index"),
        meta: { auth: { roles: ["admin", "/settings"] } }
    },

    {
        path: "/about",
        name: "about ",
        icon: "mdi-information",
        component: loadView("pages/about/About")
    },

    {
        path: "/login2",
        hide: true,
        name: "login",
        component: loadView("pages/auth/Login"),
        meta: { auth: false, redirect: { name: "/" } }
    },
    {
        path: "/register",
        hide: true,
        name: "register",
        component: loadView("pages/auth/Register"),
        meta: { auth: false, redirect: { name: "/" } }
    }
];

export default new VueRouter({
    history: true,
    mode: "history",
    routes,
    linkExactActiveClass: "active"
});

// router.beforeEach((to, from, next) => {
//     if (to.matched.some(record => record.meta.active)) {
//       // this route requires auth, check if logged in
//       // if not, redirect to login page.
//       if (!auth.check()) {
//         next({
//           path: '/login',
//           query: { redirect: to.fullPath }
//         })
//       } else {
//         next()
//       }
//     } else {
//       next() // make sure to always call next()!
//     }
//   })
