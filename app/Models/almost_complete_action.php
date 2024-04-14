<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class almost_complete_action extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'almost_complete_action_type_id',
        'collection_id',
        'own_book_id',
        'dt_action_completed',
        'flg_email_sent',
        'cnt_email_sent',
        'dt_last_email_sent'
    ];

    public function almost_complete_action_type()
    {
        return $this->belongsTo(almost_complete_action_type::class);
    }

    public function Collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

}
