import { shallowMount, createLocalVue } from '@vue/test-utils'
import VueRouter from 'vue-router'
import Vuex from 'vuex'
import UserHeader from '@/components/users/Header'

const localVue = createLocalVue()
localVue.use(VueRouter)
localVue.use(Vuex)

describe('UserHeader', () => {
    let state
    let getters
    let store

    beforeEach(() => {
        state = {
            user : {
                name : 'test'
            }
        }
        getters = {
            user: state => state.user
        }
        store = new Vuex.Store({
            state,
            getters
        })
    })
    
    it('ログイン時は、ログインユーザー名が表示されている', () => {
        const wrapper = shallowMount(UserHeader, {store, localVue})
        expect(wrapper.find('span.name').text()).toBe(getters.user(state).name + 'さん')
    })

    it('非ログイン時は、ユーザー名が表示されない', () => {
        state.user = null
        const wrapper = shallowMount(UserHeader, {store, localVue})
        expect(wrapper.find('span.name').exists()).toBe(false)
    })

    it('ログイン時は、ログアウトボタンが表示されている', () => {
        const wrapper = shallowMount(UserHeader, {store, localVue})
        expect(wrapper.find('button.login').exists()).toBe(false);
        expect(wrapper.find('button.logout').exists()).toBe(true);
    })

    it('非ログイン時は、ログインボタンが表示されている', () => {
        state.user = null
        const wrapper = shallowMount(UserHeader, {store, localVue})
        expect(wrapper.find('button.login').exists()).toBe(true);
        expect(wrapper.find('button.logout').exists()).toBe(false);
    })

})