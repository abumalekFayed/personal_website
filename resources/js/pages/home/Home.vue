<template>
	<div>
		<v-breadcrumbs :items="titles">
			<template v-slot:item="{ item }">
				<v-breadcrumbs-item :to="item.href" :disabled="item.disabled">
					<v-icon class="ml-2">{{ item.icon }}</v-icon>
					<span :class="item.disabled == false ? 'primary--text' : ''">{{ item.text }}</span>
				</v-breadcrumbs-item>
			</template>
		</v-breadcrumbs>

		<div class="d-flex justify-center">
			<div class="fotorama" data-allowfullscreen="true" data-autoplay="true" data-nav="thumbs" data-max-height="350" data-width="800" data-ratio="440/380">
				<img v-for="(img,i) in slider_images" :src="`/storage/${img.path}`" height="120px" />
			</div>
		</div>

		<v-divider></v-divider>
		<v-row class="justify-center mt-2">
			<v-col cols="6" sm="6" md="3" v-for="(department, i) in activeDepartments" :key="i">

				<v-card :to="'/departments/' + department.id">

					<v-card-text style="font-size:14px" class="primary white--text d-flex justify-center align-center flex-column">
						<v-icon large dark>{{department.icon}}</v-icon>
						{{department.name}}
					</v-card-text>

				</v-card>
			</v-col>

		</v-row>

		<!-- <v-layout row wrap justify-space-between>
			<v-flex xs12 sm12 md11 lg11 class="mx-auto p-0 row mt-10 elevaion-3">
				<v-btn class="fullWC mx-auto" style="height: 10rem !important;" color="blue darken-3 mt-3 text-center" dark v-for="(department, i) in activeDepartments" :key="i" :to="'/departments/' + department.id">
					<h3 class="text-center mx-auto mt-5 text-center" style="width: 100% !important">
						<v-icon class="large-font w-100 text-center" style="width: 100% !important">
							{{ department.icon }}
						</v-icon>
						<br />
						{{ department.name }}
					</h3>
				</v-btn>
			</v-flex>
		</v-layout> -->
	</div>
</template>

<script>
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
						src: require("../../assets/otibi2.jpg").default
					},
					{
						src: require("../../assets/otibi3.jpg").default
					},
				],
				slides2: [],
				titles: [
					{
						href: "/",
						icon: "mdi-home",
						text: "الرئيسية",
						disabled: true
					}
				]
			};
		},


		computed: {
			...get(["print_title", "print_type", 'settings', "departments", 'slider_images']),



			activeDepartments() {
				return this.departments.filter(d => d.state == "active");
			}
		},
		mounted() {
			if (this.slider_images.length == 0) {
				this.$store.set('sliderImages').then(() => {

					this.loadFotorama();

				})

			} else {
				this.loadFotorama();
			}

		},

		methods: {
			loadFotorama() {



				let script = document.createElement("script");
				script.src =
					"https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js";
				document.documentElement.firstChild.appendChild(script);



			},

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

		created() {
			if (this.slider_images.length == 0) this.$store.set('sliderImages')

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
	// @font-face {
	// 	font-family: kufy;
	// 	src: url("../../assets/fonts/18 Khebrat Musamim Bold.ttf");
	// }
	// #app {
	// 	font-family: kufy !important;
	// }
	// .v-btn__content {
	//     display: flex;
	//     flex-direction: column;
	// 	text-align: center !important;
	// }
</style>
