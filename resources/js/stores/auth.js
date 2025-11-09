import { defineStore } from 'pinia';
import axios from '../axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('token') || null,
        loading: false,
        error: null
    }),

    getters: {
        isAuthenticated: (state) => !!state.token,
        getUser: (state) => state.user,
    },

    actions: {
        async login(credentials) {
            this.loading = true;
            this.error = null;
            
            try {
                const response = await axios.post('/login', credentials);
                
                this.token = response.data.access_token;
                this.user = response.data.user;
                
                localStorage.setItem('token', this.token);
                localStorage.setItem('user', JSON.stringify(this.user));
                
                return response.data;
            } catch (error) {
                this.error = error.response?.data?.message || 'Login failed';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async register(data) {
            this.loading = true;
            this.error = null;
            
            try {
                const response = await axios.post('/register', data);
                
                this.token = response.data.access_token;
                this.user = response.data.user;
                
                localStorage.setItem('token', this.token);
                localStorage.setItem('user', JSON.stringify(this.user));
                
                return response.data;
            } catch (error) {
                this.error = error.response?.data?.message || 'Registration failed';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async logout() {
            try {
                await axios.post('/logout');
            } catch (error) {
                console.error('Logout error:', error);
            } finally {
                this.token = null;
                this.user = null;
                localStorage.removeItem('token');
                localStorage.removeItem('user');
            }
        },

        async fetchUser() {
            if (!this.token) return;
            
            try {
                const response = await axios.get('/user');
                this.user = response.data;
                localStorage.setItem('user', JSON.stringify(this.user));
            } catch (error) {
                console.error('Fetch user error:', error);
                this.logout();
            }
        },

        initializeAuth() {
            const token = localStorage.getItem('token');
            const user = localStorage.getItem('user');
            
            if (token && user) {
                this.token = token;
                this.user = JSON.parse(user);
            }
        }
    }
});
