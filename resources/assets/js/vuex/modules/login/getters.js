
export function getLogin(state){
    return state.login.login
}

export function isLogged(state){
    return state.login.login.token!=null;
}

