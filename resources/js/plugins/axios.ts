import { default as axios, AxiosInstance } from "axios";
import { App } from "vue";

const backendHttpClient = axios.create({
    baseURL: import.meta.env.VITE_BACKEND_BASE_URL,
    timeout: 1000,
});

export const BackendHttpClientSymbol = Symbol('AxiosInstance');

export function useBackendHttpClientPlugin() {
    return {
        install(app: App) {
            app.provide(BackendHttpClientSymbol, backendHttpClient);
        },
    };
}
