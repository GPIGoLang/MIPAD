import Vue from 'vue'

import VueRouter from 'vue-router'

import Login from '../views/auth/Login'
import Register from '../views/auth/Register.vue'
import Verify from '../views/auth/Verify.vue'
import Dashboard from '../views/user/Dashboard.vue'
import Nominate from '../views/user/Nominate.vue'
import RequestCodeResend from '../views/auth/RequestCodeResend.vue'
import LiveScore from '../views/user/assessment/LiveScore.vue'

import App from '../App.vue'

Vue.use(VueRouter)

const router = new VueRouter({

    routes: [
        { path: '/', component: Login },
        { path: '/login', component: Login },
        { path: '/register', component: Register },
        { path: '/dashboard', component: Dashboard },
        { path: '/verify', component: Verify },
        { path: '/request-code-resend', component: RequestCodeResend },
        { path: '/nominate', component: Nominate },
        { path: '/assessment/result-display', component: LiveScore }
    ]
});

export default router