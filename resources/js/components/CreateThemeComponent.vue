<template>
    <div class="row mb-3">
        <div class="d-flex justify-content-center">
            Создание стиля постов
        </div>
        <div class="d-flex justify-content-center">
            <div class="progress progressAnim" style="width: 25%;">
                <div class="progressAnim progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">25%</div>
            </div>
        </div>

    </div>
</template>
<style>
.progressAnim {
    -webkit-transition: width 2s, height 2s, background-color 2s, -webkit-transform 2s;
    transition: width 2s, height 2s, background-color 2s, transform 2s;
}
</style>

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
