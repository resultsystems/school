<template>
  <nav>
    <div class="nav-wrapper">
      <a href="#" class="brand-logo"> &nbsp; Olá {{getLogin.user.username}}</a>
      <ul id="nav-mobile" class="right hide-on-med-and-down">
        <li v-link-active><a v-link="{path:'/home'}">Início</a></li>
        <li v-link-active><a v-link="{path:'/profile'}">Perfil</a></li>
        <li v-link-active><a v-link="{path:'/about'}">Sobre</a></li>
        <li class="teal lighten-1"><a @click="tryLogout">Logout</a></li>
      </ul>
    </div>
  </nav>
  <br/>
  <div class="container">
    <div class="row">
      <div class="col l3 hide-on-med-and-down">
        <ul class="collapsible popout" data-collapsible="accordion">
          <li>
            <div class="collapsible-header grey lighten-3">Alunos</div>
            <div class="collapsible-body">
              <div class="collection">
                <a href="#!" class="collection-item">Listar</a>
                <a href="#!" class="collection-item">Cadastro</a>
              </div>
            </div>
          </li>
          <li>
            <div class="collapsible-header grey lighten-3">Funcionários</div>
            <div class="collapsible-body">
              <div class="collection">
                <a href="#!" class="collection-item">Listar</a>
                <a href="#!" class="collection-item">Cadastro</a>
              </div>
            </div>
          </li>
          <li>
            <div class="collapsible-header grey lighten-3">Horários</div>
            <div class="collapsible-body">
              <div class="collection">
                <a href="#!" class="collection-item">Listar</a>
                <a href="#!" class="collection-item">Cadastro</a>
              </div>
            </div>
          </li>
          <li>
            <div class="collapsible-header grey lighten-3">Lições</div>
            <div class="collapsible-body">
              <div class="collection">
                <a href="#!" class="collection-item">Listar</a>
                <a href="#!" class="collection-item">Cadastro</a>
              </div>
            </div>
          </li>
          <li>
            <div class="collapsible-header grey lighten-3">Matérias</div>
            <div class="collapsible-body">
              <div class="collection">
                <a href="#!" class="collection-item">Listar</a>
                <a href="#!" class="collection-item">Cadastro</a>
              </div>
            </div>
          </li>
          <li>
            <div class="collapsible-header grey lighten-3">Pagamentos</div>
            <div class="collapsible-body">
              <div class="collection">
                <a href="#!" class="collection-item">Listar</a>
                <a href="#!" class="collection-item">Cadastro</a>
              </div>
            </div>
          </li>
          <li>
            <div class="collapsible-header grey lighten-3">Professores</div>
            <div class="collapsible-body">
              <div class="collection">
                <a href="#!" class="collection-item">Listar</a>
                <a href="#!" class="collection-item">Cadastro</a>
              </div>
            </div>
          </li>
          <li>
            <div class="collapsible-header grey lighten-3">Turmas</div>
            <div class="collapsible-body">
              <div class="collection">
                <a href="#!" class="collection-item">Listar</a>
                <a href="#!" class="collection-item">Cadastro</a>
              </div>
            </div>
          </li>
        </ul>
      </div>
      <div class="col s12 l9">
        <router-view></router-view>
      </div>

    </div>

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
