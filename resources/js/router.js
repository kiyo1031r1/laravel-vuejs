import Vue from 'vue'
import Router from 'vue-router'
import store from './store'
import TaskListComponent from './components/TaskListComponent'
import TaskCreateComponent from './components/TaskCreateComponent'
import TaskShowComponent from './components/TaskShowComponent'
import TaskEditComponent from './components/TaskEditComponent'
import LoginComponent from './components/auth/LoginComponent'
import RegisterComponent from './components/auth/RegisterComponent'
import ForgotPasswordComponent from './components/auth/ForgotPasswordComponent'
import ResetPasswordComponent from './components/auth/ResetPasswordComponent'

Vue.use(Router);

const router = new Router({
    mode: 'history',
    routes: [
        {
            path:'/tasks',
            name: 'task.list',
            component: TaskListComponent
        },
        {
            path:'/tasks/create',
            name: 'task.create',
            component: TaskCreateComponent,
            meta: {
                authOnly : true
            }
        },
        {
            path:'/tasks/:taskId',
            name: 'task.show',
            component: TaskShowComponent,
            props: true
        },
        {
            path:'/tasks/:taskId/edit',
            name: 'task.edit',
            component: TaskEditComponent,
            props: true,
            meta: {
                authOnly : true
            }
        },
        {
            path:'/login',
            name: 'login',
            component: LoginComponent,
            meta: {
                guestOnly: true
            }
        },
        {
            path:'/register',
            name: 'register',
            component: RegisterComponent,
            meta: {
                guestOnly: true
            }
        },
        {
            path:'/forgot_password',
            name: 'forgot_password',
            component: ForgotPasswordComponent
        },
        {
            path:'/reset_password',
            name: 'reset_password',
            component: ResetPasswordComponent
        },
    ]
});

function isAuthenticated(){
    return localStorage.getItem('auth');
}

router.beforeEach((to, from, next) => {
    if(to.matched.some(record => record.meta.authOnly)){
        if(!isAuthenticated()) {
            next({name: 'login'});
        }
        else{
            next();
        }
    }
    else if(to.matched.some(record => record.meta.guestOnly)){
        if(isAuthenticated()) {
            next({name: 'tasks'});
        }
        else{
            next();
        }
    }
    else{
        next();
    }
});


export default router;