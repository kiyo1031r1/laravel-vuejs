<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">ユーザー作成</div>

                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">ユーザー名</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="text">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">メールアドレス</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">パスワード</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">パスワードの確認</label>

                                <div class="col-md-6">
                                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary px-4 mt-4">
                                        作成
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>