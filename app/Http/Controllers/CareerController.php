<?php

namespace App\Http\Controllers;

use App\Models\Career;
use App\Models\EmployeeBenefit;
use App\Models\JoinUs;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CareerController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $careers = Career::all();

        return Inertia::render('Career/Index', [
            'careers' => $careers,
        ]);
    }

    public function edit($id)
    {
        $career = Career::find($id);

        return Inertia::render('Career/Edit', [
            'career' => $career,
        ]);
    }

    public function update(Request $request, $id)
    {
        $career = Career::find($id);
        $career->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
        ]);

        return redirect()->route('careers.index')->with('message', 'Career updated successfully.');
    }

    public function show()
    {
        $careers = Career::where('section', 'Career')->first();

        $benefits = [
            'header' => Career::where('section', 'employee_benefits')->first(),
            'items' => EmployeeBenefit::all(),
        ];

        $joinus = [
            'header' => Career::where('section', 'join_us')->first(),
            'items' => JoinUs::with('items')->get(),
        ];

        $data = [
            'careers' => $careers,
            'benefits' => $benefits,
            'joinus' => $joinus,
        ];

        return $this->successResponse($data, 'Careers retrieved successfully');
    }
}
