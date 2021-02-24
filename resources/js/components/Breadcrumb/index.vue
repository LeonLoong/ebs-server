<template>
  <el-breadcrumb class="app-breadcrumb" separator="/">
    <transition-group name="breadcrumb">
      <el-breadcrumb-item v-for="(item, index) in levelList" :key="item.path">
        <span v-if="item.redirect==='noredirect'||index==levelList.length-1" class="no-redirect">
          {{ generateTitle(item.meta.title) }}
        </span>
        <a v-else @click.prevent="handleLink(item)">{{ generateTitle(item.meta.title) }}</a>
      </el-breadcrumb-item>
    </transition-group>
  </el-breadcrumb>
</template>

<script>
import { generateTitle } from '@/utils/i18n';
import pathToRegexp from 'path-to-regexp';

export default {
  name: 'Breadcrumb',
  data() {
    return {
      levelList: null,
    };
  },
  watch: {
    $route() {
      this.getBreadcrumb();
    },
  },
  created() {
    this.getBreadcrumb();
  },
  methods: {
    generateTitle,
    merge(a, b, i = 0) {
      return a.slice(0, i).concat(b, a.slice(i));
    },
    getBreadcrumb() {
      let matched = this.$route.matched.filter(item => item.name);

      const first = matched[0];
      if (first && first.name.trim().toLocaleLowerCase() !== 'Dashboard'.toLocaleLowerCase()) {
        matched = [{ path: '/dashboard', meta: { title: 'dashboard' }}].concat(matched);
      }

      const prevPath = (matched[matched.length - 1].meta.prevPath);
      const prevBreadcrumb = (matched[matched.length - 1].meta.prevBreadcrumb);

      if (prevPath || prevBreadcrumb) {
        prevPath.forEach((prevPath, index) => {
          matched = this.merge(matched, [{ path: prevPath, meta: { title: prevBreadcrumb[index] }}], matched.length - 1);
        });
      }

      this.levelList = matched.filter(
        item => item.meta && item.meta.title && item.meta.breadcrumb !== false
      );
    },
    pathCompile(path) {
      const { params } = this.$route;
      var toPath = pathToRegexp.compile(path);
      return toPath(params);
    },
    handleLink(item) {
      const { redirect, path } = item;
      if (redirect) {
        this.$router.push(redirect);
        return;
      }
      this.$router.push(this.pathCompile(path));
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
.app-breadcrumb.el-breadcrumb {
  display: inline-block;
  font-size: 14px;
  line-height: 50px;
  margin-left: 10px;
  .no-redirect {
    color: #97a8be;
    cursor: text;
  }
}
</style>
