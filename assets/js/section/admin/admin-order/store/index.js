import {createStore} from "vuex"
import products from "./modules/products"

const debug = process.env.NODE_ENV !== "production";

export default createStore({
    modules: {
        products
    },
    strict: debug
})