<template>
    <div class="row">
        <div class="col-7 col-lg-7">
            <div class="card mb-4" style="margin: auto;">
                <div class="card-header">Добавление набора текстов ({{listTexts.length}} эл)</div>
                <div class="card-body">
                    <input v-model="texts.name" class="form-control form-control-lg mb-3" type="text" placeholder="Обобщенное название набора">
                    <h6 class="mb-3">Перед сохранением набора добавьте не меньше 31 сообщения.</h6>
                    <div class="d-flex justify-content-end align-items-center mt-3 mb-3">
                        <button @click="saveCase()" data-toggle="tooltip" data-placement="left" title="Добавьте ссылку" type="submit" class="justify-content-end btn btn-success">Сохранить набор</button>
                    </div>
                    <h6 class="mb-3">Напишите сообщение и нажмите Добавить. <a href="https://getemoji.com/">Поддержиаются смайлы</a></h6>
                    <textarea v-model="textAdd" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                    <div class="d-flex justify-content-around align-items-center mt-3 mb-3">
                        <button @click="add()" data-toggle="tooltip" data-placement="left" title="Добавьте несколько сообщений одной тематики" type="submit" class="justify-content-end btn btn-primary">Добавить сообщение</button>
                    </div>

                    <li v-for="(item, index) in listTexts" class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="mr-auto p-2">{{ item }}</span>
                        <button @click="del(index)" type="button" class="btn btn-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"></path>
                            </svg>
                        </button>
                    </li>
                </div>
            </div>
        </div>
        <div class="col-5 col-lg-5">
            <div class="card mb-4" style="margin: auto;">
                <div class="card-header">Наборы текстов</div>
                <div class="card-body">
                    <ul class="list-group">
                        <li v-for="(item, index) in listCases" class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-dark">{{index+1}}</span>
                            <span class="mr-auto p-2">{{ item.name }} ({{ Object.keys(item.items).length }})</span>
                            <button @click="editCase(index, item.id)" type="button" class="btn btn-success mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                </svg>
                            </button>
                            <button @click="delCase(item.id)" type="button" class="btn btn-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                    <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"></path>
                                </svg>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    data:function(){
        return{
            texts: {
                name: "",
                is: "new",
                items: [],
            },
            defaultTexts: {
                name: "",
                is: "new",
                items: [],
            },
            cases: [],
            textAdd: "",
            textEditId: null,
            save: true,
        }
    },
    mounted() {
        this.update();
    },
    computed: {
        listTexts() {
            return this.texts.items;
        },
        listCases() {
            return this.cases;
        }
    },
    methods: {
        del: function(id) {
            this.texts.items.splice(id, 1);
            this.save = false;
        },
        add: function() {
            if(this.texts.items.length <= 100 && this.textAdd) {
                this.texts.items.unshift(this.textAdd);
                this.textAdd = "";
                this.save = false;
            }
        },
        delCase: function(id) {
            this.save = false;
            console.log(id);
            axios.post('/del-text/'+id).then((response) => {
                this.cases = response.data;
                console.log(response);
            });
        },
        editCase: function(id) {
            this.texts = {
                name: "",
                is: "new",
                items: [],
            };
            let oneCase = this.cases[id];
            this.texts.name = oneCase.name;
            this.texts.is = "edit";
            this.textEditId = oneCase.id;
            Object.keys(oneCase.items).forEach(key => {
                this.textAdd = oneCase.items[key];
                this.add();
            });
            this.save = false;
        },
        saveCase: function() {
            this.updatePercent();
            console.log(this.textEditId)
            if(!this.save) {
                this.save = true;
                axios.post('/save-text', {
                    texts: this.texts,
                    id: this.textEditId,
                }).then((response) => {
                    this.cases = [];
                    this.texts = {
                        name: "",
                        is: "new",
                        items: [],
                    };
                    this.cases = response.data;
                });
            }
        },
        update: function() {
            axios.get('/show-text').then((response) => {
                this.cases = response.data;
                console.log(this.cases)
            });
        },
        updatePercent() {
            this.$emit('updPercent', {
                userid: this.userid,
                urlPicture: this.urlPicture
            })},
    }
}
</script>
