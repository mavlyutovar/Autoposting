<template>
    <div>
        <div v-if="isShowModal">
            <div id="exampleModalCenter" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" style="padding-right: 17px; display: block; background: #0f1c1338">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">{{theme.name}}</h5>
                            <button type="button" class="close" @click="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="card mb-4" v-if="theme.url_audio_board.audioId">
                                <div class="card-header">Количество треков: {{theme.url_audio_board.audioId.length}} шт.</div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li v-for="(item, index) in theme.url_audio_board.audioId" class="list-group-item d-flex justify-content-between align-items-center">
                                            <span class="text-dark">{{index+1}}</span>
                                            <span class="mr-auto p-2">{{ item }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card mb-4" v-if="theme.url_picture_board">
                                <div class="card-header">Источник изображений</div>
                                <div class="card-body">
                                    {{theme.url_picture_board}}
                                </div>
                            </div>
                            <div class="card mb-4" v-if="theme.text.text">
                                <div class="card-header">Количество текста: {{theme.url_audio_board.audioId.length}} шт.</div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li v-for="(item, index) in theme.text.text" class="list-group-item d-flex justify-content-between align-items-center">
                                            <span class="text-dark">{{index+1}}</span>
                                            <span class="mr-auto p-2">{{ item }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" @click="close">Ок</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import Vue from 'vue'
import VueCircleSlider from 'vue-round-slider'

export default {
    data:function(){
        return {
            isShowModal: false,
            theme: {
                name: "Стиль",
                url_audio_board: {
                    audioId: [],
                }
            },
        }
    },
    methods: {
        update: function(id) {
            axios.post('/show-theme/'+id).then((response) => {
                this.theme = response.data;
                console.log(this.theme.url_audio_board.audioId);
                this.isShowModal = true;
            });

        },
        close: function () {
            this.isShowModal = !this.isShowModal;
        }
    }
}
</script>
