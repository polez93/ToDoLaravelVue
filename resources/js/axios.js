import axios from 'axios';

// Configurar la URL base de la API
const apiClient = axios.create({
    baseURL: 'http://localhost:8000/api', // Cambia esto por tu URL
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});


export default apiClient;