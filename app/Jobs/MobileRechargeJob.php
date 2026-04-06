<?php

namespace App\Jobs;

use App\Helpers\SeagmHelper;
use App\Models\Country;
use App\Models\Operator;
use App\Models\OperatorProduct;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class MobileRechargeJob implements ShouldQueue
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
        $response = SeagmHelper::get('v1/mobile-recharge/countries');

        if (empty($response['data'])) {
            return;
        }

        DB::transaction(function () use ($response) {

            foreach ($response['data'] as $country) {

                $countryData = Country::updateOrCreate(
                    ['api_id' => $country['id']],
                    [
                        'icon' => $country['icon'],
                        'code' => $country['code'],
                        'name' => $country['name'],
                        'calling_code' => $country['calling_code'],
                    ]
                );

                // Get Items
                $operators = SeagmHelper::get(
                    'v1/mobile-recharge/operators?country_code='.$country['code']
                );

                if (empty($operators['data'])) {
                    continue;
                }

                $items = [];

                foreach ($operators['data'] as $operator) {
                    $items[] = [
                        'api_id' => $operator['id'],
                        'country_api_id' => $country['id'],
                        'name' => $operator['name'],
                        'logo' => $operator['logo'],
                        'code' => $operator['code'],
                        'country_name' => $operator['country_name'],
                        'country_code' => $operator['country_code'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                Operator::upsert(
                    $items,
                    ['api_id'],
                    [
                        'country_api_id',
                        'name',
                        'logo',
                        'code',
                        'country_name',
                        'country_code',
                        'created_at',
                        'updated_at',
                    ]
                );

                $itemMap = Operator::whereIn('api_id', collect($items)->pluck('api_id'))
                    ->get()
                    ->keyBy('api_id');

                foreach ($operators['data'] as $card) {

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

                        OperatorProduct::updateOrCreate(
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

                    OperatorProduct::where('api_item_id', $card['id'])
                        ->whereNotIn('name', $existingFieldNames)
                        ->delete();
                }
            }
        });

    }
}
