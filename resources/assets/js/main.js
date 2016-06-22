import Vue from 'vue'
import App from './App.vue'

import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
import Routes from './routes'

import {URI} from './config.js'

Vue.use(VueRouter)
Vue.use(VueResource)

const router = new VueRouter({
    linkActiveClass: 'active',
})

router.redirect({
    '/': '/home'
})

router.map(Routes)
router.start(App, 'App')

