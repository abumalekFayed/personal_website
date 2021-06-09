<template>
<v-card>
    <v-toolbar dark dense text color="#0d1f76">
        <v-toolbar-title>Reset password </v-toolbar-title>
    </v-toolbar>
    <v-card-text>
        <v-form lazy-validation @keyup.native.enter="login" ref="form">

            <v-text-field v-model="new_password" focusable label="New password" :rules="[ v => !!v || 'new Password is required', v => (v && v.length >= 6) || 'Name must be at least 6 characters']" required />
        </v-form>
    </v-card-text>
    <v-card-actions class="pt-0">
        <v-btn color="red" text="text" @click.stop="ResetPassword" :loading="loading" :disabled="loading">Reset password</v-btn>
    </v-card-actions>

</v-card>
</template>

<script>
export default {
    data() {
        return {
            loading: false,
            new_password: '',

        }
    },
    created() {},

    methods: {

     
        ResetPassword() {
           if(!this.$refs.form.validate())return
           this.axios.post('/resetPassword', {
                    code: this.$route.params.code,
                    new_password: this.new_password
                                   })
                .then(res => {
                    alert(res.data)
                     this.$router.push('/login')
                })
                .catch(err => {
                    console.log(err)
                    alert(err)
                })
        },
        close() {
            this.$emit('closeme')
        }
    }
}
</script>
