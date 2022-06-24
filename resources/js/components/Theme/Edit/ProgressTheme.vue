<template>
    <div class="progress progressAnim" :style=" {width: percent.data+'%'}">
        <div class="progressAnim progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">{{title}}</div>
    </div>
</template>
<style>
.progressAnim {
    -webkit-transition: width 0.5s, height 0.5s, background-color 0.5s, -webkit-transform 0.5s;
    transition: width 0.5s, height 0.5s, background-color 0.5s, transform 0.5s;
}
</style>

<script>
    export default {
        data:function(){
            return{
                theme: [],
                title: "Еще",
                percent: {
                    data:0,
                },
            }
        },
        mounted() {
            this.update();
        },
        methods: {
            update: function() {
                this.percent = 0;
                axios.post('/how-ready', {
                    text: this.percent,
                    //description: this.description
                }).then((response) => {
                    this.percent = response.data;
                    this.updateTitle(this.percent.data)
                });

            },
            updateTitle: function(value){
                if(value > 0){
                    this.title = "Еще";
                }
                if(value > 15){
                    this.title = "Добавь текст";
                }
                if(value > 20){
                    this.title = "Добавь еще текста";
                }
                if(value > 25){
                    this.title = "Добавь аудио";
                }
                if(value > 35){
                    this.title = "Больше музыки!";
                }
                if(value > 46){
                    this.title = "Переходи в изображения";
                }
                if(value > 50){
                    this.title = "Больше материала - больше разных постов";
                }
                if(value > 80){
                    this.title = "Выставь вероятность в настройках";
                }
                if(value > 84){
                    this.title = "Не забудь поменять название стиля";
                }
            }
        }
    }
</script>
