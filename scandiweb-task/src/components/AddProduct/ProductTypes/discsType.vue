<template>
  <div class="form-field">
    <label>size</label>
    <div class="alert">
      <input id="size" placeholder="Size in MB" v-model="product.size" />
      <alert :v$="v$" :field="'size'" :prefix="'Size'" />
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
        size: "",
      },
    };
  },
  mounted() {
    this.$emit("validation", {
      size: { required, integer },
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
