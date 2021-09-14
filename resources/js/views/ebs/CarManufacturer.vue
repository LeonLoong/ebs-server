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

    <el-table v-loading="loading" :data="carManufacturerList" border fit highlight-current-row :default-sort="{prop: 'manufacturer', order: 'ascending'}" style="width: 100%">
      <el-table-column align="center" :label="$t('ebs.manufacturer')" sortable prop="manufacturer">
        <template slot-scope="scope">
          <router-link :to="{name: 'Car', params: {keyword: scope.row.manufacturer}}" class="link-type">
            <span>{{ scope.row.manufacturer }}</span>
          </router-link>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('ebs.image')">
        <template slot-scope="scope">
          <img v-if="scope.row.image" :src="'../storage/images/car_manufacturers/' + scope.row.image" height="60">
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
            @click="handleDelete(scope.row.id, scope.row.manufacturer);"
          />
        </template>
      </el-table-column>
    </el-table>

    <el-dialog :visible.sync="addDialogVisible" :title="'Add New Car Manufacturer'" :close-on-click-modal="false">
      <div v-loading="addDialogLoading">
        <el-form ref="addNewCarManufacturerForm" :rules="rules" :model="newCarManufacturer" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item :label="$t('ebs.manufacturer')" prop="manufacturer">
            <el-input v-model="newCarManufacturer.manufacturer" placeholder="e.g.: Honda, Perodua, Proton..." />
          </el-form-item>
          <el-form-item :label="$t('ebs.image')" prop="image">
            <vue-dropzone
              id="dropzone"
              ref="addVueDropzone"
              v-model="newCarManufacturer.image"
              :options="addNewCarManufacturerImageDropzoneOptions"
              :use-custom-slot="true"
              @vdropzone-removed-file="fileRemoved"
              @vdropzone-file-added="fileAdded"
            >
              <div class="dropzone-custom-content">
                <h3 class="dropzone-custom-title">Drag and drop to upload content!</h3>
                <div class="subtitle">...or click to select a file from your computer</div>
              </div>
            </vue-dropzone>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="addDialogVisible = false">
            {{ $t('table.cancel') }}
          </el-button>
          <el-button type="primary" @click="addCarManufacturer()">
            {{ $t('table.confirm') }}
          </el-button>
        </div>
      </div>
    </el-dialog>

    <el-dialog :visible.sync="editDialogVisible" :title="'Edit Car Manufacturer - ' + currentCarManufacturer.manufacturer" :close-on-click-modal="false" :destroy-on-close="true">
      <div v-loading="editDialogLoading">
        <el-form ref="editCurrentCarManufacturerForm" :rules="rules" :model="currentCarManufacturer" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item :label="$t('ebs.manufacturer')">
            <el-input v-model="currentCarManufacturer.manufacturer" />
          </el-form-item>
          <el-form-item :label="$t('ebs.image')" prop="image">
            <vue-dropzone
              id="dropzone"
              ref="editVueDropzone"
              v-model="currentCarManufacturer.image"
              :options="editCurrentCarManufacturerImageDropzoneOptions"
              :use-custom-slot="true"
              @vdropzone-removed-file="fileRemoved"
              @vdropzone-file-added="fileAdded"
            >
              <div class="dropzone-custom-content">
                <h3 class="dropzone-custom-title">Drag and drop to upload content!</h3>
                <div class="subtitle">...or click to select a file from your computer</div>
              </div>
            </vue-dropzone>
          </el-form-item>
        </el-form>
        <div style="text-align:right;">
          <el-button type="danger" @click="handleCancel()">
            {{ $t('table.cancel') }}
          </el-button>
          <el-button type="primary" @click="editCarManufacturer()">
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
import { getCarManufacturers, storeCarManufacturer, updateCarManufacturer, destroyCarManufacturer } from '@/api/ebs';
import vue2Dropzone from 'vue2-dropzone';
import 'vue2-dropzone/dist/vue2Dropzone.min.css';

