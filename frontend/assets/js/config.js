// API Configuration
const API_BASE = 'http://127.0.0.1:8000';
const API_URL = API_BASE + '/api';

// Export cho global use
window.API_URL = API_URL;
window.API_BASE = API_BASE;

console.log('API Config:', { API_BASE, API_URL }); 