import axios from "axios";

import { make, Payload } from "vuex-pathify";
const state = {
    department_masters: [],

    roles: [],
    users: [],

    show_message: {},
    departments: [],

    print_title: "",
    print_type: "",
    settings: [{ key: "general", value: {} }],
    slider_images: []
};

const getters = {
    ...make.getters(state),
    _settings: state => key =>
        state.settings.find(s => s.key == key)
            ? state.settings.find(s => s.key == key).value
            : {},

    general_settings: state =>
        state.settings.find(s => s.key == "general").value
};

const actions = {
    async setDepartmentMasters({ commit }) {
        const response = await axios.get("department-master");
        commit("SET_DEPARTMENT_MASTERS", response.data);
    },

    async setSliderImages({ commit }) {
        const response = await axios.get("document?slider_images=true");
        commit("SET_SLIDER_IMAGES", response.data);
    },

    async setUsers({ commit }) {
        const response = await axios.get("user");
        commit("SET_USERS", response.data);
    },

    async setRoles({ commit }) {
        const response = await axios.get("role");
        commit("SET_ROLES", response.data);
    },

    async setDepartments({ commit }) {
        const response = await axios.get("department");
        commit("SET_DEPARTMENTS", response.data);
    }
};

const mutations = {
    ...make.mutations(state),

    SET_REMOVE: (state, data) =>
        (state[data._t] = state[data._t].filter(q => q.id !== data.id)),
    SET_ADD: (state, data) => state[data._t].unshift(data),
    SET_UPDATE: (state, data) => {
        let index = state[data._t].findIndex(q => q.id == data.id);
        if (index !== -1) state[data._t].splice(index, 1, data);
    },

    SET_SHOW_MESSAGE: (state, data) => {
        if (data == "delete")
            state.show_message = {
                text: "Delete was done",
                color: "success"
            };
        else if (data == "edit")
            state.show_message = {
                text: "Edit was done",
                color: "success"
            };
        else if (data == "add")
            state.show_message = {
                text: "Saving was done",
                color: "success"
            };
        else state.show_message = data;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
