<template>
  <div class="home">
    <AddProduct />
  </div>
</template>

<script>
import AddProduct from "@/components/AddProduct/AddProduct.vue";

export default {
  name: "AddView",
  components: {
    AddProduct,
  },
};
</script>
