<template>
  <div class="nav">
    <h1>Product List</h1>
    <div class="btns-container">
      <button class="btn add"><router-link to="/add">ADD</router-link></button>
      <button class="btn" id="delete-product-btn" @click="$emit('delete')">
        MASS DELETE
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
      &.add {
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
