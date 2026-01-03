import { createAsyncThunk, createSlice, type PayloadAction } from "@reduxjs/toolkit";
import api from "@/helper/axios";
import type { cartState, cartType } from "@/type/cart";
import axios from "axios";

export const fetchCart = createAsyncThunk<
    cartType[],
    void,
    {rejectValue : string}
>(
    'cart/fetchCart',
    async (_, { rejectWithValue }) => {
        try {
            const response = await api.get<cartType[]>('/user/cart');
            return response.data;
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        } catch (error : any) {
            if (axios.isAxiosError(error) && error.response) {
                return rejectWithValue(error.message || "terjadi error validation");
            }
            return rejectWithValue(error.message || "server sedang sibuk");
        }
    }
)

const initialState : cartState = {
    data : [],
    status : 'idle',
    error : null
}

const cartSlice = createSlice({
    name : "cart",
    initialState,
    reducers : {
        addcart : (state , action : PayloadAction<cartType>) => {
            const newItem : cartType = action.payload;
            const existItems = state.data.find(state => state.id === newItem.id)
            if (existItems) {
                existItems.jumlah += 1;
            } else {
                state.data.push({...newItem, jumlah : newItem.jumlah || 1});
            }
        },
        deleteCart : (state , action : PayloadAction<number>) => {
            state.data.filter(state => state.id != action.payload);
        }
    },
    extraReducers : (builder) => {
        builder.addCase(fetchCart.pending , (state) => {
            state.status = 'loading';
            state.error = null;
        })
        .addCase(fetchCart.fulfilled , (state , action : PayloadAction<cartType[]>) => {
            state.status = 'succeded';
            state.data = action.payload;
        })
        .addCase(fetchCart.rejected , (state , action) => {
            state.status = 'failed';
            state.error = (action.payload as string) || "terjadi kesalahan saat loading";
            state.data = [];
        });
    },
});

export const {addcart , deleteCart} = cartSlice.actions;
export default cartSlice.reducer;