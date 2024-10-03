import authapi from './authapi.js'

const state = () => ({
	jwt: null,
	user: null,
    role: null
})

// getters
const getters = {
  jwt(state) {
    return state.jwt
  },
  user(state) {
	return state.user
  },
    role(state) {
      return state.role
    }
}

// actions
const actions = {
    loginIn: function ({commit}, userinfo) {
        authapi.loginIn(userinfo)
        commit('setAuth', userinfo)

    },
    loginOut: function ({commit}) {
        authapi.loginOut()
        commit('setOut')
    },
    getAuth: function ({commit}) {
        authapi.getAuth(
            (res) => commit('setAuth', res),
            () => commit('setOut')
        )
    },
    setRole({commit}, res) {
        commit('setRole', res)
    }
}

// mutations
const mutations = {
    setAuth: function (state, res) {
        state.jwt = res.jwt
        state.user = res.user
    },
    setOut: function (state) {
        state.jwt = null
        state.user = null
    },
    setRole(state, res) {
        state.role = res
    }
}

export default {
  namespaced: false,
  state,
  getters,
  actions,
  mutations,
}
