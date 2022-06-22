<template>
    <div class="progress progressAnim" :style=" {width: percent.data+'%'}">
        <div class="progressAnim progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Чем больше эта полоса, тем больше вариантов для постов</div>
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
                percent: 10,
            }
        },
        mounted() {
            this.update();
        },
        methods: {
            update: function() {
                this.percent = 19;
                axios.post('/how-ready', {
                    text: this.percent,
                    //description: this.description
                }).then((response) => {
                    console.log("ready");

                    console.log(response.data);
                    this.percent = response.data;
                });

            }
        }
    }
</script>
