<template>
	<v-card>
		<v-toolbar class="primary white--text" dark>
			<v-toolbar-title>
				{{department.name}}
			</v-toolbar-title>
			<v-spacer></v-spacer>
			<v-text-field v-if="$vuetify.breakpoints =='xs'" hide-details outlined dense prepend-inner-icon="mdi-magnify" placeholder="بحث" v-model="search"></v-text-field>

			<v-btn class="mx-2" v-if="$auth.check()" dark color="info" @click="addDepartmentItem()">
				<v-icon size="30" color="white">mdi-plus</v-icon>
			</v-btn>

			<v-btn class="mx-2" dark color="info" to="/">
				<v-icon size="30" color="white">mdi-home</v-icon>
			</v-btn>
		</v-toolbar>

		<div class="d-flex align-center justify-center flex-wrap" style="gap:10px">
			<v-card v-for="item in filteredItems" :key="item.id" rounded class="ma-2" @click="$router.push('/departments/'+ item.department_id + '/' + item.id)">
				<v-card-title v-if="department.file_type=='video'" class="justify-center" style="gap:20px">
					<div class="video-container">
						<iframe :src="`https://www.youtube.com/embed/${item.youtube_link}`" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
					</div>
				</v-card-title>

				<v-card-title v-else class="justify-center" style="gap:20px">
					<img :src="'/storage/' + item.main_image" width="200" height="120" onerror="this.src='/storage/imgs/bg1.jpg'">
				</v-card-title>

				<v-card-text>
					<h4> {{item.name}} </h4>
					<small>{{$moment(item.created_at).format('YYYY-MM-DD')}}</small>
					<div>{{ellipsify(item.description)}}</div>
					<!-- <p>{{item.description}}</p> -->
				</v-card-text>

				<v-card-actions v-if="$auth.check()" class="justify-center">
					<v-chip small class="mx-2" @click.stop="editDepartmentItem(item)">تعديل</v-chip>
					<v-chip small color="error" @click.stop="deleteDepartmentItem(item.id)">حذف</v-chip>
				</v-card-actions>
			</v-card>
		</div>

		<!-- <v-row class="justify-center">
			<v-col cols="12" sm="6" md="4" lg="3" v-for="item in filteredItems" :key="item.id">
				<v-card outlined rounded class="ma-2" @click="$router.push('/departments/'+ item.department_id + '/' + item.id)">
					<v-card-title v-if="department.file_type=='video'" class="justify-center" style="gap:20px">
						<div class="video-container">
							<iframe :src="`https://www.youtube.com/embed/${item.youtube_link}`" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						</div>
					</v-card-title>

					<v-card-title v-else class="justify-center" style="gap:20px">
						<img :src="'/storage/' + item.main_image" width="200" height="120" onerror="this.src='/storage/imgs/bg1.jpg'">
					</v-card-title>

					<v-card-text>
						<h4> {{item.name}} </h4>
						<small>{{$moment(item.created_at).format('YYYY-MM-DD')}}</small>
						<div>{{ellipsify(item.description)}}</div>
					</v-card-text>

					<v-card-actions v-if="$auth.check()" class="justify-center">
						<v-chip small class="mx-2" @click.stop="editDepartmentItem(item)">تعديل</v-chip>
						<v-chip small color="error" @click.stop="deleteDepartmentItem(item.id)">حذف</v-chip>
					</v-card-actions>
				</v-card>
			</v-col>
		</v-row> -->

		<d-dialog v-model="show_add_item" width="500px" title="اضافة" @save="saveDepartmentItem">
			<v-text-field outlined dense v-model="item.name" label="العنوان" :rules="[ v => !!v || 'Name is required']" required />
			<v-file-input v-if="department.file_type=='image'" clearable prepend-icon="" prepend-inner-icon="mdi-file" multiple outlined dense v-model="item.documents" label="الملفات"></v-file-input>
			<v-text-field v-if="department.file_type=='video'" outlined dense v-model="item.youtube_link" label="رابط اليوتويب" :rules="[ v => !!v || 'Name is required']" required />
			<v-textarea outlined dense label="الوصف" v-model="item.description"></v-textarea>
		</d-dialog>

		<!-- <d-dialog v-model="show_add_file" width="500px" title="اضافة مرفق" @save="saveFile()">
			<v-text-field outlined dense v-model="file.name" label="اسم الملف" :rules="[ v => !!v || 'Name is required']" required />
			<v-file-input clearable prepend-icon="" prepend-inner-icon="mdi-file" outlined dense v-model="file.data" label="الملف " :rules="[ v => !!v || 'Name is required']" required></v-file-input>
			<v-select outlined dense v-model="file.type" label="النوع" :items="['صورة','فيديو','ملف']" :rules="[ v => !!v || 'Name is required']" required></v-select>
		</d-dialog> -->

		<v-card-actions class="justify-center">
			<v-pagination></v-pagination>
		</v-card-actions>
	</v-card>
</template>

<script>
	import jsonToFormDataMin from "../../plugins/jsonToFormData.min";
	export default {
		data() {
			return {
				show_add_item: false,
				department: "",
				item: {
					documents: [],
				},
				search: "",
			};
		},

		created() {
			this.fetchDepartment();
		},

		computed: {
			filteredItems() {
				if (!this.search) return this.department.items;
				return this.department.items.filter((item) =>
					item.name.includes(this.search)
				);
			},
		},

		methods: {
			fetchDepartment() {
				axios.get("department/" + this.$route.params.id).then((res) => {
					this.department = res.data;
				});
			},

			ellipsify(str) {
				if (str.length > 20) {
					return str.substring(0, 20) + "...";
				} else {
					return str;
				}
			},

			addDepartmentItem() {
				this.item = {
					department_id: this.$route.params.id,
					documents: [],
				};
				this.show_add_item = true;
			},

			saveDepartmentItem() {
				if (this.item.id) return this.updateDepartmentItem();
				const data = jsonToFormData(this.item);
				this.axios.post("department-item", data).then((res) => {
					this.department.items.unshift(res.data);
					this.show_add_item = false;
				});
			},

			updateDepartmentItem() {
				this.item._method = "put";
				const data = jsonToFormData(this.item);

				this.axios.post("department-item/" + this.item.id, data).then((res) => {
					let index = this.department.items.findIndex(
						(item) => item.id == this.item.id
					);
					if (index != -1) this.department.items.splice(index, 1, res.data);
					this.show_add_item = false;
				});
			},

			editDepartmentItem(item) {
				this.item.name = item.name;
				this.item.description = item.description;
				this.item.id = item.id;
				this.item.documents = [];
				this.show_add_item = true;
			},

			deleteDepartmentItem(id) {
				if (!confirm("هل انت متاكد من الحذف؟")) return;
				this.axios.delete("department-item/" + id).then(() => {
					this.department.items = this.department.items.filter(
						(item) => item.id != id
					);
				});
			},

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
</style>
