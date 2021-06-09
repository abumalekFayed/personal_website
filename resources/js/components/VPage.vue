<template>
	<div>
		<div id="header">

			<slot name="tools"></slot>
			<v-chip color="white" v-if="add" label @click="$emit('add')">
				<v-icon left>mdi-plus</v-icon> {{ $t('add') }}
			</v-chip>

			<v-chip color="white" v-if="print" label @click="$emit('print')">
				<v-icon left>mdi-printer</v-icon> {{ $t('print') }}
			</v-chip>

			<v-chip color="white" v-if="exportExc" label @click="$emit('exportExc')">
				<v-icon left>mdi-export</v-icon> {{ $t('export') }}
			</v-chip>
			<v-chip color="white" label @click="$emit('refresh')">
				<v-icon>mdi-refresh</v-icon>
			</v-chip>
			<!-- </template> -->

			<v-spacer></v-spacer>

			<v-btn rounded v-if="!no_back" @click="$router.back()">

				<v-icon>
				mdi-arrow-left

				</v-icon>
			</v-btn>
			<!-- <v-chip  color="primary">{{$t($route.name)}} </v-chip> -->

			<slot name="header"></slot>

		</div>

		<div id="content">
			<slot></slot>
		</div>

	</div>
</template>

<script>
export default {

	data () {
		return {

		}
	},

	props: {
		no_back: { type: Boolean, default: false },
		print: { type: Boolean, default: false },
		exportExc: { type: Boolean, default: false },
		add: { type: Boolean, default: false }

	},
	//  functional: true
}
</script>

<style lang="scss" scoped>
#header {
	display: flex;
	flex-wrap: wrap;
	gap: 10px;
	align-items: center;
	padding: 20px;
}

// @media only screen and (max-width: 600px) {
//     #header {
//         background-color: white;
//         display: flex;
//         flex-wrap: wrap;
//         gap: 10px;
//         align-items: center;
//         justify-content: center;
//         padding: 10px;
//     }
// }

#content {
	padding: 10px;
}
</style>
