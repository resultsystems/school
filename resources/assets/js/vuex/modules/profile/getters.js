
export function getProfile(state){
    return state.profile
}

export function getLogin(state){
    return state.profile.login
}

export function isLogged(state){
    return state.profile.login.token!=null;
}

