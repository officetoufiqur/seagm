<?php

namespace App\Http\Controllers;

use App\Models\Terms;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TermsController extends Controller
{
    use ApiResponse;

    public function terms(Request $request)
    {
        $type = $request->query('type');
        
        $terms = Terms::when($type, function ($query) use ($type) {
            return $query->where('type', $type);
        })->get();

        return $this->successResponse($terms, 'Terms retrieved successfully.');
    }
    
    public function index()
    {
        $terms = Terms::all();

        return Inertia::render('Terms/Index', [
            'terms' => $terms,
        ]);
    }

    public function create()
    {
        return Inertia::render('Terms/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'content' => 'required',
        ]);

        Terms::create([
            'type' => $request->type,
            'content' => $request->content,
        ]);

        return redirect()->route('terms.index')->with('message', 'Terms created successfully.');
    }

    public function edit($id)
    {
        $term = Terms::findOrFail($id);

        return Inertia::render('Terms/Edit', [
            'term' => $term,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required',
            'content' => 'required',
        ]);

        $term = Terms::findOrFail($id);
        $term->update([
            'type' => $request->type,
            'content' => $request->content,
        ]);

        return redirect()->route('terms.index')->with('message', 'Terms updated successfully.');
    }

    public function destroy($id)
    {
        $term = Terms::findOrFail($id);
        $term->delete();

        return redirect()->route('terms.index')->with('message', 'Terms deleted successfully.');
    }
}
