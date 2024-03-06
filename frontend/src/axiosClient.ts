import axios, { AxiosInstance } from 'axios';

const axiosClient: AxiosInstance = axios.create({
    baseURL: 'http://127.0.0.1:8000/api/', 
    timeout: 5000, 
    headers: {
        'Content-Type': 'application/json', // Set the desired content type
    },
});

export default axiosClient;