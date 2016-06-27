const state = {
  name: "",
  email:""
}
const mutations = {
  LOADPROFILE (state, profile) {
    state.name = profile.name;
    state.email = profile.email;
  }
}
export default {state,mutations}

