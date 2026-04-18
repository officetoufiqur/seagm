<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use App\Models\Star;
use App\Models\StarAbout;
use App\Trait\ApiResponse;

class StarApiController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $banner = Star::with('items')->first();

        $cards = StarAbout::where('section', 'cards')->get();
        $about = StarAbout::where('section', 'about')->get();

        $carousel = Carousel::get();

        $data = [
            'banner' => $banner,
            'cards' => $cards,
            'about' => $about,
            'carousel' => $carousel
        ];
        

        return $this->successResponse($data, 'Star about all data fetch successfully');
    }
}
