
import {URI} from '../../../config.js'
import {message} from '../../../functions.js'

export function doLogin({dispatch}, user, handler) {
    this.$http.post(`${URI}/auth/login`,user).then((response) => {
           let token = "loremipsum";
           dispatch("LOGIN", user, token);
           handler(true);
      }, (response) => {
        console.log(response);
        //melhorar isso ;)
        message(`Error ${JSON.stringify(response.data)}`);
        handler(false);
      });
}

export function doLogout({dispatch}) {
  //a fake logout
  dispatch("LOGOUT");
}
