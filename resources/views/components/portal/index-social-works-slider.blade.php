<section x-data="{works: @js($lastWorks)}" class="flex gap-4">
    <div class="flex">
        <img src="https://pervajakniga.ru/img/social/default_work_pic_1.svg" class="w-64 h-auto" alt="">
    </div>
    @foreach($lastWorks as $work)
        <x-ui.card-social-work-mini :work="$work"/>
    @endforeach
</section>
