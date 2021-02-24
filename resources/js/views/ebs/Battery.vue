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

    <el-table v-loading="loading" :data="batteries" border fit highlight-current-row :default-sort="{prop: 'model', order: 'ascending'}" style="width: 100%">
      <el-table-column align="center" :label="$t('ebs.model')" sortable prop="model">
        <template slot-scope="scope">
          <span>{{ scope.row.model }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('ebs.modelReference')">
        <template slot-scope="scope">
          <router-link :to="'battery-manufacturers/' + scope.row.id + '/batteries'" class="link-type">
            <span>{{ scope.row.model_reference }}</span>
          </router-link>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('ebs.manufacturer')">
        <template slot-scope="scope">
          <router-link :to="'battery-manufacturers/' + scope.row.id + '/batteries'" class="link-type">
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
          <span>{{ scope.row.type_en }}</span>
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

    <el-dialog :visible.sync="addDialogVisible" :title="'Add New Battery'" :close-on-click-modal="false">
      <div v-loading="addDialogLoading">
        <el-form ref="addBatteryForm" :rules="rules" :model="newBattery" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item :label="$t('ebs.model')" prop="model">
            <el-input v-model="newBattery.model" placeholder="e.g.: 38B20L, 46B24L, 65D26L..." />
          </el-form-item>
          <el-form-item :label="$t('ebs.modelReference')" prop="model">
            <el-input v-model="newBattery.modelReference" placeholder="e.g.: NS40ZL, NS60L, NS70L..." />
          </el-form-item>
          <el-form-item :label="$t('ebs.manufacturer')" prop="manufacturers">
            <el-select v-model="newBattery.battery_manufacturer_id" placeholder="Select">
              <el-option
                v-for="(manufacturer, index) in manufacturer_list"
                :key="index"
                :label="manufacturer.manufacturer"
                :value="manufacturer.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('ebs.series')" prop="series">
            <el-select v-model="newBattery.battery_series_id" placeholder="Select">
              <el-option
                v-for="(series, index) in series_list"
                :key="index"
                :label="series.series"
                :value="series.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('ebs.type')" prop="types">
            <el-select v-model="newBattery.battery_type_id" placeholder="Select">
              <el-option
                v-for="(type, index) in type_list"
                :key="index"
                :label="type.type_en"
                :value="type.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item :label="$t('ebs.price')" prop="price">
            <el-input v-model="newBattery.price">
              <template slot="prepend">RM</template>
            </el-input>
          </el-form-item>
          <el-form-item :label="$t('ebs.tradeIn')" prop="tradeIn">
            <el-select v-model="newBattery.battery_trade_in_id" placeholder="Select">
              <el-option
                v-for="(tradeIn, index) in tradeIn_list"
                :key="index"
                :label="'RM' + tradeIn.price"
                :value="tradeIn.id"
              />
            </el-select>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="addDialogVisible = false">
            {{ $t('table.cancel') }}
          </el-button>
          <el-button type="primary" @click="createBattery()">
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
import { getBatteries, storeBattery, getBatteryManufacturers, getBatterySeries, getBatteryTypes, getBatteryTradeIns } from '@/api/ebs';

export default {
  name: 'Battery',
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
      newBattery: {
        battery_manufacturer_id: 0,
        battery_series_id: 0,
        model: '',
      },
      batteries: [],
      manufacturer_list: [],
      series_list: [],
      type_list: [],
      tradeIn_list: [],
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
      getBatteries(this.query)
        .then(response => {
          this.loading = true;
          const { limit, page } = this.query;
          const { data, meta } = response;
          this.batteries = data;
          this.batteries.forEach((element, index) => {
            element['index'] = (page - 1) * limit + index + 1;
          });
          this.total = meta.total;
        });

      getBatteryManufacturers()
        .then(response => {
          const { data } = response;
          this.manufacturer_list = data;
          this.loading = false;
        });

      getBatterySeries()
        .then(response => {
          const { data } = response;
          this.series_list = data;
          this.loading = false;
        });

      getBatteryTypes()
        .then(response => {
          const { data } = response;
          this.type_list = data;
          this.loading = false;
        });

      getBatteryTradeIns()
        .then(response => {
          const { data } = response;
          this.tradeIn_list = data;
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
        const data = this.formatJson(filterVal, this.batteries);
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'batteries',
        });
        this.downloading = false;
      });
    },

    createBattery() {
      this.$refs['addBatteryForm'].validate((valid) => {
        if (valid) {
          this.addDialogLoading = true;
          this.newBattery.battery_manufacturer_id = this.newBattery.battery_manufacturer_id;
          this.newBattery.battery_series_id = this.newBattery.battery_series_id;
          this.newBattery.model = this.newBattery.model;

          storeBattery(this.newBattery)
            .then(response => {
              this.$message({
                message: 'New battery ' + this.newBattery.model + ' has been created successfully.',
                type: 'success',
                duration: 5 * 1000,
              });
              this.resetNewBattery();
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

    resetNewBattery() {
      this.newBattery = {
        battery_manufacturer_id: '',
        battery_series_id: '',
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
