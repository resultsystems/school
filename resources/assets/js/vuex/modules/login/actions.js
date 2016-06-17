export function doLogin({dispatch},user){
  //a fake login
  setTimeout(function(){ 
    let token = "loremipsum";
    dispatch("LOGIN",user,token);
  }, 2000);
}

export function doLogout({dispatch}){
  //a fake logout
  dispatch("LOGOUT");
}
