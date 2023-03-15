import {concatUrlByParams, getUrlProductsByCategory} from "../../../../../utils/url-generator";
import axios from "axios";
import {StatusCodes} from "http-status-codes";
import {apiConfig} from "../../../../main/utils/settings";

const state = () => ({
    categories: [],
    categoryProducts:[],
    newOrderProduct: {
        categoryId: "",
        productId: "",
        quantity: "",
        pricePerOne: ""
    },
    orderProducts: [],
    busyProductsIds: [],
    staticStore: {
        orderId: window.staticStore.orderId,
        url: {
            viewProduct: window.staticStore.urlViewProduct,
            apiOrderProduct: window.staticStore.urlApiOrderProduct,
            apiCategory: window.staticStore.urlApiCategory,
            apiProducts: window.staticStore.urlApiProducts,
            apiOrder: window.staticStore.urlApiOrder,
        },
        productCountLimit: 30,
    }
})

const getters = {
    freeCategoryProducts(state) {
        return state.categoryProducts.filter(
            item => state.busyProductsIds.indexOf(item.id) === -1
        );
    }
};

const actions = {
    async getOrderProducts({commit, state}) {
        const url = concatUrlByParams(state.staticStore.url.apiOrder, state.staticStore.orderId);

        const response = await axios.get(url, apiConfig);
        console.log(response)
        if (response.data && response.status === StatusCodes.OK) {
            commit('setOrderProducts', response.data.orderProducts);
            commit('setBusyProductIds');
        }
    },
    async getProductsByCategory({commit, state}) {
        const url = getUrlProductsByCategory(
            state.staticStore.url.apiProducts,
            state.newOrderProduct.categoryId,
            1,
            state.staticStore.productCountLimit
        );

        const response = await axios.get(url, apiConfig);

        if (response.data && response.status === StatusCodes.OK) {
            commit('setCategoryProducts', response.data["hydra:member"]);
        }
    },
    async getCategories({commit, state}) {
        const url = state.staticStore.url.apiCategory;

        const response = await axios.get(url, apiConfig);

        if (response.data && response.status === StatusCodes.OK) {
            commit('setCategories', response.data["hydra:member"]);
        }
    },
    async removeOrderProduct({state, dispatch}, orderProductId) {
        const url = concatUrlByParams(state.staticStore.url.apiOrderProduct, orderProductId);
        const response = await axios.delete(url, apiConfig);

        if (response.status === StatusCodes.NO_CONTENT) {
            dispatch('getOrderProducts');
        }
    },
    async addNewOrderProduct({state, dispatch}) {
        const url = state.staticStore.url.apiOrderProduct;
        const data = {
            pricePerOne: String(state.newOrderProduct.pricePerOne),
            quantity: parseInt(state.newOrderProduct.quantity),
            product: "/api/products/" + state.newOrderProduct.productId,
            appOrder: "/api/orders/" + state.staticStore.orderId
        };

        const response = await axios.post(url, data, apiConfig);

        if (response.data && response.status === StatusCodes.CREATED) {
            dispatch('getOrderProducts');
        }
    }
};

const mutations = {
    setCategories(state, categories) {
        state.categories = categories;
    },
    setNewProductInfo(state, formData) {
        state.newOrderProduct.categoryId = formData.categoryId;
        state.newOrderProduct.productId = formData.productId;
        state.newOrderProduct.quantity = formData.quantity;
        state.newOrderProduct.pricePerOne = formData.pricePerOne;
    },
    setCategoryProducts(state, categoryProducts) {
        state.categoryProducts = categoryProducts;
    },
    setOrderProducts(state, orderProducts) {
        state.orderProducts = orderProducts;
    },
    setBusyProductIds(state) {
        state.busyProductsIds = state.orderProducts.map(item => item.product.id);
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}