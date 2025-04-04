import { ref } from "vue";
import axios from "axios";
import { API_ENDPOINTS } from "@/config";

export const currentUser = ref(null);

export const getAuthToken = () => localStorage.getItem("token");


export const setAuthToken = (token) => {
    if (token) {
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
        localStorage.setItem("token", token);
    } else {
        delete axios.defaults.headers.common["Authorization"];
        localStorage.removeItem("token");
        currentUser.value = null;
    }
};

export const fetchUser = async () => {
    try {
        const response = await axios.get(API_ENDPOINTS.auth + "/me");
        currentUser.value = response.data;
    } catch (error) {
        currentUser.value = null;
    }
};

// === USE AUTH HOOK ===
export function useAuth(router) {
    const isLogin = ref(true);
    const isLoading = ref(false);
    const error = ref(null);
    const success = ref(null);
    const formData = ref({
        name: "",
        email: "",
        password: "",
    });

    const toggleAuthMode = () => {
        isLogin.value = !isLogin.value;
        error.value = null;
        isLoading.value = false;
        formData.value = {
            name: "",
            email: "",
            password: "",
        };
    };

    const handleSubmit = async () => {
        try {
            error.value = null;
            isLoading.value = true;

            const endpoint = isLogin.value
                ? API_ENDPOINTS.auth + "/login"
                : API_ENDPOINTS.auth + "/register";

            const payload = isLogin.value
                ? {
                    email: formData.value.email,
                    password: formData.value.password,
                }
                : formData.value;

            const response = await axios.post(endpoint, payload);

            if (isLogin.value) {
                success.value = "Login successful";
                setAuthToken(response.data.token);
                await fetchUser(); // ⚡️ реактивно обновляет currentUser
                router.push("/profile");
            } else {
                success.value = "Register successful";
                isLogin.value = true;
            }
        } catch (err) {
            console.error(err);
            error.value =
                err?.response?.data?.error ||
                "An error occurred during authentication";
        } finally {
            isLoading.value = false;
        }
    };

    return {
        isLogin,
        isLoading,
        error,
        success,
        formData,
        toggleAuthMode,
        handleSubmit,
    };
}
