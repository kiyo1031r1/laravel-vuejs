<template>
    <div class="container-fluif bg-dark mb-3">
        <div class="container">
            <nav class="navbar navbar-dark">
                <span class="nabvar-brand mb-0 h1 text-light">Laravel-vuejs</span>
                <div>
                    <router-link :to="{ name: 'task.list'}">
                        <button class="btn btn-success">一覧</button>
                    </router-link>
                    <router-link :to="{ name: 'task.create'}">
                        <button class="btn btn-success">新規作成</button>
                    </router-link>
                    <router-link :to="{ name: 'login'}">
                        <button class="btn btn-success">ログイン</button>
                    </router-link>
                </div>
            </nav>
        </div>
    </div>
</template>