<template>
	<div>

		<v-breadcrumbs :items="titles">
			<template v-slot:item="{ item }">
				<v-breadcrumbs-item :to="item.href" :disabled="item.disabled">
					<v-icon class="ml-2">{{ item.icon }}</v-icon>
					<span>{{ item.text }}</span>
				</v-breadcrumbs-item>
			</template>
		</v-breadcrumbs>

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
				titles: [
					{
						href: "/",
						icon: "mdi-home",
						text: "الرئيسية",
						disabled: false
					},
					{
						href: "/",
						icon: "mdi-card-account-mail",
						text: "تواصل معنا",
						disabled: true
					}
				]
			};
		},
		created() { },
		computed: {},
		methods: {
			saveMessage() {
				// const data = jsonToFormData(this.item);
				this.axios.post("email", this.message).then((res) => {
					alert("تم ارسال الرسالة بنجاح");
					this.$router.push("/");
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
