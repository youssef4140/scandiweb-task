<template>
  <AddProductNav @submit="submit()" />
  <form id="product_form">
    <div class="form-field">
      <label>SKU</label>
      <div class="alert">
        <input id="sku" placeholder="Unique" v-model="productForm.sku" />
        <span style="color: red">{{ duplicationValidation }}</span>
        <alert :v$="v$" :field="'sku'" />
      </div>
      <label>Name</label>
      <div class="alert">
        <input id="name" v-model="productForm.name" />

        <alert :v$="v$" :field="'name'" />
      </div>

      <label>Price ($)</label>
      <div class="alert">
        <input id="price" v-model="productForm.price" />
        <alert :v$="v$" :field="'price'" />
      </div>
    </div>
    <div class="alert">
      <div class="form-field select">
        <label>Product Type</label>
        <select id="productType" v-model="productForm.product_type">
          <option style="display: none" value="" selected>Select a type</option>
          <option value="discs">DVD</option>
          <option value="books">Book</option>
          <option value="furniture">Furniture</option>
        </select>
      </div>
      <alert :v$="v$" :field="'product_type'" />
    </div>

    <component
      v-if="productForm.product_type"
      :is="productForm.product_type"
      @product="handleProduct"
      @validation="setTypeValidation"
      :v$="v$"
    />
  </form>
</template>

<script>
import AddProductNav from "./AddProductNav.vue";
import books from "./ProductTypes/booksType.vue";
import discs from "./ProductTypes/discsType.vue";
import furniture from "./ProductTypes/furnitureType.vue";
import axios from "axios";
import useValidate from "@vuelidate/core";
import { required, integer } from "@vuelidate/validators";
import alert from "@/components/HelperComponents/ValidationAlert.vue";
export default {
  name: "AddProduct",
  components: { AddProductNav, books, discs, furniture, alert },
  data() {
    return {
      v$: useValidate(),
      typeValidation: { required },
      productForm: {
        sku: "",
        name: "",
        price: "",
        product_type: "",
        product: {},
      },
      duplicationValidation: "",
    };
  },
  validations() {
    return {
      productForm: {
        sku: { required },
        name: { required },
        price: { required, integer },
        product_type: { required },
        product: this.typeValidation,
      },
    };
  },
  methods: {
    handleProduct(product) {
      this.productForm.product = product;
    },
    async submit() {
      this.v$.$validate();

      if (!this.v$.$invalid) {
        const flattenedObject = { ...this.productForm.product };

        for (let key in this.productForm) {
          if (key !== "product") {
            flattenedObject[key] = this.productForm[key];
          }
        }
        // console.log(flattenedObject);
        try {
          const response = await axios.post(
            process.env.VUE_APP_API_BASE,
            flattenedObject
          );
          if (response.status === 200) {
            // console.log(response.data);
            if (response.data) {
              this.$router.push({ path: "/" });
            }
          }
        } catch (error) {
          if (error.response?.data.Code === "23000") {
            this.triggerDuplicationAlert();
          }
        }
      }
    },
    triggerDuplicationAlert() {
      this.duplicationValidation = "This SKU already exists";

      setTimeout(() => {
        this.duplicationValidation = "";
      }, 2400);
    },
    setTypeValidation(validation) {
      this.typeValidation = validation;
    },
    alert() {
      return "smth";
    },
  },
};
</script>

<style lang="scss">
#product_form {
  min-height: 70vh;
  margin-top: 1vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  // @media screen and (max-width: 600px) {
  //   height: auto;
  // }

  .form-field {
    width: 50%;
    display: grid;
    gap: 10px;
    grid-template-columns: 1fr 2fr;
    place-items: center;

    .alert {
      display: flex;
      flex-direction: column;
    }

    &.select {
      grid-template-columns: 4fr 4fr;
      place-items: center;
      margin: 5vh 0 5vh 0;
    }
  }

  input,
  select {
    margin-top: 5%;
    border: 2px solid black;
    padding: 10px;
    width: 250px;
    @media screen and (max-width: 600px) {
      width: 125px;
    }
  }

  select {
    width: 155px;
    @media screen and (max-width: 600px) {
      width: 125px;
    }
  }

  label {
    display: flex;
    align-items: center;
    margin-top: 5%;
    margin-left: 2%;
    text-align: left;
    width: fit-content;
    font-weight: bold;
    font-size: 26px;
    white-space: nowrap;
    @media screen and (max-width: 600px) {
      font-size: 20px;
      margin-left: 0;
    }
  }
}
</style>
