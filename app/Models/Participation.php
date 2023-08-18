<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participation extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'printorder_id'
    ];


    public function user() {
        return $this->belongsTo(User::class);
    }

    public function Collection() {
        return $this->belongsTo(Collection::class);
    }

    public function Pat_status() {
        return $this->belongsTo(Pat_status::class);
    }

    public function Printorder() {
        return $this->belongsTo(Printorder::class)->orderBy('send_to_name', 'desc');;
    }

    public function Participation_work() {
        return $this->hasMany(Participation_work::class);
    }

    public function Preview_comment() {
        return $this->hasMany(preview_comment::class);
    }

    public function Chat() {
        return $this->belongsTo(Chat::class);
    }

    public function vote() {
        return $this->hasmany(vote::class);
    }

    public function Transaction() {
        return $this->hasmany(Transaction::class);
    }

}
