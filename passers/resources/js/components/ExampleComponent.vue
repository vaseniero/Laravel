<template>
    <div class="row">
        <div class="col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">Please Enter for Search</div>
                <div class="panel-body">
                    <div>
                        <input type="text" placeholder="what are you looking for?" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div style="padding: 25px 0px 20px 0px;">&nbsp;</div>
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name of Examinee</th>
                        <th>Campus Eligibility</th>
                        <th>School</th>
                        <th>Division</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="tag in Examinees.data" :key="tag.id">
                        <td>{{ tag.id }}</td>
                        <td>{{ tag.name_of_examinee }}</td>
                        <td>{{ tag.campus_eligibility }}</td>
                        <td>{{ tag.school }}</td>
                        <td>{{ tag.division }}</td>
                    </tr>
                </tbody>
            </table>
            <pagination :data="Examinees" :limit="10" @pagination-change-page="getResults"></pagination>
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
                Examinees: {},
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
      
                this.$http.get('/examinees?page=' + page)
                    .then(response => {
                        return response.json(); })
                    .then(data => {
                        this.Examinees = data; });
            }
        }
    }
</script>
