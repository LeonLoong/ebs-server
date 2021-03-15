<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="query.keyword" :placeholder="$t('table.keyword')" style="width: 200px;" class="filter-item" @keyup.enter.native="handleSearch" />
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

    <el-dialog :visible.sync="addDialogVisible" :title="'Add New Car Manufacturer'" :close-on-click-modal="false">
      <div v-loading="addDialogLoading">
        <el-form ref="addCarManufacturerForm" :rules="rules" :model="new_car" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item :label="$t('ebs.manufacturer')" prop="manufacturer">
            <el-input v-model="new_car.manufacturer" />
          </el-form-item>
          <el-form-item :label="$t('ebs.logo')" prop="logo">
            <div class="editor-container">
              <dropzone id="myVueDropzone" url="https://httpbin.org/post" @dropzone-removedFile="dropzoneR" @dropzone-success="dropzoneS" />
            </div>
          </el-form-item>
        </el-form>

        <div slot="footer" class="dialog-footer">
          <el-button @click="addDialogVisible = false">
            {{ $t('table.cancel') }}
          </el-button>
          <el-button type="primary" @click="createCarManufacturer()">
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
import Dropzone from '@/components/Dropzone';
import permission from '@/directive/permission';
import waves from '@/directive/waves';
import { getCarManufacturers, storeCarManufacturer } from '@/api/ebs';

export default {
  name: 'Project',
  components: { Pagination, Dropzone },
  directives: { permission, waves },

  data() {
    return {
      query: {
        page: 1,
        limit: 50,
      },
      rules: {
        manufacturer: [{ required: true, message: 'Manufacturer is required', trigger: 'blur' }],
      },
      new_car: {
        manufacturer: '',
      },
      downloading: false,
      addDialogVisible: false,
      addDialogLoading: false,
      loading: true,
      carManufacturers: [],
      total: 0,
      dropzoneOptions: {
        url: 'https://httpbin.org/post',
        autoProcessQueue: false,
      },
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
      this.resetNewCarManufacturer();
      this.addDialogVisible = true;
      this.$nextTick(() => {
        this.$refs['addCarManufacturerForm'].clearValidate();
      });
    },

    createCarManufacturer() {
      this.$refs['addCarManufacturerForm'].validate((valid) => {
        if (valid) {
          this.addDialogLoading = true;
          this.new_car.manufacturer = this.new_car.manufacturer;
          console.log(this.new_car);

          storeCarManufacturer(this.new_car)
            .then(response => {
              this.$message({
                message: 'New car manufacturer ' + this.new_car.manufacturer + ' has been created successfully.',
                type: 'success',
                duration: 5 * 1000,
              });
              this.resetNewCarManufacturer();
              this.addDialogVisible = false;
              this.handleSearch();
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

    resetNewCarManufacturer() {
      this.new_car = {
        manufacturer: '',
      };
    },

    handleExport() {
      this.downloading = true;
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['id', 'manufacturer'];
        const filterVal = ['index', 'manufacturer'];
        const data = this.formatJson(filterVal, this.carManufacturers);
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'car_manufacturers',
        });
        this.downloading = false;
      });
    },

    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]));
    },

    dropzoneS(file) {
      this.$message({ message: 'Upload success', type: 'success' });
    },
    dropzoneR(file) {
      this.$message({ message: 'Delete success', type: 'success' });
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
