<?php
use Illuminate\Support\Facades\Session;
?>

@if (session::has('info'))
    <div class="alert alert-success" role="alert">
        {{session::get('info')}}
    </div>
@endif

