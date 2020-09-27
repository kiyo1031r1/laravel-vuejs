<template>
    <div class="container">
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">タイトル</th>
                    <th scope="col">内容</th>
                    <th scope="col">詳細</th>
                    <th scope="col">編集</th>
                    <th scope="col">削除</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="task in tasks" :key="task.id">
                    <th scope="row">{{ task.id}}</th>
                    <th>{{ task.title }}</th>
                    <th>{{ task.content }}</th>
                    <th>
                        <router-link :to="{ name: 'task.show', params: {taskId: task.id} }">
                            <button class="btn btn-primary">詳細</button>
                        </router-link>
                    </th>
                    <th>
                        <router-link :to="{ name: 'task.edit', params: {taskId: task.id} }">
                            <button class="btn btn-success">編集</button>
                        </router-link>
                    </th>
                    <th>
                        <button class="btn btn-danger" @click="deleteTask(task.id)">削除</button>
                    </th>
                </tr>
            </tbody>
        </table>
    </div>
</template>


<script>
export default {
    data() {
        return {
            tasks: []
        }
    },
    methods: {
        getTasks(){
            axios.get('/api/tasks')
            .then((res) => {
                this.tasks = res.data;
            });
        },
        deleteTask(id){
            axios.delete('/api/tasks/' + id)
            .then((res) => {
                this.getTasks();
            })
        }
    },
    mounted(){
        this.getTasks();
    }

}
</script>