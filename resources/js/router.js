import Vue from 'vue'
import Router from 'vue-router'
import store from './store'
// import TaskListComponent from './components/tasks/TaskListComponent'
// import TaskCreateComponent from './components/tasks/TaskCreateComponent'
// import TaskShowComponent from './components/tasks/TaskShowComponent'
// import TaskEditComponent from './components/tasks/TaskEditComponent'
import LoginComponent from './components/auth/LoginComponent'
import RegisterComponent from './components/auth/RegisterComponent'
import ForgotPasswordComponent from './components/auth/ForgotPasswordComponent'
import ResetPasswordComponent from './components/auth/ResetPasswordComponent'
import SendMailComponent from './components/auth/SendMailComponent'
import ChangedPasswordComponent from './components/auth/ChangedPasswordComponent'
import AdminLoginComponent from './components/auth/AdminLoginComponent'
import AdminHomeComponent from './components/admin/AdminHomeComponent'
import UserHomeComponent from './components/users/UserHomeComponent'

Vue.use(Router);

const router = new Router({
    mode: 'history',
    routes: [
        // {
        //     path:'/tasks',
        //     name: 'task.list',
        //     component: TaskListComponent
        // },
        // {
        //     path:'/tasks/create',
        //     name: 'task.create',
        //     component: TaskCreateComponent,
        //     meta: {
        //         authOnly : true
        //     }
        // },
        // {
        //     path:'/tasks/:taskId',
        //     name: 'task.show',
        //     component: TaskShowComponent,
        //     props: true
        // },
        // {
        //     path:'/tasks/:taskId/edit',
        //     name: 'task.edit',
        //     component: TaskEditComponent,
        //     props: true,
        //     meta: {
        //         authOnly : true
        //     }
        // },
        {
            path:'/',
            name: 'home',
            component: UserHomeComponent
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
        {
            path:'/send_mail',
            name: 'send_mail',
            component: SendMailComponent
        },
        {
            path:'/changed_password',
            name: 'changed_password',
            component: ChangedPasswordComponent
        },
        {
            path:'/admin/login',
            name: 'admin_login',
            component: AdminLoginComponent,
            meta: {
               admin_guestOnly: true
            }
        },
        {
            path:'/admin',
            name: 'admin',
            component: AdminHomeComponent,
            meta: {
                admin_authOnly: true,
             }
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
            next({name: 'home'});
        }
        else{
            next();
        }
    }
    else{
        next();
    }
});

router.beforeEach((to, from, next) => {
    if(to.matched.some(record => record.meta.admin_authOnly)){
        axios.get('/api/user')
        .then(res => {
            let user = res.data;
            if(user.role_id != 2){
                next({name: 'admin_login'});
            }
            else{
                next();
            }
        })
        .catch(() => {
            next({name: 'admin_login'});
        });
    }
    else if(to.matched.some(record => record.meta.admin_guestOnly)){
        axios.get('/api/user')
        .then(res => {
            let user = res.data;
            if(user.role_id === 2){
                next({name: 'admin'});
            }
            else{
                next();
            }
        })
        .catch(() => {
            next();
        });
    }
    else{
        next();
    }
});

export default router;