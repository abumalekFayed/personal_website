<template>
    <v-card class="p-0">
        <!-- <v-toolbar class="primary white--text" dark>
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
		</v-toolbar> -->
        <v-alert dark class="w-100" color="blue darken-3" dense>
            <v-breadcrumbs :items="titles">
                <template v-slot:item="{ item }">
                    <v-breadcrumbs-item
                        :href="item.href"
                        :disabled="item.disabled"
                    >
                        <v-icon class="ml-2">{{ item.icon }}</v-icon>
                        <span
                            :class="item.disabled == false ? 'white--text' : ''"
                            >{{ item.text }}</span
                        >
                    </v-breadcrumbs-item>
                </template>
            </v-breadcrumbs>
        </v-alert>

        <v-card-text
            class="row p-0 mx-auto ml-0"
            v-if="item.department.file_type == 'image'"
        >
            <!-- <a
                v-for="document in item.documents"
                id="lightgallery"
                :href="'/storage/' + document.path"
                :key="document"
                class="item-img  mx-auto"
            >
                <img
                    width="90%"
                    height="200"
                    :src="'/storage/' + document.path"
                    class="mx-auto"
                />
            </a> -->
            <v-slide-group class="pa-4 mx-auto" show-arrows>
                <v-slide-item v-for="slide in slides" :key="slide.src">
                    <v-card class="ma-4" height="200" width="200" link>
                        <a :href="slide.src" class="fotorama">
                            <v-img
                                height="100%"
                                width="100%"
                                :src="slide.src"
                            ></v-img>
                        </a>
                    </v-card>
                    
                </v-slide-item>
            </v-slide-group>
            <div class="fotorama" id="fotorama" style="height: 40px">
                        <img src="https://s.fotorama.io/1.jpg" />
                        <img src="https://s.fotorama.io/2.jpg" />
                        <img src="https://s.fotorama.io/1.jpg">
  <img src="https://s.fotorama.io/2.jpg">
                    </div>
        </v-card-text>

        <v-card-title v-else>
            <div class="video-container">
                <iframe
                    :src="`https://www.youtube.com/embed/${item.youtube_link}`"
                    frameborder="0"
                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                ></iframe>
            </div>
        </v-card-title>
    </v-card>
</template>

<script>
import jsonToFormDataMin from "../../plugins/jsonToFormData.min";
export default {
    data() {
        return {
            show_add_item: false,
            item: "",
            titles: [
                {
                    href: "/",
                    icon: "mdi-home",
                    text: "الرئيسية",
                    disabled: false
                },
                {
                    href: "",
                    icon: "",
                    text: "",
                    disabled: false
                },
                {
                    href: "",
                    icon: "",
                    text: "",
                    disabled: true
                }
            ],
            slides: [
                {
                    src: require("../../assets/otibi.jpg").default
                },
                {
                    src: require("../../assets/otibi.jpg").default
                },
                {
                    src: require("../../assets/otibi.jpg").default
                },
                {
                    src: require("../../assets/otibi.jpg").default
                }
            ]
        };
    },
    created() {
        this.fetchDepartment();
        setTimeout(() => {
            this.fetchDepartmentItem();
        }, 350);
    },
    methods: {
        fetchDepartmentItem() {
            axios
                .get("department-item/" + this.$route.params.item_id)
                .then(res => {
                    this.item = res.data;
                    this.titles[2].text = res.data.name;
                    this.titles[2].icon = "mdi-info";
                });
        },
        fetchDepartment() {
            axios.get("department/" + this.$route.params.id).then(res => {
                this.department = res.data;
                setTimeout(() => {
                    this.titles[1].text = res.data.name;
                    this.titles[1].icon = res.data.icon;
                    this.titles[1].href = `/departments/${this.$route.params.id}`;
                }, 300);
            });
        }
    },
    mounted() {},
    watch: {}
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
.item-img {
    width: 25%;
}
@media (max-width: 780px) {
    .item-img {
        width: 100%;
        background-color: red;
    }
}

</style>
