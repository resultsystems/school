
const LOGIN_KEY = 'login-school'

let emptyLogin = '{"user":{"username":null,"password":null},"token":null}'

const state = {
  login: JSON.parse(localStorage.getItem(LOGIN_KEY) || emptyLogin)
}

const mutations = {
  LOGIN (state, user, token) {
    state.login = {user,token}
    localStorage.setItem(LOGIN_KEY,JSON.stringify(state.login));
  }
}

export default {state,mutations,LOGIN_KEY}