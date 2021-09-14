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

    <el-table v-loading="loading" :data="transactionRecordsList" border fit highlight-current-row :default-sort="{prop: 'phoneNo', order: 'ascending'}" style="width: 100%">
      <el-table-column align="center" :label="$t('ebs.name')">
        <template slot-scope="scope">
          <router-link :to="'name/' + scope.row.id" class="link-type">
            <span>{{ scope.row.user }}</span>
          </router-link>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('ebs.phoneNo')" width="130">
        <template slot-scope="scope">
          <span>{{ scope.row.phone_no }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('ebs.transactionRecords.car')">
        <template slot-scope="scope">
          <span v-if="scope.row.car_description !== null">
            {{ scope.row.car_manufacturer + ' ' + scope.row.car_model + ' ' + scope.row.car_description }}
          </span>
          <span v-else>
            {{ scope.row.car_manufacturer + ' ' + scope.row.car_model }}
          </span>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('ebs.plateNo')" width="130">
        <template slot-scope="scope">
          <span>{{ scope.row.plate_no }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('ebs.transactionRecords.battery')">
        <template slot-scope="scope">
          <span>{{ scope.row.battery_manufacturer + ' ' + scope.row.battery_model }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" scoped-slot>
        <template slot="header">
          <span v-for="(text, index) in $t('ebs.transactionRecords.paymentMethod').split(' ')" :key="index">{{ text }}<br></span>
        </template>
        <template slot-scope="scope">
          <span>{{ scope.row.payment_method }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" scoped-slot>
        <template slot="header">
          <span v-for="(text, index) in $t('ebs.transactionRecords.servicePoint').split(' ')" :key="index">{{ text }}<br></span>
        </template>
        <template slot-scope="scope">
          <span>{{ scope.row.service_point }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="$t('ebs.createdAt')" width="160">
        <template slot-scope="scope">
          <span v-for="(createdAt, index) in scope.row.created_at.split(' ')" :key="index">{{ createdAt }}<br></span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Actions">
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

    <el-dialog :visible.sync="addDialogVisible" :title="'Add New Transaction Record'" :close-on-click-modal="false">
      <div v-loading="addDialogLoading">
        <el-form ref="addNewTransactionRecordForm" :rules="rules" :model="newTransactionRecord" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item :label="$t('ebs.phoneNo')" prop="phoneNo">
            <el-input v-model="newTransactionRecord.phoneNo" placeholder="e.g.: 60129652529..." />
          </el-form-item>
          <el-form-item :label="$t('ebs.transactionRecords.car')" prop="carID">
            <el-select v-model="newTransactionRecord.carID" placeholder="Select" filterable>
              <el-option
                v-for="(car, index) in carList"
                :key="index"
                :label="car.manufacturer + ' ' + car.model + car.description"
                :value="car.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('ebs.plateNo')" prop="plateNo">
            <el-input v-model="newTransactionRecord.plateNo" placeholder="e.g.: DBJ8722..." />
          </el-form-item>
          <el-form-item :label="$t('ebs.transactionRecords.battery')" prop="batteryID">
            <el-select v-model="newTransactionRecord.batteryID" placeholder="Select" filterable>
              <el-option
                v-for="(battery, index) in batteryList"
                :key="index"
                :label="battery.manufacturer + ' ' + battery.model"
                :value="battery.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('ebs.transactionRecords.paymentMethod')" prop="paymentMethod">
            <el-select v-model="newTransactionRecord.paymentMethodID" placeholder="Select" filterable>
              <el-option
                v-for="(paymentMethod, index) in paymentMethodList"
                :key="index"
                :label="paymentMethod.method"
                :value="paymentMethod.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('ebs.transactionRecords.servicePoint')" prop="servicePoint">
            <el-input v-model="newTransactionRecord.servicePoint" placeholder="e.g.: Batu 2..." />
          </el-form-item>
          <el-form-item :label="$t('ebs.createdAt')">
            <el-date-picker
              v-model="newTransactionRecord.createdAt"
              type="datetime"
              value-format="yyyy-MM-dd HH:mm:ss"
              placeholder="Select date and time"
            />
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="addDialogVisible = false">
            {{ $t('table.cancel') }}
          </el-button>
          <el-button type="primary" @click="addTransactionRecord()">
            {{ $t('table.confirm') }}
          </el-button>
        </div>
      </div>
    </el-dialog>

    <el-dialog :visible.sync="editDialogVisible" :title="'Edit Transaction Record - ' + currentTransactionRecord.model" :close-on-click-modal="false">
      <div v-loading="editDialogLoading">
        <el-form ref="editCurrentTransactionRecordForm" :rules="rules" :model="currentTransactionRecord" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item :label="$t('ebs.phoneNo')" prop="phoneNo">
            <el-input v-model="currentTransactionRecord.phoneNo" placeholder="e.g.: 60129652529..." />
          </el-form-item>
          <el-form-item :label="$t('ebs.transactionRecords.car')" prop="carID">
            <el-select v-model="currentTransactionRecord.carID" placeholder="Select" filterable>
              <el-option
                v-for="(car, index) in carList"
                :key="index"
                :label="car.manufacturer + ' ' + car.model + car.description"
                :value="car.id"
              />
            </el-select>
          </el-form-item>
        </el-form>
        <div style="text-align:right;">
          <el-button type="danger" @click="handleCancel()">
            {{ $t('table.cancel') }}
          </el-button>
          <el-button type="primary" @click="editTransactionRecord()">
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
import moment from 'moment';
import { getTransactionRecords, storeTransactionRecord, updateTransactionRecord, destroyTransactionRecord, getCars, getBatteries, getPaymentMethods } from '@/api/ebs';

export default {
  name: 'TransactionRecordManufacturer',
  components: {
    Pagination,
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
        model: [{ required: true, message: 'TransactionRecord is required', trigger: 'blur' }],
      },
      currentTransactionRecord: {
        manufacturerID: '',
        model: '',
        description: '',
        image: '',
      },
      currentTransactionRecordID: 0,
      editCurrentTransactionRecordImageDropzoneOptions: {
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
      newTransactionRecord: {
        batteryID: 0,
        carID: 0,
        paymentMethodID: 0,
        phoneNo: '',
        plateNo: '',
        servicePoint: '',
        createdAt: new Date(),
      },
      addNewTransactionRecordImageDropzoneOptions: {
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
      transactionRecordsList: [],
      carList: [],
      batteryList: [],
      paymentMethodList: [],
      total: 0,
    };
  },

  created() {
    this.query.keyword = this.$route.params.keyword;
    this.getData();
  },

  methods: {
    async getData() {
      getTransactionRecords(this.query)
        .then(response => {
          this.loading = true;
          const { limit, page } = this.query;
          const { data, meta } = response;
          this.transactionRecordsList = data;
          this.transactionRecordsList.forEach((element, index) => {
            element['index'] = (page - 1) * limit + index + 1;
          });
          this.total = meta.total;
          this.loading = false;
        });

      getCars()
        .then(response => {
          const { data } = response;
          this.carList = data;
          this.carList.forEach((element, index) => {
            if (element['description'] !== null) {
              element['description'] = ' ' + '(' + element['description'] + ')';
            } else {
              element['description'] = '';
            }
          });
          this.loading = false;
        });

      getBatteries()
        .then(response => {
          const { data } = response;
          this.batteryList = data;
          this.loading = false;
        });

      getPaymentMethods()
        .then(response => {
          const { data } = response;
          this.paymentMethodList = data;
          this.loading = false;
        });
    },

    handleSearch() {
      this.query.page = 1;
      this.getData();
    },

    handleAdd() {
      this.resetNewTransactionRecord();
      this.newTransactionRecord = {
        createdAt: moment(new Date()).format('YYYY-MM-DD hh:mm:ss'),
      };
      this.addDialogVisible = true;
      this.$nextTick(() => {
        this.$refs['addNewTransactionRecordForm'].clearValidate();
      });
    },

    addTransactionRecord() {
      this.$refs['addNewTransactionRecordForm'].validate((valid) => {
        if (valid) {
          this.addDialogLoading = true;
          storeTransactionRecord(this.newTransactionRecord)
            .then(response => {
              this.$message({
                message: 'New transaction record has been created successfully.',
                type: 'success',
                duration: 5 * 1000,
              });
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
        const tHeader = ['id', 'username', 'phoneNo', 'description'];
        const filterVal = ['index', 'username', 'phoneNo', 'description'];
        const data = this.formatJson(filterVal, this.transactionRecordsList);
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'cars',
        });
        this.downloading = false;
      });
    },

    async handleEdit(carID) {
      this.resetCurrentTransactionRecord();
      this.editDialogVisible = true;
      this.editDialogLoading = true;
      this.currentTransactionRecordID = carID;
      this.editCurrentTransactionRecordImageDropzoneOptions.url = '/api/ebs/cars/' + this.carID;
      const car = this.transactionRecordsList.find(car => car.id === carID);
      this.currentTransactionRecord = {
        manufacturerID: car.manufacturer_id,
        model: car.model,
        description: car.description,
        image: car.image,
        imageSize: car.image_size,
      };
      this.$nextTick(() => {
        this.$refs['editCurrentTransactionRecordForm'].clearValidate();
      });
      this.editDialogLoading = false;
    },

    handleCancel() {
      this.editDialogVisible = false;
    },

    editTransactionRecord() {
      this.$refs['editCurrentTransactionRecordForm'].validate((valid) => {
        if (valid) {
          this.currentTransactionRecordForm.append('manufacturerID', this.currentTransactionRecord.manufacturerID);
          this.currentTransactionRecordForm.append('model', this.currentTransactionRecord.model);
          this.currentTransactionRecordForm.append('description', this.currentTransactionRecord.description);
          this.currentTransactionRecordForm.append('image', this.currentTransactionRecord.image);
          this.currentTransactionRecordForm.append('imageSize', this.currentTransactionRecord.imageSize);
          /* Since HTML forms can't make PUT, PATCH, or DELETE requests,
          you will need to add a hidden _method field to spoof these HTTP verbs. */
          this.currentTransactionRecordForm.append('_method', 'PUT');
          updateTransactionRecord(this.currentTransactionRecordID, this.currentTransactionRecordForm)
            .then(response => {
              this.$message({
                message: 'TransactionRecord information has been updated successfully',
                type: 'success',
                duration: 5 * 1000,
              });
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
        destroyTransactionRecord(carID).then(response => {
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

    resetNewTransactionRecord() {
      this.newTransactionRecord = {
        manufacturer: '',
        model: '',
        description: '',
        image: '',
      };
      this.newTransactionRecordManufacturerForm = new FormData();
    },

    resetCurrentTransactionRecord() {
      this.currentTransactionRecord = {
        manufacturer: '',
        model: '',
        description: '',
        image: '',
      };
      this.currentTransactionRecordManufacturerForm = new FormData();
    },

    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]));
    },

    fileAdded(file) {
      console.log(file.name + ' added');
      this.newTransactionRecordForm.append('file', file);
      this.currentTransactionRecordForm.append('file', file);
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
