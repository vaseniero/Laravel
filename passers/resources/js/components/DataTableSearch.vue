<template>
  <div class="data-table">
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
    <div style="padding: 5px 0px 10px 0px;">&nbsp;</div>
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
        <td>{{ idNumber(key1) }}</td>
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
</template>

<script type="text/ecmascript-6">
export default {
  props: {
    fetchUrl: { type: String, required: true },
    columns: { type: Array, required: true },
  },
  data() {
    return {
      tableData: [],
      url: '',
      pagination: {
        meta: { to: 1, from: 1 }
      },
      offset: 4,
      currentPage: 1,
      perPage: 50,
      sortedColumn: this.columns[0],
      order: 'asc'
    }
  },
  watch: {
    fetchUrl: {
      handler: function(fetchUrl) {
        this.url = fetchUrl
      },
      immediate: true
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
      if (to >= this.pagination.last_page) {
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
      if (typeof this.currentPage === 'undefined') {
          this.currentPage = 1;
      }
      if (typeof this.sortedColumn === 'undefined') {
          this.sortedColumn = 'name_of_examinee';
      }
      if (typeof this.order === 'undefined') {
          this.order = 'asc';
      }
      if (typeof this.perPage === 'undefined') {
          this.perPage = 50;
      }
  
      let dataFetchUrl = 'examinees/search/data-table?page='+this.currentPage+'&column='+this.sortedColumn+'&order='+this.order+'&per_page='+this.perPage;
      axios.get(dataFetchUrl)
        .then(data => {
          this.pagination = data.data
          this.tableData = data.data.data
        }).catch(error => this.tableData = [])
    },
    /**
     * Get the id number.
     * @param key
     * */
    idNumber(key) {
      return (this.currentPage - 1) * this.perPage + 1 + key
    },
    /**
     * Change the page.
     * @param pageNumber
     */
    changePage(pageNumber) {
      this.currentPage = pageNumber
      this.fetchData()
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