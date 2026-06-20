<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSection;
use App\Models\GiftCard;
use App\Models\Faq;
use App\Models\HomeFeature;
use App\Models\HomePromo;
use App\Models\GiftGiver;
use App\Models\Subscriber;
use App\Models\FooterSection;
use App\Models\FooterItem;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HomeContentController extends Controller
{
    public function index()
    {
        $heroSections = HeroSection::all();
        $giftCards = GiftCard::all();
        $faqs = Faq::all();
        
        $features = HomeFeature::all();
        
        $promo = HomePromo::first();
        if (!$promo) {
            $promo = new HomePromo();
        }

        $giftGiver = GiftGiver::first();
        if (!$giftGiver) {
            $giftGiver = new GiftGiver();
        }

        $subscribers = Subscriber::orderBy('created_at', 'desc')->get();
        $footerSections = FooterSection::with('items')->orderBy('sort_order')->get();

        $newsletterTitle = Setting::getVal('newsletter_title', 'Get 10% off your first order');
        $newsletterDescription = Setting::getVal('newsletter_description', 'Join our community and create magical moments for the children you love.');

        $footerBrandDescription = Setting::getVal('footer_brand_description', 'Crafting personalized stories that celebrate the magic of childhood and the bonds of family.');
        $footerLogoPath = Setting::getVal('footer_logo_path', '');
        $footerCopyright = Setting::getVal('footer_copyright', '© ' . date('Y') . ' Lymetales HQ, Inc. All Rights Reserved.');
        $socialLinksJson = Setting::getVal('social_media_links', null);
        if ($socialLinksJson) {
            $socialLinks = json_decode($socialLinksJson, true);
        } else {
            $socialLinks = [];
            $inst = Setting::getVal('social_instagram', '');
            if ($inst) $socialLinks[] = ['label' => 'Instagram', 'url' => $inst];
            $tktk = Setting::getVal('social_tiktok', '');
            if ($tktk) $socialLinks[] = ['label' => 'TikTok', 'url' => $tktk];
            $fb = Setting::getVal('social_facebook', '');
            if ($fb) $socialLinks[] = ['label' => 'Facebook', 'url' => $fb];
        }

        return view('admin.home-content.index', compact(
            'heroSections', 
            'giftCards', 
            'faqs',
            'features',
            'promo',
            'giftGiver',
            'subscribers',
            'footerSections',
            'newsletterTitle',
            'newsletterDescription',
            'footerBrandDescription',
            'footerLogoPath',
            'footerCopyright',
            'socialLinks'
        ));
    }

    public function storeHero(Request $request)
    {
        // Requirement 1: Only title, image, 2 button texts
        $request->validate([
            'title' => 'required|string|max:255',
            'button_one_text' => 'nullable|string|max:255',
            'button_two_text' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'title' => $request->input('title'),
            'button_one_text' => $request->input('button_one_text'),
            'button_two_text' => $request->input('button_two_text'),
        ];

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
        // Requirement 3: Link omitted
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'title' => $request->input('title'),
            'subtitle' => $request->input('subtitle'),
        ];

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

    // --- Highlight Features (Requirement 2) ---
    public function storeFeature(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        HomeFeature::create($request->all());

        return back()->with('success', 'Feature added successfully.');
    }

    public function destroyFeature(HomeFeature $feature)
    {
        $feature->delete();
        return back()->with('success', 'Feature deleted successfully.');
    }

    // --- Middle Promo Section (Requirement 4) ---
    public function updatePromo(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'button_text' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $promo = HomePromo::first();
        if (!$promo) {
            $promo = new HomePromo();
        }

        $promo->title = $request->input('title');
        $promo->description = $request->input('description');
        $promo->button_text = $request->input('button_text');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($promo->image_path && File::exists(public_path($promo->image_path))) {
                File::delete(public_path($promo->image_path));
            }
            $imageName = 'promo_' . time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads/home'), $imageName);
            $promo->image_path = 'uploads/home/' . $imageName;
        }

        $promo->save();

        return back()->with('success', 'Promo section updated successfully.');
    }

    // --- Legendary Gift Giver Section (Requirement 5) ---
    public function updateGiftGiver(Request $request)
    {
        $request->validate([
            'subtitle' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'step_1_text' => 'required|string|max:255',
            'step_2_text' => 'required|string|max:255',
            'step_3_text' => 'required|string|max:255',
            'step_1_image' => 'nullable|image|max:2048',
            'step_2_image' => 'nullable|image|max:2048',
            'step_3_image' => 'nullable|image|max:2048',
        ]);

        $giver = GiftGiver::first();
        if (!$giver) {
            $giver = new GiftGiver();
        }

        $giver->subtitle = $request->input('subtitle');
        $giver->title = $request->input('title');
        $giver->step_1_text = $request->input('step_1_text');
        $giver->step_2_text = $request->input('step_2_text');
        $giver->step_3_text = $request->input('step_3_text');

        // Handle step 1 image
        if ($request->hasFile('step_1_image')) {
            if ($giver->step_1_image && File::exists(public_path($giver->step_1_image))) {
                File::delete(public_path($giver->step_1_image));
            }
            $imgName = 'step1_' . time() . '_' . $request->file('step_1_image')->getClientOriginalName();
            $request->file('step_1_image')->move(public_path('uploads/home'), $imgName);
            $giver->step_1_image = 'uploads/home/' . $imgName;
        }

        // Handle step 2 image
        if ($request->hasFile('step_2_image')) {
            if ($giver->step_2_image && File::exists(public_path($giver->step_2_image))) {
                File::delete(public_path($giver->step_2_image));
            }
            $imgName = 'step2_' . time() . '_' . $request->file('step_2_image')->getClientOriginalName();
            $request->file('step_2_image')->move(public_path('uploads/home'), $imgName);
            $giver->step_2_image = 'uploads/home/' . $imgName;
        }

        // Handle step 3 image
        if ($request->hasFile('step_3_image')) {
            if ($giver->step_3_image && File::exists(public_path($giver->step_3_image))) {
                File::delete(public_path($giver->step_3_image));
            }
            $imgName = 'step3_' . time() . '_' . $request->file('step_3_image')->getClientOriginalName();
            $request->file('step_3_image')->move(public_path('uploads/home'), $imgName);
            $giver->step_3_image = 'uploads/home/' . $imgName;
        }

        $giver->save();

        return back()->with('success', 'Legendary Gift-Giver section updated successfully.');
    }

    // --- Newsletter & Subscriptions (Requirement 7) ---
    public function updateNewsletter(Request $request)
    {
        $request->validate([
            'newsletter_title' => 'required|string|max:255',
            'newsletter_description' => 'required|string',
        ]);

        Setting::updateOrCreate(
            ['key' => 'newsletter_title'],
            ['value' => $request->input('newsletter_title')]
        );

        Setting::updateOrCreate(
            ['key' => 'newsletter_description'],
            ['value' => $request->input('newsletter_description')]
        );

        return back()->with('success', 'Newsletter settings updated successfully.');
    }

    public function destroySubscriber(Subscriber $subscriber)
    {
        $subscriber->delete();
        return back()->with('success', 'Subscriber removed successfully.');
    }

    // --- Footer Settings (Requirement 8) ---
    public function storeFooterSection(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $maxSort = FooterSection::max('sort_order') ?? 0;

        FooterSection::create([
            'title' => $request->input('title'),
            'sort_order' => $maxSort + 1,
        ]);

        return back()->with('success', 'Footer section added successfully.');
    }

    public function updateFooterSection(Request $request, FooterSection $section)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $section->update([
            'title' => $request->input('title'),
        ]);

        return back()->with('success', 'Footer section updated successfully.');
    }

    public function reorderFooterSections(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:footer_sections,id',
        ]);

        foreach ($request->input('order') as $index => $id) {
            FooterSection::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function destroyFooterSection(FooterSection $section)
    {
        $section->delete(); // Cascading delete will handle items
        return back()->with('success', 'Footer section deleted successfully.');
    }

    public function storeFooterItem(Request $request)
    {
        $request->validate([
            'footer_section_id' => 'required|exists:footer_sections,id',
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
        ]);

        $maxSort = FooterItem::where('footer_section_id', $request->input('footer_section_id'))->max('sort_order') ?? 0;

        FooterItem::create([
            'footer_section_id' => $request->input('footer_section_id'),
            'label' => $request->input('label'),
            'url' => $request->input('url'),
            'sort_order' => $maxSort + 1,
        ]);

        return back()->with('success', 'Footer item link added successfully.');
    }

    public function updateFooterItem(Request $request, FooterItem $item)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
        ]);

        $item->update([
            'label' => $request->input('label'),
            'url' => $request->input('url'),
        ]);

        return back()->with('success', 'Footer item link updated successfully.');
    }

    public function destroyFooterItem(FooterItem $item)
    {
        $item->delete();
        return back()->with('success', 'Footer item link deleted successfully.');
    }

    // --- Footer Brand Info, Logo & Social Links ---
    public function updateFooterBrandSocials(Request $request)
    {
        $request->validate([
            'footer_brand_description' => 'nullable|string|max:500',
            'footer_copyright'         => 'nullable|string|max:255',
            'footer_logo'              => 'nullable|image|max:2048',
            'social_media_links'       => 'nullable|string',
        ]);

        Setting::updateOrCreate(['key' => 'footer_brand_description'], ['value' => $request->input('footer_brand_description', '')]);
        Setting::updateOrCreate(['key' => 'footer_copyright'],         ['value' => $request->input('footer_copyright', '')]);

        // Process and sanitize dynamic social links
        $sanitizedLinks = [];
        if ($request->filled('social_media_links')) {
            $linksArray = json_decode($request->input('social_media_links'), true);
            if (is_array($linksArray)) {
                $prefixes = [
                    'instagram' => 'instagram.com/',
                    'tiktok'    => 'tiktok.com/@',
                    'facebook'  => 'facebook.com/',
                    'youtube'   => 'youtube.com/',
                ];

                foreach ($linksArray as $link) {
                    $label = trim($link['label'] ?? '');
                    $url = trim($link['url'] ?? '');

                    if ($label !== '' && $url !== '') {
                        if (!preg_match('/^https?:\/\//i', $url)) {
                            // Determine domain prefix if possible
                            $cleanLabel = strtolower($label);
                            if (isset($prefixes[$cleanLabel]) && stripos($url, $cleanLabel . '.com') === false) {
                                $username = ltrim($url, '@/ ');
                                $url = 'https://' . $prefixes[$cleanLabel] . $username;
                            } else {
                                $url = 'https://' . ltrim($url, '@/ ');
                            }
                        }
                        $sanitizedLinks[] = [
                            'label' => $label,
                            'url' => $url,
                        ];
                    }
                }
            }
        }
        Setting::updateOrCreate(['key' => 'social_media_links'], ['value' => json_encode($sanitizedLinks)]);

        // Handle logo upload
        if ($request->hasFile('footer_logo')) {
            // Delete old logo if it was a local file
            $oldLogo = Setting::getVal('footer_logo_path', '');
            if ($oldLogo && !filter_var($oldLogo, FILTER_VALIDATE_URL) && File::exists(public_path($oldLogo))) {
                File::delete(public_path($oldLogo));
            }
            $logoName = 'footer_logo_' . time() . '.' . $request->file('footer_logo')->getClientOriginalExtension();
            $request->file('footer_logo')->move(public_path('uploads/home'), $logoName);
            Setting::updateOrCreate(['key' => 'footer_logo_path'], ['value' => 'uploads/home/' . $logoName]);
        }

        return back()->with('success', 'Footer brand info and social links updated successfully.');
    }

    public function updateHero(Request $request, HeroSection $hero)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'button_one_text' => 'nullable|string|max:255',
            'button_two_text' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'title' => $request->input('title'),
            'button_one_text' => $request->input('button_one_text'),
            'button_two_text' => $request->input('button_two_text'),
        ];

        if ($request->hasFile('image')) {
            if ($hero->image_path && File::exists(public_path($hero->image_path))) {
                File::delete(public_path($hero->image_path));
            }
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads/home'), $imageName);
            $data['image_path'] = 'uploads/home/' . $imageName;
        }

        $hero->update($data);

        return back()->with('success', 'Hero section updated successfully.');
    }

    public function updateFeature(Request $request, HomeFeature $feature)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $feature->update($request->all());

        return back()->with('success', 'Feature updated successfully.');
    }

    public function updateGift(Request $request, GiftCard $gift)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'title' => $request->input('title'),
            'subtitle' => $request->input('subtitle'),
        ];

        if ($request->hasFile('image')) {
            if ($gift->image_path && File::exists(public_path($gift->image_path))) {
                File::delete(public_path($gift->image_path));
            }
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads/home'), $imageName);
            $data['image_path'] = 'uploads/home/' . $imageName;
        }

        $gift->update($data);

        return back()->with('success', 'Gift card updated successfully.');
    }

    public function updateFaq(Request $request, Faq $faq)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        $faq->update($request->all());

        return back()->with('success', 'FAQ updated successfully.');
    }
}
