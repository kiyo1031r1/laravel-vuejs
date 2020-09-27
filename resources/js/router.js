import Vue from 'vue'
import Router from 'vue-router'
import TaskListComponent from './components/TaskListComponent'
import TaskCreateComponent from './components/TaskCreateComponent'
import TaskShowComponent from './components/TaskShowComponent'
import TaskEditComponent from './components/TaskEditComponent'
import LoginComponent from './components/auth/LoginComponent'
import RegisterComponent from './components/auth/RegisterComponent'

Vue.use(Router);

export default new Router({
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
            component: TaskCreateComponent
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
            props: true
        },
        {
            path:'/login',
            name: 'login',
            component: LoginComponent,
        },
        {
            path:'/register',
            name: 'register',
            component: RegisterComponent,
        }
    ]
});