import Vue from 'vue'
import Router from 'vue-router'

//auth
import LoginComponent from './components/auth/LoginComponent'
import RegisterComponent from './components/auth/RegisterComponent'
import ForgotPasswordComponent from './components/auth/ForgotPasswordComponent'
import ResetPasswordComponent from './components/auth/ResetPasswordComponent'
import SendMailComponent from './components/auth/SendMailComponent'
import ChangedPasswordComponent from './components/auth/ChangedPasswordComponent'
import SNSLoggedInComponent from './components/auth/SNSLoggedInComponent'

//admin
import AdminLoginComponent from './components/auth/AdminLoginComponent'
import AdminHomeComponent from './components/admin/AdminHomeComponent'
import UserManagementIndex from './components/admin/userManagement/Index'
import UserManagementEdit from './components/admin/userManagement/Edit'
import VideoManagementIndex from './components/admin/videoManagement/Index'
import VideoManagementCreate from './components/admin/videoManagement/Create'
import VideoManagementEdit from './components/admin/videoManagement/Edit'
import VideoManagementWatch from './components/admin/videoManagement/Watch'

//user
import UserHomeComponent from './components/users/UserHomeComponent'
import UserVideoIndex from './components/users/videos/Index'

Vue.use(Router);

const router = new Router({
    mode: 'history',
    routes: [
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
            component: ForgotPasswordComponent,
            meta: {
                guestOnly: true
            }
        },
        {
            path:'/reset_password',
            name: 'reset_password',
            component: ResetPasswordComponent,
            meta: {
                guestOnly: true
            }
        },
        {
            path:'/send_mail',
            name: 'send_mail',
            component: SendMailComponent,
            meta: {
                guestOnly: true
            }
        },
        {
            path:'/changed_password',
            name: 'changed_password',
            component: ChangedPasswordComponent
            //コンポーネントにナビゲーションガードあり
        },
        {
            path:'/sns_login',
            name: 'sns_login',
            component: SNSLoggedInComponent,
            //コンポーネントにナビゲーションガードあり
        },
        {
            path:'/video',
            name: 'video',
            component: UserVideoIndex,
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
        {
            path:'/admin/user_management',
            name: 'user_management',
            component: UserManagementIndex,
            meta: {
                admin_authOnly: true,
             }
        },
        {
            path:'/admin/user_management/:id',
            name: 'user_management_edit',
            component: UserManagementEdit,
            props: true,
            meta: {
                admin_authOnly: true,
             }
        },
        {
            path:'/admin/video_management',
            name: 'video_management',
            component: VideoManagementIndex,
            meta: {
                admin_authOnly: true,
             }
        },
        {
            path:'/admin/video_management/create',
            name: 'video_management_create',
            component: VideoManagementCreate,
            meta: {
                admin_authOnly: true,
             }
        },
        {
            path:'/admin/video_management/:id',
            name: 'video_management_edit',
            component: VideoManagementEdit,
            props: true,
            meta: {
                admin_authOnly: true,
             }
        },
        {
            path:'/admin/video_management/watch/:id',
            name: 'video_management_watch',
            component: VideoManagementWatch,
            props: true,
            meta: {
                admin_authOnly: true,
             }
        }
    ]
});

//ユーザーページの認証ガード
router.beforeEach((to, from, next) => {
    if(to.matched.some(record => record.meta.authOnly)){
        axios.get('/api/user')
        .then(() => {
            next();
        })
        .catch(() => {
            next({name: 'login'});
        });
    }
    else if(to.matched.some(record => record.meta.guestOnly)){
        axios.get('/api/user')
        .then(() => {
            next({name: 'home'});
        })
        .catch(() => {
            next();
        });
    }
    else{
        next();
    }
});

//管理者ページの認証ガード
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
                next({name: 'video_management'});
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