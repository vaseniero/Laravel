<template>
  <div class="data-table">
    <div class="row">
      <div class="col-sm-8 col-sm-4">
          <div class="panel panel-default">
              <div class="panel-heading">Please Enter for Search</div>
              <div class="panel-body">
                  <div>
                      <input id="searchTerm" type="text" placeholder="what are you looking for?" @keyup.enter="searchNow($event)" class="form-control">
                  </div>
              </div>
          </div>
      </div>
    </div>
    <div class="row" style="padding: 10px 0px 10px 0px;">
      <div class="col-sm-12">
        <table class="table table-striped">
          <thead>
          <tr>
            <th class="table-head">#</th>
            <th v-for="column in columns" :key="column" @click="sortByColumn(column)" class="table-head">
              {{ column | columnHead }}
                <span v-if="column === sortedColumn">
                    <i v-if="order === 'asc' " class="fas fa-arrow-up"></i>
                    <i v-else class="fas fa-arrow-down"></i>
                </span>          
            </th>
          </tr>
          </thead>
          <tbody>
          <tr class="" v-if="tableData.length === 0">
            <td class="lead text-center" :colspan="columns.length + 1">No data found.</td>
          </tr>
          <tr v-for="(data, key1) in tableData" :key="key1" class="m-datatable__row" v-else>
            <td v-for="(value, key) in data" :key="key">{{ value }}</td>
          </tr>
          </tbody>
        </table>
        <nav v-if="pagination && tableData.length > 0">
          <ul class="pagination">
            <li class="page-item" :class="{'disabled' : currentPage === 1}">
              <a class="page-link" href="#" @click.prevent="changePage(currentPage - 1)">Previous</a>
            </li>
            <li v-for="page in pagesNumber" :key="page" class="page-item"
                :class="{'active': page == pagination.meta.current_page}">
              <a href="javascript:void(0)" @click.prevent="changePage(page)" class="page-link">{{ page }}</a>
            </li>
            <li class="page-item" :class="{'disabled': currentPage === pagination.meta.last_page }">
              <a class="page-link" href="#" @click.prevent="changePage(currentPage + 1)">Next</a>
            </li>
            <span style="margin-top: 8px;"> &nbsp; <i>Displaying {{ pagination.data.length }} of {{ pagination.meta.total }} entries.</i></span>
          </ul>
        </nav>
      </div>
    </div>
    <div class="row">
      <div class="offset-sm-2 col-sm-8 offset-sm-2">
        <p class="text-center alert alert-danger" v-if="hasErrors">Please fill all fields!</p>
        <div class="form-group">
            <label for="name">Examinee:</label>
            <input type="text" class="form-control" id="examinee" name="examinee" required v-model="newExaminee.examinee" placeholder=" Enter name of examinee">
        </div>
        <div class="form-group">
            <label for="campus">Campus:</label>
            <input type="text" class="form-control" id="campus" name="campus" required v-model="newExaminee.campus" placeholder=" Enter campus eligibility">
        </div>
        <div class="form-group">
            <label for="school">School:</label>
            <input type="text" class="form-control" id="school" name="school" required v-model="newExaminee.school" placeholder=" Enter school">
        </div>
        <div class="form-group">
            <label for="division">Division:</label>
            <input type="text" class="form-control" id="division" name="division" required v-model="newExaminee.division" placeholder=" Enter school division">
        </div>
        <button class="btn btn-primary" @click.prevent="examineeNewbie()" id="btnAdd" name="btnAdd">
        <span class="glyphicon glyphicon-plus"></span> ADD
        </button>      
      </div>
    </div>
  </div>
</template>

