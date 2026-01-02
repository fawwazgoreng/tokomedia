import { configureStore } from "@reduxjs/toolkit";
import cartSlice from "./store/cart";
import userAuth from "./store/login";

const store = configureStore({
    reducer : {
        "cart" : cartSlice,
        "userauth" : userAuth
    },
})


export type AppSelector = ReturnType<typeof store.getState>;
export type AppDispatch = typeof store.dispatch;
export default store;