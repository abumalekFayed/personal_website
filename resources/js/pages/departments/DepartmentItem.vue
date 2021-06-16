<template>
    <v-card>
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
        <v-card-text>
            <div
                class="fotorama grey lighten-2"
                data-allowfullscreen="true"
                data-nav="thumbs"
                data-maxheight="300"
                data-width="800"
                data-height="400"
            >
                <template v-if="item && item.department.file_type == 'image'">
                    <img
                        v-for="document in item.documents"
                        :key="document.id"
                        :src="'/storage/' + document.path"
                    />
                </template>
                <template v-else>
                    <a
                        :href="
                            'https://youtube.com/watch?v=' + item.youtube_link
                        "
                        >Desert Rose</a
                    >
                </template>
            </div>
        </v-card-text>

        <v-card-text>
            {{ item.description }}
        </v-card-text>
    </v-card>
</template>

<script>
export default {
    data() {
        return {
            show_add_item: false,
            item: "",
            gallery: [],
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
            ]
        };
    },

    mounted() {
		this.fetchDepartment();
        setTimeout(() => {
            this.fetchDepartmentItem();
        }, 350);
    },

    methods: {
        loadFotorama() {
            let script = document.createElement("script");
            script.src =
                "https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js";
            document.documentElement.firstChild.appendChild(script);
        },
        fetchDepartmentItem() {
            axios
                .get("department-item/" + this.$route.params.item_id)
                .then(res => {
                    this.item = res.data;
                    this.loadFotorama();
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
