/**
 * Authentication Helper Functions
 */

const TOKEN_KEY = 'auth_token';
const USER_KEY = 'auth_user';

/**
 * Generate random token (for testing, real app uses backend token)
 */
function generateToken(userId) {
    const payload = {
        iss: 'http://127.0.0.1:8000',
        sub: userId,
        iat: Math.floor(Date.now() / 1000),
        exp: Math.floor(Date.now() / 1000) + (24 * 60 * 60),
    };
    return btoa(JSON.stringify(payload));
}

/**
 * Parse token to get user ID
 */
function parseToken(token) {
    try {
        if (!token) return null;
        const payload = JSON.parse(atob(token));
        return payload.sub || null;
    } catch (e) {
        return null;
    }
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    const token = localStorage.getItem(TOKEN_KEY);
    if (!token) return false;
    
    const payload = parseToken(token);
    if (!payload) return false;
    
    return true;
}

/**
 * Get stored token
 */
function getToken() {
    return localStorage.getItem(TOKEN_KEY);
}

/**
 * Get stored user data
 */
function getCurrentUser() {
    const userJson = localStorage.getItem(USER_KEY);
    if (!userJson) return null;
    try {
        return JSON.parse(userJson);
    } catch (e) {
        return null;
    }
}

/**
 * Store auth data
 */
function saveAuthData(user, token) {
    localStorage.setItem(TOKEN_KEY, token);
    localStorage.setItem(USER_KEY, JSON.stringify(user));
}

/**
 * Clear auth data
 */
function logout() {
    localStorage.removeItem(TOKEN_KEY);
    localStorage.removeItem(USER_KEY);
    window.location.href = './login.html';
}

/**
 * Check auth status and redirect if needed
 */
function checkAuthStatus(requireLogin = true) {
    const isLogged = isLoggedIn();
    
    if (requireLogin && !isLogged) {
        window.location.href = './login.html?redirect=' + encodeURIComponent(window.location.href);
        return false;
    }
    
    if (!requireLogin && isLogged) {
        // Already logged in, redirect to dashboard
        // window.location.href = './dashboard.html';
        return true;
    }
    
    return isLogged;
}

/**
 * Set Authorization header
 */
function getAuthHeaders() {
    const token = getToken();
    if (!token) {
        return {
            'Content-Type': 'application/json'
        };
    }
    return {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token
    };
}

// Export to global
window.Auth = {
    isLoggedIn,
    getToken,
    getCurrentUser,
    saveAuthData,
    logout,
    checkAuthStatus,
    getAuthHeaders,
    parseToken
};
