import axios, { AxiosInstance } from 'axios';
import store from './store';

const axiosClient: AxiosInstance = axios.create({
    baseURL: 'http://127.0.0.1:8000/api/', 
    timeout: 5000, 
    headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' +  '',
    },
});

axiosClient.interceptors.request.use(config => {
    config.headers.Authorization = 'Bearer ' + store.state.user.token;
    return config
})

export default axiosClient;