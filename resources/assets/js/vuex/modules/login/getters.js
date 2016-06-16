
export function getLogin(state){
    return state.login.login
}

export function isLogged(state){
    console.log(state.login.login.token!=null);
    return state.login.login.token!=null;
}