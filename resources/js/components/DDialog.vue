<template>
	<v-dialog class="d-dialog" v-model="show" :max-width="width" persistent>
		<v-card :disabled="$store.state.loading" class="grey lighten-3">
			<v-card-title>
				{{ title }}
			</v-card-title>

			<v-card-text class="pt-2 mt-2 white">
				<v-form lazy-validation ref="frm">
					<slot></slot>
				</v-form>
			</v-card-text>
			<v-card-actions class="justify-center">
				<slot name="tools">
					<v-btn large v-if="!close_only" @click="save" color="success">
						حفظ
					</v-btn>

					<v-btn @click="close">الغاء</v-btn>
				</slot>

			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
	export default {
		props: ["value", "width", "title", "close_only", "disable_loading"],
		data() {
			return {
				show: this.value,
			};
		},

		watch: {
			value(val) {
				this.show = val;
				if ((val = true)) return;
				this.$refs.frm ? this.$refs.frm.reset() : "";
			},
		},

		methods: {
			save() {
				if (!this.$refs.frm.validate()) return;
				this.$emit("save");
			},

			close() {
				this.$emit("input", false);
			},
		},
	};
</script>

<style lang="scss" scoped>
	.d-dialog {
		// overflow-x: auto;
		border-left: solid white 10px;
		border-right: solid white 10px;
	}
</style>
