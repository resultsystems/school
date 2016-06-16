export function doLogin({dispatch},user){

  //a fake login
  let token = "loremipsum";

  dispatch("LOGIN",user,token);
}