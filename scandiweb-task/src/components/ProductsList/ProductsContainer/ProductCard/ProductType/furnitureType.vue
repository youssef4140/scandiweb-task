<template>
  <div v-if="!isEditing" class="card">
    <span>{{ product.sku }}</span>
    <span>{{ product.name }}</span>
    <span>{{ product.price }} $</span>
    <span>{{ product.product_type }}</span>
    <span>
      Dimensions: {{ product.product.dimension_l }}X{{
        product.product.dimension_w
      }}X{{ product.product.dimension_h }}
    </span>
  </div>
  <div v-if="isEditing" class="card">
    <input type="text" v-model="editedproduct.sku" />
    <input type="text" v-model="editedproduct.name" />
    <input type="number" v-model="editedproduct.price" />

    <div class="dimensions">
      <span>
        Dimensions:
        <input type="number" v-model="editedproduct.product.dimension_l" />
        X
        <input type="number" v-model="editedproduct.product.dimension_w" />
        X
        <input type="number" v-model="editedproduct.product.dimension_h" />
      </span>
    </div>
  </div>
</template>

<script>
import { required, integer } from "@vuelidate/validators";

export default {
  props: {
    product: Object,
    isEditing: Boolean,
  },
  data() {
    return {
      editedproduct: this.product,
    };
  },
  mounted() {
    this.$emit("validation", {
      dimension_l: { required, integer },
      dimension_w: { required, integer },
      dimension_h: { required, integer },
    });
  },
  watch: {
    editedproduct: {
      handler(val) {
        // console.log(val);
        this.$emit("edited", val);
      },
      deep: true,
    },
  },
  emits: ["edited", "delete", "validation"],
};
</script>

<style lang="scss" scoped>
input {
  text-align: center;
  margin-top: 5px;
  font-weight: bold;
  color: #2c3e50;
  border: 1px solid #2c3e50;
}
.dimensions {
  input {
    width: 30px;
  }
}
</style>
