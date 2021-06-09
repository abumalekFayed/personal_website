<template>
	<div class="ma-2 pa-2">
		<p class="text-center">All website settings can be changed from this page ,you can load default settings </p>

		<!-- <v-btn color="success" small tile @click="loadDefaultSettings">Load default</v-btn>
		<v-btn color="success" small tile @click="$store.dispatch('fetchInfos')">Refresh</v-btn>
		<v-btn small tile @click="updateSettings" color="primary lighten-2" :loading="loading" :disabled="loading">Update</v-btn> -->

		<v-text-field dense outlined label="Title" v-model="general_settings.websit_title" class="mt-6"></v-text-field>
		<v-textarea outlined label="Description" v-model="general_settings.description"></v-textarea>
		<v-text-field dense outlined label="Address" v-model="general_settings.address"></v-text-field>
		<v-text-field dense outlined label="Telephone" v-model="general_settings.telephons"></v-text-field>
		<v-text-field dense outlined label="Email" v-model="general_settings.email"></v-text-field>

		<!-- <div class="border">
			<label>Color</label>
			<v-color-picker label="Color" v-model="settings.color" :value="settings.color" @input="changeAppColor" title="Primary color"></v-color-picker>

		</div> -->

		<v-file-input label="الصورة الشخصية" v-model="main_image"> </v-file-input>

		<div style="height:50px">
			<img height="50px" :src="'/storage/'+ general_settings.bg">

		</div>
		<v-file-input label="change background image" v-model="bg_image"> </v-file-input>
		<v-file-input label="change side bar image" v-model="side_bare_image"> </v-file-input>

		<v-btn color="success" @click="saveSettings">Save</v-btn>
	</div>
</template>

<script>
	import { get } from "vuex-pathify";
	import jsonToFormDataMin from "../../plugins/jsonToFormData.min";
	export default {
		data() {
			return {
				bg_image: [],
				side_bare_image: [],
				main_image: [],
				loading: false,
				default_settings: {
					title: "Smart dental clinic",
					description: "Smart dental services",
					address: "Egypt",
					telephons: "123456789-123456789-123456789",
					email: "smart.dental@dental.com",
					work_time: "all dayes",
					color: "#c126a7",
					taxes: 15,
					price_include_taxes: 0,
				},
			};
		},
		computed: {
			...get(["general_settings"]),
		},

		methods: {
			loadDefaultSettings() {
				this.$store.dispatch("fetchDefaultGeneralInfo");
			},

			changeAppColor() {
				this.$vuetify.theme.themes.light.primary = this.settings.color
					? this.settings.color
					: "#005a94";
			},

			saveSettings() {
				const data = {
					data: this.general_settings,
					side_bare_image: this.side_bare_image,
					bg_image: this.bg_image,
					main_image: this.main_image,
				};
				const form_data = jsonToFormData(data);
				axios.post("setting", form_data).then((res) => {
					this.$store.set("settings", res.data);
				});
			},

			updateSettings() {
				let data = window.jsonToFormData({
					_method: "put",
					update_general_info: true,
					data: this.settings,
				});
				this.$store.dispatch("updateGeneralInfo", data);
				if (this.settings.color)
					localStorage.setItem("app_color", this.settings.color);
			},
		},
	};
</script>

<style lang="scss" scoped>
</style>
