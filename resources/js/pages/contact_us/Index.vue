<template>
	<v-card>
		<v-toolbar>
			<v-toolbar-title>
				البريد الوارد
			</v-toolbar-title>
		</v-toolbar>

		<v-card-text>
			<v-data-table :headers="headers" :items="emails">
				<template #item.options="{item}">
					<v-icon color="error">mdi-close</v-icon>
					<v-icon color="success" @click="addReply(item)">mdi-email</v-icon>

				</template>
			</v-data-table>
		</v-card-text>

		<d-dialog v-model="show_add_reply" :title="'ارسال ايميل الي' + reply.to" @save="saveReply" width="600px">

			<p>{{reply.origin}}</p>

			<v-textarea title="الرسالة" outlined v-model="reply.body"></v-textarea>
		</d-dialog>
	</v-card>

</template>

<script>
	export default {
		data() {
			return {
				search: "",
				message: {},
				emails: [],
				headers: [
					{ text: "الاسم", value: "name" },
					{ text: "الايميل", value: "email" },
					{ text: "الرسالة", value: "body" },
					{ text: "خيارات", value: "options" },
				],
				reply: { to: "", origin: "" },
				show_add_reply: false,
				seleced_email: "",
			};
		},
		created() {
			this.fetchEmails();
		},
		computed: {},
		methods: {
			fetchEmails() {
				this.axios.get("email").then((res) => (this.emails = res.data));
			},

			addReply(email) {
				this.reply = { body: "", to: email.email, origin: email.body };
				this.seleced_email = email;
				this.show_add_reply = true;
			},

			saveReply() {
				this.axios.put("email/" + this.seleced_email.id).then((res) => {
					this.show_add_reply = false;
					alert("تم ارسال الرد");
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
