import { shallowMount, createLocalVue } from '@vue/test-utils'
import Register from '@/components/auth/Register'
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
                        email: ['メールアドレスは必ず入力してください。'],
                        name: ['名前は必ず入力してください。'],
                        password: ['パスワードが確認用の値と一致しません。'],
                    }
                }
            }})
        }
        else{
            resolve()
        }
    }),
}))

describe ('Register', () => {
    const mockRouterPush = jest.fn();

    const factory = (values = {}) => {
        return shallowMount(Register, {
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

    it('ユーザー登録成功時の遷移確認', async() => {
        const wrapper = factory()
        wrapper.find('form').trigger('submit.prevent')
        await flushPromises()
        expect(mockRouterPush).toHaveBeenCalledWith({
            name: 'video'
        })
    })

    it('バリデーションエラー時に内容を格納し、遷移しないこと', async() => {
        mockError = true
        const wrapper = factory()
        expect(Object.keys(wrapper.vm.errors).length).toBe(0)
        wrapper.find('form').trigger('submit.prevent')
        await flushPromises()
        expect(wrapper.vm.errors.name[0]).toBe('名前は必ず入力してください。')
        expect(wrapper.vm.errors.email[0]).toBe('メールアドレスは必ず入力してください。')
        expect(wrapper.vm.errors.password[0]).toBe('パスワードが確認用の値と一致しません。')
        expect(mockRouterPush).not.toHaveBeenCalled()
        
        //確認用パスワードフィールドの初期化確認
        expect(wrapper.vm.user.password_confirmation).toBe('')
    })
    
    it('バリデーションエラー時に、エラーが表示されていること', () => {
        const wrapper = factory({errors: {
            name : ['名前は必ず入力してください。'],
            email : ['メールアドレスは必ず入力してください。'],
            password : ['パスワードが確認用の値と一致しません。'],
        }})
        expect(wrapper.find(sel('error_name')).text()).toBe('名前は必ず入力してください。')
        expect(wrapper.find(sel('error_email')).text()).toBe('メールアドレスは必ず入力してください。')
        expect(wrapper.find(sel('error_password')).text()).toBe('パスワードが確認用の値と一致しません。')
    })

    it('エラーがない場合は、エラー表示されていないこと', () => {
        const wrapper = factory()
        expect(wrapper.find(sel('error_name')).exists()).toBe(false)
        expect(wrapper.find(sel('error_email')).exists()).toBe(false)
        expect(wrapper.find(sel('error_password')).exists()).toBe(false)
    })

})