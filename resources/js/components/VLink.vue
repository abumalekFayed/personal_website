<template>
	<v-list-item link :to="tolink" dense>

		<v-avatar color="#00C58E" size="8" />
		<v-list-item-icon v-if="icon1">
			<v-icon>{{icon1}}</v-icon>
		</v-list-item-icon>
		<v-list-item-content>
			<v-list-item-title class="ma-2" v-text="title"></v-list-item-title>
		</v-list-item-content>
		<v-chip dark v-if="icon" class="ma-2" :color="color" x-small>
			{{icon}}
		</v-chip>

	</v-list-item>
</template>

<script>
export default {
	props: ['tolink', 'icon', 'title', 'avatar', 'color', 'icon1'],
	methods: {
		goto () {
			this.$router.push(this.tolink)
		}
	}


}
</script>

<style scoped>
.active_link {
	color: rgb(54, 190, 107) !important;
	font-weight: 600 !important;
	background-color: #64b5f6 !important;
	border-right: 10px solid #0d1f76;
}

a {
	text-decoration: none !important;
}
</style>
