import { shallowMount, createLocalVue } from '@vue/test-utils'
import AdminLogin from '@/components/auth/AdminLogin'
import flushPromises from 'flush-promises'

const localVue = createLocalVue()
const sel = id => `[data-testid="${id}"]`

let mockError = false
jest.mock("axios", () => ({
    get: () => Promise.resolve(),
    post: () => new Promise((resolve, reject) => {
        if(mockError){
            reject({ response: {
                data : {
                    errors : {
                        not_found: ['メールアドレスかパスワードが間違っています。'],
                    }
                }
            }})
        }
        else{
            resolve()
        }
    }),
}))

describe ('AdminLogin', () => {
    const mockRouterPush = jest.fn();
    let mockRouteQuery = jest.fn();

    const factory = (values = {}) => {
        return shallowMount(AdminLogin, {
            data(){
                return {
                    ...values
                }
            }, 
            mocks: {
                $router : {
                    push : mockRouterPush
                },
                $route : {
                    query : mockRouteQuery
                }
            },
            stubs: ['v-icon', 'router-link'], 
            localVue 
        })
    }

    afterEach(() => {
        mockRouterPush.mockClear()
    })

    it('passwordHiddenToggleのテスト', () => {
        const wrapper = factory()
        expect(wrapper.vm.is_password_hidden).toBe(true);
        wrapper.find(sel('password_icon')).trigger('click')
        expect(wrapper.vm.is_password_hidden).toBe(false);
        wrapper.find(sel('password_icon')).trigger('click')
        expect(wrapper.vm.is_password_hidden).toBe(true);
    })

    it('password表示切り替え_非表示', () => {
        const wrapper = factory()
        expect(wrapper.find(sel('input_password')).attributes().type).toBe('password')
        expect(wrapper.find(sel('eye_slash')).exists()).toBe(true)
        expect(wrapper.find(sel('eye')).exists()).toBe(false)
    })

    it('password表示切り替え_表示', () => {
        const wrapper = factory({is_password_hidden: false})
        expect(wrapper.find(sel('input_password')).attributes().type).toBe('text')
        expect(wrapper.find(sel('eye_slash')).exists()).toBe(false)
        expect(wrapper.find(sel('eye')).exists()).toBe(true)
    })

    it('ログイン成功時の遷移確認', async() => {
        const wrapper = factory()
        wrapper.find('form').trigger('submit.prevent')
        await flushPromises()
        expect(mockRouterPush).toHaveBeenCalledWith({
            name: 'video_management'
        })
    })

    it('ログイン成功時の遷移確認_クエリあり', async() => {
        mockRouteQuery = {redirect : '/admin/user_management'}
        const wrapper = factory()
        wrapper.find('form').trigger('submit.prevent')
        await flushPromises()
        expect(mockRouterPush).toHaveBeenCalledWith(
            '/admin/user_management'
        )
    })

    it('バリデーションエラー時に内容を格納し、遷移しないこと', async() => {
        mockError = true
        const wrapper = factory()
        expect(Object.keys(wrapper.vm.errors).length).toBe(0)
        wrapper.find('form').trigger('submit.prevent')
        await flushPromises()
        expect(wrapper.vm.errors.not_found[0]).toBe('メールアドレスかパスワードが間違っています。')
        expect(mockRouterPush).not.toHaveBeenCalled()
    })
    
    it('バリデーションエラー時に、エラーが表示されていること', () => {
        const wrapper = factory({errors: {
            not_found : ['メールアドレスかパスワードが間違っています。'],
        }})
        expect(wrapper.find(sel('error_not_found')).text()).toBe('メールアドレスかパスワードが間違っています。')
    })

    it('エラーがない場合は、エラー表示されていないこと', () => {
        const wrapper = factory()
        expect(wrapper.find(sel('error_not_found')).exists()).toBe(false)
    })

})