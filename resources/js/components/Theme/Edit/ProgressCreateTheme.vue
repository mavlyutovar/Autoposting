<template>
    <div class="row mb-3">
        <div class="d-flex justify-content-center">
            <div class="progress progressAnim" :style=" {width: percent+'%'}">
                <div class="progressAnim progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">{{percent}}%</div>
            </div>
        </div>

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
                axios.post('/get-theme', {
                    text: this.percent,
                    //description: this.description
                }).then((response) => {
                    this.theme = response.data;
                    if(typeof(this.theme.text) != "undefined" && this.theme.text !== null) {
                        if(this.theme.text.length > 50) {
                            this.percent += 33;
                        }
                    }
                    if(typeof(this.theme.url_picture_board) != "undefined" && this.theme.url_picture_board !== null) {
                        this.percent += 33;
                        console.log("this.theme");
                    }
                    if(typeof(this.theme.url_audio_board) != "undefined" && this.theme.url_audio_board !== null) {
                        this.percent += 33;
                        console.log("this.theme");
                    }
                });

            }
        }
    }
</script>
