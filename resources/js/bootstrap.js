import axios from 'axios';
window.axios = axios;

window.axios.defaults.baseURL = '/api/v1';
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;
window.axios.defaults.withXSRFToken = true;
