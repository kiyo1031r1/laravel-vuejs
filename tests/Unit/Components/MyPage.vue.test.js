import { shallowMount, createLocalVue } from '@vue/test-utils'
import Vuex from 'vuex'
import MyPage from '@/components/users/MyPage'
import flushPromises from 'flush-promises'

const localVue = createLocalVue()
localVue.use(Vuex)

let mockError = false
jest.mock("axios", () => ({
    put: () => new Promise((resolve, reject) => {
        if(mockError){
            reject()
        }
        else{
            resolve()
        }
    }),
}))

describe('MyPage', () => {
    let state, getters, store
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
    
    it('', () => {

    })

})