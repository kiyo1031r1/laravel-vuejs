import { shallowMount, createLocalVue } from '@vue/test-utils'
import Login from '@/components/users/Login'
import flushPromises from 'flush-promises'

const localVue = createLocalVue()
const sel = id => `[data-testid="${id}"]`

let mockError = false
jest.mock("axios", () => ({
    put: () => new Promise((resolve, reject) => {
        if(mockError){
            reject({ response: {
                data : {
                    errors : {
                        email: ['メールアドレスかパスワードが間違っています。'],
                        password: ['メールアドレスかパスワードが間違っています。'],
                    }
                }
            }})
        }
        else{
            resolve()
        }
    }),
}))

describe ('Login', () => {
    const factory = (values = {}) => {
        return shallowMount(Login, {
            data(){
                return {
                    ...values
                }
            }, 
            mocks: {
                $router : {
                    push : mockRouterPush
                }
            },
            stubs: ['v-icon', 'router-link'], 
            localVue 
        })
    }

    it('', () => {

    })

})