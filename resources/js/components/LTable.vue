<template>
	<div class="l-table">
		<table>
			<thead>
				<td v-if="selectable" style="width:30px">
					<input @input="setSelectAll()" v-model="select_all" type="checkbox">

				</td>
				<th v-for="(header,i) in  _headers" :key="i">{{$t(header.text)}}</th>
				<th v-if="options && options.length>0">{{$t('options')}}</th>
			</thead>
			<tbody>
				<tr v-if="inputs">
					<td v-for="(h,i) in _headers" :key="i">
						<slot :name="'input-' + h.value"> </slot>
					</td>
					<td>
						<slot name="input-options"> </slot>
					</td>
				</tr>

				<tr v-for="(item,i) in items" :key="i">
					<td v-if="selectable" style="width:30px">
						<input type="checkbox" v-model="item.___selected" @change="setTest(item)">
					</td>
					<td v-for="(h,i) in _headers" :key="i">
						<slot :name="h.value" v-bind:item="item">
							{{getValue(item, h.value)}}
						</slot>
					</td>

					<td v-if="options && options.length>0">
						<v-icon v-if="options.includes('show')" color="blue" class="ml-2" @click="$emit('show',item)">mdi-eye </v-icon>
						<v-icon v-if="options.includes('edit')" color="blue" class="ml-2" @click="$emit('edit',item)">mdi-pencil </v-icon>
						<v-icon v-if="options.includes('remove')" color="red" class="ml-2" @click="$emit('remove',item)"> mdi-delete </v-icon>
					</td>

				</tr>
				<!-- totals row -->

				<tr v-if="totals">

					<td v-for="(h,i) in _headers" :key="i" :class="{'lighten':totals.includes(h.value)}">
						<span v-if="totals.includes(h.value)"> {{items.reduce((a, b) => a + parseInt(b[h.value]),	0)}} </span>
					</td>
				</tr>

				<slot name="totals">

				</slot>
			</tbody>
		</table>
	</div>
</template>

<script>
export default {
	props: {
		value: { type: Array },
		items: { type: Array, default: function () { return [] } },
		headers: { type: Array },
		search: { type: String },
		options: { type: Array },
		totals: { type: Array },
		inputs: { type: Boolean },
		selectable: { type: Boolean, default: false },
	},
	data () {
		return {
			select_all: "",
		};
	},

	computed: {
		_headers () {
			return this.headers.map((h) => {
				let items = h.split("|");
				let text = items[0];
				let value = items.length > 1 ? items[1] : items[0];
				return {
					text: text,
					align:  "right",
					value: value,
				};
			});
		},
	},

	methods: {
		setTest (item) {
			this.$emit("input", this.items.filter((i) => i.___selected == true));

		},

		setSelectAll () {
			this.items.forEach((i) => (i.___selected = !this.select_all));
			this.$emit("input", this.items.filter((i) => i.___selected == true));

		},

		getValue (o, s) {
			s = s.replace(/\[(\w+)\]/g, ".$1"); // convert indexes to properties
			s = s.replace(/^\./, ""); // strip a leading dot
			var a = s.split(".");
			for (var i = 0, n = a.length; i < n; ++i) {
				var k = a[i];
				if (k in o) {
					o = o[k];
				} else {
					return;
				}
			}
			return o;
		},
	},
};
</script>

<style lang="scss" scoped>
.lighten {
	background-color: rgb(191, 255, 185);
}

.l-table {
	// width: 100%;
	overflow-x: auto;
	background-color: white;
	padding: 5px;
	table {
		border-collapse: collapse;
		width: 100%;

		td {
			padding: 5px;
			border-bottom: solid 1px rgb(238, 238, 238);
			text-align: center;
		}

		th {
			min-width: 100px;
			padding: 5px;
			border-bottom: solid 1px rgb(238, 238, 238);
			text-align: center;
		}
	}
}
</style>
