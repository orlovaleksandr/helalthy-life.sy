import axios from "axios";
import {StatusCodes} from "http-status-codes";
import {apiConfig, apiConfigPatch} from "../../../utils/settings";
import {concatUrlByParams} from "../../../../../utils/url-generator";
import _ from 'lodash';

const state = () => ({
    cart: {},
    staticStore: {
        url: {
            apiCart: window.staticStore.urlCart,
            apiCartProduct: window.staticStore.urlCartProduct,
            viewProduct: window.staticStore.urlViewProduct,
            viewCart: window.staticStore.urlViewCart,
            assetImageProducts: window.staticStore.urlAssetImageProducts
        }
    }
});

const getters = {
    totalPrice(state) {
        let result = 0;

        if (!state.cart.cartProducts) {
            return 0;
        }

        state.cart.cartProducts.forEach(
            cartProduct => {
                result += cartProduct.product.price * cartProduct.quantity
            }
        )

        return result;
    }
};

const actions = {
    async getCart({state, commit, dispatch}) {
        const url = state.staticStore.url.apiCart;

        const response = await axios.get(url, apiConfig);

        if (
            response.data &&
            response.data["hydra:member"].length &&
            response.status === StatusCodes.OK
        ) {
            commit('setCart', response.data["hydra:member"][0]);
        } else {
            dispatch('createCart');
        }
    },
    async cleanCart({state, commit}) {
        const url = concatUrlByParams(state.staticStore.url.apiCart, state.cart.id);

        const response = await axios.delete(url, apiConfig);

        if (response.status === StatusCodes.NO_CONTENT) {
            commit('setCart', {});
        }
    },
    async removeCartProduct({state, commit,  dispatch}, cartProductId) {
        const url = concatUrlByParams(state.staticStore.url.apiCartProduct, cartProductId);

        const response = await axios.delete(url, apiConfig);

        if (response.status === StatusCodes.NO_CONTENT) {
            dispatch('getCart');
        }
    },
    addCartProduct({state, dispatch}, productData) {
        const existsCartProduct = state.cart.cartProducts.find(
            cartProduct => cartProduct.product.uuid === productData.uuid
        );

        if(existsCartProduct) {
            dispatch('addExistCartProduct', existsCartProduct);
        } else {
            dispatch('addNewCartProduct', productData);
        }
    },
    async createCart({state, dispatch}) {
        const url = state.staticStore.url.apiCart;

        const response = await axios.post(url, {}, apiConfig);

        if (response.data && response.status === StatusCodes.CREATED) {
            dispatch('getCart');
        }
    },
    async addExistCartProduct({state, dispatch}, existsCartProduct) {
        const url = concatUrlByParams(state.staticStore.url.apiCartProduct, existsCartProduct.id);

        const data = {
            "quantity": existsCartProduct.quantity + 1
        };

        const response = await axios.patch(url, data, apiConfigPatch);

        if (response.status === StatusCodes.OK) {
            dispatch('getCart');
        }
    },
    async addNewCartProduct({state, dispatch}, productData) {
        const url = state.staticStore.url.apiCartProduct;
        const data = {
            cart: '/api/carts/' + state.cart.id,
            product: '/api/products/' + productData.uuid,
            quantity: 1
        };

        const response = await axios.post(url, data, apiConfig);

        if (response.data && response.status === StatusCodes.CREATED) {
            dispatch('getCart')
        }
    },

};

const mutations = {
    setCart(state,cart) {
        state.cart = cart;
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}