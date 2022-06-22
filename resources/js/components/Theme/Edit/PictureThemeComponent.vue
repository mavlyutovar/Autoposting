<template>
            <div class="row">
                <div class="bg-light">
                    <div class="card mb-4">
                        <div class="card-header">Источник изображений</div>
                        <div class="card-body">
                            <h5 class="mb-3 text-center">
                                Добавьте источник изображений. <br>Поддерживаются доски сайта <a href="https://www.pinterest.ru/kdnamehere/thismood-picture/">https://www.pinterest.ru/</a>.
                                Или укажите любой ID открытой группы Вконтакте. <br>Система автоматически будет подгружать случайную картинку из набора доски или группы ВК.
                            </h5>
                            <div v-if="change" class="d-flex justify-content-left align-items-center mb-3">
                                <button @click="del(userid)" type="button" class="btn btn-outline-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">
                                        <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z"/>
                                    </svg>
                                    {{ urlPicture }}
                                </button>
                            </div>
                            <input v-model="urlPicture" class="form-control form-control-lg" type="text" placeholder="https://www.pinterest.ru/">
                            <div class="d-flex justify-content-around align-items-center mt-3">
                                <button @click="add()" data-toggle="tooltip" data-placement="left" title="Добавьте ссылку" type="submit" class="justify-content-end btn btn-success">Применить</button>
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
                urlPicture: null,
                userid: 1,
                change: false,
            }
        },
        mounted() {
            this.update();
        },
        methods: {
            updatePercent() {
                this.$emit('updPercent', {
                        userid: this.userid,
                        urlPicture: this.urlPicture
                    })},
            add: function() {
                axios.post('/add-pic-theme', {
                    url_picture_board: this.urlPicture,
                    //description: this.description
                }).then((response) => {
                    this.urlPicture = response.data
                    if(this.urlPicture){
                        this.change = true;
                    }
                    this.updatePercent();
                });
            },
            update: function() {
                this.urlPicture = null;
                axios.get('/show-pic-theme').then((response) => {
                    this.urlPicture = response.data;
                    if(this.urlPicture) {
                        this.change = true;
                    }
                    this.updatePercent();
                });
            },
            del: function(id) {
                axios.post('/update-pic-theme', {
                    id: id,
                    //description: this.description
                }).then((response) => {
                    this.urlPicture = null;
                    this.change = false;
                    this.updatePercent();
                });
            }
        }
    }
</script>
