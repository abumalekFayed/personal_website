<template>
	<div class="justify-center d-flex align-center flex-column">
		<v-alert dark class="w-100" color="blue darken-3" dense>
			<v-breadcrumbs :items="titles">
				<template v-slot:item="{ item }">
					<v-breadcrumbs-item :href="item.href" :disabled="item.disabled">
						<v-icon class="ml-2">{{ item.icon }}</v-icon>
						<span :class="item.disabled == false ? 'white--text' : ''">{{ item.text }}</span>
					</v-breadcrumbs-item>
				</template>
			</v-breadcrumbs>
		</v-alert>
		<!-- <img src="storage/imgs/logo2.jpg" style="max-height:350px"> -->
		<v-card outlined v-if="$auth.check() == false" class="rounded-lg log">
			<v-img :src="require('../../assets/omma.jpg').default">
				<v-card-title class="justify-center flex-column white--text" style="font-size: 1.5rem">
					تسجيل الدخول
					<!-- <v-avatar size="100" color="primary" class=""> -->
					<v-icon class="title white--text mt-3" style="font-size: 7rem !important " dark color="black darken-3">mdi-account-circle</v-icon>
					<!-- </v-avatar> -->
				</v-card-title>
				<v-card-text>
					<div class="alert alert-danger" v-if="has_error">
						البيانات غير صحيحة
					</div>
					<v-form lazy-validation @keyup.native.enter="login" ref="form">
						<v-text-field prepend-inner-icon="mdi-account" outlined dense v-model="email" solo rounded ref="loginTxt" focusable label="البريد الإلكتروني" :rules="[
                                v => !!v || 'البريد الإلكتروني مطلوب',
                                v => /.+@.+/.test(v) || 'E-mail must be valid'
                            ]" required />
						<v-text-field prepend-inner-icon="mdi-lock" outlined dense solo rounded v-model="password" type="password" label="كلمة المرور" :rules="[
                                v => !!v || 'كلمة المرور مطلوبة',
                                v => (v && v.length >= 6) || 'يرجي إدخال 6 رموز'
                            ]" required />
					</v-form>
				</v-card-text>

				<v-card-actions class="justify-center flex-column" style="gap:10px">
					<v-chip label class="white--text" color="blue darken-3" @click.stop="login">
						<v-icon class="ml-1">mdi-login</v-icon> تسجيل
						دخول
					</v-chip>
					<v-btn text label @click.stop="resetEmail" color="white">استعادة الرقم السري</v-btn>
				</v-card-actions>
			</v-img>
		</v-card>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				test: false,
				reset: false,
				email: null,
				password: null,
				has_error: false,
				rcode: "",
				newcode: "",
				titles: [
					{
						href: "/",
						icon: "mdi-home",
						text: "الرئيسية",
						disabled: false
					},
					{
						href: "/login2",
						icon: "mdi-account-circle",
						text: "تسجيل الدخول",
						disabled: true
					}
				]
			};
		},
		created() { },

		mounted() {
			if (localStorage.em) this.email = localStorage.em;
			if (localStorage.ps) this.password = localStorage.ps;
			this.$refs.loginTxt.focus();
		},
		methods: {
			login() {
				// get the redirect object
				if (!this.$refs.form.validate()) return;
				// var redirect = this.$auth.redirect()
				var app = this;
				// alert(window.location.hostname)
				this.axios
					.get(
						window.location.protocol +
						"//" +
						window.location.hostname +
						"/sanctum/csrf-cookie"
					)
					.then(res => {
						this.$auth
							.login({
								data: {
									email: this.email,
									password: this.password
									//  useCredentails: true
								},
								rememberMe: true,
								staySignedIn: true,
								fetchUser: true,
								redirect: {
									path: "/"
								}
							})
							.then(
								() => {
									//this.$router.push('/')
								},
								() => {
									this.has_error = true;
								}
							);
					});
			},

			SendResetCode() {
				// get the redirect object
				if (this.remail == "") {
					alert("Email feild is empty");
					return;
				}
				this.axios
					.post("/send-reset-password-link", {
						email: this.email
					})
					.then(res => {
						alert(res.data);
					})
					.catch(err => {
						alert(err);
					});
			},

			close() {
				this.$emit("closeme");
			}
		}
	};
</script>
<style>
	.log {
		width: 50%;
	}
	@media (max-width: 780px) {
		.log {
			width: 100%;
		}
	}
</style>