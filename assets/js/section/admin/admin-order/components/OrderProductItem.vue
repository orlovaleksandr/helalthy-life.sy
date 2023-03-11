<template>
  <div class="row mb-1">
    <div class="col-md-1 text-center">
      {{rowNumber}}
    </div>
    <div class="col-md-2">
      {{productTitle}}
    </div>
    <div class="col-md-2">
      {{categoryTitle}}
    </div>
    <div class="col-md-2">
      {{orderProduct.quantity}}
    </div>
    <div class="col-md-2">
      {{orderProduct.pricePerOne}}$
    </div>
    <div class="col-md-3">
      <button class="btn btn-outline-info">Details</button>
      <button class="btn btn-outline-success">Remove</button>
    </div>
  </div>
</template>

<script>
export default {
  name: "OrderProductItem",
  props: {
    orderProduct: {
      type: Object,
      default: {}
    },
    index: {
      type: Number,
      default: 0
    }
  },
  computed: {
    rowNumber() {
      return this.index + 1;
    },
    productTitle() {
      return this.orderProduct.product.title;
    },
    categoryTitle() {
      return this.orderProduct.product.category.title;
    }
  }
}
</script>