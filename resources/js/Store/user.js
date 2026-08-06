import { defineStore } from 'pinia'
import axios from 'axios'
export const useUserStore = defineStore('user', {
  state: () =>
({
    user: null,
    profile: null,
    loading: false,
    token:  null,
    error: null,
    isAuthenticated: false,
  }),

  getters: {
    isAdmin: (state) => {
        return state.user?.roles?.some(role => role.name === 'ADMIN') ||
               state.profile?.roles?.some(role => role.name === 'ADMIN');
      }
  },
  actions: {
    async fetchProfile(userId) {
      try {
        this.loading = true
        const response = await axios.get(`/api/profile/${userId}`, {
          withCredentials: true,
          headers: 
          {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${this.token}`
          }
        })
        this.profile = response.data
        this.isAuthenticated = true;
      }
      catch (err)
      {
        this.error = err.response?.data?.message || 'Ошибка загрузки профиля'
      } finally {
        this.loading = false
      }
    },


    async login(credentials) {
        try {
          this.loading = true;
          const response = await axios.post('/api/login', credentials, {
            withCredentials: true
          });

          this.user = response.data.user;
          this.token = response.data.token;
          this.isAuthenticated = true;


          if (this.user) {
            await this.fetchProfile(this.user.id);
          }
          return true;
        } catch (error) {
          this.clearUser();
          throw error;
        } finally {
          this.loading = false;
        }
      },
    async register(userData) {
      try {
        this.loading = true
        const response = await axios.post('/api/register', userData, {
          withCredentials: true
        })
        this.user = response.data.user
        this.isAuthenticated = true;
        await this.fetchProfile(this.user.id)
      } catch (error) {
        this.error = error.response?.data?.message || 'Ошибка регистрации'
        throw error
      } finally {
        this.loading = false
      }
    },

    async logout() {
        try {
          this.loading = true;
          await axios.post('/api/logout', {}, {
            withCredentials: true,
            headers: {
              'Authorization': `Bearer ${this.token}`
            }
          });
          this.clearUser();
          return true;
        } catch (err) {
          this.error = 'Ошибка при выходе из системы';
          console.error('Logout error:', err);
          return false;
        } finally {
          this.loading = false;
        }
      },

    setUser(user) {
      this.user = user
      if (user) this.fetchProfile(user.id)
    },

    clearUser() {
        this.user = null;
        this.profile = null;
        this.isAuthenticated = false;
        this.error = null;
      }
  }
})