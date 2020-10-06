<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <form @submit.prevent="submit">
                    <div class="form group row border-bottom">
                        <label for="title" class="col-sm3 col-form-label">タイトル</label>
                        <input type="text" class="col-sm9 form-control" id="title" v-model="task.title">
                    </div>
                    <div class="form group row border-bottom">
                        <label for="content" class="col-sm3 col-form-label">内容</label>
                        <input type="text" class="col-sm9 form-control" id="content" v-model="task.content">
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">作成</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            task:{}
        }
    },
    methods:{
        submit() {
            axios.post('/api/tasks', this.task)
            .then((res) => {
                this.$router.push({name: 'task.list'});
            });
        }
    }
}
</script>