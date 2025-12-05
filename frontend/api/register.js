import { API_URL } from '../assets/js/config.js';

export async function registerUser(formData) {
    try {
        const response = await fetch(`${API_URL}/auth/register`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                name: formData.name,
                email: formData.email,
                phone: formData.phone,
                username: formData.username,
                password: formData.password,
                password_confirmation: formData.passwordConfirm
            })
        });

        const data = await response.json();

        if (response.ok) {
            // Lưu token
            localStorage.setItem('auth_token', data.token);
            localStorage.setItem('user', JSON.stringify(data.user));
            
            // Redirect to dashboard
            window.location.href = '/index.html';
            return data;
        } else {
            throw new Error(data.message || 'Registration failed');
        }
    } catch (error) {
        console.error('Register error:', error);
        throw error;
    }
}