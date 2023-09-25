<template>
  <div class="nav">
    <h1>Product Add</h1>
    <div class="btns-container">
      <button class="btn" @click="$emit('submit')">Save</button>
      <button class="btn cancel">
        <router-link to="/">Cancel</router-link>
      </button>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.nav {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 90vw;
  border-bottom: 2px solid black;
  .btns-container {
    .btn {
      margin-right: 10px;
      &.cancel {
        padding: 0;
        a {
          display: block;
          padding: 5px;
        }
      }
    }
  }
}
span {
  text-align: center;
}
</style>
