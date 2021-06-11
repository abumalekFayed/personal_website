<template>
    <div>
        <v-alert dark class="w-100" color="blue darken-3" dense id="break">
            <v-breadcrumbs :items="titles">
                <template v-slot:item="{ item }">
                    <v-breadcrumbs-item
                        :href="item.href"
                        :disabled="item.disabled"
                    >
                        <v-icon class="ml-2">{{ item.icon }}</v-icon>
                        <span
                            :class="item.disabled == false ? 'white--text' : ''"
                            >{{ item.text }}</span
                        >
                    </v-breadcrumbs-item>
                </template>
            </v-breadcrumbs>
        </v-alert>
        <!-- <v-container fluid style="height: contain; margin-top: 15rem"> -->
        <v-layout row wrap justify-space-between>

            <v-flex xs12 sm12 md12 lg12 class="mx-auto">
                <!-- <v-avatar size="350" class="mt-5 w-100"> -->
                <!-- <img
                        :src="require('../../assets/otibi.jpg').default"
                        alt="النائب / خالد العتيبي"
                    /> -->
                <v-carousel
                    cycle
                    height="500"
                    hide-delimiter-background
                    show-arrows-on-hover
                    class="w-100 mt-5"
                >
                    <v-carousel-item v-for="(slide, i) in slides" :key="i">
                        <v-avatar size="500" class="mt-5 w-100" tile
                        >
                            <img
                                :src="slide.src"
                                alt="النائب / خالد العتيبي"
                            />
                        </v-avatar>
                    </v-carousel-item>
                </v-carousel>
                <!-- </v-avatar> -->
                <!-- <v-img
                    :src="require('../../assets/otibi.jpg').default"
                    class="mt-5"
                    height="50rem"
                ></v-img> -->
            </v-flex>
            <!-- <v-flex xs12 md7 lg8 class="mx-auto">
                <v-card class="mt-5" color="" style="height: 50rem !important">
                    <v-card-text class="black white--text">
                        <v-icon dark>mdi-account-circle</v-icon>
                        المعلومات الشخصية
                    </v-card-text>
                </v-card>
            </v-flex> -->

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
        </v-layout>
        <!-- </v-container> -->
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
            icons: [
                "mdi-facebook",
                "mdi-email",
                "mdi-whatsapp",
                "mdi-linkedin"
            ],
            dialog: false,
            slides: [
                {
                    src: require("../../assets/otibi.jpg").default
                },
                {
                    src: require("../../assets/otibi.jpg").default
                },
                {
                    src: require("../../assets/otibi.jpg").default
                },
                {
                    src: require("../../assets/otibi.jpg").default
                }
            ],
            titles: [
                {
                    href: "/",
                    icon: "mdi-home",
                    text: "الرئيسية",
                    disabled: true
                },
                
            ]
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
