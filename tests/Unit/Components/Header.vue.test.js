import { shallowMount, createLocalVue } from '@vue/test-utils'
import Vuex from 'vuex'
import Header from '@/components/users/Header'
import flushPromises from 'flush-promises'

const localVue = createLocalVue()
localVue.use(Vuex)

const sel = id => `[data-testid="${id}"]`

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

describe('Header', () => {
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

    const factory = () => {
        return shallowMount(Header, {
            mocks: { 
                $router : {
                    push : mockRouterPush
                }
            },
            store,
            stubs : ['router-link'],
            localVue,
        })
    }

    afterEach(() => {
        mockRouterPush.mockClear()
    })
    
    it('ログイン時に、ログインユーザー名が表示されていること', () => {
        const wrapper = factory()
        expect(wrapper.find('span.name').text()).toBe(getters.user(state).name + 'さん')
    })

    it('非ログイン時に、ログインユーザー名が表示されていないこと', () => {
        state.user = null
        const wrapper = factory()
        expect(wrapper.find('span.name').exists()).toBe(false)
    })

    it('ログイン時に、ログアウトボタンが表示され、ログインボタンが表示されないこと', () => {
        const wrapper = factory()
        expect(wrapper.find(sel('logout')).exists()).toBe(true);
        expect(wrapper.find(sel('login')).exists()).toBe(false);
    })

    it('非ログイン時に、ログインボタンが表示され、ログアウトボタンが表示されないこと', () => {
        state.user = null
        const wrapper = factory()
        expect(wrapper.find(sel('login')).exists()).toBe(true);
        expect(wrapper.find(sel('logout')).exists()).toBe(false);
    })

    it('logoutのテスト', async () => {
        const wrapper = factory()
        wrapper.find(sel('logout')).trigger('click')
        await flushPromises()
        expect(mockRouterPush).toHaveBeenCalledWith({
          name: 'login'
        })
    })

    it('logoutのテスト(自動ログアウト時)', async () => {
        mockError = true
        const wrapper = factory()
        wrapper.find(sel('logout')).trigger('click')
        await flushPromises()
        expect(mockRouterPush).toHaveBeenCalledWith({
          name: 'login'
        })
    })

})