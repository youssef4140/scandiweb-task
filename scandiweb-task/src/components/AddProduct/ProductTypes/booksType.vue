<template>
  <div class="form-field">
    <label>weight</label>
    <div class="alert">
      <input id="weight" placeholder="Weight in KG" v-model="product.weight" />
      <alert :v$="v$" :field="'weight'" :prefix="'Weight'" />
    </div>
  </div>
</template>

<script>
import { required, integer } from "@vuelidate/validators";
import alert from "@/components/HelperComponents/ValidationAlert.vue";

export default {
  name: "discsType",
  components: { alert },
  props: {
    v$: Object,
  },
  data() {
    return {
      product: {
        weight: "",
      },
    };
  },
  mounted() {
    this.$emit("validation", {
      weight: { required, integer },
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
