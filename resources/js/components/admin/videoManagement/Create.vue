<template>
    <div>
        <div class="col-md-6 mx-auto my-4">
            <div class="card">
                <div class="card-header">ビデオ新規作成</div>
                <div class="card-body">
                    <form @submit.prevent="">
                        <div class="form-group row">
                            <label class="col-form-label col-md-2" for="title">タイトル</label>
                            <div class="col-md-8">
                                <input class="form-control" id="title">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2" for="category">カテゴリー</label>
                            <div class="col-md-6">
                                <select class="form-control" id="category">
                                    <option value="1">カテゴリー</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2" for="about">概要</label>
                            <div class="col-md-8">
                                <textarea class="form-control" id="about" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2" for="thumbnail">サムネイル</label>
                            <input class="form-control-file col-md-8" type="file" id="thumbnail">
                            <img src="" class="img-thumbnail">
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2" for="capture">動画ファイル</label>
                            <input class="form-control-file col-md-8" type="file">
                        </div>

                        <div class="col-md-4 mx-auto mt-5">
                            <button class="btn btn-primary btn-block" type="submit">作成</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>