<template>
	<div>
		<div class="d-flex align-center">
			<v-btn class="mx-2" v-if="$auth.check()" fab dark color="info" @click="addDepartment()">
				<v-icon size="50" color="white">mdi-plus</v-icon>
			</v-btn>
			<h2 class="ph" style="flex:1">
				الاقسام الرئيسية
			</h2>
			<v-btn class="mx-2" fab dark color="info" to="/">
				<v-icon size="40" color="white" @click="addDepartment()">mdi-home</v-icon>
			</v-btn>

		</div>

		<v-card outlined v-for="department in departments" :key="department.id" rounded class="ma-2" @click="$router.push('/departments/'+ department.id )">
			<v-card-title class="justify-center">
				{{department.order + '  ' + department.name}}
			</v-card-title>

			<v-card-actions v-if="$auth.check()">
				<v-spacer></v-spacer>
				<v-chip class="mx-2" @click.stop="editDepartment(department)">تعديل</v-chip>
				<v-chip color="error" @click.stop="deleteDepartment(department.id)">حذف</v-chip>
			</v-card-actions>
		</v-card>

		<d-dialog v-model="show_add_item" width="500px" title=" اضافة قسم" @save="saveDepartment">
			<v-text-field outlined dense v-model="department.name" label="القسم" :rules="[ v => !!v || 'Name is required']" required />
			<v-text-field outlined type="number" dense v-model="department.order" label="الترتيب" :rules="[ v => !!v || 'Name is required']" required />
			<v-select outlined dense label="الحالة" :items="[{id:'active',name:'نشط'},{id:'in_active',name:'غير نشط'}]" item-text="name" item-value="id" v-model="department.state"></v-select>
			<v-select outlined dense label="اللون" :items="['primary','secondary','success','info','error']" v-model="department.color"></v-select>
			<v-select outlined dense label="النوع" :items="['image','video']" v-model="department.file_type"></v-select>
		</d-dialog>
	</div>
</template>

<script>
	import jsonToFormDataMin from "../../plugins/jsonToFormData.min";
	import { get } from "vuex-pathify";
	export default {
		data() {
			return {
				show_add_item: false,
				department: {
					state: "active",
				},
				search: "",
			};
		},

		created() {
			if (this.departments.length == 0) this.$store.set("departments");
		},

		computed: {
			...get(["departments"]),
		},
		methods: {
			addDepartment() {
				this.department = {
					img: [],
					color: "primary",
				};
				this.show_add_item = true;
			},

			saveDepartment() {
				if (this.department.id) return this.updateDepartment();
				const data = jsonToFormData(this.department);
				this.axios.post("department", data).then((res) => {
					this.$store.set("add", {
						_t: "departments",
						...res.data,
					});
					this.show_add_item = false;
				});
			},

			updateDepartment() {
				this.department._method = "put";
				const data = jsonToFormData(this.department);

				this.axios.post("department/" + this.department.id, data).then((res) => {
					this.$store.set("update", {
						_t: "departments",
						...res.data,
					});

					this.show_add_item = false;
				});
			},

			editDepartment(department) {
				this.department.id = department.id;
				this.department.name = department.name;
				this.department.order = department.order;
				this.department.state = department.state;
				this.department.color = department.color;
				this.department.file_type = department.file_type;
				this.show_add_item = true;
			},

			deleteDepartment(id) {
				if (!confirm("هل انت متاكد من الحذف؟")) return;
				this.axios.delete("department/" + id).then(() => {
					this.$store.set("remove", {
						_t: "departments",
						id: id,
					});
				});
			},
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
</style>
