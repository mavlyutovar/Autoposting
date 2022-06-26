<template>
    <div>
        <div class="card-deck">
            <div class="card mb-4">
                <div class="card-body ">
                    <h5 class="card-title">Раздел постов</h5>
                    <div class="mb-4">
                        <h5 class="mb-3 text-center">
                            Начните с выбора стиля
                        </h5>
                        <div class="dropdown w-100">
                            <button class="btn w-100 btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{themeInfo}}
                            </button>
                            <div class="dropdown-menu w-100 " aria-labelledby="dropdownMenuButton">
                                <ul class="list-group list-group-flush">
                                    <li v-for="(theme, index) in themes" class="list-group-item">
                                        <a class="dropdown-item" @click="addTheme(index)" href="#">{{ theme.name }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="mt-2" v-if="themeId >= 0">
                            <p class="card-text">Выбранный стиль: <b>{{themes[themeId].name}}</b> </p>
                            <p v-if="themes[themeId].description" class="card-text">Описание: {themes[themeId].description}</p>
                        </div>

                    </div>
                    <div class="mb-4">
                        <h5 class="mb-3 text-center">
                            Далее, выберите группы
                        </h5>
                        <p class="card-text">Выберите одну или несколько групп к которым будет применяться стиль. Учтите, что для каждой группы будет создана своя запись.</p>
                        <ul class="list-group">
                            <li v-for="(group, index) in groups" class="list-group-item d-flex justify-content-start align-items-center">
                                <div class="ml-2 form-check form-switch">
                                    <input class="form-check-input cursor-pointer" type="checkbox" :id='group.id' @click="addGroup(index)">
                                    <label class="form-check-label" :for='group.id'>{{ group.name }} {{ group.id }}</label>
                                </div>
                            </li>
                        </ul>
                    </div>
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

                    </div>

                    <div class="row d-flex justify-content-center align-items-center mb-3">
                        <div class="d-flex justify-content-center  col-4">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button @click="setWeak(0)" type="button" v-bind:class="{'btn btn-secondary': !weak.monday,  'btn btn-success': weak.monday}">ПН</button>
                                <button @click="setWeak(1)" type="button" v-bind:class="{'btn btn-secondary': !weak.tuesday,  'btn btn-success': weak.tuesday}">ВТ</button>
                                <button @click="setWeak(2)" type="button" v-bind:class="{'btn btn-secondary': !weak.wednesday,  'btn btn-success': weak.wednesday}">СР</button>
                                <button @click="setWeak(3)" type="button" v-bind:class="{'btn btn-secondary': !weak.thursday,  'btn btn-success': weak.thursday}">ЧТ</button>
                                <button @click="setWeak(4)" type="button" v-bind:class="{'btn btn-secondary': !weak.friday,  'btn btn-success': weak.friday}">ПТ</button>
                                <button @click="setWeak(5)" type="button" v-bind:class="{'btn btn-secondary': !weak.saturday,  'btn btn-success': weak.saturday}">СБ</button>
                                <button @click="setWeak(6)" type="button" v-bind:class="{'btn btn-secondary': !weak.sunday,  'btn btn-success': weak.sunday}">ВС</button>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h5><b>Выберите переодичность.</b> Выходы постов зависят от дня недели. Оставьте кнопку серой, если хотите пропустить день.</h5>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center">
                        <button @click="savePostTime()" type="button" class="btn btn-primary col-3">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-deck">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Запланированные посты</h5>
                    <div class="mb-4">
                        <ul class="list-group">
                            <li v-for="(post, index) in posts" class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-dark mr-2">{{index+1}}</span>
                                <div class="p-2 truncate col-7">
                                    Группа <b class="text-primary">{{ post.groupName }}</b><br>
                                    Стиль для группы <b class="text-dark">{{ post.themeName }}</b><br>
                                    Время для поста <b class="text-dark">{{ post.time }}:00</b>
                                </div>
                                <div class="mr-auto p-2">

                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button @click="setWeak(0)" type="button" v-bind:class="{'btn btn-secondary': !post.weak.monday,  'btn btn-success': post.weak.monday}" disabled>ПН</button>
                                        <button @click="setWeak(1)" type="button" v-bind:class="{'btn btn-secondary': !post.weak.tuesday,  'btn btn-success': post.weak.tuesday}" disabled>ВТ</button>
                                        <button @click="setWeak(2)" type="button" v-bind:class="{'btn btn-secondary': !post.weak.wednesday,  'btn btn-success': post.weak.wednesday}" disabled>СР</button>
                                        <button @click="setWeak(3)" type="button" v-bind:class="{'btn btn-secondary': !post.weak.thursday,  'btn btn-success': post.weak.thursday}" disabled>ЧТ</button>
                                        <button @click="setWeak(4)" type="button" v-bind:class="{'btn btn-secondary': !post.weak.friday,  'btn btn-success': post.weak.friday}" disabled>ПТ</button>
                                        <button @click="setWeak(5)" type="button" v-bind:class="{'btn btn-secondary': !post.weak.saturday,  'btn btn-success': post.weak.saturday}" disabled>СБ</button>
                                        <button @click="setWeak(6)" type="button" v-bind:class="{'btn btn-secondary': !post.weak.sunday,  'btn btn-success': post.weak.sunday}" disabled>ВС</button>
                                        <button @click="del(post.id)" type="button" class="btn btn-warning">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <button @click="send(post.id)" type="button" class="btn btn-primary mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                                        <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z"/>
                                    </svg>
                                </button>
<!--                                <button @click="del(post.id)" type="button" class="btn btn-warning">-->
<!--                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">-->
<!--                                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"></path>-->
<!--                                    </svg>-->
<!--                                </button>-->
<!--                              Удалить можно при редактировании-->
                            </li>
                        </ul>
                    </div>


                </div>
                <div class="card-footer">
                    <a class="btn btn-secondary" href="/" role="button">Назад</a>
                </div>
            </div>
        </div>


    </div>
</template>

<style>
.truncate {
    white-space: nowrap; /* Текст не переносится */
    overflow: hidden; /* Обрезаем всё за пределами блока */
    text-overflow: ellipsis; /* Добавляем многоточие */
}
</style>

<script>
import Multiselect from 'vue-multiselect'
import VueTaggableSelect from "vue-taggable-select";

export default {
    data:function(){
        return {
            weak: {
                monday: false,
                tuesday: true,
                wednesday: true,
                thursday: true,
                friday: true,
                saturday: true,
                sunday: true,
            },
            themeId: -1,
            saveTheme: -1,
            themeInfo: "Выберите стиль",
            groupInfo: null,
            posts: [],
            time: 13,
            groups: [],
            themes: [],
        }
    },
    components: {
        VueTaggableSelect
    },
    mounted() {
        this.update();
        this.getAllTheme();
        this.getAllGroup();
    },
    methods: {
        setWeak(id){
            if(id === 0) {
                this.weak.monday = !this.weak.monday;
            }
            if(id === 1) {
                this.weak.tuesday = !this.weak.tuesday;
            }
            if(id === 2) {
                this.weak.wednesday = !this.weak.wednesday;
            }
            if(id === 3) {
                this.weak.thursday = !this.weak.thursday;
            }
            if(id === 4) {
                this.weak.friday = !this.weak.friday;
            }
            if(id === 5) {
                this.weak.saturday = !this.weak.saturday;
            }
            if(id === 6) {
                this.weak.sunday = !this.weak.sunday;
            }
        },
        addGroup(id) {
            if(this.groups[id].info === true) {
                this.groups[id].info = false;
            }
            else {
                this.groups[id].info = true;
            }

        },
        addTheme(id) {
            this.themeId = id;
            this.themeInfo = this.themes[id].name;
            this.saveTheme = this.themes[id].id;
        },
        getAllTheme: function() {
            this.percent = 0;
            axios.post('/get-all-theme', {
                text: this.percent,
                //description: this.description
            }).then((response) => {
                this.themes = response.data;
            });

        },
        getAllGroup: function() {
            axios.post('/show-all-group').then((response) => {
                this.groups = response.data;
            });

        },
        tooltipFormatter(e) {
            return `${e.value}:00`;
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
                console.log(this.posts)
            });
        },
        update: function() {
            axios.post('/show-all-post').then((response) => {
                this.posts = response.data;
                console.log(response)
            });
        },
        del: function(id) {
            axios.post('/delete-post/'+id).then((response) => {
                this.posts = response.data
            });
        },
        send: function(id) {
            axios.post('/send-post/'+id).then((response) => {
            });
        }
    }
}
</script>
