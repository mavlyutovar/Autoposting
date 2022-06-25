<template>
            <div class="row">
                <div class="">
                    <div class="row d-flex justify-content-center mb-3 mt-3">

                        <SetThemeName></SetThemeName>
                    </div>
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-9 mb-3 text-center">
                            <h3>Настройки вероятности</h3>
                            <h5>Настройки вероятности помогают сделать контент разнообразнее.<br>Алгоритм решает добавлять или не добавлять определенный контент на моменте постинга.</h5>
                            <h5>Рекомендуем хотя-бы одному компоненту выставить значение <b>100%</b></h5>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="d-flex justify-content-center  col-3">
                            <round-slider
                                v-model="setting.textProbability+'%'"
                                start-angle="315"
                                end-angle="+270"
                                line-cap="round"
                                radius="81"
                                range-color="#3490dc"
                                :tooltip-format="tooltipFormatter"
                            ></round-slider>
                        </div>
                        <div class="col-lg-6">
                            <h5>Вероятность добавления случайного <b>текста</b> в пост</h5>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="d-flex justify-content-center  col-3">
                            <round-slider
                                v-model="setting.audioProbability"
                                start-angle="315"
                                end-angle="+270"
                                line-cap="round"
                                radius="81"
                                range-color="#3490dc"
                                :tooltip-format="tooltipFormatter"
                            ></round-slider>
                        </div>
                        <div class="col-lg-6">
                            <h5>Вероятность добавления случайной <b>аудиозаписи</b> в пост</h5>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="d-flex justify-content-center  col-3">
                            <round-slider
                                v-model="setting.pictureProbability"
                                start-angle="315"
                                value="sd"
                                end-angle="+270"
                                line-cap="round"
                                radius="81"
                                range-color="#3490dc"
                                :tooltip-format="tooltipFormatter"
                            ></round-slider>
                        </div>
                        <div class="col-lg-6">
                            <h5>Вероятность добавления случайного <b>изображения</b> в пост</h5>
                        </div>

                    </div>
                    <div class="row d-flex justify-content-center">
                        <button @click="setSettingTheme()" type="button" class="btn btn-primary col-3">Сохранить в список стилей</button>
                    </div>
                </div>
            </div>
</template>

<script>
import Vue from 'vue'
import VueCircleSlider from 'vue-round-slider'

import SetThemeName from './SetNameThemeComponent'

    export default {
        data:function(){
            return{
                setting: {
                    textProbability: 50,
                    pictureProbability: 50,
                    audioProbability: 50,
                },
            }
        },
        components: {
            SetThemeName,
        },
        mounted() {
            this.update();
        },
        methods: {
            tooltipFormatter(e) {
                if (e.value === 100) {
                    return "Всега";
                }
                return `${e.value}%`;
            },
            setSettingTheme: function() {
                axios.post('/set-setting-theme', {
                    setting: this.setting,
                    //description: this.description
                }).then((response) => {
                    this.theme = response.data;
                    location.reload();
                });
            },
            update: function() {
                axios.post('/get-setting-theme', {
                    text: 0,
                    //description: this.description
                }).then((response) => {
                    this.setting = response.data;
                    console.log(this.theme)
                });

            },
        }
    }
</script>
