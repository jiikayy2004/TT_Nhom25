/**
 * API Authentication Functions
 * Handles register, login, profile requests
 */

/**
 * Register new account
 */
async function register(formData) {
    try {
        const response = await fetch(window.API_URL + '/auth/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        // Parse JSON only when appropriate; otherwise read text for friendly messages
        let data = {};
        const contentType = response.headers.get('content-type') || '';
        if (contentType.includes('application/json')) {
            data = await response.json();
        } else {
            const text = await response.text();
            data = { message: text };
        }

        if (!response.ok) {
            throw {
                status: response.status,
                message: data.message || 'Registration failed',
                errors: data.errors || {}
            };
        }

        // Do NOT auto-save auth data on registration. Redirecting to login gives clearer UX.
        return {
            success: true,
            message: data.message || 'Registration successful',
            user: data.user,
            token: data.token
        };
    } catch (error) {
        console.error('Register error:', error);
        return {
            success: false,
            message: error.message || 'Registration failed',
            errors: error.errors || {}
        };
    }
}

/**
 * Login user
 */
async function login(email, password) {
    try {
        const response = await fetch(window.API_URL + '/auth/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });

        const data = await response.json();

        if (!response.ok) {
            throw {
                status: response.status,
                message: data.message || 'Login failed'
            };
        }

        // Save auth data
        if (data.token && data.user) {
            window.Auth.saveAuthData(data.user, data.token);
        }

        return {
            success: true,
            message: data.message || 'Login successful',
            user: data.user,
            token: data.token
        };
    } catch (error) {
        console.error('Login error:', error);
        return {
            success: false,
            message: error.message || 'Login failed'
        };
    }
}

/**
 * Get current user profile
 */
async function getProfile() {
    try {
        const response = await fetch(window.API_URL + '/auth/profile', {
            method: 'GET',
            headers: window.Auth.getAuthHeaders()
        });

        const data = await response.json();

        if (!response.ok) {
            throw {
                status: response.status,
                message: data.message || 'Failed to fetch profile'
            };
        }

        return {
            success: true,
            user: data.user
        };
    } catch (error) {
        console.error('Get profile error:', error);
        return {
            success: false,
            message: error.message || 'Failed to fetch profile'
        };
    }
}

/**
 * Check if email already exists
 */
async function checkEmailExists(email) {
    try {
        const response = await fetch(window.API_URL + '/auth/check-email?email=' + encodeURIComponent(email), {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();
        return data.exists || false;
    } catch (error) {
        console.error('Check email error:', error);
        return false;
    }
}

/**
 * Check if username already exists
 */
async function checkUsernameExists(username) {
    try {
        const response = await fetch(window.API_URL + '/auth/check-username?username=' + encodeURIComponent(username), {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();
        return data.exists || false;
    } catch (error) {
        console.error('Check username error:', error);
        return false;
    }
}

// Export to global
window.AuthAPI = {
    register,
    login,
    getProfile,
    checkEmailExists,
    checkUsernameExists
};