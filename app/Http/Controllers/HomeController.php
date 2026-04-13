<?php

namespace App\Http\Controllers;

use App\Helpers\FileUpload;
use App\Models\Advantage;
use App\Models\AdvantageCard;
use App\Models\Brand;
use App\Models\HeroSection;
use App\Models\HomeCMS;
use App\Models\Milestone;
use App\Models\Page;
use App\Models\ThroughUsItem;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    use ApiResponse;

    public function landingPage()
    {
        $hero = HeroSection::first();

        $services = HomeCMS::where('section', 'services')->get();
        $servicePage = Page::where('slug', 'services')->first();

        $advantages = Advantage::get();
        $advantagePage = Page::where('slug', 'advantages')->first();
        $advantageCard = AdvantageCard::first();

        $powerup = HomeCMS::where('section', 'power-up')->get();
        $powerupPage = Page::where('slug', 'power-up')->first();

        $globalAccess = HomeCMS::where('section', 'global-access')->get();
        $globalAccessPage = Page::where('slug', 'global-access')->first();
        
        $brands = Brand::get();
        $brandsPage = Page::where('slug', 'brands')->first();

        $through = HomeCMS::where('section', 'through-us')->get();
        $throughPage = Page::where('slug', 'through-us')->first();
        $throughItems = ThroughUsItem::get();
        
        $awards = HomeCMS::where('section', 'awards')->get();
        $milestonePage = Page::where('slug', 'milestone_awards')->first();
        $milestone = Milestone::get(); 

        $data = [
            'hero' => $hero,
            'services' => ['servicePage' => $servicePage, 'services' => $services],
            'advantages' => ['advantagePage' => $advantagePage, 'advantageCard' => $advantageCard ,'advantages' => $advantages],
            'powerup' => ['powerupPage' => $powerupPage, 'powerup' => $powerup],
            'globalAccess' => ['globalAccessPage' => $globalAccessPage, 'globalAccess' => $globalAccess],
            'brands' => ['brandsPage' => $brandsPage, 'brands' => $brands],
            'through' => ['throughPage' => $throughPage, 'through' => $through, 'throughItems' => $throughItems],
            'awards' => ['milestonePage' => $milestonePage, 'milestone' => $milestone, 'awards' => $awards],
        ];

        return $this->successResponse($data, 'Page fetched successfully.');
    }

    public function index()
    {
        $hero = HeroSection::first();

        return Inertia::render('Hero/Index', [
            'hero' => $hero,
        ]);
    }

    public function edit($id)
    {
        $hero = HeroSection::find($id);

        return Inertia::render('Hero/Edit', [
            'hero' => $hero,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'heading' => 'required',
            'image' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'background_image' => 'required|file|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $hero = HeroSection::find($id);

        $image = $hero->image;
        $background_image = $hero->background_image;

        if ($request->hasFile('image')) {
            $image = FileUpload::updateFile($request->file('image'), 'uploads/hero', $image);
        }

        if ($request->hasFile('background_image')) {
            $background_image = FileUpload::updateFile($request->file('background_image'), 'uploads/hero', $background_image);
        }

        $hero->update([
            'title' => $request->title,
            'heading' => $request->heading,
            'image' => $image,
            'background_image' => $background_image,
        ]);

        return redirect()->route('home-hero.index')->with('message', 'Hero updated successfully.');
    }

    public function page()
    {
        $page = Page::get();

        return Inertia::render('Page/Index', [
            'page' => $page,
        ]);
    }

    public function pageEdit($id)
    {
        $page = Page::find($id);

        return Inertia::render('Page/Edit', [
            'page' => $page,
        ]);
    }

    public function pageUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'subtitle' => 'required',
            'slug' => 'required',
        ]);

        $page = Page::find($id);

        $page->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'slug' => $request->slug,
        ]);

        return redirect()->route('home-page.index')->with('message', 'Page updated successfully.');
    }
}
