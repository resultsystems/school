<template>
  <div id="app">
    <Login></Login>
  </div>
</template>

<script>
  
import store from './vuex/store'
import Login from './components/login.vue'

export default {
  components: {
    Login
  },
  created() {
    Materialize.toast('Hello World', 1000) 
  },
  store
}
</script>