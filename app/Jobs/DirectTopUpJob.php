<?php

namespace App\Jobs;

use App\Helpers\SeagmHelper;
use App\Models\DirectTopUp;
use App\Models\TopUpField;
use App\Models\TopUpItem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class DirectTopUpJob implements ShouldQueue
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
        $response = SeagmHelper::get('v1/recharge-categories');

        if (empty($response['data'])) {
            return;
        }

        DB::transaction(function () use ($response) {

            foreach ($response['data'] as $category) {

                $categoryData = DirectTopUp::updateOrCreate(
                    ['api_id' => $category['id']],
                    [
                        'name' => $category['name'] ?? null,
                        'code' => $category['code'] ?? null,
                        'mode' => $category['mode'] ?? null,
                        'region' => $category['region'] ?? null,
                        'auto_delivery' => $category['auto_delivery'] ?? false,
                    ]
                );

                // Get Items
                $cards = SeagmHelper::get(
                    'v1/recharge-categories/'.$categoryData->api_id.'/recharge-types'
                );

                if (empty($cards['data'])) {
                    continue;
                }

                $items = [];

                foreach ($cards['data'] as $card) {
                    $items[] = [
                        'api_id' => $card['id'],
                        'api_category_id' => $card['category_id'],
                        'direct_top_up_id' => $categoryData->id,

                        'name' => $card['name'],
                        'par_value_currency' => $card['par_value_currency'] ?? null,
                        'par_value' => $card['par_value'] ?? 0,
                        'currency' => $card['currency'] ?? null,

                        'unit_price' => $card['unit_price'],
                        'origin_price' => $card['origin_price'] ?? 0,

                        'discount_rate' => $card['discount_rate'] ?? 0,

                        'min_amount' => $card['min_amount'] ?? 1,
                        'max_amount' => $card['max_amount'] ?? 1,

                        'account_check' => $card['account_check'] ?? false,

                        'profit_margin' => 0,
                        'final_price' => $card['unit_price'],

                        'status' => true,

                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                TopUpItem::upsert(
                    $items,
                    ['api_id'],
                    [
                        'api_category_id',
                        'direct_top_up_id',
                        'name',
                        'par_value_currency',
                        'par_value',
                        'currency',
                        'unit_price',
                        'origin_price',
                        'discount_rate',
                        'min_amount',
                        'max_amount',
                        'account_check',
                        'profit_margin',
                        'final_price',
                        'status',
                        'updated_at',
                    ]
                );

                $itemMap = TopUpItem::whereIn('api_id', collect($items)->pluck('api_id'))
                    ->get()
                    ->keyBy('api_id');

                foreach ($cards['data'] as $card) {

                    $item = $itemMap[$card['id']] ?? null;
                    if (! $item) {
                        continue;
                    }

                    if (empty($card['fields'])) {
                        continue;
                    }

                    $existingFieldNames = [];

                    foreach ($card['fields'] as $field) {

                        $existingFieldNames[] = $field['name'];

                        TopUpField::updateOrCreate(
                            [
                                'api_item_id' => $card['id'],
                                'name' => $field['name'],
                            ],
                            [
                                'top_up_item_id' => $item->id,

                                'type' => $field['type'],
                                'label' => $field['label'] ?? null,
                                'label_zh' => $field['label_zh'] ?? null,

                                'multiline' => $field['multiline'] ?? false,
                                'placeholder' => $field['placeholder'] ?? null,
                                'prefix' => $field['prefix'] ?? null,

                                'position' => $field['position'] ?? 0,
                            ]
                        );
                    }

                    TopUpField::where('api_item_id', $card['id'])
                        ->whereNotIn('name', $existingFieldNames)
                        ->delete();
                }
            }
        });

    }
}
