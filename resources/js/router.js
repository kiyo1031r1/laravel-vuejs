import Vue from 'vue'
import Router from 'vue-router'
import store from './store'

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
import UserMyPage from './components/users/MyPage'
import UserVideoIndex from './components/users/videos/Index'
import UserVideoShow from './components/users/videos/Show'
import UserVideoWatch from './components/users/videos/Watch'
import UserPremiumRegister from './components/users/premium/Register'
import UserPremiumChangedPremium from './components/users/premium/ChangedPremium'
import UserPremiumCancel from './components/users/premium/Cancel'
import UserPremiumChangedNormal from './components/users/premium/ChangedNormal'

Vue.use(Router);

const router = new Router({
    mode: 'history',
    routes: [
        {
            path:'/',
            name: 'home',
            component: UserHomeComponent,
            meta: {
                guestOnly: true
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
            meta: {
                common: true
            }
        },
        {
            path:'/video/:category',
            name: 'video_show',
            component: UserVideoShow,
            props: true,
            meta: {
                common: true
            }
        },
        {
            path:'/video/watch/:id',
            name: 'video_watch',
            component: UserVideoWatch,
            props: true,
            meta: {
                authOnly: true
            }
        },
        {
            path:'/my_page',
            name: 'my_page',
            component: UserMyPage,
            meta: {
                authOnly: true
             }
        },
        {
            path:'/premium/register',
            name: 'premium_register',
            component: UserPremiumRegister,
            meta: {
                normalOnly: true,
             }
        },
        {
            path:'/premium/changed_premium',
            name: 'changed_premium',
            component: UserPremiumChangedPremium,
             //コンポーネントにナビゲーションガードあり
        },
        {
            path:'/premium/cancel',
            name: 'premium_cancel',
            component: UserPremiumCancel,
            meta: {
                premiumOnly: true,
             }
        },
        {
            path:'/premium/changed_normal',
            name: 'changed_normal',
            component: UserPremiumChangedNormal,
             //コンポーネントにナビゲーションガードあり
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
        .then((res) => {
            const user = res.data;
            store.dispatch('setUser', user);
            next();
        })
        .catch(() => {
            localStorage.removeItem(process.env.MIX_APP_NAME);
            localStorage.removeItem(process.env.MIX_APP_NAME + '-admin');
            //遷移予定だったpathを取得
            next({name: 'login', 
                query: {
                    redirect: to.path
                }
            });
        });
    }
    else if(to.matched.some(record => record.meta.guestOnly)){
        axios.get('/api/user')
        .then(() => {
            next({name: 'video'});
        })
        .catch(() => {
            localStorage.removeItem(process.env.MIX_APP_NAME);
            localStorage.removeItem(process.env.MIX_APP_NAME + '-admin');
            next();
        });
    }
    else{
        next();
    }
});


//ユーザーページの共通ページ
router.beforeEach((to, from, next) => {
    if(to.matched.some(record => record.meta.common)){
        axios.get('/api/user')
        .then(() => {
            next();
        })
        .catch(() => {
            localStorage.removeItem(process.env.MIX_APP_NAME);
            localStorage.removeItem(process.env.MIX_APP_NAME + '-admin');
            next();
        });
    }
    else{
        next();
    }
});


//プレミアムページ
router.beforeEach((to, from, next) => {
    if(to.matched.some(record => record.meta.premiumOnly)){
        axios.get('/api/user')
        .then(res => {
            const user = res.data;
            store.dispatch('setUser', user);
            if(user.status === 'premium'){
                next();
            }
            else{
                next({name: 'premium_register'});
            }
        })
        .catch(() => {
            localStorage.removeItem(process.env.MIX_APP_NAME);
            localStorage.removeItem(process.env.MIX_APP_NAME + '-admin');
            next({name: 'login', 
                query: {
                    redirect: to.path
                }
            });
        });
    }
    else if(to.matched.some(record => record.meta.normalOnly)){
        axios.get('/api/user')
        .then(res => {
            const user = res.data;
            store.dispatch('setUser', user);
            if(user.status === 'normal'){
                next();
            }
            else{
                next({name: 'premium_cancel'});
            }
        })
        .catch(() => {
            localStorage.removeItem(process.env.MIX_APP_NAME);
            localStorage.removeItem(process.env.MIX_APP_NAME + '-admin');
            next({name: 'login', 
                query: {
                    redirect: to.path
                }
            });
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
            const user = res.data;
            store.dispatch('setUser', user);
            if(user.role_id != 2){
                next({name: 'admin_login'});
            }
            else{
                next();
            }
        })
        .catch(() => {
            localStorage.removeItem(process.env.MIX_APP_NAME + '-admin');
            //遷移予定だったpathを取得
            next({name: 'admin_login',
                query: {
                    redirect: to.path
                }
            });
        });
    }
    else if(to.matched.some(record => record.meta.admin_guestOnly)){
        axios.get('/api/user')
        .then(res => {
            const user = res.data;
            store.dispatch('setUser', user);
            if(user.role_id === 2){
                next({name: 'video_management'});
            }
            else{
                next();
            }
        })
        .catch(() => {
            localStorage.removeItem(process.env.MIX_APP_NAME + '-admin');
            next();
        });
    }
    else{
        next();
    }
});

export default router;