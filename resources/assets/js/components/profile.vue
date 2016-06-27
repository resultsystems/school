<template>

      <div class="card-panel grey lighten-3 z-depth-2">
        <div class="row valign-wrapper">
          <div class="col s2">
            <i class="medium material-icons">perm_identity</i>
          </div>
          <div class="col s10">
            <h5>Perfil</h5>
          </div>
        </div>

        <div class="row valign-wrapper">
          <div class="col s10">
            <dl>

              <dt>Nome</dt>
              <dd>{{getProfile.name}}</dd>
              <dt>Email</dt>
              <dd>{{getProfile.email}}</dd>
              <dt>Função</dt>
              <dd>Professor</dd>
            </dl>
          </div>
        </div>

        <div class="card-action">
              <a class="btn" href="#">Editar</a>
            </div>

      </div>


</template>
<script>
import {loadProfile} from '../vuex/modules/profile/actions'
import {getProfile} from '../vuex/modules/profile/getters'

export default{
  vuex:{
    actions:{
      loadProfile
    },
    getters:{
      getProfile
    }
  },
  data(){
    return{
      profile: getProfile
    }
  },
  ready(){
    this.loadProfile();
  }
}
</script>
