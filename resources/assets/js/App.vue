<template>
  <div id="app" >
    <Login v-if="!isLogged"></Login>
    <Admin v-else></Admin>
  </div>
</template>

<script>
import store from './vuex/store'
import {isLogged,getLogin} from './vuex/modules/profile/getters'
import Login from './components/login.vue'
import Admin from './components/admin.vue'

export default {
  components: {
    Login,Admin
  },
  vuex: {
      getters: {
        isLogged, getLogin
      }
  },
  created() {
    if (this.isLogged){
      Materialize.toast(`Hello, ${this.getLogin.user.username}!` , 1000)
    }
  },
  store
}
</script>
