<template>
	<v-card class="transparent" style="background-color:transparent" flat :disabled="$store.state.loading">

		<v-tabs>
			<v-tab href="#general">الاعدادات</v-tab>

			<v-tab-item value="general">
				<v-card-text>
					<v-card class="my-2">
						<v-card-title>
							اعدادات عامة </v-card-title>
						<v-card-text>
							<v-text-field dense outlined label="عنوان رئيسي" v-model="general_settings.websit_title"></v-text-field>
							<v-text-field dense outlined label="عنوان فرعي" v-model="general_settings.websit_subtitle"></v-text-field>
							<v-textarea outlined rows="2" label="وصف" v-model="general_settings.description"></v-textarea>
							<v-text-field dense outlined label="العنوان" v-model="general_settings.address"></v-text-field>
							<v-text-field dense outlined label="التليفون" v-model="general_settings.telephons"></v-text-field>
							<v-text-field dense outlined label="الايميل" v-model="general_settings.email"></v-text-field>
							<v-text-field dense outlined type="number" persistent-hint hint="لضمان سرعة استجابة عالية يفضل الا يزيد العدد عن 12 عنصر" label="عدد العناصر في كل صفحة" v-model="general_settings.items_per_page"></v-text-field>
						</v-card-text>
					</v-card>
					<v-card class="my-2">
						<v-card-title>
							روابط التواصل الاجتماعي
						</v-card-title>

						<v-card-text>

							<v-text-field dense outlined label="رابط الفيس بوك" v-model="general_settings.facebook_link"></v-text-field>
							<v-text-field dense outlined label="رابط  تويتر" v-model="general_settings.twitter_link"></v-text-field>
							<v-text-field dense outlined label="رابط  انستحرام" v-model="general_settings.instgram_link"></v-text-field>
							<v-text-field dense outlined label="رابط  قناة اليوتيوب" v-model="general_settings.youtube_link"></v-text-field>

						</v-card-text>
					</v-card>
					<v-card>
						<v-card-title>
							الخلفيات
						</v-card-title>

						<v-card-text>
							<!-- <v-file-input label="الصور الرئيسية" v-model="slider_images" multiple> </v-file-input> -->

							<v-file-input label="خلفية الشريط العلوي " v-model="app_bar_image"> </v-file-input>

							<v-file-input label="الخلفية الرئيسية" v-model="bg_image"> </v-file-input>
							<v-file-input label="خلفية الشريط الجانبي" v-model="side_bare_image"> </v-file-input>
						</v-card-text>
					</v-card>

				</v-card-text>

				<v-card-actions class="justify-center">
					<v-btn color="success" @click="saveSettings">حفظ الاعدادات</v-btn>

				</v-card-actions>
			</v-tab-item>

			<v-tab href="#slider">محرك الصور</v-tab>

			<v-tab-item value="slider">
				<v-card>
					<v-card-title>
						الخلفيات
						<v-spacer></v-spacer>
						<v-btn small elevation="" color="" @click="addDocument">اضافة صورة</v-btn>

					</v-card-title>

					<v-card-text>

						<v-card v-for="(img,i) in slider_images" :key="i">
							<v-card-title class="justify-center">
								<img height="120px" :src="/storage/ + img.path">
							</v-card-title>
							<v-card-actions class="justify-center">
								<v-btn small elevation="" color="" @click="deleteSliderImage(img.id)">حذف</v-btn>

							</v-card-actions>
						</v-card>

						<d-dialog v-model="show_add_document_dialog" width="500px" title="اضافة صورة" @save="saveDocument()">
							<div class="upload-example">
								<div class="justify-center d-flex pa-2" style="gap:10px">
									<v-btn small @click="$refs.file.click()">Load image</v-btn>
									<v-btn small @click="rotate(90)">Rotate</v-btn>

								</div>
								<div style="max-width:300px">
									<cropper class="upload-example-cropper" ref="cropper" :src="image" />

								</div>

								<input class="d-none" type="file" ref="file" @change="loadImage($event)" accept="image/*">

							</div>

							<v-text-field outlined dense label="العنوان" v-model="document.name"></v-text-field>
						</d-dialog>

					</v-card-text>
				</v-card>
			</v-tab-item>
		</v-tabs>

	</v-card>
</template>

<script>
	import { get } from "vuex-pathify";
	import jsonToFormDataMin from "../../plugins/jsonToFormData.min";
	import { Cropper } from 'vue-advanced-cropper'
	import 'vue-advanced-cropper/dist/style.css';

	export default {
		data() {
			return {
				show_add_document_dialog: false,
				image: '',
				document: {
					image: [],
				},
				bg_image: [],
				side_bare_image: [],
				main_image: [],

				app_bar_image: [],
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
			...get(["general_settings", 'slider_images']),
		},
		components: {
			Cropper
		},
		created() {
			this.$store.set('sliderImages')
		},

		methods: {
			addDocument() {
				this.document = { image: [], name: '' }
				this.show_add_document_dialog = true
			},
			saveDocument() {
				const { canvas } = this.$refs.cropper.getResult();
				if (!canvas) return alert('please select image')
				canvas.toBlob(blob => {
					this.document.image = blob;
					let data = window.jsonToFormData(this.document)
					this.axios.post('/document', data)
						.then((res) => {
							this.$store.set('add', { _t: 'slider_images', ...res.data })
							this.show_add_document_dialog = false
						})
				})
			},

			deleteSliderImage(id) {
				if (!confirm('هل انت متاكد من الحذف')) return
				this.axios.delete('document/' + id).then(() => {
					this.$store.set('remove', { _t: 'slider_images', id: id })
				})
			},

			loadImage(event) {
				// Reference to the DOM input element
				var input = event.target;
				// Ensure that you have a file before attempting to read it
				if (input.files && input.files[0]) {
					// create a new FileReader to read this image and convert to base64 format
					var reader = new FileReader();
					// Define a callback function to run, when FileReader finishes its job
					reader.onload = (e) => {
						// Note: arrow function used here, so that "this.imageData" refers to the imageData of Vue component
						// Read image as base64 and set to imageData
						this.image = e.target.result;
					};
					// Start the reader job - read file as a data url (base64 format)
					reader.readAsDataURL(input.files[0]);
				}
			},
			flip(x, y) {
				this.$refs.cropper.flip(x, y);
			},
			rotate(angle) {
				this.$refs.cropper.rotate(angle);
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
					app_bar_image: this.app_bar_image
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
