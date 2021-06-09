<template>
	<div>

		<v-toolbar flat color="primary lighten-5">
			<v-icon dark color="primary" @click="$router.back()">mdi-arrow-left</v-icon>

			<v-btn color="success" small class="ma-2" @click="">Add user to role </v-btn>
			<v-btn color="success" small class="ma-2" @click="">Edit role </v-btn>

			<v-btn color="primary" class="ma-2" small @click="fetchRole"> Refresh </v-btn>
		</v-toolbar>

		<d-block title="name">
			{{role.name}}
		</d-block>

		<d-block title="permissions">
			<v-btn color="primary" small>Edit</v-btn>

			<ol style="max-height:200px;overflow:auto;margin:10px">
				<li v-for="item in role.permissions" :key="item"> {{ item}} </li>
			</ol>
		</d-block>

		<d-block title="users">
			<v-btn color="primary" small @click="adduserToRole">Add</v-btn>
			<v-data-table :headers="headers" :items="role.users" class="elevation-1" :search="search">
				<template v-slot:[`item.roles`]="{ item }">
					{{item.roles.map(r=>r.name).join('  - ')}}
				</template>

				<template v-slot:[`item.action`]="{ item }">
					<v-icon class="ml-4 mr-4" @click="$router.push('/users/list/' + item.id)" color="primary">visibility</v-icon> |
					<v-icon class="ma-2" @click="deleteUserRole(item.id)" color="error">close</v-icon>
				</template>
			</v-data-table>
		</d-block>

		<!-- add new role -- edit role -->
		<v-dialog v-model="show_select_user" scrollable max-width="500px">
			<v-card :disabled="$store.state.loading" :loading="$store.state.loading">
				<v-card-title>
					{{$t('Add user to role')}}
				</v-card-title>

				<v-card-text>
					<v-combobox :items="users" item-text="name" v-model="selected_user" return-object label="select user" outlined dense append-icon="refresh" @click:append="$store.set('users')"></v-combobox>
				</v-card-text>
				<v-card-actions class="justify-center d-flex">
					<v-btn @click="saveUserToRole" color="success">{{$t('save')}}</v-btn>
					<v-btn @click="show_select_user=false">{{$t('cancel')}}</v-btn>
				</v-card-actions>

			</v-card>
		</v-dialog>
	</div>
</template>

<script>
import {
	get
} from 'vuex-pathify'
export default {
	data: () => ({

		role: '',
		selected_user: '',
		search: '',
		show_role_dialog: false,
		show_select_user: false,

		headers: [{

			text: 'Id',
			align: 'left',
			value: 'id'
		}, {

			text: 'Name',
			align: 'left',
			value: 'name'
		}, {

			text: 'Roles',
			align: 'left',
			value: 'roles'
		},

		{
			text: 'Actions',
			align: 'left',
			value: 'action',
			sortable: false
		}
		],

	}),

	created () {

		this.fetchRole()
	},

	computed: {
		users: get('users'),
		visibleRoutes () {
			return this.routes.filter(r => r.hide != true)
		}
	},

	methods: {
		fetchRole () {
			this.axios.get('roles/' + this.$route.params.id).then(res => this.role = res.data)
		},

		adduserToRole () {
			this.selected_user = ''
			this.show_select_user = true
		},

		saveUserToRole () {
			if (!this.selected_user) return alert('Please select user first')
			this.axios.put('roles/' + this.role.id, { add_user: true, user_id: this.selected_user.id }).then(res => {
				this.role = res.data
				this.show_select_user = false
			})
		},

		editRole (item) {

			this.role = {
				...item
			}
			if (!Array.isArray(item.permissions)) item.permissions = []
			this.selected = item.permissions
			this.show_role_dialog = true

			setTimeout(() => {
				this.pages = {}
				Object.keys(this.role.permissions).forEach(key => {
					this.pages[key] = '1'
				})
				this.selected = this.role.permissions

			}, 250);

		},

		addNewRole () {
			this.selected = []
			this.role = {
				name: '',
				permissions: [],

			}
			this.show_role_dialog = true
			if (this.$refs.frm) this.$refs.frm.reset();
		},

		deleteUserRole (id) {
			if (confirm('Are you sure to delete this role?'))
				this.axios.put('roles/' + this.role.id, { remove_user: true, user_id: id }).then(() => {
					this.role.users = this.role.users.filter(u => u.id !== id)
				})

		},

		save () {
			this.role.permissions = this.selected

			if (this.role.id) {

				this.axios.put('roles/' + this.role.id, this.role)
					.then(res => {
						this.$store.set('UPDATE_ROLE', res.data)
						this.show_role_dialog = false
					}).catch(err => alert(err.response.data.errors.name))
			} else this.axios.post('roles', this.role)
				.then(res => {
					this.$store.set('ADD_ROLE', res.data)
					this.show_role_dialog = false
				}).catch(err => alert(err.response.data.errors.name))
		},

	}
}
</script>
