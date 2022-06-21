@extends('layouts.app')
@section('content')
    <div class="container">
        <create-theme-component :data="{{ json_encode($data) }}"></create-theme-component>
        <div class="d-flex justify-content-end mb-3">
            <button @click="showMText" type="button" class="btn btn-secondary m-1">Текст</button>
            <button @click="showComp1" type="button" class="btn btn-secondary m-1">Картинки</button>
            <button @click="showComp1" type="button" class="btn btn-secondary m-1">Аудио</button>
            <button @click="showComp1" type="button" class="btn btn-info m-1">Запланировать</button>
        </div>
        <Component :is="currentComponent" :data="{{ json_encode($data) }}"></Component>
    </div>
@endsection

