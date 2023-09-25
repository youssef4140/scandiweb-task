<template>
  <ProductsListNav @delete="deleteProducts" />
  <div class="products-container">
    <div v-for="product in products" :key="product.id">
      <ProductCard
        :product="product"
        @delete="deleteProduct"
        @checked="addToDeleteList"
      />
    </div>
  </div>
</template>

<script>
import axios from "axios";
import ProductsListNav from "./ProductsListNav.vue";
import ProductCard from "@/components/ProductsList/ProductsContainer/ProductCard/ProductCard.vue";
export default {
  name: "ProductsHome",
  components: { ProductsListNav, ProductCard },
  data() {
    return {
      products: Array,
      deleteList: new Set(),
    };
  },
  mounted() {
    this.getProducts();
  },
  methods: {
    async deleteProducts() {
      try {
        const response = await axios.delete(
          `${process.env.VUE_APP_API_BASE}/products`,
          {
            data: [...this.deleteList],
          }
        );
        if (response.status === 200) {
          this.deleteFromProductsList();
        }
      } catch (error) {
        console.error("Error:", error);
      }
    },
    async getProducts() {
      try {
        const response = await axios.get(process.env.VUE_APP_API_BASE);
        if (response.status === 200) {
          const data = response.data;
          this.products = data;
        } else {
          console.error("HTTP error! Status:", response.status);
        }
      } catch (error) {
        console.error("Error:", error);
      }
    },
    addToDeleteList(id) {
      if (this.deleteList.has(id)) {
        this.deleteList.delete(id);
      } else {
        this.deleteList.add(id);
      }
      this.$emit("deleteList", this.deleteList);
    },
    deleteFromProductsList() {
      this.products = this.products.filter((product) => {
        return !this.deleteList.has(product.id);
      });
    },
    async deleteProduct(id) {
      try {
        const response = await axios.delete(
          `${process.env.VUE_APP_API_BASE}/product?id=${id}`
        );
        if (response) {
          this.products = this.products.filter((product) => {
            return product.id !== id;
          });
        }
      } catch (error) {
        console.error("Error:", error);
      }
    },
  },
};
</script>

<style>
.products-container {
  display: grid;
  grid-template-columns: repeat(4, minmax(20vw, 1fr));
  grid-gap: 20px;
  width: 90vw;
  margin-top: 20px;
  margin-bottom: 20px;
  @media screen and (max-width: 900px) {
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  }
}
</style>
