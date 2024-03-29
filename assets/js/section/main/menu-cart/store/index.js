import {createStore} from "vuex"
import cart from "./modules/cart"

const debug = process.env.NODE_ENV !== "production";

export default createStore({
    modules: {
        cart
    },
    strict: debug
})