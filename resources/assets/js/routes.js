import Home from './components/home.vue'
import About from './components/about.vue'
import Profile from './components/profile.vue'

const Routes = {
    '/home': {
        component: Home
    },
    '/about':{
        component: About
    },
    '/profile':{
      component: Profile
    }
}

export default Routes;
