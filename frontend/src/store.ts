// store.js
import Vuex from 'vuex';

export default new Vuex.Store({
  state: {
    sidebar: localStorage.getItem('sidebar') === 'true' || false as boolean,
    user: JSON.parse(localStorage.getItem('user') || '{}') as any,
  },
  mutations: {
    changeSidebar(state) {
        state.sidebar = !state.sidebar;
        localStorage.setItem('sidebar', state.sidebar.toString() );
    },
    setUser(state, user) {
        state.user = user;
        localStorage.setItem('user', JSON.stringify(user));
    },
  },
  actions: {
    toggleSidebar({ commit }) {
      commit('changeSidebar');
    },
    loginUser({ commit }, user) {
      commit('setUser', user);
    },
  },
  getters: {
    // Tus getters aquí
  }
});
