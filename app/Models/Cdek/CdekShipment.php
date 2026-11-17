<?php

namespace App\Models\Cdek;

use Illuminate\Database\Eloquent\Model;

class CdekShipment extends Model
{
    protected $fillable = [
        'created_by', 'own_book_id', 'origin_city_code', 'print_order_id', 'book_title', 'recipient_address', 'address_type', 'delivery_point',
        'own_book_ids', 'book_titles', 'collection_ids', 'shipment_type', 'recipient_name', 'recipient_phone',
        'weight', 'length', 'width', 'height', 'tariff_code', 'status', 'cdek_uuid',
        'cdek_number', 'request_payload', 'response_payload', 'error_message',
    ];

    protected function casts(): array
    {
        return [
            'own_book_ids' => 'array',
            'book_titles' => 'array',
            'collection_ids' => 'array',
            'request_payload' => 'array',
            'response_payload' => 'array',
        ];
    }

    public function ownBook()
    {
        return $this->belongsTo(\App\Models\OwnBook\OwnBook::class);
    }

    public function printOrder()
    {
        return $this->belongsTo(\App\Models\PrintOrder\PrintOrder::class);
    }

    public function syncPrintOrderTrackNumber(?string $cdekNumber = null, ?string $requestState = null): void
    {
        $number = $cdekNumber ?: $this->cdek_number;

        if (!$this->print_order_id || blank($number) || $requestState === 'INVALID') {
            return;
        }

        \App\Models\PrintOrder\PrintOrder::query()
            ->whereKey($this->print_order_id)
            ->update(['track_number' => $number]);
    }
}
