<template>
  <div class="form-field">
    <label>Dimensions</label>
    <div class="dimensions">
      <input id="length" placeholder="L" v-model="product.dimension_l" />
      <span>X</span>
      <input id="width" placeholder="W" v-model="product.dimension_w" />
      <span>X</span>
      <input id="height" placeholder="H" v-model="product.dimension_h" />
    </div>
    <div></div>
    <div class="alert">
      <alert :v$="v$" :field="'dimension_l'" :prefix="'Length'" />
      <alert :v$="v$" :field="'dimension_w'" :prefix="'Width'" />
      <alert :v$="v$" :field="'dimension_h'" :prefix="'Height'" />
    </div>
  </div>
</template>

<script>
import { required, integer } from "@vuelidate/validators";
import alert from "@/components/HelperComponents/ValidationAlert.vue";
export default {
  name: "furnitureType",
  components: { alert },
  props: {
    v$: Object,
  },
  data() {
    return {
      product: {
        dimension_l: "",
        dimension_w: "",
        dimension_h: "",
      },
    };
  },
  emits: ["validation", "product"],
  mounted() {
    this.$emit("validation", {
      dimension_l: { required, integer },
      dimension_w: { required, integer },
      dimension_h: { required, integer },
    });
  },
  watch: {
    product: {
      handler(val) {
        this.$emit("product", val);
      },
      deep: true,
    },
  },
};
</script>

<style lang="scss" scoped>
.form-field {
  margin-top: 1.4%;

  .dimensions {
    display: flex;
    align-items: center;
    justify-content: center;
    @media screen and (max-width: 600px) {
      flex-direction: column;
    }

    input {
      width: 40px !important;
      margin-top: 0 !important;
      text-align: center;
    }

    span {
      margin: 0 5px;
      font-weight: bold;
    }
  }
}

.alert {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
</style>
