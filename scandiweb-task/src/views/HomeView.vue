<template>
  <div class="home">
    <ProductsList />
  </div>
</template>

<script>
import ProductsList from "@/components/ProductsList/ProductsList.vue";

export default {
  name: "HomeView",
  components: {
    ProductsList,
  },
};
</script>
