

import {URI} from '../../../config.js'
import {message} from '../../../functions.js'

export function doLogin({dispatch}, user, handler) {
    this.$http.post(`${URI}/auth/login`,user).then((response) => {
           let token = response.data.token;
           user.password="";
           dispatch("LOGIN", user, token);
           handler(true);
      }, (response) => {
        //melhorar isso ;)
        message(`Error ${JSON.stringify(response.data)}`);
        handler(false);
      });
}

export function doLogout({dispatch}) {
  dispatch("LOGOUT");
}

export function saveProfile({dispatch}, profile, handler) {
   this.$http.post(`${URI}/??/??`,profile).then((response) => {
           dispatch("SAVE_PROFILE", profile);
           handler(true);
      }, (response) => {
        //melhorar isso ;)
        message(`Error ${JSON.stringify(response.data)}`);
        handler(false);
      });
}

export function loadProfile({dispatch}) {

  let mockProfile = {
      name:"profileName",
      email:"profileEmail"
  }

   dispatch("LOADPROFILE", mockProfile);
}

