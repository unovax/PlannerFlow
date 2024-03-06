// store.js
import Vuex from 'vuex';

export default new Vuex.Store({
  state: {
    sidebar: localStorage.getItem('sidebar') === 'true' || false as boolean,
  },
  mutations: {
    changeSidebar(state) {
        state.sidebar = !state.sidebar;
        localStorage.setItem('sidebar', state.sidebar.toString() );
    }
  },
  actions: {
    toggleSidebar({ commit }) {
      commit('changeSidebar');
    }
  },
  getters: {
    // Tus getters aquí
  }
});
