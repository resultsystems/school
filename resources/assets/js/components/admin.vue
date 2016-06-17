<template>
<nav>
    <div class="nav-wrapper">
        <a href="#" class="brand-logo"> &nbsp; Olá {{getLogin.user.username}}</a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
        <li  v-link-active><a v-link="{path:'/home'}">Início</a></li>
        <li  v-link-active><a v-link="{path:'/profile'}">Perfil</a></li>
        <li  v-link-active><a v-link="{path:'/about'}">Sobre</a></li>
        <li class="teal lighten-1"><a @click="tryLogout">Logout</a></li>
        </ul>
    </div>
</nav>
<br/>
    <div class="container">
     <router-view></router-view>
  </div>
</template>
<script>

import {getLogin} from '../vuex/modules/login/getters'
import {doLogout} from '../vuex/modules/login/actions'

export default {
    data(){
        return{

        }
    },
    vuex: {
        getters:{
            getLogin
        },
        actions:{
            doLogout
        }
    },
    methods: {
        tryLogout() {
            this.doLogout();
        }
    }
}
</script>
