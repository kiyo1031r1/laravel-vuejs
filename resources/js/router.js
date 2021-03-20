import Vue from 'vue'
import Router from 'vue-router'
import store from './store'

//auth
import Login from './components/auth/Login'
import Register from './components/auth/Register'
import ForgotPassword from './components/auth/ForgotPassword'
import ResetPassword from './components/auth/ResetPassword'
import SendMail from './components/auth/SendMail'
import ChangedPassword from './components/auth/ChangedPassword'
import SNSLoggedIn from './components/auth/SNSLoggedIn'

//admin
import AdminLogin from './components/auth/AdminLogin'
import UserManagementIndex from './components/admin/userManagement/Index'
import UserManagementEdit from './components/admin/userManagement/Edit'
import VideoManagementIndex from './components/admin/videoManagement/Index'
import VideoManagementCreate from './components/admin/videoManagement/Create'
import VideoManagementEdit from './components/admin/videoManagement/Edit'
import VideoManagementWatch from './components/admin/videoManagement/Watch'

//user
import Home from './components/Home'
import UserMyPage from './components/users/MyPage'
import UserVideoCategoryIndex from './components/users/videos/CategoryIndex'
import UserVideoIndex from './components/users/videos/Index'
import UserVideoWatch from './components/users/videos/Watch'
import UserPremiumRegister from './components/users/premium/Register'
import UserPremiumCancel from './components/users/premium/Cancel'
import UserPremiumEditCard from './components/users/premium/EditCard'

Vue.use(Router);

const router = new Router({
    mode: 'history',
    routes: [
        {
            path: '*',
            redirect: '/'
        },
        {
            path:'/',
            name: 'home',
            component: Home,
            meta: {
                guestOnly: true
            }
        },
        {
            path:'/login',
            name: 'login',
            component: Login,
            meta: {
                guestOnly: true
            }
        },
        {
            path:'/register',
            name: 'register',
            component: Register,
            meta: {
                guestOnly: true
            }
        },
        {
            path:'/forgot_password',
            name: 'forgot_password',
            component: ForgotPassword,
            meta: {
                guestOnly: true
            }
        },
        {
            path:'/reset_password',
            name: 'reset_password',
            component: ResetPassword,
            meta: {
                guestOnly: true
            }
        },
        {
            path:'/send_mail',
            name: 'send_mail',
            component: SendMail,
            meta: {
                guestOnly: true
            }
        },
        {
            path:'/changed_password',
            name: 'changed_password',
            component: ChangedPassword,
            //コンポーネントにナビゲーションガードあり
        },
        {
            path:'/sns_login',
            name: 'sns_login',
            component: SNSLoggedIn,
            //コンポーネントにナビゲーションガードあり
        },
        {
            path:'/video',
            name: 'video',
            component: UserVideoCategoryIndex,
            meta: {
                common: true
            }
        },
        {
            path:'/video/:category',
            name: 'video_index',
            component: UserVideoIndex,
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
                authOnly: true,
                subscriptionOnly: true
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
            path:'/premium/cancel',
            name: 'premium_cancel',
            component: UserPremiumCancel,
            meta: {
                premiumOnly: true,
             }
        },
        {
            path:'/premium/edit_card',
            name: 'edit_card',
            component: UserPremiumEditCard,
            meta: {
                customerOnly : true,
            }
        },
        {
            path:'/admin/login',
            name: 'admin_login',
            component: AdminLogin,
            meta: {
              admin_guestOnly: true
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
            store.dispatch('resetUser');
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
        .then((res) => {
            const user = res.data;
            store.dispatch('setUser', user);
            next({name: 'video'});
        })
        .catch(() => {
            store.dispatch('resetUser');
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
        .then(res => {
            const user = res.data;
            store.dispatch('setUser', user);
            next();
        })
        .catch(() => {
            store.dispatch('resetUser');
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
            store.dispatch('resetUser');
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
            store.dispatch('resetUser');
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

//プレミアム動画ページ
router.beforeEach((to, from, next) => {
    if(to.matched.some(record => record.meta.subscriptionOnly)){
        axios.get('/api/user')
        //ログイン確認
        .then(res => {
            const user = res.data;
            store.dispatch('setUser', user);

            //動画ステータスを取得
            axios.post('/api/videos/watch/' + to.params.id)
            .then(res => {
                const video = res.data.video;
                if(video.status === 'premium'){
                    //課金状況を取得
                    axios.get('/api/subscription/get_status')
                    .then(res => {
                        const subscription_status = res.data.status;
                        store.dispatch('setSubscriptionStatus', res.data.status);

                        if(subscription_status === 'premium' || subscription_status === 'cancel'){
                            next();
                        }
                        else{
                            next({name: 'premium_register',
                                query: {
                                    redirect: to.path
                                }
                            });
                        }
                    });
                }
                else{
                    next();
                }
            })
            .catch(() => {
                next({name: 'video'});
            });
        })
        .catch(() => {
            store.dispatch('resetUser');
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
            store.dispatch('resetUser');
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
            store.dispatch('resetUser');
            next();
        });
    }
    else{
        next();
    }
});

//クレジットカード変更ページの認証ガード
router.beforeEach((to, from, next) => {
    if(to.matched.some(record => record.meta.customerOnly)){
        axios.get('/api/user')
        .then((res) => {
            const user = res.data;
            store.dispatch('setUser', user);

            //一度でもプレミアム登録をしているかチェック
            if(user.stripe_id){
                next();
            }
            else{
                next({name: 'premium_register'});
            }
        })
        .catch(() => {
            store.dispatch('resetUser');
            //遷移予定だったpathを取得
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

export default router;