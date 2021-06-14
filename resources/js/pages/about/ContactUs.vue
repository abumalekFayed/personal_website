<template>
    <div>
        <!-- <div class="d-flex align-center">

			<h2 class="ph" style="flex:1">
				تواصل معنا
			</h2>
			<v-btn class="mx-2" fab dark color="info" to="/">
				<v-icon size="40" color="white">mdi-home</v-icon>
			</v-btn>

		</div> -->
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
        <v-card elevation="3" class="mt-5 d-flex flex-row">
            <!-- <v-card-title class="info white--text">
                راسلنا
            </v-card-title> -->
            <!-- :style="innerWidth > 780 ? 'width: 50%' : 'width: 100%'" -->
            
                <v-img
                    style="height: 100%"
                    :src="require('../../assets/otibi.jpg').default"
					class="cont"
                ></v-img>
           
            <v-card-text class="cont">
                <div style="padding:30px" class="text-center primary--text">
                    يسعدنا تلقي استفساراتكم والاجابة عليها
                </div>

                <v-text-field
                    outlined
                    v-model="message.name"
                    label="الاسم"
                    :rules="[v => !!v || 'حقل الاسم مطلوب']"
                    required
                />
                <v-text-field
                    outlined
                    v-model="message.email"
                    label="الايميل"
                    :rules="[v => !!v || 'حقل الايميل مطلوب']"
                    required
                />
                <v-textarea
                    outlined
                    label="الرسالة"
                    v-model="message.body"
                ></v-textarea>
				<v-btn color="success" @click="saveMessage()"
				class="mr-auto" >ارسال الرسالة</v-btn
                >
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
export default {
    data() {
        return {
            innerWidth: window.innerWidth,
            message: {},
            titles: [
                {
                    href: "/",
                    icon: "mdi-home",
                    text: "الرئيسية",
                    disabled: false
                },
                {
                    href: "/",
                    icon: "mdi-phone-in-talk",
                    text: "تواصل معنا",
                    disabled: true
                }
            ]
        };
    },
    created() {},
    computed: {},
    methods: {
        saveMessage() {
            // const data = jsonToFormData(this.item);
            // this.axios.post("department-item", data).then((res) => {
            // 	this.department.items.unshift(res.data);
            // 	this.show_add_item = false;
            // });
        }
    }
};
</script>

<style lang="scss" scoped>
.ph {
    background: #bcc3be;
    padding: 10px;
    border-radius: 30px;
    margin: 10px;
    text-align: center;
}
.cont {
    width: 50%;
}
@media (max-width: 780px) {
    .cont {
        width: 100%;
    }
}
</style>
