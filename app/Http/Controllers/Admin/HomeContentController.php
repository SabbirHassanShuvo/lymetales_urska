<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSection;
use App\Models\GiftCard;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class HomeContentController extends Controller
{
    public function index()
    {
        $heroSections = HeroSection::all();
        $giftCards = GiftCard::all();
        $faqs = Faq::all();

        return view('admin.home-content.index', compact('heroSections', 'giftCards', 'faqs'));
    }

    public function storeHero(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'button_one_text' => 'nullable|string|max:255',
            'button_one_link' => 'nullable|string|max:255',
            'button_two_text' => 'nullable|string|max:255',
            'button_two_link' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads/home'), $imageName);
            $data['image_path'] = 'uploads/home/' . $imageName;
        }

        HeroSection::create($data);

        return back()->with('success', 'Hero section added successfully.');
    }

    public function destroyHero(HeroSection $hero)
    {
        if ($hero->image_path && File::exists(public_path($hero->image_path))) {
            File::delete(public_path($hero->image_path));
        }
        $hero->delete();
        return back()->with('success', 'Hero section deleted successfully.');
    }

    public function storeGift(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads/home'), $imageName);
            $data['image_path'] = 'uploads/home/' . $imageName;
        }

        GiftCard::create($data);

        return back()->with('success', 'Gift card added successfully.');
    }

    public function destroyGift(GiftCard $gift)
    {
        if ($gift->image_path && File::exists(public_path($gift->image_path))) {
            File::delete(public_path($gift->image_path));
        }
        $gift->delete();
        return back()->with('success', 'Gift card deleted successfully.');
    }

    public function storeFaq(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        Faq::create($request->all());

        return back()->with('success', 'FAQ added successfully.');
    }

    public function destroyFaq(Faq $faq)
    {
        $faq->delete();
        return back()->with('success', 'FAQ deleted successfully.');
    }
}
