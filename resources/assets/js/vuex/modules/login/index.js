
const LOGIN_KEY = 'login-school'

const state = {
  login: JSON.parse(localStorage.getItem(LOGIN_KEY) || '{}')
}

const mutations = {
  LOGIN (state, user, token) {
    state.login = {user,token}
    localStorage.setItem(LOGIN_KEY,JSON.stringify(state.login));
  }
}

export default {state,mutations,LOGIN_KEY}