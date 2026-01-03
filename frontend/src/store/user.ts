import {
  createAsyncThunk,
  createSlice,
  type PayloadAction,
} from "@reduxjs/toolkit";
import api from "@/helper/axios";
import type { errorTemplate, user, userStoreType } from "@/type/login";
import { userStore } from "@/helper/auth/userauth";
import axios from "axios";

export const initCsrf = createAsyncThunk("auth/csrf", async () => {
  await api.get("/sanctum/csrf-cookie");
});

export const login = createAsyncThunk<user , {email : string , password : string}>(
  "user/login",
  async (payload: { email: string; password: string } , {rejectWithValue}) => {
    try {
      await api.get("/sanctum/csrf-cookie");
      const res = await api.post("/api/user/login", payload );
      userStore.set(res.data.token);
      return res.data.user;
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    } catch (error : any) {
      if (axios.isAxiosError(error) && error.response) {
        return rejectWithValue(error.response.data);
      }
      return rejectWithValue({message : "server sedang sibuk silahkan coba lagi nanti"});
    }
  }
);

export const register = createAsyncThunk<user , {email : string , password : string , name : string}>(
  "user/register",
  async (payload: { email: string; password: string  , name : string} , {rejectWithValue}) => {
    try {
      await api.get("/sanctum/csrf-cookie");
      const res = await api.post("/api/user/register", payload );
      userStore.set(res.data.token);
      return res.data.user;
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    } catch (error : any) {
      if (axios.isAxiosError(error) && error.response) {
        return rejectWithValue(error.response.data);
      }
      return rejectWithValue({message : "server sedang sibuk silahkan coba lagi nanti"});
    }
  }
);

export const refresh = createAsyncThunk('user/refresh' , async () => {
  const res = await api.post('/api/user/refresh');
  userStore.set(res.data.token);
  console.log(userStore.get());
  return true;
});

export const profile = createAsyncThunk("user/profile", async () => {
  const res = await api.get("/api/user/profile");
  return res.data;
});

export const logout = createAsyncThunk("user/logout", async () => {
  await api.delete("/api/user/logout");
  userStore.clear();
});

const initialState: userStoreType = {
  status: "idle",
  user: null,
  isLogin: false,
  token: null,
  error : ''
};

const userAuth = createSlice({
  name: "user",
  initialState,
  reducers: {
    setToken : (state , action : PayloadAction<string | null>) => {
      state.token = action.payload;
      state.isLogin = true;
    }
  },
  extraReducers: (builder) => {
    builder
      /* login */
      .addCase(login.pending, (state) => {
        state.status = "loading";
      })
      .addCase(login.fulfilled, (state, action: PayloadAction<user> ) => {
        state.status = "authenticated";
        state.user = action.payload;
        state.isLogin = true;
      })
      .addCase(login.rejected, (state , action  ) => {
        state.status = 'idle';
        state.user = null;
        state.isLogin = false;
        const payload = action.payload as errorTemplate ;
        state.error = payload?.error || payload?.message || payload?.status;
      })
      /* profile */
      .addCase(profile.pending, (state) => {
        state.status = "loading";
      })
      .addCase(profile.fulfilled, (state, action: PayloadAction<user>) => {
        state.status = "authenticated";
        state.user = action.payload;
        state.isLogin = true;
      })
      .addCase(profile.rejected, (state) => {
        state.status = "idle";
        state.user = null;
        state.isLogin = false;
      })
      .addCase(refresh.fulfilled, (state) => {
        state.status = "authenticated";
        state.isLogin = true;
      })
      .addCase(refresh.rejected, (state) => {
        state.status = "idle";
        state.user = null;
        state.isLogin = false;
      })
      /* logout */
      .addCase(logout.fulfilled, (state) => {
        state.status = "idle";
        state.user = null;
        state.isLogin = false;
      });
  },
});

export default userAuth.reducer;
export const {setToken} = userAuth.actions;