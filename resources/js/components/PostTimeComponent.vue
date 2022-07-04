<template>
    <div>
        <SettingThemePost :postTime="editPost" @updateTimeList="update"></SettingThemePost>
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
                                    Время для поста <b class="text-dark">{{ post.time }}:00</b><br>
                                </div>
                                <div class="mr-auto p-2">

                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" v-bind:class="{'btn btn-secondary': !post.weak.Mon,  'btn btn-success': post.weak.Mon}" disabled>ПН</button>
                                        <button type="button" v-bind:class="{'btn btn-secondary': !post.weak.Tue,  'btn btn-success': post.weak.Tue}" disabled>ВТ</button>
                                        <button type="button" v-bind:class="{'btn btn-secondary': !post.weak.Wed,  'btn btn-success': post.weak.Wed}" disabled>СР</button>
                                        <button type="button" v-bind:class="{'btn btn-secondary': !post.weak.Thu,  'btn btn-success': post.weak.Thu}" disabled>ЧТ</button>
                                        <button type="button" v-bind:class="{'btn btn-secondary': !post.weak.Fri,  'btn btn-success': post.weak.Fri}" disabled>ПТ</button>
                                        <button type="button" v-bind:class="{'btn btn-secondary': !post.weak.Sat,  'btn btn-success': post.weak.Sat}" disabled>СБ</button>
                                        <button type="button" v-bind:class="{'btn btn-secondary': !post.weak.Sun,  'btn btn-success': post.weak.Sun}" disabled>ВС</button>
                                        <button @click="edit(index)" type="button" class="btn btn-warning">
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
                    <footer-menu></footer-menu>
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
import FooterMenu from "./FooterMenuComponent";
import SettingThemePost from "./Theme/Edit/SettingThemeComponent";


export default {
    data:function(){
        return {
            posts: [],
            editPost: -1,
        }
    },
    components: {
        FooterMenu,
        SettingThemePost
    },
    mounted() {
        this.update();
    },
    methods: {
        update(data = false) {
            if(data.groupid) {
                console.log("YES")
            }
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
        edit: function(id) {
            this.editPost = this.posts[id].id;
        },
        send: function(id) {
            axios.post('/send-post/'+id).then((response) => {
            });
        }
    }
}
</script>
