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

    <el-table v-loading="loading" :data="cars" border fit highlight-current-row :default-sort="{prop: 'model', order: 'ascending'}" style="width: 100%">
      <el-table-column align="center" :label="$t('ebs.model')" sortable prop="model">
        <template slot-scope="scope">
          <router-link :to="'cars/' + scope.row.id + '/batteries'" class="link-type">
            <span>{{ scope.row.model }}</span>
          </router-link>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('ebs.manufacturer')">
        <template slot-scope="scope">
          <router-link :to="'car-manufacturers/' + scope.row.id + '/cars'" class="link-type">
            <span>{{ scope.row.manufacturer }}</span>
          </router-link>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Actions" width="160">
        <template slot-scope="scope">
          <el-button
            v-if="scope.row.name !== 'admin'"
            v-permission="['update ebs']"
            size="small"
            type="primary"
            icon="el-icon-edit"
            @click="handleEdit(scope.row.id);"
          />
          <el-button
            v-permission="['delete ebs']"
            size="small"
            type="danger"
            icon="el-icon-delete"
            @click="handleDelete(scope.row.id, scope.row.name);"
          />
        </template>
      </el-table-column>
    </el-table>

    <el-dialog :visible.sync="addDialogVisible" :title="'Add New Car'" :close-on-click-modal="false">
      <div v-loading="addDialogLoading">
        <el-form ref="addCarForm" :rules="rules" :model="newCar" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item :label="$t('ebs.model')" prop="model">
            <el-input v-model="newCar.model" />
          </el-form-item>
          <el-form-item :label="$t('ebs.manufacturer')" prop="manufacturers">
            <el-select v-model="newCar.car_manufacturer_id" placeholder="Select" filterable>
              <el-option
                v-for="(manufacturer, index) in manufacturer_list"
                :key="index"
                :label="manufacturer.manufacturer"
                :value="manufacturer.id"
              />
            </el-select>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="addDialogVisible = false">
            {{ $t('table.cancel') }}
          </el-button>
          <el-button type="primary" @click="createCar()">
            {{ $t('table.confirm') }}
          </el-button>
        </div>
      </div>
    </el-dialog>

    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getData" />
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import permission from '@/directive/permission';
import waves from '@/directive/waves';
import { getCars, storeCar, getCarManufacturers } from '@/api/ebs';

export default {
  name: 'Car',
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
      newCar: {
        car_manufacturer_id: 0,
        model: '',
      },
      cars: [],
      manufacturer_list: [],
      downloading: false,
      addDialogVisible: false,
      addDialogLoading: false,
      loading: true,
      total: 0,
    };
  },
  created() {
    this.getData();
  },

  methods: {
    async getData() {
      getCars(this.query)
        .then(response => {
          this.loading = true;
          const { limit, page } = this.query;
          const { data, meta } = response;
          this.cars = data;
          this.cars.forEach((element, index) => {
            element['index'] = (page - 1) * limit + index + 1;
          });
          this.total = meta.total;
        });

      getCarManufacturers()
        .then(response => {
          const { data } = response;
          this.manufacturer_list = data;
          this.loading = false;
        });
    },

    handleSearch() {
      this.query.page = 1;
      this.getData();
    },

    handleFilter() {
      this.query.page = 1;
      this.getData();
    },

    handleAdd() {
      this.resetNewCar();
      this.addDialogVisible = true;
      this.$nextTick(() => {
        this.$refs['addCarForm'].clearValidate();
      });
    },

    handleExport() {
      this.downloading = true;
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['id', 'name', 'departments', 'start_at', 'end_at'];
        const filterVal = ['index', 'name', 'departments', 'start_at', 'end_at'];
        const data = this.formatJson(filterVal, this.cars);
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'cars',
        });
        this.downloading = false;
      });
    },

    createCar() {
      this.$refs['addCarForm'].validate((valid) => {
        if (valid) {
          this.addDialogLoading = true;
          this.newCar.model = this.newCar.model;
          this.newCar.car_manufacturer_id = this.newCar.car_manufacturer_id;

          storeCar(this.newCar)
            .then(response => {
              this.$message({
                message: 'New battery ' + this.newCar.model + ' has been created successfully.',
                type: 'success',
                duration: 5 * 1000,
              });
              this.resetNewCar();
              this.addDialogVisible = false;
              this.handleFilter();
            })
            .catch(error => {
              console.log(error);
            })
            .finally(() => {
              this.addDialogLoading = false;
            });
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },

    resetNewCar() {
      this.newBattery = {
        battery_manufacturer_id: '',
        model: '',
      };
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
