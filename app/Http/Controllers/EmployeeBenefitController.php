<?php

namespace App\Http\Controllers;

use App\Models\EmployeeBenefit;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmployeeBenefitController extends Controller
{
    public function index()
    {
        $employeeBenefits = EmployeeBenefit::all();
        
        return Inertia::render('EmployeeBenefit/Index', [
            'employeeBenefits' => $employeeBenefits
        ]);
    }

    public function create()
    {
        return Inertia::render('EmployeeBenefit/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string',
        ]);

        EmployeeBenefit::create([
            'title' => $request->title,
            'description' => $request->description,
            'icon' => $request->icon
        ]);

        return redirect()->route('employee-benefits.index')->with('message', 'Employee Benefit created successfully.');
    }

    public function edit($id)
    {
        $employeeBenefit = EmployeeBenefit::findOrFail($id);

        return Inertia::render('EmployeeBenefit/Edit', [
            'employeeBenefit' => $employeeBenefit
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string',
        ]);

        $employeeBenefit = EmployeeBenefit::findOrFail($id);
        $employeeBenefit->update([
            'title' => $request->title,
            'description' => $request->description,
            'icon' => $request->icon
        ]);

        return redirect()->route('employee-benefits.index')->with('message', 'Employee Benefit updated successfully.');
    }

    public function destroy($id)
    {
        EmployeeBenefit::findOrFail($id)->delete();
        return redirect()->route('employee-benefits.index')->with('message', 'Employee Benefit deleted successfully.');
    }
}
