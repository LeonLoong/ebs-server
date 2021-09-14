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
      <span>aaa</span>
    </div>

    <el-table v-loading="loading" :data="batteryList" border fit highlight-current-row :default-sort="{prop: 'model', order: 'ascending'}" style="width: 100%">
      <el-table-column align="center" :label="$t('ebs.model')" sortable="custom" prop="model">
        <template slot-scope="scope">
          <span>{{ scope.row.model }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" scoped-slot>
        <template slot="header">
          <span v-for="(text, index) in $t('ebs.modelReference').split(' ')" :key="index">{{ text }}<br></span>
        </template>
        <template slot-scope="scope">
          <router-link :to="'battery-manufacturers/' + scope.row.id + '/batteries'" class="link-type">
            <span>{{ scope.row.model_reference }}</span>
          </router-link>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('ebs.manufacturer')" sortable prop="manufacturer">
        <template slot-scope="scope">
          <router-link :to="'batteries/' + scope.row.id + '/batteries'" class="link-type">
            <span>{{ scope.row.manufacturer }}</span>
          </router-link>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('ebs.series')">
        <template slot-scope="scope">
          <span>{{ scope.row.series }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('ebs.type')">
        <template slot-scope="scope">
          <span>{{ scope.row.type }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('ebs.price')">
        <template slot-scope="scope">
          <span>{{ scope.row.price }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('ebs.warranty')">
        <template slot-scope="scope">
          <span>{{ scope.row.warranty }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('ebs.tradeIn')">
        <template slot-scope="scope">
          <span>{{ scope.row.trade_in }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('ebs.stock')">
        <template slot-scope="scope">
          <span>{{ scope.row.stock }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('ebs.volt')">
        <template slot-scope="scope">
          <span>{{ scope.row.volt }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" scoped-slot>
        <template slot="header">
          <span v-for="(text, index) in $t('ebs.ah').split(' ')" :key="index">{{ text }}<br></span>
        </template>
        <template slot-scope="scope">
          <span>{{ scope.row.ah }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" scoped-slot>
        <template slot="header">
          <span v-for="(text, index) in $t('ebs.cca').split(' ')" :key="index">{{ text }}<br></span>
        </template>
        <template slot-scope="scope">
          <span>{{ scope.row.cca }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" scoped-slot>
        <template slot="header">
          <span v-for="(text, index) in $t('ebs.rc').split(' ')" :key="index">{{ text }}<br></span>
        </template>
        <template slot-scope="scope">
          <span>{{ scope.row.rc }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" scoped-slot>
        <template slot="header">
          <span v-for="(text, index) in $t('ebs.length').split(' ')" :key="index">{{ text }}<br></span>
        </template>
        <template slot-scope="scope">
          <span>{{ scope.row.length }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" scoped-slot>
        <template slot="header">
          <span v-for="(text, index) in $t('ebs.width').split(' ')" :key="index">{{ text }}<br></span>
        </template>
        <template slot-scope="scope">
          <span>{{ scope.row.width }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" scoped-slot>
        <template slot="header">
          <span v-for="(text, index) in $t('ebs.height').split(' ')" :key="index">{{ text }}<br></span>
        </template>
        <template slot-scope="scope">
          <span>{{ scope.row.height }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('ebs.image')">
        <template slot-scope="scope">
          <img v-if="scope.row.image" :src="'../storage/images/batteries/' + scope.row.image" height="60">
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

    <el-dialog :visible.sync="addDialogVisible" :title="'Add Battery Manufacturer - ' + currentCarBattery.model" :close-on-click-modal="false">
      <div v-loading="addDialogLoading">
        <el-form ref="addNewBatteryForm" :rules="rules" :model="newBattery" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item :label="$t('ebs.manufacturer')" prop="manufacturerID">
            <el-select v-model="newBattery.manufacturerID" placeholder="Select" filterable>
              <el-option
                v-for="(manufacturer, index) in batteryManufacturerList"
                :key="index"
                :label="manufacturer.manufacturer"
                :value="manufacturer.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('ebs.model')" prop="model">
            <el-input v-model="newBattery.model" placeholder="e.g.: 38B20L, 46B24L, 65D26L..." />
          </el-form-item>
          <el-form-item :label="$t('ebs.modelReference')" prop="modelReference">
            <el-input v-model="newBattery.modelReference" placeholder="e.g.: NS40ZL, NS60L, NS70L..." />
          </el-form-item>
          <el-form-item :label="$t('ebs.series')" prop="seriesID">
            <el-select v-model="newBattery.seriesID" placeholder="Select" filterable>
              <el-option
                v-for="(series, index) in batterySeriesList"
                :key="index"
                :label="series.series"
                :value="series.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('ebs.type')" prop="typeID">
            <el-select v-model="newBattery.typeID" placeholder="Select" filterable>
              <el-option
                v-for="(type, index) in batteryTypeList"
                :key="index"
                :label="type.type"
                :value="type.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('ebs.price')" prop="price">
            <el-input v-model="newBattery.price">
              <template slot="prepend">RM</template>
            </el-input>
          </el-form-item>
          <el-form-item :label="$t('ebs.warranty')" prop="warranty">
            <el-select v-model="newBattery.warranty" placeholder="Select" filterable>
              <el-option
                v-for="(warranty, index) in [3, 6, 9, 12, 15, 18, 24, 27, 30, 33, 36]"
                :key="index"
                :label="warranty + ' ' + 'months'"
                :value="warranty"
              />
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('ebs.tradeIn')" prop="tradeInID">
            <el-select v-model="newBattery.tradeInID" placeholder="Select" filterable>
              <el-option
                v-for="(tradeIn, index) in batteryTradeInList"
                :key="index"
                :label="'RM' + tradeIn.price"
                :value="tradeIn.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('ebs.stock')" prop="stock">
            <el-input v-model="newBattery.stock" placeholder="888" />
          </el-form-item>
          <el-form-item :label="$t('ebs.image')" prop="image">
            <vue-dropzone
              id="dropzone"
              ref="addVueDropzone"
              v-model="newBattery.image"
              :options="addNewBatteryImageDropzoneOptions"
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
          <el-button type="primary" @click="addBattery()">
            {{ $t('table.confirm') }}
          </el-button>
        </div>
      </div>
    </el-dialog>

    <el-dialog :visible.sync="editDialogVisible" :title="'Edit Battery - ' + currentBattery.model" :close-on-click-modal="false" :destroy-on-close="true">
      <div v-loading="editDialogLoading">
        <el-form ref="editCurrentBatteryForm" :rules="rules" :model="currentBattery" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item :label="$t('ebs.manufacturer')" prop="manufacturerID">
            <el-select v-model="currentBattery.manufacturerID" placeholder="Select" filterable>
              <el-option
                v-for="(manufacturer, index) in batteryManufacturerList"
                :key="index"
                :label="manufacturer.manufacturer"
                :value="manufacturer.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('ebs.model')" prop="model">
            <el-input v-model="currentBattery.model" placeholder="e.g.: 38B20L, 46B24L, 65D26L..." />
          </el-form-item>
          <el-form-item :label="$t('ebs.modelReference')" prop="modelReference">
            <el-input v-model="currentBattery.modelReference" placeholder="e.g.: NS40ZL, NS60L, NS70L..." />
          </el-form-item>
          <el-form-item :label="$t('ebs.series')" prop="seriesID">
            <el-select v-model="currentBattery.seriesID" placeholder="Select" filterable>
              <el-option
                v-for="(series, index) in batterySeriesList"
                :key="index"
                :label="series.series"
                :value="series.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('ebs.type')" prop="typeID">
            <el-select v-model="currentBattery.typeID" placeholder="Select" filterable>
              <el-option
                v-for="(type, index) in batteryTypeList"
                :key="index"
                :label="type.type"
                :value="type.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('ebs.price')" prop="price">
            <el-input v-model="currentBattery.price">
              <template slot="prepend">RM</template>
            </el-input>
          </el-form-item>
          <el-form-item :label="$t('ebs.warranty')" prop="warranty">
            <el-select v-model="currentBattery.warranty" placeholder="Select" filterable>
              <el-option
                v-for="(warranty, index) in [3, 6, 9, 12, 15, 18, 24, 27, 30, 33, 36]"
                :key="index"
                :label="warranty + ' ' + 'months'"
                :value="warranty"
              />
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('ebs.tradeIn')" prop="tradeInID">
            <el-select v-model="currentBattery.tradeInID" placeholder="Select" filterable>
              <el-option
                v-for="(tradeIn, index) in batteryTradeInList"
                :key="index"
                :label="'RM' + tradeIn.price"
                :value="tradeIn.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('ebs.stock')" prop="stock">
            <el-input v-model="currentBattery.stock" placeholder="888" />
          </el-form-item>
          <el-form-item :label="$t('ebs.image')" prop="image">
            <vue-dropzone
              id="dropzone"
              ref="editVueDropzone"
              v-model="currentBattery.image"
              :options="editCurrentBatteryImageDropzoneOptions"
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
          <el-button type="primary" @click="editBattery()">
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
import { getCarBatteries, storeBattery, updateBattery, destroyBattery, getBatteryManufacturers, getBatterySeries, getBatteryTypes, getBatteryTradeIns } from '@/api/ebs';
import vue2Dropzone from 'vue2-dropzone';
import 'vue2-dropzone/dist/vue2Dropzone.min.css';

export default {
  name: 'Battery',
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
        typeID: [{ required: true, message: 'Type is required', trigger: 'change' }],
        price: [{ required: true, message: 'Price is required', trigger: 'blur' }],
        warranty: [{ required: true, message: 'Warranty is required', trigger: 'change' }],
        tradeInID: [{ required: true, message: 'Trade-in is required', trigger: 'change' }],
        stock: [{ required: true, message: 'Type is required', trigger: 'blur' }],
      },
      currentBattery: {
        manufacturerID: '',
        model: '',
        modelReference: '',
        seriesID: '',
        typeID: '',
        price: '0',
        warranty: '0 month',
        tradeInID: '',
        stock: 0,
        image: '',
      },
      currentBatteryID: 0,
      currentBatteryForm: new FormData(),
      editCurrentBatteryImageDropzoneOptions: {
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
      newBattery: {
        manufacturerID: '',
        model: '',
        modelReference: '',
        seriesID: '',
        typeID: '',
        price: '0',
        warranty: '0 month',
        tradeInID: '',
        stock: 0,
        image: '',
      },
      newBatteryForm: new FormData(),
      addNewBatteryImageDropzoneOptions: {
        url: '/api/ebs/batteries',
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
      batteryList: [],
      batteryManufacturerList: [],
      batterySeriesList: [],
      batteryTypeList: [],
      batteryTradeInList: [],
      total: 0,
    };
  },

  created() {
    this.query.keyword = this.$route.params.keyword;
    this.getData();
  },

  methods: {
    async getData() {
      getCarBatteries(this.query)
        .then(response => {
          this.loading = true;
          const { limit, page } = this.query;
          const { data, meta } = response;
          this.batteryList = data;
          this.batteryList.forEach((element, index) => {
            element['index'] = (page - 1) * limit + index + 1;
          });
          this.total = meta.total;
          this.loading = false;
        });

      getBatteryManufacturers()
        .then(response => {
          const { data } = response;
          this.batteryManufacturerList = data;
          this.loading = false;
        });

      getBatterySeries()
        .then(response => {
          const { data } = response;
          this.batterySeriesList = data;
          this.loading = false;
        });

      getBatteryTypes()
        .then(response => {
          const { data } = response;
          this.batteryTypeList = data;
          this.loading = false;
        });

      getBatteryTradeIns()
        .then(response => {
          const { data } = response;
          this.batteryTradeInList = data;
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
        this.$refs['addNewBatteryForm'].clearValidate();
      });
    },

    addBattery() {
      this.$refs['addNewBatteryForm'].validate((valid) => {
        if (valid) {
          this.newBatteryForm.append('manufacturerID', this.newBattery.manufacturerID);
          this.newBatteryForm.append('model', this.newBattery.model);
          this.newBatteryForm.append('modelReference', this.newBattery.modelReference);
          this.newBatteryForm.append('seriesID', this.newBattery.seriesID);
          this.newBatteryForm.append('typeID', this.newBattery.typeID);
          this.newBatteryForm.append('price', this.newBattery.price);
          this.newBatteryForm.append('warranty', this.newBattery.warranty);
          this.newBatteryForm.append('tradeInID', this.newBattery.tradeInID);
          this.newBatteryForm.append('stock', this.newBattery.stock);
          this.addDialogLoading = true;
          storeBattery(this.newBatteryForm)
            .then(response => {
              this.$message({
                message: 'New battery ' + this.newBattery.model + ' has been created successfully.',
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
        const tHeader = ['id', 'model', 'model_reference', 'manufacturer', 'series', 'type', 'price', 'warranty', 'trade_in', 'stock'];
        const filterVal = ['index', 'model', 'model_reference', 'manufacturer', 'series', 'type', 'price', 'warranty', 'trade_in', 'stock'];
        const data = this.formatJson(filterVal, this.batteryList);
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'batteries',
        });
        this.downloading = false;
      });
    },

    async handleEdit(batteryID) {
      this.resetCurrentBattery();
      this.editDialogVisible = true;
      this.editDialogLoading = true;
      this.currentBatteryID = batteryID;
      this.editCurrentBatteryImageDropzoneOptions.url = '/api/ebs/batteries/' + this.batteryID;
      const battery = this.batteryList.find(battery => battery.id === batteryID);
      this.currentBattery = {
        manufacturerID: battery.manufacturer_id,
        model: battery.model,
        modelID: battery.id,
        modelReference: battery.model_reference,
        series: battery.series,
        seriesID: battery.series_id,
        typeID: battery.type_id,
        price: battery.price,
        warranty: battery.warranty,
        tradeInID: battery.trade_in_id,
        stock: battery.stock,
        image: battery.image,
        imageSize: battery.image_size,
      };
      this.$nextTick(() => {
        this.$refs['editCurrentBatteryForm'].clearValidate();
        var file = { size: this.currentBattery.imageSize, name: this.currentBattery.image, type: 'image/png' };
        console.log(this.currentBattery);
        var url = '../storage/images/batteries/';
        if (this.currentBattery.image) {
          this.$refs.editVueDropzone.manuallyAddFile(file, url + this.currentBattery.image);
        }
      });
      this.editDialogLoading = false;
    },

    handleCancel() {
      this.$refs.editVueDropzone.removeAllFiles();
      this.editDialogVisible = false;
    },

    editBattery() {
      this.$refs['editCurrentBatteryForm'].validate((valid) => {
        if (valid) {
          this.currentBatteryForm.append('manufacturerID', this.currentBattery.manufacturerID);
          this.currentBatteryForm.append('model', this.currentBattery.model);
          this.currentBatteryForm.append('modelReference', this.currentBattery.modelReference);
          this.currentBatteryForm.append('seriesID', this.currentBattery.seriesID);
          this.currentBatteryForm.append('typeID', this.currentBattery.typeID);
          this.currentBatteryForm.append('price', this.currentBattery.price);
          this.currentBatteryForm.append('warranty', this.currentBattery.warranty);
          this.currentBatteryForm.append('tradeInID', this.currentBattery.tradeInID);
          this.currentBatteryForm.append('stock', this.currentBattery.stock);
          this.currentBatteryForm.append('image', this.currentBattery.image);
          this.currentBatteryForm.append('imageSize', this.currentBattery.imageSize);
          /* Since HTML forms can't make PUT, PATCH, or DELETE requests,
          you will need to add a hidden _method field to spoof these HTTP verbs. */
          this.currentBatteryForm.append('_method', 'PUT');
          updateBattery(this.currentBatteryID, this.currentBatteryForm)
            .then(response => {
              this.$message({
                message: 'Battery information has been updated successfully',
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

    handleDelete(batteryID, model) {
      this.$confirm('This will permanently delete ' + model + '. Continue?', 'Warning', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        type: 'warning',
      }).then(() => {
        destroyBattery(batteryID).then(response => {
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

    resetNewBattery() {
      this.newBattery = {
        manufacturerID: '',
        model: '',
        modelReference: '',
        seriesID: '',
        typeID: '',
        price: '0',
        warranty: '0 month',
        tradeInID: '',
        stock: 0,
        image: '',
      };
      this.newBatteryForm = new FormData();
    },

    resetCurrentBattery() {
      this.currentBattery = {
        manufacturerID: '',
        model: '',
        modelReference: '',
        seriesID: '',
        typeID: '',
        price: '0',
        warranty: '0 month',
        tradeInID: '',
        stock: 0,
        image: '',
      };
      this.currentBatteryForm = new FormData();
    },

    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]));
    },

    fileAdded(file) {
      console.log(file.name + ' added');
      this.newBatteryForm.append('file', file);
      this.currentBatteryForm.append('file', file);
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
