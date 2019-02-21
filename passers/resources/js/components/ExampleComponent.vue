<template>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>School</th>
                        <th>Passers</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="tag in SchoolPassers.data" :key="tag.school">
                        <td>{{ tag.school }}</td>
                        <td>{{ tag.passers }}</td>
                    </tr>
                </tbody>
            </table>
            <pagination :data="SchoolPassers" :limit="10" @pagination-change-page="getResults"></pagination>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data() {
            return {
                SchoolPassers: {},
            }
        },
        created() {
            this.getResults();
        },
        methods: {
            getResults(page) {
                if (typeof page === 'undefined') {
                    page = 1;
                }
      
                this.$http.get('/school/passers?page=' + page)
                    .then(response => {
                        return response.json(); })
                    .then(data => {
                        this.SchoolPassers = data; });
            }
        }
    }
</script>
