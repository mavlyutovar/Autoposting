<template>
    <div class="card-deck">
        <div class="card mb-4">
            <div class="card-body ">
                <h5 class="card-title">Раздел постов</h5>
                <div class="mb-4">
                    <h5 class="mb-3 text-center">
                        Начните с выбора группы
                    </h5>
                    <div class="dropdown w-100">
                        <button class="btn w-100 btn-primary dropdown-toggle" type="button" id="groupsMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{groupInfo}}
                        </button>
                        <div class="dropdown-menu w-100 " aria-labelledby="groupsMenuButton">
                            <ul class="list-group list-group-flush">
                                <li v-for="(group, index) in groups" class="list-group-item">
                                    <a class="dropdown-item" @click="setGroup(index)" href="#">{{ group.name }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div><hr>
                <div class="row d-flex justify-content-center align-items-center mb-3">
                    <div class="d-flex justify-content-center  col-4">
                        <round-slider
                            v-model="setting.textProbability"
                            start-angle="315"
                            end-angle="+270"
                            line-cap="round"
                            radius="81"
                            range-color="#3490dc"
                            :tooltip-format="percentFormatter"
                        ></round-slider><br>
                    </div>
                    <div class="col-lg-6">
                        <button class="btn w-100 btn-success dropdown-toggle mb-3" type="button" id="textsMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{textInfo}}
                        </button>
                        <div class="dropdown-menu w-100 " aria-labelledby="textsMenuButton">
                            <ul class="list-group list-group-flush">
                                <li v-for="(group, index) in groups" class="list-group-item">
                                    <a class="dropdown-item" @click="addTheme(index)" href="#">{{ group.name }}</a>
                                </li>
                            </ul>
                        </div>
                        <h5>Настройте вероятность добавления случайного <b>текста</b> в пост. Отредактируйте настройки.</h5>
                        <div class="ml-3 form-check form-switch">
                            <input class="form-check-input cursor-pointer" type="checkbox" :id='1' @click="setTextRepeat()">
                            <label class="form-check-label" :for='1'> Отключить повтор текста.</label>
                        </div>
                        <div class="ml-3 mb-3 form-check form-switch">
                            <input class="form-check-input cursor-pointer" type="checkbox" :id='1' @click="setTextSmile()">
                            <label class="form-check-label" :for='1'> Добавлять случайный смайлик в конце.</label>
                        </div>
                    </div><hr>
                    <div class="d-flex justify-content-center  col-4">
                        <round-slider
                            v-model="setting.audioProbability"
                            start-angle="315"
                            end-angle="+270"
                            line-cap="round"
                            radius="81"
                            range-color="#3490dc"
                            :tooltip-format="percentFormatter"
                        ></round-slider>
                    </div>
                    <div class="col-lg-6">
                        <button class="btn w-75 btn-success dropdown-toggle mb-3" type="button" id="audiosMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{audioInfo}}
                        </button>
                        <div class="dropdown-menu  " aria-labelledby="audiosMenuButton">
                            <ul class="list-group list-group-flush">
                                <li v-for="(group, index) in groups" class="list-group-item">
                                    <a class="dropdown-item" @click="addTheme(index)" href="#">{{ group.name }}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="btn-group w-auto mb-3" role="group" aria-label="Basic example">
                            <button @click="setAudioCount(-1)" type="button" class="btn btn-secondary">-</button>
                            <button @click="setAudioCount(1)" type="button" class="btn btn-primary">{{  audios.count }} эл</button>
                            <button @click="setAudioCount(1)" type="button" class="btn btn-secondary">+</button>
                        </div>
                        <h5>Настройте вероятность добавления случайной <b>аудиозаписи</b> в пост. Отредактируйте настройки. <b>Нажмите</b> на + для добавления нескольких аудиозаписей.</h5>
                        <div class="ml-3 form-check form-switch">
                            <input class="form-check-input cursor-pointer" type="checkbox" :id='1' @click="setAudioRepeat()">
                            <label class="form-check-label" :for='1'> Отключить повтор аудиозаписей.</label>
                        </div>
                    </div><hr>
                    <div class="d-flex justify-content-center  col-4">
                        <round-slider
                            v-model="setting.pictureProbability"
                            start-angle="315"
                            value="sd"
                            end-angle="+270"
                            line-cap="round"
                            radius="81"
                            range-color="#3490dc"
                            :tooltip-format="percentFormatter"
                        ></round-slider>
                    </div>
                    <div class="col-lg-6">
                        <button class="btn w-75 btn-success dropdown-toggle mb-3" type="button" id="picturesMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{pictureInfo}}
                        </button>
                        <div class="dropdown-menu  " aria-labelledby="picturesMenuButton">
                            <ul class="list-group list-group-flush">
                                <li v-for="(group, index) in groups" class="list-group-item">
                                    <a class="dropdown-item" @click="addTheme(index)" href="#">{{ group.name }}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="btn-group w-auto mb-3" role="group" aria-label="Basic example">
                            <button @click="setPictureCount(-1)" type="button" class="btn btn-secondary">-</button>
                            <button @click="setPictureCount(1)" type="button" class="btn btn-primary">{{  pictures.count }} эл</button>
                            <button @click="setPictureCount(1)" type="button" class="btn btn-secondary">+</button>
                        </div>
                        <h5>Найстройте вероятность добавления случайного <b>изображения</b> в пост. Отредактируйте настройки. <b>Нажмите</b> на + для добавления нескольких картинок.</h5>
                    </div>
                </div><hr>
                <div class="row d-flex justify-content-center align-items-center">
                    <div class="d-flex justify-content-center  col-4">
                        <round-slider
                            v-model="time"
                            start-angle="315"
                            value="sd"
                            max="23"
                            end-angle="+270"
                            line-cap="round"
                            radius="81"
                            range-color="#3490dc"
                            :tooltip-format="tooltipFormatter"
                        ></round-slider>
                    </div>
                    <div class="col-lg-6">
                        <h5><b>Настройте время публикации.</b> Посты будут выходить с задержкой не больше 30 минут, их можно будет найти в отложенных записях. Задержка каждый раз со случайным значением.</h5>
                    </div>
                </div><hr>
                <div class="row d-flex justify-content-center align-items-center mb-3">
                    <div class="d-flex justify-content-center  col-4">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button @click="setWeak(0)" type="button" v-bind:class="{'btn btn-secondary': !weak.Mon,  'btn btn-success': weak.Mon}">ПН</button>
                            <button @click="setWeak(1)" type="button" v-bind:class="{'btn btn-secondary': !weak.Tue,  'btn btn-success': weak.Tue}">ВТ</button>
                            <button @click="setWeak(2)" type="button" v-bind:class="{'btn btn-secondary': !weak.Wed,  'btn btn-success': weak.Wed}">СР</button>
                            <button @click="setWeak(3)" type="button" v-bind:class="{'btn btn-secondary': !weak.Thu,  'btn btn-success': weak.Thu}">ЧТ</button>
                            <button @click="setWeak(4)" type="button" v-bind:class="{'btn btn-secondary': !weak.Fri,  'btn btn-success': weak.Fri}">ПТ</button>
                            <button @click="setWeak(5)" type="button" v-bind:class="{'btn btn-secondary': !weak.Sat,  'btn btn-success': weak.Sat}">СБ</button>
                            <button @click="setWeak(6)" type="button" v-bind:class="{'btn btn-secondary': !weak.Sun,  'btn btn-success': weak.Sun}">ВС</button>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h5><b>Выберите переодичность.</b> Выходы постов зависят от дня недели. Оставьте кнопку серой, если хотите пропустить день.</h5>
                    </div>
                </div><hr>
                <div class="row d-flex justify-content-center">
                    <button @click="savePostTime()" type="button" class="btn btn-primary col-3">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from 'vue'
import VueCircleSlider from 'vue-round-slider'

import SetThemeName from './SetNameThemeComponent'

    export default {
        props: ['postTime'],
        data:function(){
            return{
                weak: {
                    Mon: true,
                    Tue: true,
                    Wed: true,
                    Thu: true,
                    Fri: true,
                    Sat: true,
                    Sun: true,
                },
                setting: {
                    textProbability: 50,
                    pictureProbability: 100,
                    audioProbability: 50,
                    textSmile: false,
                    textRepeat: true,
                    audioRepeat: true,
                },
                texts: {
                    textId: null,
                    count: 1,
                },
                audios: {
                    audioId: null,
                    count: 1,
                },
                pictures: {
                    pictureId: null,
                    count: 3,
                },
                themeId: -1,
                saveTheme: -1,
                groupInfo: "Выберите группу",
                textInfo: "Выберите набор текстов",
                audioInfo: "Выберите набор аудиозаписей",
                pictureInfo: "Выберите источник изображения",
                time: 13,
                groups: [],
                themes: [],
            }
        },
        components: {
            SetThemeName,
        },
        mounted() {
            this.update();
            this.getAllGroup();
        },
        methods: {
            setAudioCount(val){
                if((this.audios.count < 5 || val < 0) && (this.audios.count > 1 || val > 0)){
                    this.audios.count += val;
                }
            },
            setPictureCount(val){
                if((this.pictures.count < 5 || val < 0) && (this.pictures.count > 1 || val > 0)){
                    this.pictures.count += val;
                }
            },
            setGroup(id){
                this.$emit('updateTimeList', {
                    groupid: this.groups[id].id,
                })
                console.log(this.groups[id].name)
            },
            setTextSmile: function() {
                this.setting.textSmile = !this.setting.textSmile;
            },
            setTextRepeat: function() {
                this.setting.textRepeat = !this.setting.textRepeat;
            },
            setAudioRepeat: function() {
                this.setting.audioRepeat = !this.setting.audioRepeat;
            },
            update: function() {
                axios.post('/show-all-post').then((response) => {
                    this.posts = response.data;
                });
            },
            setWeak(id){
                if(id === 0) {
                    this.weak.Mon = !this.weak.Mon;
                }
                if(id === 1) {
                    this.weak.Tue = !this.weak.Tue;
                }
                if(id === 2) {
                    this.weak.Wed = !this.weak.Wed;
                }
                if(id === 3) {
                    this.weak.Thu = !this.weak.Thu;
                }
                if(id === 4) {
                    this.weak.Fri = !this.weak.Fri;
                }
                if(id === 5) {
                    this.weak.Sat = !this.weak.Sat;
                }
                if(id === 6) {
                    this.weak.Sun = !this.weak.Sun;
                }
            },
            getAllGroup: function() {
                axios.post('/show-all-group').then((response) => {
                    this.groups = response.data;
                });

            },
            tooltipFormatter(e) {
                return `${e.value}:00`;
            },
            percentFormatter(e) {
                if (e.value === 100) {
                    return "Всега";
                }
                return `${e.value}%`;
            },
            savePostTime: function() {
                let groupList = [];
                for (let i = 0; i < this.groups.length; i++) {
                    if(this.groups[i].info === true){
                        groupList[i] = this.groups[i].id;
                    }
                }
                console.log(groupList)
                axios.post('/add-post', {
                    groups:  groupList,
                    themeId:    this.saveTheme,
                    time:  this.time,
                    weak: this.weak,
                }).then((response) => {
                    this.posts = response.data;
                    this.$emit("update")
                    console.log(this.posts)
                });
            },
        }
    }
</script>
