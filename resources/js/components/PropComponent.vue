<template>
    <div class="container">
            <div class="form-group">
                <label for="exampleFormControlTextarea1">Введите предложение</label>
                <textarea v-model="textAdd" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
            </div>
            <button @click="add()" type="submit" class="btn btn-success">Добавить</button>
        <ul class="list-group">
            <li v-for="(item, index) in data.text" class="list-group-item d-flex justify-content-between align-items-center">
                {{ item }} {{ index }}
                <button @click="del(index)" type="button" class="btn btn-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"></path>
                    </svg>
                </button>
            </li>
        </ul>
        <div class="row">
            <button @click="update()" class="btn btn-default text mb-1" v-if="!is_refresh">Обновить {{id}}</button>
            <span class="badge badge-info mb-1" v-if="is_refresh">Обновление</span>
        </div>
    </div>
</template>

<script>
    export default {
        data:function(){
            return{
                data: [],
                is_refresh: false,
                id: 0,
                textAdd: "",
                params: [],
            }
        },
        mounted() {
            this.update();
        },
        methods: {
            add: function() {
                console.log(this.params)
                axios.post('/add-text-theme', {
                    text: this.textAdd,
                    //description: this.description
                }).then((response) => {
                    console.log(response.data)
                    this.data = response.data
                });
            },
            update: function() {
                this.is_refresh = true;
                axios.get('/show-text').then((response) => {
                    console.log(response.data)
                    this.data = response.data;
                    this.is_refresh = false;
                    this.id++;
                });
            },
            del: function(id) {
                axios.post('/update-text-theme', {
                    id: id,
                    //description: this.description
                }).then((response) => {
                    this.data = response.data
                });
            }
        }
    }
</script>
