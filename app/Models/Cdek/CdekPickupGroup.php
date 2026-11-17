<?php

namespace App\Models\Cdek;

use Illuminate\Database\Eloquent\Model;

class CdekPickupGroup extends Model
{
    protected $fillable = [
        'created_by', 'printing_company_id', 'printing_company_name', 'shipment_ids', 'pickup_city_code', 'pickup_address', 'delivery_point',
        'pickup_contact_name', 'pickup_contact_phone', 'intake_date', 'intake_time_from',
        'intake_time_to', 'total_weight', 'status', 'order_status', 'intake_status',
        'cdek_order_uuid', 'cdek_number', 'intake_uuid', 'request_payload', 'response_payload',
        'intake_payload', 'intake_response_payload', 'error_message',
    ];

    protected function casts(): array
    {
        return [
            'shipment_ids' => 'array',
            'intake_date' => 'date',
            'request_payload' => 'array',
            'response_payload' => 'array',
            'intake_payload' => 'array',
            'intake_response_payload' => 'array',
        ];
    }
}
