<template>
	<v-card flat :loading="$store.state.loading">
		<v-breadcrumbs :items="titles" class="grey lighten-2">

			<template v-slot:item="{ item }">
				<v-breadcrumbs-item :to="item.href" :disabled="item.disabled">
					<v-icon class="ml-2">{{ item.icon }}</v-icon>
					<span>{{ item.text }}</span>
				</v-breadcrumbs-item>
			</template>

		</v-breadcrumbs>

		<div class="grey lighten-2 d-flex align-center pa-2">
			<v-btn v-if="$auth.check()" class="mx-2" dark color="info" @click="addDepartmentItem()">
				<v-icon size="30" color="white">mdi-plus</v-icon>
			</v-btn>
			<v-spacer></v-spacer>
			<v-text-field clearable v-model="search" hide-details outlined dense style="max-width:300px" placeholder="بحث" prepend-inner-icon="mdi-magnify"></v-text-field>
		</div>

		<div class="d-flex align-center justify-center flex-wrap grey lighten-3" style="gap:10px">

			<v-card style="gap:20px;width:250px" v-for="item in items.data" :key="item.id" rounded class="ma-2 mt-5" @click=" $router.push(
                            '/departments/' + item.department_id + '/' + item.id
                        )
                    ">

				<v-card-title style="gap:20px" v-if="department.file_type == 'video'" class="justify-center pa-0">
					<div class="video-container">
						<iframe :src="`https://www.youtube.com/embed/${item.youtube_link}`" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
					</div>
				</v-card-title>

				<v-card-title v-else class="justify-center pa-0" style="gap:20px">
					<img :src="'/storage/' + item.main_image" width="250" height="170" onerror="this.src='/storage/imgs/bg1.jpg'" />
				</v-card-title>

				<v-card-text style="font-size:14px;height:50px" class="primary text-center white--text d-flex align-center justify-center pa-1">{{item.name}}</v-card-text>

				<v-card-text class="pt-1">
					<p> {{$date().format('DD-MM-YYYY')}}</p>
					<small>{{
                            $moment(item.created_at).format("dddd DD  MMM  YYYY")
                        }}</small>
					<v-divider class=""></v-divider>
					<div style="height:50px">{{ ellipsify(item.description_summary) }}</div>
				</v-card-text>

				<v-card-actions class="justify-center py-0">
					<v-btn color="primary" text>قرائة المزيد</v-btn>
					<v-icon v-if="$auth.check()" @click.stop="editDepartmentItem(item)">mdi-pencil</v-icon>
					<v-icon v-if="$auth.check()" color="error" @click.stop="deleteDepartmentItem(item.id)">mdi-close</v-icon>
				</v-card-actions>
			</v-card>
		</div>

		<d-dialog v-model="show_add_item" width="500px" title="اضافة" @save="saveDepartmentItem">
			<v-text-field outlined dense v-model="item.name" label="العنوان" :rules="[v => !!v || 'هذا الحقل مطلوب']" required />
			<v-file-input v-if="department.file_type == 'image'" clearable prepend-icon="" prepend-inner-icon="mdi-file" multiple outlined dense v-model="item.documents" label="الملفات"></v-file-input>
			<v-text-field v-if="department.file_type == 'video'" outlined dense v-model="item.youtube_link" label="رابط اليوتويب" :rules="[v => !!v || 'Name is required']" required />
			<v-text-field outlined dense v-model="item.description_summary" label="الوصف المختصر" :rules="[v => !!v || 'هذا الحقل مطلوب']" required />

			<h4>الوصف</h4>
			<div contenteditable class="grey lighten-4 pa-2 border" ref="html_content" style="border:solid 1px grey !important"></div>
			<!-- <v-textarea contenteditable outlined dense label="الوصف" v-model="item.description"></v-textarea> -->

		</d-dialog>
		<v-card-actions class="justify-center">
			<v-pagination v-model="page" :length="items.last_page"></v-pagination>
		</v-card-actions>
	</v-card>
</template>

