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

    <el-table v-loading="loading" :data="carList" border fit highlight-current-row :default-sort="{prop: 'model', order: 'ascending'}" style="width: 100%">
      <el-table-column align="center" :label="$t('ebs.manufacturer')" sortable prop="manufacturer">
        <template slot-scope="scope">
          <router-link :to="'cars/' + scope.row.id + '/cars'" class="link-type">
            <span>{{ scope.row.manufacturer }}</span>
          </router-link>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('ebs.model')" sortable prop="model">
        <template slot-scope="scope">
          <router-link :to="'cars/' + scope.row.id + '/batteries'" class="link-type">
            <span>{{ scope.row.model }}</span>
            <span v-if="scope.row.description">({{ scope.row.description }})</span>
          </router-link>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('ebs.image')">
        <template slot-scope="scope">
          <img v-if="scope.row.image" :src="'../storage/images/cars/' + scope.row.image" height="60">
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
            @click="handleDelete(scope.row.id, scope.row.model);"
          />
        </template>
      </el-table-column>
    </el-table>

    <el-dialog :visible.sync="addDialogVisible" :title="'Add New Car Model'" :close-on-click-modal="false">
      <div v-loading="addDialogLoading">
        <el-form ref="addNewCarForm" :rules="rules" :model="newCar" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item :label="$t('ebs.manufacturer')" prop="manufacturerID">
            <el-select v-model="newCar.manufacturerID" placeholder="Select" filterable>
              <el-option
                v-for="(manufacturer, index) in carManufacturerList"
                :key="index"
                :label="manufacturer.manufacturer"
                :value="manufacturer.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('ebs.model')" prop="model">
            <el-input v-model="newCar.model" placeholder="e.g.: Civic, Myvi, Vios..." />
          </el-form-item>
          <el-form-item :label="$t('ebs.description')" prop="description">
            <el-input v-model="newCar.description" placeholder="e.g.: 1992-2021(AH10), Eco Idle, ..." />
          </el-form-item>
          <el-form-item :label="$t('ebs.image')" prop="image">
            <vue-dropzone
              id="dropzone"
              ref="addVueDropzone"
              v-model="newCar.image"
              :options="addNewCarImageDropzoneOptions"
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
          <el-button type="primary" @click="addCar()">
            {{ $t('table.confirm') }}
          </el-button>
        </div>
      </div>
    </el-dialog>

    <el-dialog :visible.sync="editDialogVisible" :title="'Edit Car - ' + currentCar.model" :close-on-click-modal="false" :destroy-on-close="true">
      <div v-loading="editDialogLoading">
        <el-form ref="editCurrentCarForm" :rules="rules" :model="currentCar" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item :label="$t('ebs.manufacturer')" prop="manufacturerID">
            <el-select v-model="currentCar.manufacturerID" placeholder="Select" filterable>
              <el-option
                v-for="(manufacturer, index) in carManufacturerList"
                :key="index"
                :label="manufacturer.manufacturer"
                :value="manufacturer.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('ebs.model')" prop="model">
            <el-input v-model="currentCar.model" placeholder="e.g.: Civic, Myvi, Vios..." />
          </el-form-item>
          <el-form-item :label="$t('ebs.description')" prop="description">
            <el-input v-model="currentCar.description" placeholder="e.g.: 1992-2021(AH10), Eco Idle, ..." />
          </el-form-item>
          <el-form-item :label="$t('ebs.image')" prop="image">
            <vue-dropzone
              id="dropzone"
              ref="editVueDropzone"
              v-model="currentCar.image"
              :options="editCurrentCarImageDropzoneOptions"
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
          <el-button type="primary" @click="editCar()">
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
import { getCars, storeCar, updateCar, destroyCar, getCarManufacturers } from '@/api/ebs';
import vue2Dropzone from 'vue2-dropzone';
import 'vue2-dropzone/dist/vue2Dropzone.min.css';

