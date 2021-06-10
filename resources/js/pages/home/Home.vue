<template>
    <div>
        <v-card
            class="overflow-hidden p-0 mb-0"
            height="100%"
            min-height="100%"
        >
            <v-app-bar
                absolute
                color="black"
                dark
                shrink-on-scroll
                prominent
                :src="require('../../assets/download.jpg').default"
                fade-img-on-scroll
                scroll-target="#scrolling-techniques-3"
            >
                <template v-slot:img="{ props }">
                    <v-img
                        v-bind="props"
                        gradient="to top right, rgba(100,115,201,.1), rgba(25,32,72,.3)"
                    ></v-img>
                </template>

                <!-- <v-app-bar-nav-icon></v-app-bar-nav-icon> -->

                <div style="width:100%;" class="mt-3 mb-3">
                    <h4 style="width:100%">النائب / خالد العتيبي</h4>
                    <h6 style="width:100%; font-stretch: ultra-expanded;">
                        عضو مجلس الأمة الكويتي
                    </h6>
                </div>

                <v-spacer></v-spacer>
                <v-btn icon @click="drawer = !drawer">
                    <v-icon>mdi-menu</v-icon>
                </v-btn>
                <template v-slot:extension>
                    <v-tabs align-with-title>
                        <v-tab style="width: 11rem"
                            >تسجيل الدخول
                            <v-icon class="mr-3">mdi-login</v-icon>
                        </v-tab>
                        <!-- <v-tab style="width: 11rem">Tab 2</v-tab>
          <v-tab style="width: 11rem">Tab 3</v-tab> -->
                    </v-tabs>
                </template>
            </v-app-bar>
            <v-sheet
                id="scrolling-techniques-3"
                class="overflow-y-auto p-0"
                max-height="100vh"
                style="overflow-x: hidden !important"
            >
                <v-container fluid style="height: contain; margin-top: 15rem">
                    <v-layout row wrap justify-space-between>
                        <v-flex xs12 md4 lg3 class="mx-auto">
                            <v-img
                                :src="require('../../assets/otibi.jpg').default"
                                class="mt-5"
                                :height="innerWidth > 750 ? '60vh' : '80vh'"
                            ></v-img>
                        </v-flex>
                        <v-flex xs12 md7 lg8 class="mx-auto">
                            <v-card
                                class="mt-5"
                                color=""
                                style="height: 50rem !important"
                            >
                                <v-card-text class="black white--text">
                                    <v-icon dark>mdi-account-circle</v-icon>
                                    المعلومات الشخصية
                                </v-card-text>
                            </v-card>
                        </v-flex>

                        <v-flex
                            xs12
                            sm12
                            md11
                            lg11
                            class="mx-auto p-0 row mt-10 elevaion-3"
                        >
                            <!-- <v-card class="elevation-3 mt-10 p-0"> -->
                            <!-- <v-row class=""> -->
                            <v-btn
                                class="fullWC mx-auto"
                                style="height: 10rem !important;"
                                color="blue darken-3 mt-3 text-center"
                                dark
                                v-for="(department, i) in activeDepartments"
                                :key="i"
								:to="'/departments/' + department.id"
                            >
                                <h3
                                    class="text-center mx-auto mt-5 text-center"
                                    style="width: 100% !important"
                                >
                                    <v-icon
                                        class="large-font w-100 text-center"
                                        style="width: 100% !important"
                                    >
                                        {{ department.icon }}
                                    </v-icon>
                                    <br />
                                    {{ department.name }}
                                </h3>
                            </v-btn>
                            <!-- </v-row> -->
                            <!-- </v-card> -->
                        </v-flex>
                        <v-flex xs12 md12 lg12>
                            <v-footer dark padless class="mt-10">
                                <v-card
                                    flat
                                    tile
                                    width="100%"
                                    class="blue darken-3 text-center"
                                >
                                    <v-card-text>
                                        <v-btn
                                            v-for="icon in icons"
                                            :key="icon"
                                            class="mx-4"
                                            icon
                                            dark
                                        >
                                            <v-icon size="24px">
                                                {{ icon }}
                                            </v-icon>
                                        </v-btn>
                                    </v-card-text>

                                    <v-divider></v-divider>

                                    <v-card-text class="white--text">
                                        <strong
                                            >جميع الحقوق محفوظة - النائب / خالد
                                            العتيبي</strong
                                        >
                                        - {{ new Date().getFullYear() }}
                                    </v-card-text>
                                </v-card>
                            </v-footer>
                        </v-flex>
                    </v-layout>
                </v-container>
                <v-navigation-drawer
                    dark
                    fixed
                    clipped
                    app
                    flat
                    left
                    v-model="drawer"
                >
                    <v-list>
                        <v-list-item
                            v-if="$auth.check()"
                            class="px-2 primary lighten-5"
                            style="margin-top: -9px;"
                        >
                            <v-list-item-avatar>
                                <v-img src="storage/imgs/logo2.jpg"></v-img>
                            </v-list-item-avatar>
                            <v-list-item-content>
                                <v-list-item-title class="black--text">{{
                                    $auth.user().name
                                }}</v-list-item-title>
                                <v-list-item-subtitle> </v-list-item-subtitle>
                            </v-list-item-content>
                            <v-chip
                                small
                                color="error"
                                class="mx-2"
                                label
                                v-if="$auth.check()"
                                @click="logout"
                                >logout</v-chip
                            >
                        </v-list-item>
                        <template>
                            <template
                                v-if="
                                    $auth.check() &&
                                        $auth.user().account_state !== 'active'
                                "
                            >
                                <v-list-item
                                    to="/user-dashboard"
                                    color="primary"
                                >
                                    <v-list-item-icon>
                                        <v-icon>mdi-view-dashboard</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-title
                                        >لوحة التحكم</v-list-item-title
                                    >
                                </v-list-item>
                            </template>
                            <div v-for="(item, index) in routes" :key="index">
                                <template v-if="item.children">
                                    <v-list-group
                                        v-if="
                                            $auth.check(
                                                item.meta
                                                    ? item.meta.auth.roles
                                                    : ''
                                            )
                                        "
                                        :group="item.path"
                                        no-action
                                        :key="item.name"
                                        :prepend-icon="item.icon"
                                        active-class="cls"
                                    >
                                        <template v-slot:activator>
                                            <v-list-item-title
                                                v-text="
                                                    item.name
                                                        .charAt(0)
                                                        .toUpperCase() +
                                                        item.name.slice(1)
                                                "
                                            ></v-list-item-title>
                                        </template>
                                        <template v-for="link in item.children">
                                            <v-link
                                                v-if="
                                                    !link.hide &&
                                                        $auth.check(
                                                            link.meta
                                                                ? link.meta.auth
                                                                      .roles
                                                                : ''
                                                        )
                                                "
                                                :key="link.id"
                                                :tolink="link.path"
                                                :title="
                                                    $t(
                                                        link.label
                                                            ? link.label
                                                            : link.name
                                                    )
                                                "
                                            />
                                        </template>
                                    </v-list-group>
                                </template>
                                <template v-else>
                                    <v-list-item
                                        color="indigo"
                                        :key="index"
                                        :to="item.path"
                                        v-if="
                                            !item.hide &&
                                                $auth.check(
                                                    item.meta
                                                        ? item.meta.auth.roles
                                                        : ''
                                                )
                                        "
                                    >
                                        <v-list-item-icon>
                                            <v-icon>{{ item.icon }}</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-title>{{
                                            item.name.charAt(0).toUpperCase() +
                                                item.name.slice(1)
                                        }}</v-list-item-title>
                                    </v-list-item>
                                </template>
                            </div>
                        </template>
                        <v-list-item
                            v-for="(department, i) in activeDepartments"
                            :key="'d_' + department.id"
                            :to="'/departments/' + department.id"
                            color="primary"
                        >
                            <v-list-item-icon>
                                <v-icon>{{ department.icon }}</v-icon>
                            </v-list-item-icon>
                            <v-list-item-title>{{
                                department.name
                            }}</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-navigation-drawer>
            </v-sheet>
        </v-card>
    </div>
