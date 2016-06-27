<template>
  <nav>
    <div class="nav-wrapper">
     <a href="#" data-activates="slide-out" class="button-collapse top-nav full hide-on-large-only"><i class="material-icons">menu</i></a></i></a>
      <a href="#" class="brand-logo"> &nbsp; Olá {{getLogin.user.username}}</a>
      <ul id="nav-mobile" class="right hide-on-med-and-down">
        <li v-link-active><a v-link="{path:'/home'}">Início</a></li>
        <li v-link-active><a v-link="{path:'/profile'}">Perfil</a></li>
        <li v-link-active><a v-link="{path:'/about'}">Sobre</a></li>
        <li class="teal lighten-1"><a @click="tryLogout">Logout</a></li>
      </ul>
      <ul id="slide-out" class="side-nav">
        <ul class="collapsible collapsible-accordion">

          <li class="bold"><a v-link="{path:'/home'}" class="waves-effect waves-teal">Início</a></li>

          <li class="bold"><a v-link="{path:'/profile'}" class="waves-effect waves-teal">Perfil</a></li>

          <li class="bold"><a v-link="{path:'/about'}" class="waves-effect waves-teal">Sobre</a></li>

          <li class="bold"><a @click="tryLogout" class="waves-effect waves-teal">Logout</a></li>

            <li class="bold"><a class="collapsible-header  waves-effect waves-teal">Alunos</a>
              <div class="collapsible-body">
                <ul>
                  <li><a href="color.html">Color</a></li>
                  <li><a href="grid.html">Grid</a></li>
                </ul>
              </div>
            </li>
            <li class="bold"><a class="collapsible-header  waves-effect waves-teal">Funcionarios</a>
              <div class="collapsible-body">
                <ul>
                  <li><a href="badges.html">Badges</a></li>
                  <li><a href="buttons.html">Buttons</a></li>
                </ul>
              </div>
            </li>
            <li class="bold"><a class="collapsible-header active waves-effect waves-teal">Horários</a>
              <div class="collapsible-body">
                <ul>
                  <li><a href="collapsible.html">Collapsible</a></li>
                  <li><a href="dialogs.html">Dialogs</a></li>
                </ul>
              </div>
            </li>
             <li class="bold"><a class="collapsible-header active waves-effect waves-teal">Lições</a>
              <div class="collapsible-body">
                <ul>
                  <li><a href="collapsible.html">Collapsible</a></li>
                  <li><a href="dialogs.html">Dialogs</a></li>
                </ul>
              </div>
            </li>
             <li class="bold"><a class="collapsible-header active waves-effect waves-teal">Materias</a>
              <div class="collapsible-body">
                <ul>
                  <li><a href="collapsible.html">Collapsible</a></li>
                  <li><a href="dialogs.html">Dialogs</a></li>
                </ul>
              </div>
            </li>
             <li class="bold"><a class="collapsible-header active waves-effect waves-teal">Pagamentos</a>
              <div class="collapsible-body">
                <ul>
                  <li><a href="collapsible.html">Collapsible</a></li>
                  <li><a href="dialogs.html">Dialogs</a></li>
                </ul>
              </div>
            </li>
             <li class="bold"><a class="collapsible-header active waves-effect waves-teal">Professores</a>
              <div class="collapsible-body">
                <ul>
                  <li><a href="collapsible.html">Collapsible</a></li>
                  <li><a href="dialogs.html">Dialogs</a></li>
                </ul>
              </div>
            </li>
             <li class="bold"><a class="collapsible-header active waves-effect waves-teal">Turmas</a>
              <div class="collapsible-body">
                <ul>
                  <li><a href="collapsible.html">Collapsible</a></li>
                  <li><a href="dialogs.html">Dialogs</a></li>
                </ul>
              </div>
            </li>
          </ul>
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

import {getLogin} from '../vuex/modules/profile/getters'
import {doLogout} from '../vuex/modules/profile/actions'

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
    },
    ready(){
      $(".button-collapse").sideNav();
      $('.collapsible').collapsible({accordion : true });
    }
}
</script>
