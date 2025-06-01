<div class="flex">

    <div class="flex-1">
        <h1 class="text-xl">Места</h1>
        @foreach($candidates as $candidate)
            {{$candidate->author_name}}<br>
        @endforeach
    </div>

    <div class="flex-1">
        <h1 class="text-xl">Голоса</h1>
        @foreach($record->collectionVotes as $vote)
            {{$vote->participation_from['author_name']}} --> {{$vote->participation_to['author_name']}}<br>
        @endforeach
    </div>

</div>
