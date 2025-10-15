@props([
    'work'
])
<div class="flex rounded flex-col">
    <img src="{{$work['cover_url']}}" alt="">
    <x-ui.link-simple>{{$work->user['name']}}</x-ui.link-simple>
    <p>{{$work['title']}}</p>
</div>
