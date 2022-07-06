<template>
    <div>
        <div class="card-deck mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Запланированные посты</h5>
                    <div class="mb-4">
                        <h5 class="mb-3 text-center">
                            Начните с выбора группы
                        </h5>
                        <div class="dropdown w-100">
                            <button class="btn w-100 btn-primary dropdown-toggle" type="button" id="groupsMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{groupInfo.name}}
                            </button>
                            <div class="dropdown-menu w-100 " aria-labelledby="groupsMenuButton">
                                <ul class="list-group list-group-flush">
                                    <li v-for="(group, index) in groups" class="list-group-item">
                                        <a class="dropdown-item" @click="setGroup(group)">{{ group.name }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div><hr>
                    <ul class="list-group">
                        <li v-for="(post, index) in posts" class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-dark mr-2">{{index+1}}</span>
                            <div class="p-2 truncate col-4">
                                <h2 v-bind:class="{'text-dark': post.status === 'run',  'text-success': post.status === 'pause'}">
                                    <button @click="changeStatus(post.id, 'run')" type="button" v-if="post.status === 'pause'" class="btn btn-dark">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-play-fill" viewBox="0 0 16 16">
                                            <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z"/>
                                        </svg>
                                    </button>
                                    <button @click="changeStatus(post.id, 'pause')" type="button" v-if="post.status === 'run'" class="btn btn-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pause-fill" viewBox="0 0 16 16">
                                            <path d="M5.5 3.5A1.5 1.5 0 0 1 7 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5zm5 0A1.5 1.5 0 0 1 12 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5z"/>
                                        </svg>
                                    </button>
                                    {{ post.time }}:00
                                </h2>
                                Набор текстов: <b class="text-primary">{{ post.textCaseName }}</b> <b v-if="post.settings.textSmile" class="text-dark">(🎲)</b><br>
                                Набор аудиозаписей: <b class="text-dark">{{ post.audioCaseName }} ({{post.style.audioCount}} эл)</b><br>
                                Набор изображений: <b class="text-dark">{{ post.pictureCaseName }} ({{post.style.pictureCount}} эл)</b><br>
                            </div>
                            <div class="p-2 truncate col-3">
                                Вероятность текста: <b class="text-dark">{{ post.settings.textProbability }}%</b><br>
                                Вероятность аудиозаписей: <b class="text-dark">{{ post.settings.audioProbability }}%</b>
                                Вероятность изображений: <b class="text-dark">{{ post.settings.pictureProbability }}%</b><br>
                                <b v-if="post.settings.textRepeat" class="text-primary">Повтор текста</b><br>
                                <b v-if="post.settings.audioRepeat" class="text-primary">Повтор аудиозаписей</b><br>
                                <b v-if="post.settings.pictureRepeat" class="text-primary">Повтор изображений</b><br>
                            </div>
                            <div class="mr-auto p-2">

                                Источник: <b class="text-primary" :title="post.url_source"><a :href="post.url_source">{{ post.url_source }}</a></b><br>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button @click="del(post.id)" type="button" class="btn btn-warning" href="#setting">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                        </svg>
                                    </button>
                                    <button type="button" :class="['btn', {'btn-secondary': !post.weak.Mon,  'btn-success': post.weak.Mon}]" disabled>ПН</button>
                                    <button type="button" :class="['btn', {'btn-secondary': !post.weak.Tue,  'btn btn-success': post.weak.Tue}]" disabled>ВТ</button>
                                    <button type="button" :class="['btn', {'btn-secondary': !post.weak.Wed,  'btn btn-success': post.weak.Wed}]" disabled>СР</button>
                                    <button type="button" :class="['btn', {'btn-secondary': !post.weak.Thu,  'btn btn-success': post.weak.Thu}]" disabled>ЧТ</button>
                                    <button type="button" :class="['btn', {'btn-secondary': !post.weak.Fri,  'btn btn-success': post.weak.Fri}]" disabled>ПТ</button>
                                    <button type="button" :class="['btn', {'btn-secondary': !post.weak.Sat,  'btn btn-success': post.weak.Sat}]" disabled>СБ</button>
                                    <button type="button" :class="['btn', {'btn-secondary': !post.weak.Sun,  'btn btn-success': post.weak.Sun}]" disabled>ВС</button>
                                    <button @click="send(post.id)" type="button" class="btn btn-primary mr-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                                            <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z"/>
                                        </svg>
                                    </button>

                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card-footer" v-if="!groupInfo.id">
                    <footer-menu></footer-menu>
                </div>
            </div>
        </div>

        <SettingThemePost v-show="groupInfo.id" @updateTimeList="setPostsTime" ref="settingComponent"></SettingThemePost>



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
import FooterMenu from "./FooterMenuComponent";
import SettingThemePost from "./Theme/Edit/SettingThemeComponent";


export default {
    data:function(){
        return {
            posts: [],
            editPost: -1,
            groupInfo: {
                name: "Выберите группу",
                id:false,
            },
            groups:[],
        }
    },
    components: {
        FooterMenu,
        SettingThemePost
    },
    mounted() {
        this.getAllGroup();
    },
    methods: {
        getAllGroup: function() {
            axios.post('/show-all-group').then((response) => {
                this.groups = response.data;
            });

        },
        setGroup(group){
            this.groupInfo = group;
            this.getAllPostTime(group)
            this.$refs.settingComponent.setSettingGroup(group);
        },
        setPostsTime(item) {
            console.log(item.posts)
            this.posts = item.posts;
        },
        getAllPostTime(group) {
            group = this.groupInfo
            if(group.id) {
                axios.post('/show-all-post/'+group.id).then((response) => {
                    this.posts = response.data;
                    console.log(this.posts)
                });
            }
        },

        changeStatus: function(id, newStatus) {
            axios.post('/change-status-post/'+id, {
                status: newStatus,
            }).then((response) => {
                this.posts = response.data
            });
        },
        del: function(id) {
            axios.post('/delete-post/'+id).then((response) => {
                this.posts = response.data
            });
        },
        edit: function(post) {
            this.editPost = post;
            this.$refs.settingComponent.editPostTime(post);
        },
        send: function(id) {
            axios.post('/send-post/'+id).then((response) => {
            });
        }
    }
}
</script>
