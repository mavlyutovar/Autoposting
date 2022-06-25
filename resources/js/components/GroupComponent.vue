<template>
    <div>
        <div class="card-deck">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Раздел групп</h5>
                    <div class="row">

                        <div class="mt-3 mb-3">
                            <h6 class=" text-center">
                                На данный момент поддерживаются только группы Вконтакте.
                            </h6>
                        </div>
                        <div class="col-7 col-lg-7">
                            <ul class="list-group">
                                <li v-for="(group, index) in groups" class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="text-dark">{{index+1}}</span>
                                    <span class="mr-auto p-2">{{ group.name }}</span>
                                    <button @click="del(group.id)" type="button" class="btn btn-warning">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"></path>
                                        </svg>
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="col-5 col-lg-5">
                            <div class="d-flex">
                                <div class="card mb-2">
                                    <div class="card-header">Добавить группу</div>
                                    <div class="card-body">
                                        <h6 class="mb-2">Название группы. </h6>
                                        <input v-model="groupName" class="form-control form-control-lg" type="text">
                                    </div>
                                    <div class="card-body">
                                        <h6 class="mb-2">Уникальный номер группы Вконтакте. Пример: 195291116</h6>
                                        <input v-model="groupId" class="form-control form-control-lg" type="number">
                                    </div>
                                    <div class="card-body">
                                        <h6 class="mb-2">Описание группы. </h6>
                                        <textarea v-model="groupInfo" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                        <div class="d-flex justify-content-around align-items-center mt-3">
                                            <button @click="add()" data-toggle="tooltip" data-placement="left" title="Добавьте несколько сообщений одной тематики" type="submit" class="justify-content-end btn btn-success">Добавить</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a class="btn btn-secondary" href="/" role="button">Назад</a>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    data:function(){
        return{
            groupName: null,
            groupId: null,
            groupInfo: null,
            groups: [],
        }
    },
    mounted() {
        this.update();
    },
    methods: {
        add: function() {
            axios.post('/add-group', {
                groupName:  this.groupName,
                groupId:    this.groupId,
                groupInfo:  this.groupInfo,
            }).then((response) => {
                this.groups = response.data;
                this.groupName = null;
                this.groupId = null;
                this.groupInfo = null;
                console.log(this.groups)
            });
        },
        update: function() {
            this.is_refresh = true;
            axios.post('/show-all-group').then((response) => {
                this.groups = response.data;
            });
        },
        del: function(id) {
            axios.post('/delete-group/'+id).then((response) => {
                this.groups = response.data
            });
        }
    }
}
</script>
