
import {URI} from '../../../config.js'
import {message} from '../../../functions.js'

export function saveProfile({dispatch}, profile, handler) {
   this.$http.post(`${URI}/??/??`,profile).then((response) => {
           dispatch("SET_PROFILE", profile);
           handler(true);
      }, (response) => {
        //melhorar isso ;)
        message(`Error ${JSON.stringify(response.data)}`);
        handler(false);
      });
}

export function loadProfile({dispatch}, profile) {
   dispatch("SET_PROFILE", profile);
}

