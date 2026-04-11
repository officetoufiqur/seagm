<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use App\Trait\ApiResponse;

class AboutUsController extends Controller
{
    use ApiResponse;

    public function show()
    {
        $aboutUs = AboutUs::first();

        if (! $aboutUs) {
            return $this->errorResponse('About Us information not found.', 404);
        }

        return $this->successResponse($aboutUs, 'About Us information retrieved successfully.');
    }
}
