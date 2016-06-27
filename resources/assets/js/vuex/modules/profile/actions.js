
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

