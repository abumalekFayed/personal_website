<template>

	<v-card>
		<v-toolbar class="primary white--text" dark>
			<v-toolbar-title>
				{{item.name}}
			</v-toolbar-title>
			<v-spacer></v-spacer>

			<v-btn class="mx-2" dark color="info" @click="$router.back()">
				<v-icon size="40" color="white">mdi-arrow-left</v-icon>
			</v-btn>

			<v-btn class="mx-2" dark color="info" to="/">
				<v-icon size="30" color="white">mdi-home</v-icon>
			</v-btn>
		</v-toolbar>

		<v-card-text class="d-flex align-center justify-center" style="gap:10px" v-if="item.department.file_type=='image'">
			<a v-for="document in item.documents" class="spotlight" :href="'/storage/' + document.path">
				<img width="100" height="100" :src="'/storage/' + document.path">
			</a>
		</v-card-text>
		<v-card-title v-else>
			<div class="video-container">
				<iframe :src="`https://www.youtube.com/embed/${item.youtube_link}`" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			</div>
		</v-card-title>
		<v-card-text>
			{{item.description}}
		</v-card-text>
	</v-card>

</template>

<script>
	import jsonToFormDataMin from "../../plugins/jsonToFormData.min";
	export default {
		data() {
			return {
				show_add_item: false,
				item: "",
			};
		},
		created() {
			this.fetchDepartmentItem();
		},
		methods: {
			fetchDepartmentItem() {
				axios.get("department-item/" + this.$route.params.item_id).then((res) => {
					this.item = res.data;
				});
			},
		},
	};
</script>

<style lang="scss" scoped>
	.ph {
		background: #ffffff;
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