</template>

<script>
import vLink from "../../components/VLink";
import { bus } from "../../app.js";
import { get } from "vuex-pathify";

export default {
    data() {
        return {
            drawer: null,
            fab: false,
            color: "success",
            snackbar: false,
            text: "",
            routes: [],
            innerWidth: window.innerWidth,
            icons: ["mdi-facebook", "mdi-email", "mdi-whatsapp", "mdi-linkedin"]
        };
    },
    props: ["settings"],

    components: {
        vLink
    },

    computed: {
        ...get(["print_title", "print_type", "_settings", "departments"]),

        activeDepartments() {
            return this.departments.filter(d => d.state == "active");
        }
    },
    methods: {
        logout() {
            if (!confirm("Are you sure to logout?")) return;
            this.axios.post("auth/logout").then(res => {
                this.$auth.logout();
            });
        },

        onScroll(e) {
            if (typeof window === "undefined") return;
            const top = window.pageYOffset || e.target.scrollTop || 0;
            this.fab = top > 20;
        },

        toTop() {
            this.$vuetify.goTo(0);
        }
    },

    mounted() {
        bus.$on("__print", type_id => {
            console.log("printing");
            setTimeout(() => {
                this.$refs.__print ? this.$refs.__print.print() : "";
            }, 400);
        });
    },

    created() {
        if (this.departments.length == 0) this.$store.set("departments");

        this.$store.set("settings", this.settings);
        //lesign to alerts events
        this.$store.subscribe((mutation, state) => {
            console.log(mutation.type);
            if (mutation.type === "SET_SHOW_MESSAGE") {
                this.text = state.states.show_message.text;
                this.color = state.states.show_message.color;
                this.snackbar = true;
            }
        });

        this.routes = this.$router.options.routes.filter(
            r => r.hide != true && r.meta
        );

        // this.axios request intercepting
        this.axios.interceptors.request.use(
            config => {
                if (this.$store.state.allow_loading)
                    this.$store.commit("loading", true);
                return config;
            },
            err => {
                this.$store.commit("loading", false);
                return Promise.reject(err);
            }
        );

        this.axios.interceptors.response.use(
            response => {
                this.$store.commit("loading", false);
                this.color = "success";
                if (response.data.success) {
                    this.text = response.data.success;
                    this.snackbar = true;
                }
                return response;
            },
            err => {
                this.$store.commit("loading", false);
                this.color = "error";
                if (err.response.data.errors) {
                    this.text =
                        err.response.data.errors[
                            Object.keys(err.response.data.errors)[0]
                        ];
                    this.snackbar = true;
                } else if (err.response.data.error) {
                    this.text = err.response.data.error;
                    this.snackbar = true;
                }
                throw err;
            }
        );
    }
};
</script>

