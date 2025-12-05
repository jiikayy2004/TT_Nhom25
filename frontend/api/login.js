import { API_URL } from '..config.js';

export async function loginUser(email, password) {
    try {
        const response = await fetch(`${API_URL}/auth/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                email: email,
                password: password
            })
        });

        const data = await response.json();

        if (response.ok) {
            // Lưu token & user info
            localStorage.setItem('auth_token', data.token);
            localStorage.setItem('user', JSON.stringify(data.user));
            
            // Redirect
            if (data.user.role === 'admin') {
                window.location.href = '/admin/dashboard.html';
            } else if (data.user.role === 'trainer') {
                window.location.href = '/trainer/dashboard.html';
            } else {
                window.location.href = '/index.html';
            }
            
            return data;
        } else {
            throw new Error(data.message || 'Login failed');
        }
    } catch (error) {
        console.error('Login error:', error);
        throw error;
    }
}