<template>
    <div class="d-flex align-center justify-center">
        <v-card width="500px" :loading="loading">
            <v-toolbar dark dense text color="#0d1f76">
                <v-toolbar-title>
                    Register new user
                </v-toolbar-title>
            </v-toolbar>
            <v-card-text>
                <v-form ref="form" lazy-validation>
                    <div class="alert alert-danger" v-if="has_error == true">
                        <ul>
                            <li
                                v-for="error in errors"
                                :key="error.id"
                                style="margin:0px"
                            >
                                {{ error[0] }}
                            </li>
                        </ul>
                    </div>
                    <v-text-field
                        v-model="user.name"
                        label="Name"
                        :rules="[v => !!v || 'arabic name is required']"
                    />
                    <v-text-field
                        v-model="user.email"
                        label="Email"
                        :rules="[
                            v => !!v || 'E-mail is required',
                            v => /.+@.+/.test(v) || 'E-mail must be valid'
                        ]"
                        required
                    />
                    <v-text-field
                        type="password"
                        v-model="user.password"
                        label="Password"
                        :rules="[
                            v => !!v || 'Password is required',
                            v =>
                                (v && v.length >= 6) ||
                                'Name must be at least 6 characters'
                        ]"
                        required
                    />
                    <v-text-field
                        v-model="user.address"
                        label="Address"
                        :rules="[
                            v => !!v || 'address is required',
                            v =>
                                (v && v.length <= 100) ||
                                'Address must be less than 100 characters'
                        ]"
                        required
                    ></v-text-field>
                    <v-text-field
                        v-model="user.telephone"
                        type="number"
                        label="Telephone"
                        required
                        :rules="[v => !!v || 'telephone  is required']"
                    ></v-text-field>
                    <v-file-input
                        v-model="user.img"
                        prepend-icon=""
                        :rules="[
                            value =>
                                !value ||
                                value.size < 2000000 ||
                                'this file size should be less than 2 MB!'
                        ]"
                        show-size
                        label="Prsonal image"
                        accept="image/*"
                    ></v-file-input>
                </v-form>
            </v-card-text>
            <v-card-actions class="pt-0">
                <v-spacer></v-spacer>
                <v-btn color="primary darken-1" text @click.native="save"
                    >Register</v-btn
                >
            </v-card-actions>
        </v-card>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loading: false,

            has_error: false,
            errors: [],

            user: {
                img: []
            }
        };
    },

    methods: {
        save() {
            if (this.$refs.form.validate()) {
                this.loading = true;
                let data = window.jsonToFormData(this.user);
                this.axios
                    .post("/auth/register", data)
                    .then(() => {
                        this.$auth.login({
                            params: {
                                email: this.user.email,
                                password: this.user.password
                            },
                            success: function() {
                                this.$router.push("dashboard");
                                this.loading = false;
                            },
                            error: function() {
                                this.has_error = true;
                                this.loading = false;
                            },
                            rememberMe: true,
                            fetchUser: true
                        });
                    })
                    .catch(error => {
                        this.has_error = true;
                        this.errors = error.response.data.errors;
                        this.loading = false;
                    });
            }
        }
    }
};
</script>

<style></style>