<script>
	import jsonToFormDataMin from "../../plugins/jsonToFormData.min";
	import { get } from 'vuex-pathify'
	export default {
		data() {
			return {
				test: '',
				overlay: false,
				show_add_item: false,
				department: "",
				item: {
					documents: []
				},
				items: {
					last_page: 0
				},
				search: "",
				page: 1,
				titles: [
					{
						href: "/",
						icon: "mdi-home",
						text: "الرئيسية",
						disabled: false
					},
					{
						href: "",
						icon: "",
						text: "",
						disabled: true
					}
				]
			};
		},

		created() {
			this.fetchDepartment();
			this.fetchDepartmentItems()
			this.$moment().local("ar");
		},
		watch: {
			page(v) {
				this.fetchDepartmentItems()
			},
			search() {
				if (this.typing) {
					clearTimeout(this.typing);
					this.typing = null;
				}

				this.typing = setTimeout(() => {
					this.fetchDepartmentItems()
				}, 800);
			}
		},
		computed: {
			...get(['_settings']),
			filteredItems() {
				if (!this.search) return this.department.items;
				return this.department.items.filter(item =>
					item.name.includes(this.search)
				);
			}
		},

		methods: {

			fetchDepartment() {
				axios.get("department/" + this.$route.params.id).then(res => {
					this.department = res.data;
					this.titles[1].text = res.data.name;
					this.titles[1].icon = res.data.icon;
				});
			},

			fetchDepartmentItems() {
				let params = {
					department_id: this.$route.params.id,
					page: this.page,
					search: this.search,
					items_per_page: this._settings('general').items_per_page
				}
				if (this.search) params.page = 1

				axios.get("department-item", { params }).then(res => this.items = res.data);
			},

			ellipsify(str) {
				if (!str) return ''
				if (str.length > 90) {
					return str.substring(0, 90) + "...";
				} else {
					return str;
				}
			},

			addDepartmentItem() {
				this.item = {
					department_id: this.$route.params.id,
					documents: []
				};
				this.show_add_item = true;
			},

			saveDepartmentItem() {
				if (this.item.id) return this.updateDepartmentItem();

				let html = this.$refs.html_content.innerHTML.replace(/(?:^(?:&nbsp;)+)|(?:(?:&nbsp;)+$)/g, '');
				this.item.description = html

				const data = jsonToFormData(this.item);
				this.axios.post("department-item", data).then(res => {
					this.items.data.unshift(res.data);
					this.show_add_item = false;
				});
			},

			updateDepartmentItem() {
				this.item._method = "put";
				let html = this.$refs.html_content.innerHTML.replace(/(?:^(?:&nbsp;)+)|(?:(?:&nbsp;)+$)/g, '');
				this.item.description = html
				const data = jsonToFormData(this.item);

				this.axios
					.post("department-item/" + this.item.id, data)
					.then(res => {
						let index = this.department.items.findIndex(
							item => item.id == this.item.id
						);
						if (index != -1)
							this.department.items.splice(index, 1, res.data);
						this.show_add_item = false;
					});
			},

			editDepartmentItem(item) {
				this.item.name = item.name;
				this.item.description_summary = item.description_summary
				this.item.id = item.id;
				this.item.documents = [];
				this.show_add_item = true;
				setTimeout(() => {
					this.$refs.html_content.innerHTML = item.description
				}, 500);
			},

			deleteDepartmentItem(id) {
				if (!confirm("هل انت متاكد من الحذف؟")) return;
				this.axios.delete("department-item/" + id).then(() => {
					this.department.items = this.department.items.filter(
						item => item.id != id
					);
				});
			}

			// addFile() {
			// 	this.file = { data: [], name: "", type: "" };
			// 	this.show_add_file = true;
			// },

			// saveFile() {
			// 	this.item.documents.push(this.file);
			// 	this.show_add_file = false;
			// },
		},

	};
</script>

<style lang="scss" scoped>
	.ph {
		background: #bcc3be;
		padding: 10px;
		border-radius: 30px;
		margin: 10px;
		text-align: center;
	}
	.video-container {
		overflow: hidden;
		position: relative;
		width: 100%;
	}

	.video-container::after {
		padding-top: 56.25%;
		display: block;
		content: "";
	}

	.video-container iframe {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
	}
	.v-card--reveal {
		align-items: center;
		bottom: 0;
		justify-content: center;
		opacity: 0.5;
		position: absolute;
		width: 100%;
	}
</style>
