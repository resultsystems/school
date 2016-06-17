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
  <div class="container ">
    <div class="row">
      <div class="col s2">
        <ul class="collapsible popout" data-collapsible="accordion">
          <li>
            <div class="collapsible-header grey lighten-3">First</div>
            <div class="collapsible-body">
              <div class="collection">
                <a href="#!" class="collection-item">Alvin</a>
                <a href="#!" class="collection-item">Alvin</a>
                <a href="#!" class="collection-item">Alvin</a>
                <a href="#!" class="collection-item">Alvin</a>
              </div>
            </div>
          </li>
          <li>
            <div class="collapsible-header grey lighten-3">Second</div>
            <div class="collapsible-body">
              <p>Lorem ipsum dolor sit amet.</p>
            </div>
          </li>
          <li>
            <div class="collapsible-header grey lighten-3">Third</div>
            <div class="collapsible-body">
              <p>Lorem ipsum dolor sit amet.</p>
            </div>
          </li>
        </ul>
      </div>
      <div class="col s10">
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
