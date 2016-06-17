<template>

<div class="container">
    <div class="row">
        <div class="col s12 m8 offset-m2">
            <form class="login-form">
                <div class="card">
                    <div class="card-image">
                        <img src="img/login.png">
                        <span class="card-title">
                        <h2>Login</h2>
                        <h6>Resultsystems / School</h6>
                    </span>
                    </div>
                    <div class="card-content">
                        <div class="input-field">
                            <input id="username" type="text" v-model="username" :value="getLogin.user.username">
                            <label for="username">Usuário</label>
                        </div>

                        <div class="row">
                            <div class="col s12 m8 l9">
                                <div class="input-field">
                                    <input id="password" type="password" v-model="password">
                                    <label for="password">Senha</label>
                                </div>
                            </div>
                            <div class="col s12 m4 l3">
                                <div class="input-field">
                                    <input type="checkbox" id="remember-me" @click="message('tyesye')" />
                                    <label for="remember-me">Lembrar</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-action blue-grey lighten-3">
                        <div class="center-align">
                            <a @click="tryLogin" class="btn blue-grey darken-1" v-bind:class="{ 'disabled': loading }"><i class="material-icons left">vpn_key</i>Login</a>
                        </div>
                          <div class="progress" v-show="loading">
                            <div class="indeterminate"></div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col s4">
                    <a href="#">Registrar</a>
                </div>
                <div class="col s8 right-align">
                    <a href="#" class="">Esqueci a senha</a>
                </div>
            </div>

    </div>
</div>


</template>
<script>
import {doLogin} from '../vuex/modules/login/actions'
import {getLogin,isLogged} from '../vuex/modules/login/getters'
import message from '../functions'

export default{
    data () {
        return {
            username:'',
            password:'',
            loading:false
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
            this.loading = true
            let user = {'username':this.username,'password':this.password}
            this.doLogin(user,(r)=>{
                this.$data.loading=false;
            })
        }
    }
}
</script>
