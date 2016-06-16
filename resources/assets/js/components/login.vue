<template>

    <div class="container">

        <div class="row">
            <h1>Login</h1>
        </div>

        <div class="row">
            {{getLogin|json}}
        </div>

        <div class="row">
            <div class="input-field col s6">
                <input id="username" type="text" v-model="username" />
                <label for="username">First Name</label>
            </div>
            <div class="input-field col s6">
                <input id="password" type="text" v-model="password" />
                <label for="password">Password</label>
            </div>
            <button @click="tryLogin" class="btn">Login</button>
        </div>

    </div>

</template>
<script>
import {doLogin} from '../vuex/modules/login/actions'
import {getLogin} from '../vuex/modules/login/getters'

export default{
    data () { 
        return {
            username:'', 
            password:''
        }
    },
    vuex: {
        actions: {
            doLogin
        },
        getters: {
            getLogin
        }
    },
    methods:    {
        tryLogin(){
            this.doLogin({'username':this.username,'password':this.password},'bar');
        }
    }
}
</script>