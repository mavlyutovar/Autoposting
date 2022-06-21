<template>
    <div>

        <div class="d-flex justify-content-between mb-3">
            <div class="d-flex justify-content-start">
                <h3>{{ theme.name }}</h3>
            </div>
            <div class="d-flex justify-content-end">
                <button @click="setTextTheme()" type="button" class="btn btn-secondary m-1">Текст</button>
                <button @click="setPictureTheme()" type="button" class="btn btn-secondary m-1">Картинки</button>
                <button @click="setAudioTheme()" type="button" class="btn btn-secondary m-1">Аудио</button>
                <button @click="setSettingsTheme()" type="button" class="btn btn-info m-1">Запланировать</button>
            </div>
        </div>
        <Progress></Progress>

        <Component :is="currentComponent" :data="theme"></Component>
    </div>
</template>

<script>

import Progress from './Edit/ProgressCreateTheme'
import SetTextTheme from './Edit/TextThemeComponent'
import SetAudioTheme from './Edit/AudioThemeComponent'
import SetPictureTheme from './Edit/PictureThemeComponent'
import SetSettingTheme from './Edit/SettingThemeComponent'

Vue.component('set-text-theme', require('./Edit/TextThemeComponent').default);
Vue.component('set-audio-theme', require('./Edit/AudioThemeComponent').default);
Vue.component('set-picture-theme', require('./Edit/PictureThemeComponent').default);
Vue.component('set-setting-theme', require('./Edit/SettingThemeComponent').default);

export default {
    data:function(){
        return{
            theme: {
                text: []
            },
            is_refresh: false,
            id: 0,
            textAdd: "",
            params: [],
            currentComponent: "set-text-theme"
        }
    },
    mounted() {
        this.update();
    },
    components: {
        Progress,
        SetTextTheme
    },
    methods: {
        update: function() {
            axios.post('/get-theme', {
                text: 0,
                //description: this.description
            }).then((response) => {
                this.theme = response.data;
            });

        },
        setTextTheme: function() {
            this.currentComponent = "set-text-theme";
        },
        setAudioTheme: function() {
            this.currentComponent = "set-audio-theme";
        },
        setPictureTheme: function() {
            this.currentComponent = "set-picture-theme";
        },
        setSettingsTheme: function() {
            this.currentComponent = "set-setting-theme";
        }
    }
}
</script>
