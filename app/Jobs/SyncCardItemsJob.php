<?php

namespace App\Jobs;

use App\Helpers\SeagmHelper;
use App\Models\Card;
use App\Models\CardItem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class SyncCardItemsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::transaction(function () {

            $response = SeagmHelper::get('v1/card-categories');

            foreach ($response['data'] as $category) {

                // Card sync
                $categoryData = Card::updateOrCreate(
                    ['api_id' => $category['id']],
                    [
                        'name' => $category['name'] ?? null,
                        'code' => $category['code'] ?? null,
                        'mode' => $category['mode'] ?? null,
                        'region' => $category['region'] ?? null,
                        'publisher' => $category['publisher'] ?? null,
                        'auto_delivery' => $category['auto_delivery'] ?? false,
                    ]
                );

                // Get items
                $cards = SeagmHelper::get(
                    'v1/card-categories/'.$categoryData->api_id.'/card-types'
                );

                $items = [];

                foreach ($cards['data'] as $card) {

                    $items[] = [
                        'api_id' => $card['id'],
                        'api_category_id' => $card['category_id'],

                        'name' => $card['name'],
                        'category_name' => $card['category_name'],
                        'par_value_currency' => $card['par_value_currency'],
                        'par_value' => $card['par_value'],
                        'currency' => $card['currency'],
                        'unit_price' => $card['unit_price'],
                        'max_amount' => $card['max_amount'],
                        'min_amount' => $card['min_amount'],
                        'origin_price' => $card['origin_price'],
                        'discount_rate' => $card['discount_rate'],
                        'has_stock' => $card['has_stock'] ?? true,
                        'status' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                CardItem::upsert(
                    $items,
                    ['api_id'], 
                    [
                        'api_category_id',
                        'name',
                        'category_name',
                        'par_value_currency',
                        'par_value',
                        'currency',
                        'unit_price',
                        'max_amount',
                        'min_amount',
                        'origin_price',
                        'discount_rate',
                        'has_stock',
                        'status',
                        'updated_at'
                    ]
                );
            }
        });
    }
}
