<template>
  <template v-for="(error, index) in v$.$errors" :key="index">
    <template v-if="error.$property === field">
      <span v-if="isVisible">{{ prefix }} {{ error.$message }} !</span>
    </template>
  </template>
</template>

<script>
export default {
  props: {
    v$: Object,
    field: String,
    prefix: String,
  },
  data() {
    return {
      isVisible: true,
    };
  },
  //   mounted() {
  //     this.hide();
  //   },
  //   watch: {
  //     v$: {
  //       handler() {
  //         this.show();
  //       },
  //       deep: true,
  //     },
  //   },
  //   methods: {
  //     hide() {
  //       setTimeout(() => {
  //         this.isVisible = false;
  //       }, 2400);
  //     },
  //     show() {
  //       this.isVisible = true;
  //       this.hide();
  //     },
  //   },
};
</script>

<style lang="scss" scoped>
span {
  color: red;
}
</style>
