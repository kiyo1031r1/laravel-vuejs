import { shallowMount, createLocalVue } from '@vue/test-utils'
import Vuex from 'vuex'
import MyPage from '@/components/users/MyPage'
import flushPromises from 'flush-promises'

const localVue = createLocalVue()
localVue.use(Vuex)
const sel = id => `[data-testid="${id}"]`

let mockError = false
jest.mock("axios", () => ({
    put: () => new Promise((resolve, reject) => {
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

describe('MyPage', () => {
    let state, getters, store
    let confirmSpy = jest.spyOn(window, 'confirm');
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

    const factory = (values = {}) => {
        return shallowMount(MyPage, {
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
            stubs: ['v-icon'], 
            store, 
            localVue 
        })
    }

    afterEach(() => {
        confirmSpy.mockClear()
        mockRouterPush.mockClear()
    })
    
    it('ページ遷移時、ユーザー情報表示が表示されること', () => {
        const wrapper = factory()
        expect(wrapper.find(sel('input_name_disabled')).element.value).toBe(getters.user(state).name)
        expect(wrapper.find(sel('input_name')).element.value).toBe(getters.user(state).name)
        expect(wrapper.find(sel('input_email_disabled')).element.value).toBe(getters.user(state).email)
        expect(wrapper.find(sel('input_email')).element.value).toBe(getters.user(state).email)
        expect(wrapper.find(sel('input_status_disabled')).element.value).toBe(getters.user(state).status)
    })

    it('入力値がstoreデータとバインドしていないこと', () => {
        const wrapper = factory()
        wrapper.find(sel('input_name')).setValue('change_name')
        expect(wrapper.vm.user.name).toBe('change_name')
        expect(getters.user(state).name).not.toBe('change_name')
        expect(wrapper.find(sel('input_name_disabled')).element.value).toBe(getters.user(state).name)

        wrapper.find(sel('input_email')).setValue('change_email@example.com')
        expect(wrapper.vm.user.email).toBe('change_email@example.com')
        expect(getters.user(state).email).not.toBe('change_email')
        expect(wrapper.find(sel('input_email_disabled')).element.value).toBe(getters.user(state).email)
    })

    it('passwordHiddenToggleのテスト', () => {
        const wrapper = factory()
        expect(wrapper.vm.is_password_hidden).toBe(true);
        wrapper.find(sel('password-icon')).trigger('click')
        expect(wrapper.vm.is_password_hidden).toBe(false);
        wrapper.find(sel('password-icon')).trigger('click')
        expect(wrapper.vm.is_password_hidden).toBe(true);
    })

    it('password表示切り替え_非表示', () => {
        const wrapper = factory()
        expect(wrapper.find(sel('input_password')).attributes().type).toBe('password')
        expect(wrapper.find(sel('eye-slash')).exists()).toBe(true)
        expect(wrapper.find(sel('eye')).exists()).toBe(false)
    })

    it('password表示切り替え_表示', () => {
        const wrapper = factory({is_password_hidden: false})
        expect(wrapper.find(sel('input_password')).attributes().type).toBe('text')
        expect(wrapper.find(sel('eye-slash')).exists()).toBe(false)
        expect(wrapper.find(sel('eye')).exists()).toBe(true)
    })

    it('edit成功時の遷移確認', async () => {
        confirmSpy.mockImplementation(jest.fn(() => true))
        const wrapper = factory()
        wrapper.find(sel('button_edit')).trigger('click')
        await flushPromises()
        expect(mockRouterPush).toHaveBeenCalledWith({
            name: 'video'
        })
    })

    it('更新をキャンセルした場合に遷移しないこと', () => {
        confirmSpy.mockImplementation(jest.fn(() => false))
        const wrapper = factory()
        wrapper.find(sel('button_edit')).trigger('click')
        expect(mockRouterPush).not.toHaveBeenCalled()
    })

    it('バリデーションエラー時に内容を格納し、遷移しないこと', async () => {
        mockError = true
        confirmSpy.mockImplementation(jest.fn(() => true))
        const wrapper = factory()
        expect(Object.keys(wrapper.vm.errors).length).toBe(0)
        wrapper.find(sel('button_edit')).trigger('click')
        await flushPromises()
        expect(wrapper.vm.errors.name[0]).toBe('名前は必ず入力してください。')
        expect(wrapper.vm.errors.email[0]).toBe('メールアドレスは必ず入力してください。')
        expect(wrapper.vm.errors.password[0]).toBe('パスワードが確認用の値と一致しません。')
        expect(mockRouterPush).not.toHaveBeenCalled()
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