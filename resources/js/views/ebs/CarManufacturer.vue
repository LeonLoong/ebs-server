<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="query.keyword" :placeholder="$t('table.keyword')" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleSearch">
        {{ $t('table.search') }}
      </el-button>
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-plus" @click="handleAdd">
        {{ $t('table.add') }}
      </el-button>
      <el-button v-waves :loading="downloading" class="filter-item" type="primary" icon="el-icon-download" @click="handleExport">
        {{ $t('table.export') }}
      </el-button>
    </div>

    <el-table v-loading="loading" :data="carManufacturers" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" label="Name">
        <template slot-scope="scope">
          <router-link :to="'car-manufacturers/' + scope.row.id + '/batteries'" class="link-type">
            <span>{{ scope.row.manufacturer }}</span>
          </router-link>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Actions" width="160">
        <template slot-scope="scope">
          <el-button
            v-if="scope.row.name !== 'admin'"
            v-permission="['update battery']"
            size="small"
            type="primary"
            icon="el-icon-edit"
            @click="handleEdit(scope.row.id);"
          />
          <el-button
            v-permission="['delete battery']"
            size="small"
            type="danger"
            icon="el-icon-delete"
            @click="handleDelete(scope.row.id, scope.row.name);"
          />
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getData" />
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import permission from '@/directive/permission';
import waves from '@/directive/waves';
import { getCarManufacturers } from '@/api/ebs';

export default {
  name: 'Project',
  components: { Pagination },
  directives: { permission, waves },

  data() {
    return {
      query: {
        page: 1,
        limit: 15,
      },
      rules: {
      },
      downloading: false,
      loading: true,
      carManufacturers: [],
      total: 0,
    };
  },
  created() {
    this.getData();
  },

  methods: {
    async getData() {
      getCarManufacturers(this.query)
        .then(response => {
          this.loading = true;
          const { limit, page } = this.query;
          const { data, meta } = response;
          this.carManufacturers = data;
          this.carManufacturers.forEach((element, index) => {
            element['index'] = (page - 1) * limit + index + 1;
          });
          this.total = meta.total;
          this.loading = false;
        });
    },

    handleSearch() {
      this.query.page = 1;
      this.getData();
    },

    handleAdd() {
      this.resetNewBattery();
      this.addDialogVisible = true;
      this.$nextTick(() => {
        this.$refs['addBatteryForm'].clearValidate();
      });
    },

    handleExport() {
      this.downloading = true;
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['id', 'name', 'departments', 'start_at', 'end_at'];
        const filterVal = ['index', 'name', 'departments', 'start_at', 'end_at'];
        const data = this.formatJson(filterVal, this.projects);
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'projects',
        });
        this.downloading = false;
      });
    },
  },
};
</script>

<style scoped>
  .el-select {
    display: block;
  }

  .dialog-footer {
    text-align: left;
    padding-top: 0;
    margin-left: 150px;
  }
</style>
