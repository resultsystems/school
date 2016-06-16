<template>
<nav>
    <div class="nav-wrapper">
        <a href="#" class="brand-logo"> &nbsp; Olá {{getLogin.user.username}}</a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
        <li><a href="#">Link1</a></li>
        <li><a href="#">Link2</a></li>
        <li><a href="#">Link3</a></li>
        <li class="teal lighten-1"><a @click="tryLogout">Logout</a></li>
        </ul>
    </div>
</nav>


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