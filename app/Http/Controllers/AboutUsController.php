<?php

namespace App\Http\Controllers;

use App\Helpers\FileUpload;
use App\Models\AboutUs;
use App\Models\Department;
use App\Models\Vision;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AboutUsController extends Controller
{
    use ApiResponse;

    public function show()
    {
        $aboutUs = AboutUs::first();

        if (! $aboutUs) {
            return $this->errorResponse('About Us information not found.', 404);
        }

        $department = Department::with('items')->first();

        $vision = Vision::with('items')->first();

        $data = [
            'about_us' => $aboutUs,
            'department' => $department,
            'vision' => $vision,
        ];

        return $this->successResponse($data, 'About Us information retrieved successfully.');
    }

    public function index()
    {
        $aboutUs = AboutUs::first();

        return Inertia::render('AboutUs/Index', [
            'aboutUs' => $aboutUs,
        ]);
    }

    public function edit($id)
    {
        $aboutUs = AboutUs::findOrFail($id);

        return Inertia::render('AboutUs/Edit', [
            'aboutUs' => $aboutUs,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'page_view' => 'nullable|numeric',
            'unique_visitors' => 'nullable|numeric',
            'registered_users' => 'nullable|numeric',
            'active_users' => 'nullable|numeric',
            'subscribers' => 'nullable|numeric',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $aboutUs = AboutUs::findOrFail($id);

        $file = $aboutUs->image;

        if ($request->hasFile('image')) {
            $file = FileUpload::updateFile($request->file('image'), 'uploads/about-us', $file);
        }

        $aboutUs->update([
            'title' => $request->title,
            'description' => $request->description,
            'page_view' => $request->page_view,
            'unique_visitors' => $request->unique_visitors,
            'registered_users' => $request->registered_users,
            'active_users' => $request->active_users,
            'subscribers' => $request->subscribers,
            'image' => $file,
        ]);

        return redirect()->route('about-us.index')->with('message', 'About Us information updated successfully.');

    }

    public function vision()
    {
        $vision = Vision::first();

        return Inertia::render('Vision/Index', [
            'vision' => $vision,
        ]);
    }

    public function visionEdit($id)
    {
        $vision = Vision::with('items')->findOrFail($id);

        return Inertia::render('Vision/Edit', [
            'vision' => $vision,
        ]);
    }

    public function visionUpdate(Request $request, $id)
    {
        $request->validate([
            'items' => 'nullable|array',
            'items.*.title' => 'required|string|max:255',
            'items.*.subtitle' => 'nullable|string',
            'items.*.image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $vision = Vision::findOrFail($id);

        $vision->update([
            'description' => $request->description,
        ]);

        $existingItems = $vision->items()->get();
        $existingIds = $existingItems->pluck('id')->toArray();

        $requestIds = [];

        foreach ($request->items ?? [] as $item) {
            $itemImage = null;

            if (isset($item['id'])) {
                $oldItem = $existingItems->where('id', $item['id'])->first();
                $itemImage = $oldItem?->image;
            }

            if (isset($item['image']) && $item['image'] instanceof \Illuminate\Http\UploadedFile) {
                $itemImage = FileUpload::updateFile(
                    $item['image'],
                    'uploads/visions/items',
                    $itemImage
                );
            }

            if (! empty($item['id'])) {

                $requestIds[] = $item['id'];

                $vision->items()->where('id', $item['id'])->update([
                    'title' => $item['title'],
                    'subtitle' => $item['subtitle'],
                    'image' => $itemImage,
                ]);

            } else {

                $newItem = $vision->items()->create([
                    'title' => $item['title'],
                    'subtitle' => $item['subtitle'],
                    'image' => $itemImage,
                ]);

                $requestIds[] = $newItem->id;
            }
        }

        $deleteIds = array_diff($existingIds, $requestIds);

        if (! empty($deleteIds)) {
            $vision->items()->whereIn('id', $deleteIds)->delete();
        }

        return redirect()->route('vision.index')->with('message', 'Vision updated successfully.');
    }

    public function departments()
    {
        $department = Department::first();

        return Inertia::render('Departments/Index', [
            'department' => $department,
        ]);
    }

    public function departmentsEdit($id)
    {
        $department = Department::with('items')->findOrFail($id);

        return Inertia::render('Departments/Edit', [
            'department' => $department,
        ]);
    }

    public function departmentsUpdate(Request $request, $id)
    {
        $request->validate([
            'items' => 'nullable|array',
            'items.*.title' => 'required|string|max:255',
            'items.*.subtitle' => 'nullable|string',
            'items.*.image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $department = Department::findOrFail($id);

        $file = $department->image;

        if ($request->hasFile('image')) {
            $file = FileUpload::updateFile($request->file('image'), 'uploads/departments', $file);
        }

        $department->update([
            'description' => $request->description,
            'image' => $file,
        ]);

        $existingItems = $department->items()->get();
        $existingIds = $existingItems->pluck('id')->toArray();

        $requestIds = [];

        foreach ($request->items ?? [] as $item) {
            $itemImage = null;

            if (isset($item['id'])) {
                $oldItem = $existingItems->where('id', $item['id'])->first();
                $itemImage = $oldItem?->image;
            }

            if (isset($item['image']) && $item['image'] instanceof \Illuminate\Http\UploadedFile) {
                $itemImage = FileUpload::updateFile(
                    $item['image'],
                    'uploads/departments/items',
                    $itemImage
                );
            }

            if (! empty($item['id'])) {

                $requestIds[] = $item['id'];

                $department->items()->where('id', $item['id'])->update([
                    'title' => $item['title'],
                    'subtitle' => $item['subtitle'],
                    'image' => $itemImage,
                ]);

            } else {

                $newItem = $department->items()->create([
                    'title' => $item['title'],
                    'subtitle' => $item['subtitle'],
                    'image' => $itemImage,
                ]);

                $requestIds[] = $newItem->id;
            }
        }

        $deleteIds = array_diff($existingIds, $requestIds);

        if (! empty($deleteIds)) {
            $department->items()->whereIn('id', $deleteIds)->delete();
        }

        return redirect()->route('departments.index')->with('message', 'Department updated successfully.');
    }
}
