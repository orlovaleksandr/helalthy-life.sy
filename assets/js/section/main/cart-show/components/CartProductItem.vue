<template>
  <tr>
    <td class="product-col">
      <div class="text-center">
        <figure>
          <a :href="urlShowProduct" target="_blank">
            <img
                :src="getUrlProductImage(productImage)"
                :alt="cartProduct.product.title"
            >
          </a>
        </figure>
        <div class="product-title">
          <a
              :href="urlShowProduct"
              target="_blank"
          >
            {{cartProduct.product.title}}
          </a>
        </div>
      </div>
    </td>
    <td class="price-col">
      ${{cartProduct.product.price}}
    </td>
    <td class="quantity-col">
      <input
        v-model="quantity"
        type="number"
        class="form-control"
        min="1"
        step="1"
        @focusout="updateQuantity"
      >
    </td>
    <td class="total-col">
      ${{productPrice}}
    </td>
    <td class="remove-col">
      <a
          href="#"
          class="btm-remove"
          title="Remove product"
          @click="removeCartProduct(cartProduct.id)"
      >
        X
      </a>
    </td>
  </tr>
</template>

<script>

import {mapActions, mapState} from "vuex";

export default  {
  name: "CartProductItem",
  props: {
    cartProduct: {
      type: Object,
      default: {}
    }
  },
  data() {
    return {
      quantity: 1
    }
  },
  created() {
    this.quantity = this.cartProduct.quantity;
  },
  computed: {
    ...mapState('cart', ['staticStore']),
    productImage() {
      const productImages = this.cartProduct.product.productImages;

      return productImages.length ? productImages[0] : null;
    },
    productPrice() {
      return this.quantity * this.cartProduct.product.price;
    },
    urlShowProduct() {
      return this.staticStore.url.viewProduct + '/' + this.cartProduct.product.uuid;
    }
  },
  methods: {
    ...mapActions('cart', ["removeCartProduct", "updateCartProductQuantity"]),
    getUrlProductImage(productImage) {
      return (
          this.staticStore.url.assetImageProducts +
              "/" +
              this.cartProduct.product.id +
              "/" +
              productImage.filenameSmall
      );
    },
    updateQuantity() {
      const payload = {
        cartProductId: this.cartProduct.id,
        quantity: this.quantity
      };

      this.updateCartProductQuantity(payload);
    }
  }
}
</script>

<style scoped>

</style>