<template>
  <div id="app">
    <Login v-if="!isLogged"></Login>
    <div v-else>Welcome</div>
  </div>
</template>

<script>
  
import store from './vuex/store'
import {isLogged,getLogin} from './vuex/modules/login/getters'
import Login from './components/login.vue'

export default {
  components: {
    Login
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