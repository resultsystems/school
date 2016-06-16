export function doLogin({dispatch},user){

  //a fake login
  setInterval(function(){ 
    let token = "loremipsum";
    dispatch("LOGIN",user,token);
  }, 2000);
  


}