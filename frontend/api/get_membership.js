import { API_URL } from '..config.js';
import { getAuthHeaders } from './auth.js';

export async function getMembership() {
    try {
        const response = await fetch(`${API_URL}/auth/profile`, {
            method: 'GET',
            headers: getAuthHeaders()
        });

        if (!response.ok) {
            throw new Error('Failed to fetch membership');
        }

        const data = await response.json();
        return data.user;
    } catch (error) {
        console.error('Get membership error:', error);
        throw error;
    }
}