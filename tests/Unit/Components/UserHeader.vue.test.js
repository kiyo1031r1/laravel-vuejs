import { shallowMount, createLocalVue } from '@vue/test-utils'
import Vuex from 'vuex'
import UserHeader from '@/components/users/Header'
import flushPromises from 'flush-promises'

const localVue = createLocalVue()
localVue.use(Vuex)

let mockError = false
jest.mock("axios", () => ({
    get: () => new Promise((resolve, reject) => {
        if(mockError){
            reject()
        }
        else{
            resolve()
        }
    }),
    post: () => Promise.resolve(),
}))

describe('UserHeader', () => {
    let state, getters, store
    const mockRouterPush = jest.fn();

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
    
    it('display login user name if login', () => {
        const wrapper = shallowMount(UserHeader, { store, stubs: ['router-link'], localVue})
        expect(wrapper.find('span.name').text()).toBe(getters.user(state).name + 'さん')
    })

    it('not display login user name if logout', () => {
        state.user = null
        const wrapper = shallowMount(UserHeader, {store, stubs: ['router-link'], localVue})
        expect(wrapper.find('span.name').exists()).toBe(false)
    })

    it('display logout button if login, not display login button', () => {
        const wrapper = shallowMount(UserHeader, {store, stubs: ['router-link'], localVue})
        expect(wrapper.find('button.login').exists()).toBe(false);
        expect(wrapper.find('button.logout').exists()).toBe(true);
    })

    it('display login button if logout, not display logout button', () => {
        state.user = null
        const wrapper = shallowMount(UserHeader, {store, stubs: ['router-link'], localVue})
        expect(wrapper.find('button.login').exists()).toBe(true);
        expect(wrapper.find('button.logout').exists()).toBe(false);
    })

    it('testLogout', async () => {
        const wrapper = shallowMount(UserHeader, { 
            mocks: { 
                    $router : {
                        push : mockRouterPush
                    }
            },
            store,
            stubs: ['router-link'], 
            localVue
        })

        wrapper.find('button.logout').trigger('click')
        await flushPromises()
        expect(mockRouterPush).toHaveBeenCalledWith({
          name: 'login'
        })
    })

    it('testLogout_if_auto_logout', async () => {
        mockError = true
        const wrapper = shallowMount(UserHeader, { 
            mocks: { 
                    $router : {
                        push : mockRouterPush
                    }
            },
            store,
            stubs: ['router-link'], 
            localVue
        })

        wrapper.find('button.logout').trigger('click')
        await flushPromises()
        expect(mockRouterPush).toHaveBeenCalledWith({
          name: 'login'
        })
    })

})