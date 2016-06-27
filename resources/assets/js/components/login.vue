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

              <div class="row">

                <div class="col s12 m8">
                  <div class="input-field">
                    <input id="username" type="text" v-model="username" :value="getLogin.user.username">
                    <label for="username">Usuário</label>
                  </div>
                </div>
                <div class="col s12 m4">
                  <label>Tipo</label>
                  <select v-model="ownertype">
                          <option value="Employee">Funcionário</option>
                          <option value="Teacher">Professor</option>
                          <option value="Student">Aluno</option>
                   </select>
                </div>
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

        <div class="center-align">
          <a @click.stop="logginAsEmployee" class="btn">Logar como Funcionário</a>
          <a @click="logginAsTeacher" class="btn">Logar como Professor</a>
          <a @click="logginAsStudent" class="btn">Logar como Aluno</a>
        </div>

      </div>
    </div>


</template>
<script>
import {doLogin} from '../vuex/modules/profile/actions'
import {getLogin,isLogged} from '../vuex/modules/profile/getters'
import message from '../functions'

export default{
    data () {
        return {
            username:'',
            password:'',
            ownertype:'',
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
    ready()
    {
       /* SELECT HOOK */
       /* https://github.com/Dogfalo/materialize/issues/2838 */
       $("select").val(this.ownertype);
       var suspend = false;
       $("select").material_select();
       $('select').on('change', function() {
         if (!suspend) {
            suspend = true;
          var event = new CustomEvent('change', {
            detail: 'change',
            bubbles: true
          });
          $(this).get(0).dispatchEvent(event);
        }else{suspend=false}
       });
       /* END */
    },
    methods:    {
        tryLogin(){
            this.loading = true
            let user = {'username':this.username,
                                      'password':this.password,
                                      'type':this.ownertype
                                  }
            this.doLogin(user,(r)=>{
                this.$data.loading=false;
            })
        },
        logginAsEmployee(){
          this.username='funcionario'
          this.password='funcionario123'
          this.ownertype='Employee'
        },
        logginAsStudent(){
          this.username='aluno'
          this.password='aluno123'
          this.ownertype='Student'
        },
        logginAsTeacher(){
          this.username='professor'
          this.password='professor123'
          this.ownertype='Teacher'
        }
    }
}
</script>
