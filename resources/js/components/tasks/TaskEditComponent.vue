<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <form @submit.prevent="submit">
                    <div class="form group row border-bottom">
                        <label for="id" class="col-sm3 col-form-label">ID</label>
                        <input type="text" class="col-sm9 form-control-plaintext" readonly id="id" v-model="task.id">
                    </div>
                    <div class="form group row border-bottom">
                        <label for="title" class="col-sm3 col-form-label">タイトル</label>
                        <input type="text" class="col-sm9 form-control" id="title" v-model="task.title">
                    </div>
                    <div class="form group row border-bottom">
                        <label for="content" class="col-sm3 col-form-label">内容</label>
                        <input type="text" class="col-sm9 form-control"  id="content" v-model="task.content">
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">編集</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['taskId'],
    data(){
        return{
            task: {}      
        }
    },
    methods:{
        getTask(){
            axios.get('/api/tasks/' + this.taskId)
            .then((res) => {
                this.task = res.data;
            });
        },
        submit(){
            axios.put('/api/tasks/' + this.taskId, this.task)
            .then((res) => {
                this.$router.push({name: 'task.list'});
            });
        }
    },
    mounted(){
        this.getTask();
    }
}
</script>