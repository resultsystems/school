const LOGIN_KEY = 'login-school'

let emptyLogin = '{"user":{"username":null,"password":null},"token":null}'

const state = {
  name: "",
  email:"",
  login: JSON.parse(localStorage.getItem(LOGIN_KEY) || emptyLogin)
}
const mutations = {
  LOADPROFILE (state, profile) {
    state.name = profile.name;
    state.email = profile.email;
  },
  LOGIN (state, user, token) {
    state.login = {user,token}
    localStorage.setItem(LOGIN_KEY,JSON.stringify(state.login));
  },
  LOGOUT (state){
    state.login = JSON.parse(emptyLogin);
    localStorage.setItem(LOGIN_KEY,JSON.stringify(state.login));
  }
}
export default {state,mutations,LOGIN_KEY}

