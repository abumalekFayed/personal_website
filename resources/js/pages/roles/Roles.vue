<template>
	<v-page @refresh="$store.set('roles')">
		<template slot="header">
			<v-text-field hide-details outlined dense style="max-width:250px" prepend-inner-icon="search" v-model="search" label="Find role" single-line></v-text-field>
		</template>
		<template slot="tools">
			<v-btn color="success" small @click="addNewRole">
				<v-icon left> add</v-icon>اضافة صلاحية
			</v-btn>

		</template>

		<v-data-table :headers="headers" :items="roles" class="elevation-1" :search="search">
			<!-- <template v-slot:[`item.permissions`]="{ item }">
                <v-chip label small class="ma-1" v-for="p in item.permissions" :key="p"> {{p}}</v-chip>
            </template> -->
			<template v-slot:[`item.action`]="{ item }">
				<v-icon class="ml-4 mr-4" @click="$router.push('/users/roles/' + item.id)" color="primary">mdi-eye</v-icon> |
				<v-icon class="ma-2" @click="editRole(item)" color="primary">mdi-pen</v-icon> |
				<v-icon class="ma-2" @click="deleteRole(item.id)" color="error">mdi-close</v-icon>
			</template>
			<div slot="no-data">
				<v-btn color="primary" @click="refreshData()"> Refresh</v-btn>
			</div>
		</v-data-table>

		<!-- add new role -- edit role -->
		<d-dialog v-model="show_role_dialog" width="1100px" title="اضافة صلاحية" @save="save">

			<!-- <v-btn @click="selected=[]">Reset</v-btn> -->

			<v-text-field outlined dense label="Role name" v-model="role.name" :rules="[v=>!!v || 'هذا الحقل مطلوب']"> </v-text-field>

			<v-checkbox class="ma-2" color="error" @change="setAdmin" v-model="selected" label="ادمن" value="admin" />

			<v-card outlined v-for="(route,i) in visibleRoutes" :key="i" class="ma-2">
				<v-checkbox color="success" @change="selectMain(route)" v-model="selected" :label="route.name" :value="route.path" />
				<v-chip-group v-if="route.roles" v-model="selected" multiple column active-class="primary--text">
					<v-chip :disabled="!selected.includes(route.path)" v-for="(role,w) in route.roles" :key="w" :value="route.path + '.' + role" small label>{{$t(role)}} </v-chip>
				</v-chip-group>

				<template v-if="route.children && selected.includes(route.path)">
					<div v-for="(p2,i2) in route.children.filter(c=>c.hide !=true)" :key="i2">
						<div class="d-flex align-center ma-2">
							<v-checkbox style="width:220px;margin:0px" hide-details @change="selectSuper(p2)" class="ml-4" v-model="selected" :label="p2.path.replace(route.path,'')" :value="p2.path" />
							<v-chip-group v-if="p2.roles" v-model="selected" multiple column active-class="primary--text">
								<v-chip :disabled="!selected.includes(p2.path)" v-for="(p3,i3) in p2.roles" :key="i3" :value="p2.path + '.' + p3" small label>{{p3}} </v-chip>
							</v-chip-group>
						</div>

					</div>
				</template>

			</v-card>

		</d-dialog>
	</v-page>
</template>

<script>
	import { get } from "vuex-pathify";
	export default {
		data: () => ({
			selected: [],

			routes: [],

			search: "",
			show_role_dialog: false,
			role: {
				name: "",
				permissions: [],
			},
			headers: [
				{
					text: "Id",
					align: "left",
					value: "id",
				},
				{
					text: "Name",
					align: "left",
					value: "name",
				},
				// {

				//     text: 'Permissions',
				//     align: 'left',
				//     value: 'permissions'
				// },
				{
					text: "Actions",
					align: "left",
					value: "action",
					sortable: false,
				},
			],
		}),

		created() {
			if (this.roles.length == 0) this.$store.set("roles");
			this.routes = this.$router.options.routes;
		},

		computed: {
			...get(["roles"]),

			visibleRoutes() {
				return this.routes.filter(
					(r) => r.hide != true && r.noRole != true && r.meta
				);
			},
		},

		methods: {
			setAdmin() {
				if (this.selected.includes("admin")) {
					this.selected = ["admin"];
				}
			},

			selectMain(route) {
				//clear selection
				this.selected = this.selected.filter((item) => item != "admin");

				if (this.selected.includes(route.path)) {
					if (route.roles) this.selectSuper(route);

					if (route.children) {
						route.children.forEach((r) => {
							if (!r.hide) {
								this.selected.push(r.path);
								this.selectSuper(r);
							}
						});
					}
				} else {
					if (route.roles) this.selectSuper(route);

					if (route.children) {
						route.children.forEach((r) => {
							this.selected = this.selected.filter((item) => item != r.path);

							this.selectSuper(r);
						});
					}
				}
			},

			selectSuper(route) {
				if (!route.roles) return;
				let newar = route.roles.map((r) => route.path + "." + r);

				this.selected = this.selected.filter(function (el) {
					return newar.indexOf(el) < 0;
				});

				if (this.selected.includes(route.path)) {
					this.selected = [...this.selected, ...newar];
				}
			},

			editRole(item) {
				this.role = {
					...item,
				};
				if (!Array.isArray(item.permissions)) item.permissions = [];
				this.selected = item.permissions;
				this.show_role_dialog = true;

				// setTimeout(() => {
				//     this.pages = {}
				//     Object.keys(this.role.permissions).forEach(key => {
				//         this.pages[key] = '1'
				//     })
				//     this.selected = this.role.permissions

				// }, 250);
			},

			addNewRole() {
				this.selected = [];
				this.role = {
					name: "",
					permissions: [],
				};
				this.show_role_dialog = true;
				if (this.$refs.frm) this.$refs.frm.reset();
			},

			deleteRole(id) {
				if (!confirm("Are you sure to delete this role?")) return;
				this.axios
					.delete("role/" + id)
					.then(() => this.$store.set("REMOVE", { _t: "roles", id: id }));
			},

			save() {
				this.role.permissions = this.selected;

				if (this.role.id) {
					this.axios
						.put("role/" + this.role.id, this.role)
						.then((res) => {
							res.data._t = "roles";
							this.$store.set("UPDATE", res.data);
							this.show_role_dialog = false;
						})
						.catch((err) => alert(err.response.data.errors.name));
				} else
					this.axios
						.post("role", this.role)
						.then((res) => {
							res.data._t = "roles";

							this.$store.set("ADD", res.data);
							this.show_role_dialog = false;
						})
						.catch((err) => alert(err.response.data.errors.name));
			},
		},
	};
</script>
