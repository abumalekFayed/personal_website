<template>
	<div class="d-panel">
		<div class="panel-header">
			<slot name="icon"></slot>
			<span class="panel-title"> {{ $t(title)}}</span>
			<v-spacer></v-spacer>
			<slot name="tools"></slot>
		</div>
		<div class="pa-2">
			<slot></slot>
		</div>
	</div>
</template>

<script>
	export default {
		props: ["title", "value"],
	};
</script>

<style lang="scss" scoped>
	.d-panel {
		// border-bottom: rgb(223, 223, 223) 6px solid;
		margin: 0px 0px 12px 0px;
		background-color: #ffffff;
		border-radius: 5px;
		border: solid 1px #d7d9e2;
		// background-image: url('/storage/imgs/bg2.png');background-repeat: repeat
		//overflow-x: auto;
	}
	.panel-header {
		display: flex;
		align-items: center;
		margin-right: 10px;
		margin-left: 10px;
		// background-color: rgb(241, 234, 234);
	}
	.panel-title {
		display: inline-block;
		// width: 150px;
		padding: 10px;
		font-weight: bold;
		font-size: 18px;
		color: rgb(29, 29, 29);

		// background-color: rgb(241, 234, 234);
	}
</style>
