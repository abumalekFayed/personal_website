/* eslint-disable no-undef */
import Vuex from "vuex";
import Vue from "vue";
import pathify from "vuex-pathify";
import states from "./modules/states";

// Load Vuex
Vue.use(Vuex);

// Create store
export default new Vuex.Store({
    plugins: [pathify.plugin], // activate plugin

    state: {
        loading: false,
        allow_loading: true
    },
    modules: {
        states
    },
    mutations: {
        loading(state, payload) {
            state.loading = payload;
        },
        allow_loading(state, payload) {
            state.allow_loading = payload;
        }
    }
});
