<template>
	<v-app>

		<v-navigation-drawer dark fixed app flat right v-model="drawer">
			<v-list>
				<v-list-item v-if="$auth.check()" class="px-2 primary lighten-5" style="margin-top: -9px;">
					<v-list-item-avatar>
						<v-img src="storage/imgs/logo2.jpg"></v-img>
					</v-list-item-avatar>
					<v-list-item-content>
						<v-list-item-title class="black--text">{{
                                    $auth.user().name
                                }}</v-list-item-title>
						<v-list-item-subtitle> </v-list-item-subtitle>
					</v-list-item-content>
					<v-chip small color="error" class="mx-2" label v-if="$auth.check()" @click="logout">logout</v-chip>
				</v-list-item>
				<template>
					<template v-if=" $auth.check()">
						<v-list-item to="/user-dashboard" color="primary">
							<v-list-item-icon>
								<v-icon>mdi-view-dashboard</v-icon>
							</v-list-item-icon>
							<v-list-item-title>لوحة التحكم</v-list-item-title>
						</v-list-item>
					</template>
					<div v-for="(item, index) in routes" :key="index">

						<template>
							<v-list-item color="indigo" :key="index" :to="item.path" v-if="
                                            !item.hide && $auth.check()">
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

				<v-list-item v-for="(department, i) in activeDepartments" :key="'d_' + department.id" :to="'/departments/' + department.id" color="primary">
					<v-list-item-icon>
						<v-icon>{{ department.icon }}</v-icon>
					</v-list-item-icon>
					<v-list-item-title>{{
                                department.name
                            }}</v-list-item-title>
				</v-list-item>
			</v-list>
		</v-navigation-drawer>

		<v-app-bar color="black" shrink-on-scroll :src="require('./assets/omma.jpg').default" fade-img-on-scroll scroll-target="#scrolling-techniques-3" dark dense flat app>
			<template v-slot:img="{ props }">
				<v-img v-bind="props" gradient="to top right, rgba(100,115,201,.1), rgba(25,32,72,.3)"></v-img>
			</template>

			<v-app-bar-nav-icon @click="drawer = !drawer" class="mt-2"></v-app-bar-nav-icon>

			<div class="mt-3 mb-3" @click="$router.push('/')">
				<h4 style="width:100%">النائب / خالد العتيبي</h4>
			</div>
		</v-app-bar>

		<!--  -->

		<!-- <v-sheet id="scrolling-techniques-2" class="overflow-y-auto" max-height="600"> -->

		<v-main class="grey lighten-4">

			<v-speed-dial v-model="fab2" absolute fixed top left direction="top" transition="slide-y-reverse-transition" style="top: 50%; left: 0%   ">
				<template v-slot:activator>
					<v-btn v-model="fab2" color="blue darken-2" dark fab tile>
						<v-icon v-if="fab2">
							mdi-close
						</v-icon>
						<v-icon v-else>
							mdi-account-circle
						</v-icon>
					</v-btn>
				</template>
				<v-btn fab dark small color="red">
					<v-icon>mdi-email</v-icon>
				</v-btn>
				<v-btn fab dark small color="indigo">
					<v-icon>mdi-facebook</v-icon>
				</v-btn>
				<v-btn fab dark small color="blue lighten-2">
					<v-icon>mdi-twitter</v-icon>
				</v-btn>
			</v-speed-dial>

			<v-container style="max-width:1000px;margin-bottom:180px" class="grey lighten-4">

				<v-btn v-scroll="onScroll" v-show="fab" fab dark fixed bottom right color="primary" @click="toTop">
					<v-icon>mdi-arrow-up </v-icon>
				</v-btn>

				<router-view :key="$route.fullPath">

				</router-view>

			</v-container>

			<v-footer dark padless absolute>
				<v-card flat tile width="100%" class="blue darken-3 text-center">
					<v-card-text>
						<v-btn v-for="icon in icons" :key="icon" class="mx-4" icon dark>
							<v-icon size="24px">
								{{ icon }}
							</v-icon>
						</v-btn>
					</v-card-text>

					<v-divider></v-divider>
					<v-btn color="blue darken-3" style="margin-top: -2rem" dark rounded class="white--text mb-0" small to="/contact-us">
						<v-icon small dark class="ml-3">
							mdi-phone-in-talk</v-icon>
						تواصل معانا
					</v-btn>

					<v-card-text class="white--text  mb-0">
						<strong>جميع الحقوق محفوظة - النائب / خالد
							العتيبي</strong>
						- {{ new Date().getFullYear() }}
					</v-card-text>
				</v-card>
			</v-footer>
		</v-main>

	</v-app>
</template>

<script>
	import vLink from "./components/VLink";
	import {
		bus
	} from "./app.js";
	import {
		get
	} from "vuex-pathify";
	import * as easings from "vuetify/es5/services/goto/easing-patterns";
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
				icons: ["mdi-facebook", "mdi-email", "mdi-twitter", "mdi-linkedin"],
				fab2: false,
				hidden: false,
				tabs: null,
				type: "number",
				number: -9999,
				selector: "#first",
				selections: ["#first", "#second", "#third"],
				selected: "Button",
				duration: 1000,
				offset: 0,
				easing: "easeInOutCubic",
				easings: Object.keys(easings)
			};
		},
		props: ["settings"],

		components: {
			vLink
		},

		computed: {
			// crumbs() {
			// 	let pathArray = this.$route.path.split("/")
			// 	pathArray.shift()
			// 	let breadcrumbs = pathArray.reduce((breadcrumbArray, path, idx) => {
			// 		breadcrumbArray.push({
			// 		path: path,
			// 		to: breadcrumbArray[idx - 1]
			// 		? "/" + breadcrumbArray[idx - 1].path + "/" + path
			// 		: "/" + path,
			// 		text: this.$route.matched[idx] ? this.$route.matched[idx].meta.breadCrumb : '',
			// 		});
			// 		return breadcrumbArray;
			// 	}, [])
			// 	return breadcrumbs;
			// },

			target() {
				const value = 999;
				if (!isNaN(value)) return Number(value);
				else return value;
			},
			options() {
				return {
					duration: 1000,
					offset: -500,
					easing: "easeInOutCubic",
					container: ".yourCard"
				};
			},

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
			console.log(this.settings)
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

	// .v-btn__content {
	//     display: flex;
	//     flex-direction: column;
	// 	text-align: center !important;
	// }
</style>
