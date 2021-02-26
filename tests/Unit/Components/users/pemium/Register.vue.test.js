import { shallowMount, createLocalVue } from '@vue/test-utils'
import Vuex from 'vuex'
import Register from '@/components/users/premium/Register'
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

describe('Register', () => {
    let state, getters, store
    let alertSpy = jest.spyOn(window, 'alert');
    const mockRouterPush = jest.fn();

    beforeEach(() => {
        state = {
            user : {
                id : '1',
                name : 'test',
                email : 'test@example.com',
                status : 'normal',
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
        return shallowMount(Register, {
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

    it('ノーマルユーザーの場合、登録ボタンクリックでプレミアム完了画面に遷移すること', async() => {
        const wrapper = factory()
        wrapper.find('button').trigger('click')
        await flushPromises()
        expect(mockRouterPush).toHaveBeenCalledWith({
            name: 'changed_premium'
        })
    })

    it('プレミアムユーザーの場合、遷移しないこと',() => {
        alertSpy.mockImplementation(jest.fn())
        state.user.status = 'premium'
        const wrapper = factory()
        wrapper.find('button').trigger('click')
        expect(window.alert).toHaveBeenCalledWith('すでにプレミアム会員です')
        expect(mockRouterPush).not.toHaveBeenCalled()
    })

})