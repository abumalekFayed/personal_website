<template>

	<div v-if="$vuetify.breakpoint.xs" class="mb-2">
		<span class="ma-2">{{title}}</span>
		<div class="t-data">
			<slot></slot>
		</div>
	</div>

	<tr v-else>
		<th :class="dense?'t-title-dense':'t-title'">{{title}}
			<slot name="tools"></slot>
		</th>
		<td class="t-data">
			<slot></slot>
		</td>
	</tr>
</template>

<script>
	export default {
		props: {
			title: {
				type: String,
				default: "",
			},
			type: {
				type: String,
				default: "row",
			},
			with_avatar: {
				type: Boolean,
				default: false,
			},
			icon_path: {
				type: String,
				default: "",
			},
			dense: {
				type: Boolean,
				default: false,
			},
		},

		computed: {
			state() {
				let colors = {
					error: "#ff101075",
					success: "#a2ffcc",
					primary: "#a0d1f9",
				};
				return colors[this.type] || "#ececec";
			},
		},
	};
</script>

<style scoped>
	.t-block {
		display: flex;
	}
	.t-title {
		padding: 8px;
		border-bottom: rgb(219, 219, 219) 1px solid;
		min-width: 150px;
		max-width: 150px;
		color: rgb(0, 0, 0);
		text-align: center;
		/* border-right: solid 1px #e9e9e9; */
		font-weight: 500;
		vertical-align: middle;
	}
	.t-title-dense {
		padding: 2px;
		min-width: 140px;
		border-bottom: #bbbbbb solid 1px;
		font-weight: lighter;
		font-family: sans-serif;
		display: flex;
		align-content: center;
		justify-content: center;
	}
	.t-data {
		padding: 8px;
		border-bottom: #e9e9e9 solid 1px;
		/* background-color: #f5f5f5; */
		color: grey;
		width: 100%;
		min-width: 300px;
	}

	.d-tools {
		display: flex;

		align-items: center;
		justify-content: center;
		gap: 10px;
	}
</style>