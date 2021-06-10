<template>
    <div>
        <v-row>
            <v-col cols="12">
                <div
                    outlined
                    class="justify-center d-flex align-center flex-column  primary--text"
                >
                    <!-- <h2 class="ma-2 white--text">
                    موقع النائب خالد العتيبي
                </h2> -->

                    <img
                        src="imgs/start2.jpg"
                        alt="img"
                        width="80%"
                        style="border-radius:30px"
                    />
                </div>

                <!-- <p class="text-center" style="    margin: 20px;font-size: 20px;">
                {{_settings('general').description}}
            </p> -->
            </v-col>
        </v-row>
        <v-row>
            <v-col
                cols="12"
                sm="6"
                md="4"
                v-for="department in activeDepartments"
                :key="department.id"
            >
                <v-card
                    outlined
                    :class="department.color"
                    :to="'/departments/' + department.id"
                >
                    <v-card-title
                        primary-title
                        class="justify-center white--text"
                        >{{ department.name }}
                        <!-- <v-spacer></v-spacer>
                    <v-icon>mdi-ear-hearing</v-icon> -->
                    </v-card-title>
                </v-card>
            </v-col>
        </v-row>
        <d-dialog
            v-model="show_reset_password"
            title="Reset password"
            @save="SendResetCode"
            width="400px"
        >
            <v-text-field
                prepend-icon="mdi-email"
                type="text"
                v-model="reset_email"
                focusable
                label="Email"
                :rules="[
                    v => !!v || 'E-mail is required',
                    v => /.+@.+/.test(v) || 'E-mail must be valid'
                ]"
                required
            />
        </d-dialog>
    </div>
</template>

<script>
import { get } from "vuex-pathify";
export default {
    data() {
        return {
            alerts: [],
            publications: [],
            show_reset_password: false,
            test: false,
            reset: false,
            email: null,
            password: null,
            has_error: false,
            rcode: "",
            newcode: "",
            reset_email: ""
        };
    },
    computed: {
        ...get(["_settings", "departments"]),
        activeDepartments() {
            return this.departments.filter(d => d.state == "active");
        }
    },
    created() {
        if (this.departments.length == 0) this.$store.set("departments");
    },
    methods: {
        login() {
            // get the redirect object
            if (!this.$refs.form.validate()) return;
            // var redirect = this.$auth.redirect()

            //document.cookie=""
            var app = this;

            //this.axios.get(window.location.protocol + "//" + window.location.hostname + '/sanctum/csrf-cookie')
            //.then(res => {
            this.$auth
                .login({
                    data: {
                        email: this.email,
                        password: this.password
                    },
                    rememberMe: true,
                    staySignedIn: true,
                    //fetchUser: true,
                    redirect: {
                        path: "/user-dashboard"
                    }
                })
                .then(
                    () => {},
                    () => {
                        this.has_error = true;
                    }
                );
            //	})
        },

        resetEmail() {
            this.reset_email = this.email;
            this.show_reset_password = true;
        },

        SendResetCode() {
            // get the redirect object

            this.axios
                .post("/auth/send-reset-code", {
                    email: this.reset_email
                })
                .then(res => {
                    alert(res.data);
                })
                .catch(err => {
                    alert(err);
                });
        }
    }
};
</script>

<style lang="scss" scoped>
.bg {
    background-image: url("/storage/imgs/bg16.png");
    background-repeat: repeat;
}
</style>
