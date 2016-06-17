import Vue from 'vue'
import App from './App.vue'

import VueRouter from 'vue-router'
import Routes from './routes'

Vue.use(VueRouter)

const router = new VueRouter({
    linkActiveClass: 'active',
})

router.redirect({
    '/': '/admin'
})

router.map(Routes)
router.start(App, 'App')

