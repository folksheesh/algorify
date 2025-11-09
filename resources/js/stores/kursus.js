import { defineStore } from 'pinia';
import axios from '../axios';

export const useKursusStore = defineStore('kursus', {
    state: () => ({
        kursus: [],
        currentKursus: null,
        loading: false,
        error: null,
        filters: {
            search: '',
            kategori: ''
        },
        pagination: {
            current_page: 1,
            last_page: 1,
            per_page: 9,
            total: 0
        }
    }),

    getters: {
        getKursus: (state) => state.kursus,
        getCurrentKursus: (state) => state.currentKursus,
    },

    actions: {
        async fetchKursus(page = 1) {
            this.loading = true;
            this.error = null;
            
            try {
                const params = {
                    page,
                    search: this.filters.search,
                    kategori: this.filters.kategori
                };
                
                const response = await axios.get('/kursus', { params });
                
                this.kursus = response.data.data;
                this.pagination = {
                    current_page: response.data.current_page,
                    last_page: response.data.last_page,
                    per_page: response.data.per_page,
                    total: response.data.total
                };
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch kursus';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async fetchKursusById(id) {
            this.loading = true;
            this.error = null;
            
            try {
                const response = await axios.get(`/kursus/${id}`);
                this.currentKursus = response.data;
                return response.data;
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch kursus';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        setFilter(key, value) {
            this.filters[key] = value;
        },

        clearFilters() {
            this.filters = {
                search: '',
                kategori: ''
            };
        }
    }
});
