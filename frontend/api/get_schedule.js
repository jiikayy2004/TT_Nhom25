import { API_URL } from '..config.js';
import { getAuthHeaders } from './auth.js';

export async function getSchedules() {
    try {
        const response = await fetch(`${API_URL}/class-schedules`, {
            method: 'GET',
            headers: getAuthHeaders()
        });

        if (!response.ok) {
            throw new Error('Failed to fetch schedules');
        }

        const data = await response.json();
        return data.data;
    } catch (error) {
        console.error('Get schedules error:', error);
        throw error;
    }
}