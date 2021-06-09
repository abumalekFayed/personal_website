<template>
	<div>
		<div class="d-flex align-center">

			<h2 class="ph" style="flex:1">
				تواصل معنا
			</h2>
			<v-btn class="mx-2" fab dark color="info" to="/">
				<v-icon size="40" color="white">mdi-home</v-icon>
			</v-btn>

		</div>

		<v-card outlined>
			<v-card-title class="info white--text">
				راسلنا
			</v-card-title>
			<v-card-text>
				<div style="padding:30px" class="text-center primary--text">
					يسعدنا تلقي استفساراتكم والاجابة عليها

				</div>

				<v-text-field outlined v-model="message.name" label="الاسم" :rules="[ v => !!v || 'حقل الاسم مطلوب']" required />
				<v-text-field outlined v-model="message.email" label="الايميل" :rules="[ v => !!v || 'حقل الايميل مطلوب']" required />
				<v-textarea outlined label="الرسالة" v-model="message.body"></v-textarea>

			</v-card-text>
			<v-card-actions class="justify-center">
				<v-btn color="success" @click="saveMessage()">ارسال الرسالة</v-btn>

			</v-card-actions>
		</v-card>

	</div>
</template>

<script>
	export default {
		data() {
			return {
				message: {},
			};
		},
		created() {},
		computed: {},
		methods: {
			saveMessage() {
				// const data = jsonToFormData(this.item);
				// this.axios.post("department-item", data).then((res) => {
				// 	this.department.items.unshift(res.data);
				// 	this.show_add_item = false;
				// });
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
