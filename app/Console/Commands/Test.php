<?php

namespace App\Console\Commands;

use App\Models\Printorder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use LapayGroup\RussianPost\ParcelInfo;
use LapayGroup\RussianPost\Providers\OtpravkaApi;
use Symfony\Component\Yaml\Yaml;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        DB::transaction(function () {
            $print_orders = Printorder::all();

            foreach ($print_orders as $print_order) {
                if (!$print_order['send_to_city']) { // Если не было города - это старый формат
                    $address_json = [
                        "value" => $print_order['send_to_address'],
                        "unrestricted_value" => $print_order['send_to_address'],
                        "type" => "OLD v1"
                    ];
                    $print_order->update([
                        'address' => json_encode($address_json)
                    ]);
                } elseif ($print_order['send_to_city']) {
                    $address = " г. {$print_order['send_to_city']}, {$print_order['send_to_address']}, {$print_order['send_to_index']}";
                    $address_json = [
                        "value" => $address,
                        "unrestricted_value" => $address,
                        "type" => "OLD v2"
                    ];
                    $print_order->update([
                        'address' => json_encode($address_json),
                        'address_country' => $print_order['send_to_country'] == 'РФ' ? 'Россия' : $print_order['send_to_country']
                    ]);
                }

            }


        });
        dd("Успешно обновили адреса!");
    }
}
