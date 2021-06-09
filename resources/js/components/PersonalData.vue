<template>
    <div>
            <table class="table">
                <tr>
                    <th> Name</th>
                    <td style="width:100%">{{ user.name }} </td>
                </tr>

                <tr>
                    <th> Email</th>
                    <td> {{ user.email }}</td>
                </tr>
                <tr>
                    <th>Telephone </th>
                    <td> {{ user.telephone }}</td>
                </tr>
                <tr>
                    <th>Address </th>
                    <td>{{ user.address }} </td>
                </tr>
                <tr>
                    <th>Department </th>
                    <td> <strong v-if=" user.isHead==true"> Head of </strong> {{ user.department }} department </td>
                </tr>
                <tr>
                    <th>Roles </th>
                    <td>{{ user.roles }} </td>
                </tr>
            </table>
    </div>
</template>

<script>
import {
    mapGetters
} from 'vuex'
export default {

    props: ['user'],
    computed: {
        ...mapGetters(['grades', 'roles']),
       
    },
    methods: {
        gradeLabel(id) {
            let grade = this.grades.find(grade => grade.id == id)
            return grade ? grade.text : ''
        },
    }
}
</script>

<style scoped>


th,
td {
    padding: 5px !important
}

td {
    max-width: 100px
}
</style>
