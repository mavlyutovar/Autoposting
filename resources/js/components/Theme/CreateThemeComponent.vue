<template>
    <div>
        <div class="d-flex justify-content-center align-items-center mb-3">
<!--            <div class="d-flex justify-content-center  col-6">-->
<!--                <Progress ref="updateProgress"></Progress>-->
<!--            </div>-->
            <div class="d-flex justify-content-center align-items-center">
                <button @click="setTextTheme()" type="button"  v-bind:class="{'btn btn-secondary': !textShow,  'btn btn-primary active': textShow, 'btn m-1':true}">Текст</button>
                <button @click="setAudioTheme()" type="button" v-bind:class="{'btn btn-secondary': !audioShow,  'btn btn-primary active': audioShow, 'btn m-1':true}">Аудио</button>
                <button @click="setPictureTheme()" type="button" v-bind:class="{'btn btn-secondary': !pictureShow,  'btn btn-primary active': pictureShow, 'btn m-1':true}">Изображения</button>
            </div>
        </div>

        <Component @updPercent='getPercent' :is="currentComponent"></Component>
    </div>
</template>

<script>

import Progress from './Edit/ProgressTheme'
import SetTextTheme from './Edit/TextThemeComponent'

Vue.component('set-text-theme', require('./Edit/TextThemeComponent').default);
Vue.component('set-audio-theme', require('./Edit/AudioThemeComponent').default);
Vue.component('set-picture-theme', require('./Edit/PictureThemeComponent').default);

export default {
    data:function(){
        return{
            currentComponent: "set-text-theme",
            percent: 50,
            textShow: true,
            audioShow: false,
            pictureShow: false,
            settingShow: false,
        }
    },
    mounted() {
        this.getPercent();
    },
    components: {
        Progress,
        SetTextTheme
    },
    methods: {
        setTextTheme: function() {
            this.currentComponent = "set-text-theme";
            this.textShow = true;
            this.audioShow = false;
            this.pictureShow = false;
        },
        setAudioTheme: function() {
            this.currentComponent = "set-audio-theme";
            this.textShow = false;
            this.audioShow = true;
            this.pictureShow = false;
        },
        setPictureTheme: function() {
            this.currentComponent = "set-picture-theme";
            this.textShow = false;
            this.audioShow = false;
            this.pictureShow = true;
        },
        setSettingsTheme: function() {
            this.currentComponent = "set-setting-theme";
            this.textShow = false;
            this.audioShow = false;
            this.pictureShow = false;
        },
        getPercent: function() {
            /*this.percent = 0;
            axios.post('/how-ready', {
                text: this.percent,
                //description: this.description
            }).then((response) => {
                this.percent = response.data;
                this.saveButton = true;
                if(this.percent.data >= 62) {
                    this.saveButton = false;
                }
                this.$refs.updateProgress.update();
            });*/

        }
    }
}
</script>
