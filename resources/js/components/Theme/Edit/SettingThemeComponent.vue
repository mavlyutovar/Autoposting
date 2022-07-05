<template>
    <div class="card-deck" name="setting">
        <div class="card mb-4">
            <div class="card-body">

                <h3 v-if="postCase" class="card-title">
                    Редактируем пост на {{postCase.time}}:00
                </h3>
                <h5 v-else class="card-title">Добавление постов</h5>
                <div class="row d-flex justify-content-center align-items-center mb-3">
                    <h5 class="mb-3 text-center">
                        Отредактируйте настройки поста
                    </h5>
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
                        <div class="dropdown-menu w-75 " aria-labelledby="textsMenuButton">
                            <ul class="list-group list-group-flush">
                                <li v-for="(text, index) in textCases" class="list-group-item">
                                    <a class="dropdown-item" @click="setText(text)" >{{ text.name }}</a>
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
                        <div class="dropdown-menu  w-50" aria-labelledby="audiosMenuButton">
                            <ul class="list-group list-group-flush">
                                <li v-for="(audio, index) in audioCases" class="list-group-item">
                                    <a class="dropdown-item" @click="setAudio(audio)" >{{ audio.name }}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="btn-group w-auto mb-3" role="group" aria-label="Basic example">
                            <button @click="setAudioCount(-1)" type="button" class="btn btn-secondary">-</button>
                            <button @click="setAudioCount(1)" type="button" class="btn btn-primary">{{ style.audioCount }} эл</button>
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
                        <div class="dropdown-menu  w-50" aria-labelledby="picturesMenuButton">
                            <ul class="list-group list-group-flush">
                                <li v-for="(picture, index) in pictureCases" class="list-group-item">
                                    <a class="dropdown-item" @click="setPicture(picture)" >{{ picture.name }}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="btn-group w-auto mb-3" role="group" aria-label="Basic example">
                            <button @click="setPictureCount(-1)" type="button" class="btn btn-secondary">-</button>
                            <button @click="setPictureCount(1)" type="button" class="btn btn-primary">{{  style.pictureCount }} эл</button>
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
                <div class="row d-flex justify-content-center align-items-center mb-3">
                    <div class="d-flex justify-content-center  col-lg-6">
                        <h5><b>Вставьте ссылку на источник.</b> Если указать источник, то под каждым постом будет добавлена ссылка.</h5>
                    </div>
                    <div class="col-4">
                        <input v-model="urlSource" class="form-control form-control-lg" type="text" placeholder="https://www.vk.com/thismood">
                    </div>
                </div><hr>
                <div class="row d-flex justify-content-center">
                    <button @click="savePostTime()" type="button" class="btn btn-primary col-3">Сохранить пост</button>
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
    data:function(){
        return{
            textInfo: "Выберите набор текстов",
            audioInfo: "Выберите набор аудиозаписей",
            pictureInfo: "Выберите источник изображения",
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
                audioProbability: 50,
                pictureProbability: 100,
                textSmile: false,
                textRepeat: true,
                audioRepeat: true,
            },
            style: {
                text_style_id: null,
                audio_style_id: null,
                picture_style_id: null,
                textCount: 1,
                audioCount: 1,
                pictureCount: 3,
            },
            time: 13,
            urlSource: "",
            textCases: [],
            audioCases: [],
            pictureCases: [],
            postCase: false,
            groupInfo: [],
        }
    },
    components: {
        SetThemeName,
    },
    mounted() {
        this.loadTexts();
        this.loadAudios();
        this.loadPictures();
    },
    methods: {
        savePostTime: function() {
            if(!this.postCase){
                axios.post('/add-post', {
                    setting:  this.setting,
                    style:    this.style,
                    time:  this.time,
                    weak: this.weak,
                    urlSource: this.urlSource,
                    groupInfo: this.groupInfo,
                }).then((response) => {
                    //this.posts = response.data;
                    console.log(response)
                });
            }
            this.$emit("updateTimeList")
            // this.$emit('updateTimeList', {
            //     groupid: this.groups[id].id,
            // })
            // this.groupInfo = this.groups[id].name;
        },
        loadTexts: function() {
            axios.get('/show-text').then((response) => {
                this.textCases = response.data;
            });
        },
        loadAudios: function() {
            axios.get('/show-audio').then((response) => {
                this.audioCases = response.data;
            });
        },
        loadPictures: function() {
            axios.get('/show-picture').then((response) => {
                this.pictureCases = response.data;
            });
        },
        setText(text) {
            this.textInfo = text.name;
            this.style.text_style_id = text.id;
        },
        setAudio(audio) {
            this.audioInfo = audio.name;
            this.style.audio_style_id = audio.id;
        },
        setPicture(picture) {
            this.pictureInfo = picture.name;
            this.style.picture_style_id = picture.id;
        },

        setAudioCount(val){
            if((this.style.audioCount < 5 || val < 0) && (this.style.audioCount > 1 || val > 0)){
                this.style.audioCount += val;
            }
        },
        setPictureCount(val){
            if((this.style.pictureCount < 5 || val < 0) && (this.style.pictureCount > 1 || val > 0)){
                this.style.pictureCount += val;
            }
        },
        setSettingGroup(group){ //отправляется с родительского
            this.groupInfo = group;
        },
        editPostTime(post){ //отправляется с родительского
            this.time = post.time;
            this.postCase = post;
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
        tooltipFormatter(e) {
            return `${e.value}:00`;
        },
        percentFormatter(e) {
            if (e.value === 100) {
                return "Всега";
            }
            return `${e.value}%`;
        },
    }
}
</script>
