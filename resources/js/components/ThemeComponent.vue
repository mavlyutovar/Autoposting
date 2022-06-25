<template>
    <div>
        <div class="card-deck">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Раздел стилей</h5>
                    <div v-if="!editProfile" class="mb-3 d-flex justify-content-end">
                        <button v-if="isShowTheme" @click="showTheme"  type="button" class="btn btn-outline-info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-list" viewBox="0 0 16 16">
                                <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                                <path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zM4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                            </svg>
                            Список стилей
                        </button>
                        <button v-if="isCreateTheme" @click="createTheme" type="button" class="btn btn-outline-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                                <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                            </svg>
                            Создать новый стиль
                        </button>
                    </div>
                    <div v-if="editProfile" class="mb-2 d-flex justify-content-end">
                        <button @click="undoEditTheme" type="button" class="btn btn-outline-dark mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"/>
                            </svg>
                            Отмена
                        </button>
                        <button @click="listTheme" type="button" class="btn btn-outline-primary mr-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-square" viewBox="0 0 16 16">
                                <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                                <path d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.235.235 0 0 1 .02-.022z"/>
                            </svg>
                            Сохранить стиль
                        </button>
                    </div>
                    <Component @updButton='editTheme' :is="currentComponent"></Component>
                </div>
                <div class="card-footer">
                    <a class="btn btn-dark" href="/" role="button">Назад</a>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

Vue.component('edit-theme-component', require('./Theme/EditThemeComponent.vue').default);
Vue.component('create-theme-component', require('./Theme/CreateThemeComponent.vue').default);

export default {
    data:function(){
        return{
            currentComponent: "edit-theme-component",
            editProfile: false,
            isCreateTheme: true,
            isShowTheme: false,
        }
    },
    methods: {
        createTheme() {
            this.currentComponent = "create-theme-component";
            this.isCreateTheme = !this.isCreateTheme;
            this.isShowTheme = !this.isShowTheme;
        },
        showTheme() {
            this.currentComponent = "edit-theme-component";
            this.isCreateTheme = !this.isCreateTheme;
            this.isShowTheme = !this.isShowTheme;
        },
        editTheme() {
            this.currentComponent = "create-theme-component";
            this.editProfile = !this.editProfile;
        },
        listTheme(){
            this.currentComponent = "edit-theme-component";
            this.editProfile = !this.editProfile;
        },
        undoEditTheme: function() {
            axios.post('/undo-edit-theme', {
                text: 0,
                //description: this.description
            }).then((response) => {
                this.setting = response.data;
                console.log(this.theme)
            });
            this.currentComponent = "edit-theme-component";
            this.editProfile = !this.editProfile;
        },
    }
}
</script>

