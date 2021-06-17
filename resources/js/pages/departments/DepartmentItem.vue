<template>
	<v-card class="grey lighten-4">

		<v-breadcrumbs :items="titles">
			<template v-slot:item="{ item }">
				<v-breadcrumbs-item @click="$router.push(item.href)">
					<v-icon class="ml-2">{{ item.icon }}</v-icon>
					<span :class="item.disabled == false ? 'primary--text' : ''">{{ item.text }}</span>
				</v-breadcrumbs-item>
			</template>
		</v-breadcrumbs>

		<v-card-text class="d-flex justify-center">
			<div class="d-flex flex-wrap justify-center">
				<v-card v-for="(document,i) in imgs" :key="document.id" class="ma-1 d-flex align-center justify-center" style="width:150px;height:100px">
					<img @click="showPhotoSwipe(i)" width="100%" style="max-height: 100px;" :src=" document.src" />

				</v-card>
			</div>
			<v-photoswipe :isOpen="isOpen" :items="imgs" :options="options" @close="hidePhotoSwipe"></v-photoswipe>

			<div class="video-container" v-if="item && item.department.file_type == 'video'">
				<iframe :src="`https://www.youtube.com/embed/${item.youtube_link}`" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			</div>
			<!-- <div v-else class="fotorama grey lighten-2" data-allowfullscreen="true" data-nav="thumbs" data-maxheight="300" data-width="800" data-height="400">

				<a :href="'https://youtube.com/watch?v=' + item.youtube_link"></a>
			</div> -->
		</v-card-text>
		<v-card-title class="primary white--text justify-center pa-1">
			{{item.name}}
		</v-card-title>
		<v-card-text class="pa-3">
			<p>{{$moment(item.created_at).format('DD-MM-YYYY')}}</p>
			{{ item.description }}
		</v-card-text>
	</v-card>
</template>

<script>
	import { PhotoSwipe } from 'v-photoswipe'

	export default {
		data() {
			return {
				isOpen: false,
				isOpenGallery: false,
				options: {
					index: 0,
				},

				show_add_item: false,
				item: "",
				gallery: [],
				imgs: [
					// {
					// 	src: 'https://farm4.staticflickr.com/3894/15008518202_c265dfa55f_h.jpg',
					// 	w: 1600,
					// 	h: 1600,
					// 	title: 'This is dummy caption.'
					// }, {
					// 	src: 'https://farm4.staticflickr.com/3902/14985871946_24f47d4b53_h.jpg',
					// 	w: 1600,
					// 	h: 1066,
					// 	title: 'This is dummy caption. It has been placed here solely to demonstrate the look and feel of finished, typeset text.'
					// }
				]

			};
		},

		components: {
			'v-photoswipe': PhotoSwipe,
		},

		computed: {

			titles() {
				if (!this.item) return []

				return [
					{
						href: "/",
						icon: "mdi-home",
						text: "الرئيسية",
						disabled: false
					},
					{
						href: "/departments/" + this.$route.params.id,
						icon: "mdi-graph",
						text: this.item.department.name,
						disabled: false
					},
					{
						href: "",
						icon: "",
						text: "",
						disabled: true
					}
				]

			}
		},

		mounted() {
			this.fetchDepartment();
			setTimeout(() => {
				this.fetchDepartmentItem();
			}, 350);
		},

		methods: {

			getMeta(url) {
				var img = new Image();
				img.src = url;
				return { w: img.width, h: img.height }
			},


			showPhotoSwipe(index) {
				this.isOpen = true
				this.$set(this.options, 'index', index)
			},

			hidePhotoSwipe() {
				this.isOpen = false
			},


			// loadFotorama() {
			// 	let script = document.createElement("script");
			// 	script.src =
			// 		"https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js";
			// 	document.documentElement.firstChild.appendChild(script);
			// },
			fetchDepartmentItem() {
				axios
					.get("department-item/" + this.$route.params.item_id)
					.then(res => {
						this.item = res.data;


						this.imgs = this.item.documents.map(d => {

							return {
								src: '/storage/' + d.path,
								w: d.w,
								h: d.h,
								title: d.name
							}
						})

						//this.loadFotorama();

					});
			},
			fetchDepartment() {
				axios.get("department/" + this.$route.params.id).then(res => {
					this.department = res.data;

				});
			}
		}
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
