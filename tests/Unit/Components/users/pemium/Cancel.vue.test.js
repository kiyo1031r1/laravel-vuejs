import { shallowMount, createLocalVue } from '@vue/test-utils'
import Vuex from 'vuex'
import Cancel from '@/components/users/premium/Cancel'
import flushPromises from 'flush-promises'

const localVue = createLocalVue()
localVue.use(Vuex)
const sel = id => `[data-testid="${id}"]`

let mockError = false
jest.mock("axios", () => ({
    post: () => new Promise((resolve, reject) => {
        if(mockError){
            reject()
        }
        else{
            resolve()
        }
    }),
}))

describe('Cancel', () => {
    let state, getters, store
    let alertSpy = jest.spyOn(window, 'alert');
    const mockRouterPush = jest.fn();

    beforeEach(() => {
        state = {
            user : {
                id : '1',
                name : 'test',
                email : 'test@example.com',
                status : 'premium',
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
        return shallowMount(Cancel, {
            mocks: {
                $router : {
                    push : mockRouterPush
                }
            },
            store, 
            localVue 
        })
    }

    afterEach(() => {
        alertSpy.mockClear()
        mockRouterPush.mockClear()
    })
    
    it('遷移時にユーザー名が表示されていること', () => {
        const wrapper = factory()
        expect(wrapper.find(sel('name')).text()).toBe(getters.user(state).name + 'さんは')
    })

    it('プレミアムユーザーの場合、ノーマル完了画面に遷移すること', async() => {
        const wrapper = factory()
        wrapper.find('button').trigger('click')
        await flushPromises()
        expect(mockRouterPush).toHaveBeenCalledWith({
            name: 'changed_normal'
        })
    })

    it('ノーマルユーザーの場合、遷移しないこと',() => {
        alertSpy.mockImplementation(jest.fn())
        state.user.status = 'normal'
        const wrapper = factory()
        wrapper.find('button').trigger('click')
        expect(window.alert).toHaveBeenCalledWith('すでに一般会員です')
        expect(mockRouterPush).not.toHaveBeenCalled()
    })

})