<script type="text/ecmascript-6">
  export default {
    props: {
      columns: { type: Array, required: true },
    },
    data() {
      return {
        tableData: [],
        pagination: {
          meta: { to: 1, from: 1 }
        },
        offset: 4,
        currentPage: 1,
        perPage: 50,
        sortedColumn: this.columns[0],
        order: 'asc',
        searchColumn: '',
        searchTerm: '',
        items: [],
        hasErrors: true,
        newExaminee: { 'examinee': '', 'campus': '', 'school': '', 'division': ''},
      }
    },
    created() {
      return this.fetchData()
    },
    computed: {
      /**
       * Get the pages number array for displaying in the pagination.
       * */
      pagesNumber() {
        if (!this.pagination.meta.to) {
          return []
        }
        let from = this.pagination.meta.current_page - this.offset
        if (from < 1) {
          from = 1
        }
        let to = from + (this.offset * 2)
        if (to >= this.pagination.meta.last_page) {
          to = this.pagination.meta.last_page
        }
        let pagesArray = []
        for (let page = from; page <= to; page++) {
          pagesArray.push(page)
        }
        return pagesArray
      },
      /**
       * Get the total data displayed in the current page.
       * */
      totalData() {
        return (this.pagination.meta.to - this.pagination.meta.from) + 1
      }
    },
    methods: {
      fetchData() {
        let dataFetchUrl = '';

        if (this.searchTerm === '') {
          dataFetchUrl = 'examinees/datatable?page='+this.currentPage+'&column='+this.sortedColumn+'&order='+this.order+'&per_page='+this.perPage;
        }
        else {
          dataFetchUrl = 'examinees/search/datatable?page='+this.currentPage+'&column='+this.sortedColumn+'&order='+this.order+'&per_page='+this.perPage+'&search_column='+this.searchColumn+'&search_term='+this.searchTerm;
        }

        axios.get(dataFetchUrl)
          .then(data => {
            this.pagination = data.data;
            this.tableData = data.data.data;
          }).catch(error => this.tableData = [])
      },
      /**
       * Change the page.
       * @param pageNumber
       */
      changePage(pageNumber) {
        this.currentPage = pageNumber
        this.fetchData();
      },
      /**
       * Sort the data by column.
       * */
      sortByColumn(column) {
        if (column === this.sortedColumn) {
          this.order = (this.order === 'asc') ? 'desc' : 'asc'
        } else {
          this.sortedColumn = column
          this.order = 'asc'
        }
        this.fetchData()
      },
      searchNow(e) {
        if (typeof e.target.value === 'undefined') {
            this.tableData = [];
            this.pagination = [];
            this.searchColumn = '';
            this.searchTerm = '';
            return;
        }

        this.searchExaminees(e.target.value);
      },
      searchExaminees(searchTerm) {
        this.currentPage = 1;
        this.searchTerm = searchTerm;
        this.searchColumn = this.sortedColumn;      

        let dataFetchUrl = 'examinees/search/datatable?page=1&column='+this.sortedColumn+'&order='+this.order+'&per_page='+this.perPage+'&search_column='+this.searchColumn+'&search_term='+this.searchTerm;

        axios.get(dataFetchUrl)
          .then(data => {
            this.pagination = data.data;
            this.tableData = data.data.data;
          }).catch(error => this.tableData = [])
      },
      examineeNewbie: function examineeNewbie() {
          var _this = this;
          var input = this.newExaminee;
          
          if (input['examinee'] == '' || input['campus'] == '' || input['school'] == '' || input['division'] == '' ) {
              this.hasError = false;
          } 
          else {
              this.hasError = true;

              axios.post('/examinee/newbie', input)
                .then(function (response) {
                    var newbie = response.data.name_of_examinee;
                    var fullName = newbie.split(' ');
                    _this.newExaminee = { 'examinee': '', 'campus': '', 'school': '', 'division': '' };
                    _this.searchExaminees(fullName[0]);
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });             
          }
      }    
    },
    filters: {
      columnHead(value) {
        return value.split('_').join(' ').toUpperCase()
      }
    },
    name: 'DataTable'
  }
</script>

<style scoped>
</style>