import axios from "axios";
import { userStore } from "./auth/userauth";

const api = axios.create({
    baseURL : import.meta.env.VITE_PUBLIC_BASEURL,
    timeout : 10000,
    withCredentials : true,
    withXSRFToken : true,

});

api.interceptors.request.use(
    (config) => {
        const token = userStore.get();
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

api.interceptors.response.use(
    (response) => response,
    (error) => {
        return Promise.reject(error);
    }
);

export default api;