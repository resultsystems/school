export function doLogin({dispatch},user,token){
  dispatch("LOGIN",user,token);
}