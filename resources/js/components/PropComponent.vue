<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Enter Name</div>

                    <div v-for="url in data" class="card-body">
                        {{ url.name }}
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
                data[],
            }
        },
        props: [
            'data'
        ],
        mounted() {
            this.update();
        },
        methods: {
            update: function() {
                console.log(this.data)
            }
        }
    }
</script>
