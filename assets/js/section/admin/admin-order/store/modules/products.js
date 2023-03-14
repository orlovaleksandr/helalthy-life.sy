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
    staticStore: {
        orderId: window.staticStore.orderId,
        orderProducts: window.staticStore.orderProducts,
        url: {
            viewProduct: window.staticStore.urlViewProduct,
            apiOrderProduct: window.staticStore.urlApiOrderProduct,
            apiCategory: window.staticStore.urlApiCategory,
            apiProducts: window.staticStore.urlApiProducts,
        },
        productCountLimit: 30,
    }
})

const getters = () => ({})

const actions = {
    async getProductsByCategory({commit, state}) {
        const url = getUrlProductsByCategory(
            state.staticStore.url.apiProducts,
            state.newOrderProduct.categoryId,
            1,
            state.staticStore.productCountLimit
        );
        console.log(url)

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
            console.log('Deleted!')
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
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}