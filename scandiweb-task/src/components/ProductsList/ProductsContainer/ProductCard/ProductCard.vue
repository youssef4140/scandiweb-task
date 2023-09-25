<template>
  <div class="product-card">
    <input
      type="checkbox"
      class="delete-checkbox"
      @change="$emit('checked', product.id)"
    />
    <template v-if="v$.$invalid && isEditing"> Invalid product </template>

    <component
      :is="product.product_type"
      :product="editedProduct"
      :isEditing="isEditing"
      @edited="edit"
      @delete="$emit('delete', product.id)"
      @validation="setTypeValidation"
    />
    <div class="btns-container">
      <button
        v-if="!isEditing"
        class="btn edit"
        @click="isEditing = !isEditing"
      >
        edit
      </button>
      <button v-if="isEditing" class="btn edit" @click="cancel()">
        cancel
      </button>
      <button v-if="isEditing" class="btn edit" @click="submit()">
        submit
      </button>
      <button class="btn delete" @click="$emit('delete', product.id)">
        delete
      </button>
    </div>
  </div>
</template>

<script>
import books from "@/components/ProductsList/ProductsContainer/ProductCard/ProductType/booksType.vue";
import discs from "@/components/ProductsList/ProductsContainer/ProductCard/ProductType/discsType.vue";
import furniture from "@/components/ProductsList/ProductsContainer/ProductCard/ProductType/furnitureType.vue";
import axios from "axios";
import useValidate from "@vuelidate/core";
import { required, numeric } from "@vuelidate/validators";

export default {
  props: {
    product: Object,
  },
  components: { books, discs, furniture },
  data() {
    return {
      v$: useValidate(),
      isEditing: false,
      editedProduct: JSON.parse(JSON.stringify(this.product)),
      typeValidation: { required },
    };
  },
  validations() {
    return {
      editedProduct: {
        sku: { required },
        name: { required },
        price: { required, numeric },
        product_type: { required },
        product: this.typeValidation,
      },
    };
  },
  methods: {
    setTypeValidation(validation) {
      this.typeValidation = validation;
    },
    async submit() {
      this.v$.$validate();
      if (this.v$.$invalid) return;
      const flattenedObject = { ...this.editedProduct.product };
      for (let key in this.editedProduct) {
        if (key !== "product") {
          flattenedObject[key] = this.editedProduct[key];
        }
      }
      try {
        const response = await axios.patch(
          `http://localhost:8000/api/product?id=${flattenedObject.id}`,
          flattenedObject
        );
        if (response.status === 200) {
          this.editedProduct = response.data;
          this.isEditing = !this.isEditing;
        } else {
          this.cancel();
        }
        console.log(response.data);
      } catch (error) {
        console.error("Error", error);
        // this.cancel();
      }
    },
    edit(val) {
      this.editedProduct = val;
    },
    cancel() {
      this.editedProduct = JSON.parse(JSON.stringify(this.product));
      this.isEditing = !this.isEditing;
    },
  },
};
</script>

<style lang="scss">
.product-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  border: 1px solid black;
  aspect-ratio: 1 / 0.8;
  font-weight: bold;

  .delete-checkbox {
    align-self: flex-start;
    margin: 5% 7%;
  }

  span {
    width: 80%;
    word-break: break-all;
    margin-bottom: 10px;
    font-weight: bold;
    font-size: 1.1vw;
    @media screen and (max-width: 1300px) {
      font-size: 1.6vw;
    }
    @media screen and (max-width: 900px) {
      font-size: 1.7vw;
    }
    @media screen and (max-width: 710px) {
      font-size: 3vw;
    }
    @media screen and (max-width: 465px) {
      font-size: 5vw;
    }
  }

  .btns-container {
    align-self: flex-end;
    margin: 0 5% 5% 0;

    .btn {
      font-size: 15px;
      padding: 3px;
      @media screen and (max-width: 1300px) {
        font-size: 1.2vw;
      }
      @media screen and (max-width: 900px) {
        font-size: 1.7vw;
      }
      @media screen and (max-width: 710px) {
        font-size: 2.3vw;
      }
      @media screen and (max-width: 465px) {
        font-size: 5vw;
      }

      &.delete {
        color: red;
      }

      &.edit {
        margin-right: 7px;
      }
    }
  }
}

.card {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
  aspect-ratio: 1 / 0.53;
  font-weight: bold;
}

input {
  max-width: 80%;

  &::-webkit-outer-spin-button,
  ::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  &[type="number"] {
    -moz-appearance: textfield;
  }
}
</style>
