<template>
	<div class="justify-center d-flex align-center flex-column">
		<!-- <img src="storage/imgs/logo2.jpg" style="max-height:350px"> -->
		<v-card style="width:500px" outlined v-if="$auth.check()==false">
			<v-card-title class="justify-center flex-column">
				تسجيل الدخول
				<v-avatar size="100" color="primary" class="mt-3">
					<span class="title white--text">L</span>
				</v-avatar>
			</v-card-title>
			<v-card-text>

				<div class="alert alert-danger" v-if="has_error">
					البيانات غير صحيحة
				</div>
				<v-form lazy-validation @keyup.native.enter="login" ref="form">
					<v-text-field prepend-icon="mdi-account" outlined dense v-model="email" ref="loginTxt" focusable label="الايميل" :rules="[ v => !!v || 'E-mail is required',v => /.+@.+/.test(v) || 'E-mail must be valid']" required />
					<v-text-field prepend-icon="mdi-lock" outlined dense v-model="password" type="password" label="الرقم السري" :rules="[ v => !!v || 'Password is required', v => (v && v.length >= 6) || 'Name must be at least 6 characters']" required />
				</v-form>
			</v-card-text>
			<v-card-actions class="justify-center flex-column" style="gap:10px">
				<v-chip label large color="primary darken-1" @click.stop="login"> تسجيل دخول</v-chip>
				<v-btn text label @click.stop="resetEmail" color="primary">استعادة الرقم السري</v-btn>
			</v-card-actions>
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
			};
		},
		created() {},

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
					.then((res) => {
						this.$auth
							.login({
								data: {
									email: this.email,
									password: this.password,
									//  useCredentails: true
								},
								rememberMe: true,
								staySignedIn: true,
								fetchUser: true,
								redirect: {
									path: "/",
								},
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
						email: this.email,
					})
					.then((res) => {
						alert(res.data);
					})
					.catch((err) => {
						alert(err);
					});
			},

			close() {
				this.$emit("closeme");
			},
		},
	};
</script>
