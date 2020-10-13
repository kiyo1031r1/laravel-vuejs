<template>
    <div>
        <AdminHeader></AdminHeader>
        <div class="container">
            <form class="my-4">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="ユーザー検索">
                </div>
            </form>

            <table class="table table-sm table-bordered table-hover text-center">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">ユーザー名</th>
                        <th scope="col">email</th>
                        <th scope="col">登録日</th>
                        <th scope="col">ステータス</th>
                        <th scope="col">次回更新日</th>
                        <th scope="col">編集</th>
                        <th scope="col">削除</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>名前</td>
                        <td>メール</td>
                        <td>登録日</td>
                        <td>ステータス</td>
                        <td>次回更新日</td>
                        <td><button class="btn btn-primary px-2 py-0">編集</button></td>
                        <td><button class="btn btn-danger px-2 py-0">削除</button></td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>名前</td>
                        <td>メール</td>
                        <td>登録日</td>
                        <td>ステータス</td>
                        <td>次回更新日</td>
                        <td><button class="btn btn-primary px-2 py-0">編集</button></td>
                        <td><button class="btn btn-danger px-2 py-0">削除</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</template>

<script>
import AdminHeader from '../AdminHeaderComponent'

export default {
    components:{
        AdminHeader
    }
}
</script>
