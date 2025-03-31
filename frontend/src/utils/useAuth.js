import { ref } from "vue";
import axios from "axios";
import { API_ENDPOINTS } from "@/config";
import { setAuthToken } from "@/utils/auth";

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
                localStorage.setItem("token", response.data.token);
                setAuthToken(response.data.token);
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
