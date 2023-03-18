<template>
  <div class="row">
    <div class="col-lg-12 order-block">
      <div class="order-content">
        <Alert/>
        <div v-if="showCartContent">
          <CartProductList />
          <CartTotalPrice/>
          <a
            class="btn btn-success mb-3 text-white"
            @click="makeOrder">
            MAKE ORDER
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapActions, mapState} from "vuex";
import CartProductList from "./components/CartProductList.vue";
import CartTotalPrice from "./components/CartTotalPrice.vue";
import Alert from "./components/Alert.vue";
import _ from 'lodash';

export default {
  name: "App",
  components: {Alert, CartTotalPrice, CartProductList},
  created() {
    this.getCart();
  },
  computed: {
    ...mapState('cart', ["isSentForm", "cart"]),
    showCartContent() {
      return !this.isSentForm && !_.isEmpty(this.cart);
    }
  },
  methods: {
    ...mapActions("cart", ['getCart', 'makeOrder']),
  }
}

</script>