export default {
  name: 'CarManufacturer',
  components: {
    Pagination,
    vueDropzone: vue2Dropzone,
  },
  directives: { permission, waves },

  data() {
    return {
      query: {
        page: 1,
        limit: 15,
      },
      rules: {
        manufacturer: [{ required: true, message: 'Manufacturer is required', trigger: 'blur' }],
      },
      currentCarManufacturer: {
        manufacturer: '',
        image: '',
      },
      currentCarManufacturerID: 0,
      currentCarManufacturerForm: new FormData(),
      editCurrentCarManufacturerImageDropzoneOptions: {
        url: '',
        addRemoveLinks: true,
        autoProcessQueue: false,
        thumbnailWidth: 150,
        maxFiles: 1,
        maxFilesize: 0.5,
        headers: {
          'X-CSRF-TOKEN': document.head.querySelector('[name=csrf-token]').content,
        },
      },
      newCarManufacturer: {
        manufacturer: '',
        image: '',
      },
      newCarManufacturerForm: new FormData(),
      addNewCarManufacturerImageDropzoneOptions: {
        url: '/api/ebs/car-manufacturers',
        addRemoveLinks: true,
        autoProcessQueue: false,
        thumbnailWidth: 150,
        maxFiles: 1,
        maxFilesize: 2.0,
        headers: {
          'X-CSRF-TOKEN': document.head.querySelector('[name=csrf-token]').content,
        },
      },
      downloading: false,
      addDialogVisible: false,
      addDialogLoading: false,
      editDialogVisible: false,
      editDialogLoading: false,
      loading: true,
      carManufacturerList: [],
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
          this.carManufacturerList = data;
          this.carManufacturerList.forEach((element, index) => {
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
        this.$refs['addNewCarManufacturerForm'].clearValidate();
      });
      for (var pair of this.newCarManufacturerForm.entries()) {
        console.log(pair[0] + ', ' + pair[1]);
      }
    },

    addCarManufacturer() {
      this.$refs['addNewCarManufacturerForm'].validate((valid) => {
        if (valid) {
          this.newCarManufacturerForm.append('manufacturer', this.newCarManufacturer.manufacturer);
          this.addDialogLoading = true;
          storeCarManufacturer(this.newCarManufacturerForm)
            .then(response => {
              this.$message({
                message: 'New car manufacturer ' + this.newCarManufacturer.manufacturer + ' has been created successfully.',
                type: 'success',
                duration: 5 * 1000,
              });
              this.$refs.addVueDropzone.removeAllFiles();
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

    handleExport() {
      this.downloading = true;
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['id', 'manufacturer'];
        const filterVal = ['index', 'manufacturer'];
        const data = this.formatJson(filterVal, this.carManufacturerList);
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'car_manufacturers',
        });
        this.downloading = false;
      });
    },

    async handleEdit(carManufacturerID) {
      this.resetCurrentCarManufacturer();
      this.editDialogVisible = true;
      this.editDialogLoading = true;
      this.currentCarManufacturerID = carManufacturerID;
      this.editCurrentCarManufacturerImageDropzoneOptions.url = '/api/ebs/car-manufacturers/' + this.carManufacturerID;
      const carManufacturer = this.carManufacturerList.find(carManufacturer => carManufacturer.id === carManufacturerID);
      this.currentCarManufacturer = {
        manufacturer: carManufacturer.manufacturer,
        image: carManufacturer.image,
        imageSize: carManufacturer.image_size,
      };
      this.$nextTick(() => {
        this.$refs['editCurrentCarManufacturerForm'].clearValidate();
        var file = { size: this.currentCarManufacturer.imageSize, name: this.currentCarManufacturer.image, type: 'image/png' };
        var url = '../storage/images/car_manufacturers/';
        if (this.currentCarManufacturer.image) {
          this.$refs.editVueDropzone.manuallyAddFile(file, url + this.currentCarManufacturer.image);
        }
      });
      this.editDialogLoading = false;
    },

    handleCancel() {
      this.$refs.editVueDropzone.removeAllFiles();
      this.editDialogVisible = false;
    },

    editCarManufacturer() {
      this.$refs['editCurrentCarManufacturerForm'].validate((valid) => {
        if (valid) {
          this.currentCarManufacturerForm.append('manufacturer', this.currentCarManufacturer.manufacturer);
          this.currentCarManufacturerForm.append('image', this.currentCarManufacturer.image);
          this.currentCarManufacturerForm.append('imageSize', this.currentCarManufacturer.imageSize);
          /* Since HTML forms can't make PUT, PATCH, or DELETE requests,
          you will need to add a hidden _method field to spoof these HTTP verbs. */
          this.currentCarManufacturerForm.append('_method', 'PUT');
          updateCarManufacturer(this.currentCarManufacturerID, this.currentCarManufacturerForm)
            .then(response => {
              this.$message({
                message: 'Car manufacturer information has been updated successfully',
                type: 'success',
                duration: 5 * 1000,
              });
              this.$refs.editVueDropzone.removeAllFiles();
              this.editDialogVisible = false;
              this.handleSearch();
            })
            .catch(error => {
              console.log(error);
            });
        }
      });
    },

    handleDelete(carManufacturerID, manufacturer) {
      this.$confirm('This will permanently delete ' + manufacturer + '. Continue?', 'Warning', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        type: 'warning',
      }).then(() => {
        destroyCarManufacturer(carManufacturerID).then(response => {
          this.$message({
            type: 'success',
            message: 'Delete completed',
          });
          this.handleSearch();
        }).catch(error => {
          console.log(error);
        });
      }).catch(() => {
        this.$message({
          type: 'info',
          message: 'Delete canceled',
        });
      });
    },

    resetNewCarManufacturer() {
      this.newCarManufacturer = {
        manufacturer: '',
        image: '',
      };
      this.newCarManufacturerForm = new FormData();
    },

    resetCurrentCarManufacturer() {
      this.currentCarManufacturer = {
        manufacturer: '',
        image: '',
      };
      this.currentCarManufacturerForm = new FormData();
    },

    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]));
    },

    fileAdded(file) {
      console.log(file.name + ' added');
      this.newCarManufacturerForm.append('file', file);
      this.currentCarManufacturerForm.append('file', file);
    },
    fileRemoved(file) {
      console.log(file.name + ' deleted');
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

<!-- Accomplished -->