<style lang="scss">
.chip {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 10px;
    min-width: 90px;
    border-radius: 5px;
    font-size: 23px;
}

.chip span {
    font-size: 16px;
}

@media only screen and (max-width: 600px) {
    .search-bar {
        display: none;
    }
}

.cls {
    border-left: none !important;
    color: red !important;
}

.v-list-item--active {
    border-left: solid 8px rgb(1, 158, 197);
    color: #ffffff;
    background-color: rgb(247, 255, 247);
}

.v-list-item--active i {
    color: blue !important;

    // background-color: rgb(241, 241, 241);
}

.v-list-item__title {
    line-height: 1.6rem !important;
}

a {
    text-decoration: none !important;
}

.border {
    border: solid 1px rgb(218, 218, 218);
    padding: 10px;
}

.__registered,
.__planned,
.__waiting {
    background-color: yellow !important;
}

.__confirmed {
    background-color: rgb(157, 157, 223) !important;
}

.__payed,
.__active,
.__activated {
    background-color: rgb(51, 51, 243) !important;
    color: white !important;
}

.__accepted,
.__performed {
    background-color: rgb(71, 250, 125) !important;
}

.__refused,
.__cancelled {
    background-color: rgb(247, 63, 38) !important;
    color: white !important;
}

// p {
//     color: black;
// }
.text-center {
    text-align: center !important;
}
.h-50 {
    height: 250px;
}
.w-100 {
    width: 100% !important;
}
.large-font {
    font-size: 5rem !important;
}
.fullWC {
    width: 30% !important;
}
@media (max-width: 700px) {
    .fullWC {
        width: 95% !important;
    }
}
/* width */
::-webkit-scrollbar {
    width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
    background: #f1f1f1;
}

/* Handle */
::-webkit-scrollbar-thumb {
    background: #888;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
    background: #555;
}
@font-face {
    font-family: kufy;
    src: url("../../assets/fonts/18 Khebrat Musamim Bold.ttf");
}
#app {
    font-family: kufy !important;
}
// .v-btn__content {
//     display: flex;
//     flex-direction: column;
// 	text-align: center !important;
// }
</style>
