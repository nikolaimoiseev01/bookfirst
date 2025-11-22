<div id="work_card_{{$work['id']}}" data-id="{{$work['id']}}" class="work_card_wrap @if($flgbigwork) big_block @endif">
    <div class="image_wrap">
        <div class="read_hovered_wrap">
            <a href="{{route('social.work_page', $work['id'])}}">Читать</a>
        </div>
        <img src="{{$work['picture_cropped'] ?? '/img/social/default_work_pic_' . rand(1,4) . '.svg'}}" alt="">
    </div>

    <div class="icons_wrap">
        <div class="icon_wrap">
            <span>
                @if($work->work_like) {{ $work->work_like->count('id') ?? 0}}
                @else 0
                @endif
            </span>
            <i class="fa-regular fa-heart"></i>
        </div>

        <div class="icon_wrap">
              <span>
                  @if($work->work_comment) {{ $work->work_comment->count('id') ?? 0}}
                  @else 0 @endif
              </span>
            <i class="fa-regular fa-comment"></i>
        </div>
    </div>

    <div class="info_wrap">
        <a href="{{route('social.user_page', $work['user_id'])}}" target="_blank"
           class="link social">
            {{Str::limit(prefer_name($work->user['name'], $work->user['surname'], $work->user['nickname']), 20, '...')}}
        </a>
        <p>{{Str::limit(Str::ucfirst(Str::lower($work['title'])), 20, '...')}}</p>
    </div>
</div>