export default {
  name: 'Car',
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
        keyword: this.keyword,
      },
      rules: {
        manufacturerID: [{ required: true, message: 'Manufacturer is required', trigger: 'blur' }],
        model: [{ required: true, message: 'Model is required', trigger: 'blur' }],
      },
      currentCar: {
        manufacturerID: '',
        model: '',
        description: '',
        image: '',
      },
      currentCarID: 0,
      currentCarForm: new FormData(),
      editCurrentCarImageDropzoneOptions: {
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
      newCar: {
        manufacturerID: '',
        model: '',
        description: '',
        image: '',
      },
      newCarForm: new FormData(),
      addNewCarImageDropzoneOptions: {
        url: '/api/ebs/cars',
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
      carList: [],
      carManufacturerList: [],
      total: 0,
    };
  },

  created() {
    this.query.keyword = this.$route.params.keyword;
    this.getData();
  },

  methods: {
    async getData() {
      getCars(this.query)
        .then(response => {
          this.loading = true;
          const { limit, page } = this.query;
          const { data, meta } = response;
          this.carList = data;
          this.carList.forEach((element, index) => {
            element['index'] = (page - 1) * limit + index + 1;
          });
          this.total = meta.total;
          this.loading = false;
        });

      getCarManufacturers()
        .then(response => {
          const { data } = response;
          this.carManufacturerList = data;
          this.loading = false;
        });
    },

    handleSearch() {
      this.query.page = 1;
      this.getData();
    },

    handleAdd() {
      this.resetNewCar();
      this.addDialogVisible = true;
      this.$nextTick(() => {
        this.$refs['addNewCarForm'].clearValidate();
      });
    },

    addCar() {
      this.$refs['addNewCarForm'].validate((valid) => {
        if (valid) {
          this.newCarForm.append('manufacturerID', this.newCar.manufacturerID);
          this.newCarForm.append('model', this.newCar.model);
          this.newCarForm.append('description', this.newCar.description);
          this.addDialogLoading = true;
          storeCar(this.newCarForm)
            .then(response => {
              this.$message({
                message: 'New car ' + this.newCar.model + ' has been created successfully.',
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
        const tHeader = ['id', 'manufacturer', 'model', 'description'];
        const filterVal = ['index', 'manufacturer', 'model', 'description'];
        const data = this.formatJson(filterVal, this.carList);
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'cars',
        });
        this.downloading = false;
      });
    },

    async handleEdit(carID) {
      this.resetCurrentCar();
      this.editDialogVisible = true;
      this.editDialogLoading = true;
      this.currentCarID = carID;
      this.editCurrentCarImageDropzoneOptions.url = '/api/ebs/cars/' + this.carID;
      const car = this.carList.find(car => car.id === carID);
      this.currentCar = {
        manufacturerID: car.manufacturer_id,
        model: car.model,
        description: car.description,
        image: car.image,
        imageSize: car.image_size,
      };
      this.$nextTick(() => {
        this.$refs['editCurrentCarForm'].clearValidate();
        var file = { size: this.currentCar.imageSize, name: this.currentCar.image, type: 'image/png' };
        var url = '../storage/images/cars/';
        if (this.$refs.editVueDropzone.getQueuedFiles().length || this.currentCar.image) {
          this.$refs.editVueDropzone.manuallyAddFile(file, url + this.currentCar.image);
        }
      });
      this.editDialogLoading = false;
    },

    handleCancel() {
      this.$refs.editVueDropzone.removeAllFiles();
      this.editDialogVisible = false;
    },

    editCar() {
      this.$refs['editCurrentCarForm'].validate((valid) => {
        if (valid) {
          this.currentCarForm.append('manufacturerID', this.currentCar.manufacturerID);
          this.currentCarForm.append('model', this.currentCar.model);
          this.currentCarForm.append('description', this.currentCar.description);
          this.currentCarForm.append('image', this.currentCar.image);
          this.currentCarForm.append('imageSize', this.currentCar.imageSize);
          /* Since HTML forms can't make PUT, PATCH, or DELETE requests,
          you will need to add a hidden _method field to spoof these HTTP verbs. */
          this.currentCarForm.append('_method', 'PUT');
          updateCar(this.currentCarID, this.currentCarForm)
            .then(response => {
              this.$message({
                message: 'Car information has been updated successfully',
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

    handleDelete(carID, model) {
      this.$confirm('This will permanently delete ' + model + '. Continue?', 'Warning', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        type: 'warning',
      }).then(() => {
        destroyCar(carID).then(response => {
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

    resetNewCar() {
      this.newCar = {
        manufacturer: '',
        model: '',
        description: '',
        image: '',
      };
      this.newCarManufacturerForm = new FormData();
    },

    resetCurrentCar() {
      this.currentCar = {
        manufacturer: '',
        model: '',
        description: '',
        image: '',
      };
      this.currentCarManufacturerForm = new FormData();
    },

    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]));
    },

    fileAdded(file) {
      console.log(file.name + ' added');
      this.newCarForm.append('file', file);
      this.currentCarForm.append('file', file);
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
