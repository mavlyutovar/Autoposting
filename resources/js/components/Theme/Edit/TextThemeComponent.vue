<template>
            <div class="row">
                <div class="col-7 col-lg-7 bg-light">
                    <div class="card mb-4">
                        <div class="card-header">Количество текста: {{theme.text.length}} шт.</div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li v-for="(item, index) in theme.text" class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="text-dark">{{index+1}}</span>
                                    <span class="mr-auto p-2">{{ item }}</span>
                                    <button @click="del(index)" type="button" class="btn btn-warning">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"></path>
                                        </svg>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-5 col-lg-5">
                    <div class="d-flex">
                        <div class="card mb-4">
                            <div class="card-header">Введите предложение</div>
                            <div class="card-body">
                                <span>Добавьте несколько сообщений одной тематики. Поддержиаются смайлы <a href="https://getemoji.com/">Ctrl+C Ctrl+V</a>.</span>
                                <textarea v-model="textAdd" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                <div class="d-flex justify-content-around align-items-center mt-3">
                                    <button @click="add()" data-toggle="tooltip" data-placement="left" title="Добавьте несколько сообщений одной тематики" type="submit" class="justify-content-end btn btn-success">Добавить</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</template>

<script>
    export default {
        data:function(){
            return{
                theme: {
                    text: []
                },
                textAdd: "",
            }
        },
        mounted() {
            this.update();
        },
        methods: {
            add: function() {
                axios.post('/add-text-theme', {
                    text: this.textAdd,
                    //description: this.description
                }).then((response) => {
                    this.theme = response.data
                });
            },
            update: function() {
                this.is_refresh = true;
                axios.get('/show-text-theme').then((response) => {
                    this.theme = response.data;
                });
            },
            del: function(id) {
                axios.post('/update-text-theme', {
                    id: id,
                    //description: this.description
                }).then((response) => {
                    this.theme = response.data
                });
            }
        }
    }
</script>
