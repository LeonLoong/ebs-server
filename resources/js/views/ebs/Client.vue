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

    <el-table v-loading="loading" :data="clients" border fit highlight-current-row :default-sort="{prop: 'handphoneNumber', order: 'ascending'}" style="width: 100%">
      <el-table-column align="center" :label="$t('ebs.client.handphoneNumber')" prop="handphoneNumber" width="130">
        <template slot-scope="scope">
          <span>{{ scope.row.handphone_number }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('ebs.client.car')" width="150">
        <template slot-scope="scope">
          <router-link :to="'cars/' + scope.row.id" class="link-type">
            <span>{{ scope.row.car_manufacturer + ' ' + scope.row.car_model }}</span>
          </router-link>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('ebs.client.battery')" width="150">
        <template slot-scope="scope">
          <router-link :to="'cars/' + scope.row.id" class="link-type">
            <span>{{ scope.row.battery_manufacturer + ' ' + scope.row.battery_model }}</span>
          </router-link>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('ebs.client.paymentMethod')" width="130" :render-header="set_paymentMethodHeader">
        <template slot-scope="scope">
          <span>{{ scope.row.payment_method }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('ebs.client.discount')" width="130">
        <template slot-scope="scope">
          <span v-if="scope.row.discount === '0'">No</span>
          <span v-else>{{ 'RM' + scope.row.discount }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('ebs.client.servicePoints')" width="200" :render-header="set_servicePointsHeader">
        <template slot-scope="scope">
          <span>{{ scope.row.service_points }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('ebs.client.totalVisits')" width="130">
        <template slot-scope="scope">
          <span>{{ scope.row.total_visits }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('ebs.client.totalTransactions')" width="130">
        <template slot-scope="scope">
          <span>{{ scope.row.total_transactions }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('ebs.client.serviceDifficulty')" width="130" :render-header="set_serviceDifficulyHeader">
        <template slot-scope="scope">
          <span>{{ scope.row.service_difficulty }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('ebs.client.description')" width="200">
        <template slot-scope="scope">
          <span>{{ scope.row.description }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('ebs.client.firstVisitAt')" width="160">
        <template slot-scope="scope">
          <span>{{ scope.row.first_visit_at }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('ebs.client.lastVisitAt')" width="160">
        <template slot-scope="scope">
          <span>{{ scope.row.last_visit_at }}</span>
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
          <el-form-item :label="$t('ebs.modelReference')" prop="model">
            <el-input v-model="newCar.modelReference" />
          </el-form-item>
          <el-form-item :label="$t('ebs.manufacturer')" prop="manufacturers">
            <el-select v-model="newCar.car_manufacturer_id" placeholder="Select">
              <el-option
                v-for="(manufacturer, index) in manufacturer_list"
                :key="index"
                :label="manufacturer.manufacturer"
                :value="manufacturer.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('ebs.price')" prop="price">
            <el-input v-model="newCar.price" />
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
import { getClients, getCarManufacturers } from '@/api/ebs';

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
      clients: [],
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
    set_serviceDifficulyHeader(h, { column, $index }) {
      return h('div', {
      }, [
        h('span', 'Service'),
        h('br'),
        h('span', 'Difficulty'),
      ]);
    },

    set_paymentMethodHeader(h, { column, $index }) {
      return h('div', {
      }, [
        h('span', 'Payment'),
        h('br'),
        h('span', 'Method'),
      ]);
    },

    set_servicePointsHeader(h, { column, $index }) {
      return h('div', {
      }, [
        h('span', 'Service'),
        h('br'),
        h('span', 'Points'),
      ]);
    },

    async getData() {
      getClients(this.query)
        .then(response => {
          this.loading = true;
          const { limit, page } = this.query;
          const { data, meta } = response;
          this.clients = data;
          this.clients.forEach((element, index) => {
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

    // createCar() {
    //   this.$refs['addCarForm'].validate((valid) => {
    //     if (valid) {
    //       this.addDialogLoading = true;
    //       this.newCar.battery_manufacturer_id = this.newCar.car_manufacturer_id;
    //       this.newCar.model = this.newCar.model;

    //       storeCar(this.newCar)
    //         .then(response => {
    //           this.$message({
    //             message: 'New battery ' + this.newCar.model + ' has been created successfully.',
    //             type: 'success',
    //             duration: 5 * 1000,
    //           });
    //           this.resetNewCar();
    //           this.addDialogVisible = false;
    //           this.handleFilter();
    //         })
    //         .catch(error => {
    //           console.log(error);
    //         })
    //         .finally(() => {
    //           this.addDialogLoading = false;
    //         });
    //     } else {
    //       console.log('error submit!!');
    //       return false;
    //     }
    //   });
    // },

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
