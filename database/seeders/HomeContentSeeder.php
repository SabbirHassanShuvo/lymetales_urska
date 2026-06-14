<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeroSection;
use App\Models\HomeFeature;
use App\Models\GiftCard;
use App\Models\HomePromo;
use App\Models\GiftGiver;
use App\Models\Faq;
use App\Models\FooterSection;
use App\Models\FooterItem;
use App\Models\Setting;

class HomeContentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clear existing content first
        HeroSection::query()->delete();
        HomeFeature::query()->delete();
        GiftCard::query()->delete();
        HomePromo::query()->delete();
        GiftGiver::query()->delete();
        Faq::query()->delete();
        FooterItem::query()->delete();
        FooterSection::query()->delete();

        // 2. Seed Hero Section (Requirement 1)
        HeroSection::create([
            'title' => 'Summer Stories',
            'image_path' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&q=80&w=1200',
            'button_one_text' => 'PERSONALISE NOW',
            'button_two_text' => 'MORE BOOKS',
        ]);

        // 3. Seed Highlight Features (Requirement 2)
        $features = [
            [
                'title' => 'More than just a story',
                'description' => 'Each book creates a personal journey that sparks imagination and builds confidence in every child.',
            ],
            [
                'title' => 'Values that truly last',
                'description' => 'Our stories gently inspire empathy, courage, kindness, and love that stay for a lifetime.',
            ],
            [
                'title' => 'A hero like no other',
                'description' => 'Unique illustrations and personalization make every child the star of their own masterpiece.',
            ],
            [
                'title' => 'Magic delivered with care',
                'description' => 'Lovingly crafted and carefully prepared for shipping within just 1–2 business days.',
            ]
        ];
        foreach ($features as $f) {
            HomeFeature::create($f);
        }

        // 4. Seed Gift Cards (Requirement 3: link omitted)
        GiftCard::create([
            'title' => 'Personalised Gift Voucher',
            'subtitle' => 'Give the gift of choice with our custom gift cards.',
            'image_path' => 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&q=80&w=400',
        ]);

        // 5. Seed Thoughtfully Made Promo Section (Requirement 4)
        HomePromo::create([
            'title' => 'Thoughtfully made for the people you love most',
            'description' => 'From names to skin tones, clothing colors to heartfelt dedications — everything is customisable to create a gift uniquely for them.',
            'button_text' => 'Shop the Story',
            'image_path' => 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&q=80&w=600',
        ]);

        // 6. Seed Legendary Gift-Giver (Requirement 5)
        GiftGiver::create([
            'subtitle' => 'BECOME A',
            'title' => 'Legendary gift-giver',
            'step_1_image' => 'https://images.unsplash.com/photo-1502086223501-7ea6ecd79368?auto=format&fit=crop&q=80&w=400',
            'step_1_text' => 'Fill in a few bits of info',
            'step_2_image' => 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?auto=format&fit=crop&q=80&w=400',
            'step_2_text' => 'Preview personalisation in real time',
            'step_3_image' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&q=80&w=400',
            'step_3_text' => 'Deliver smiles of joy to your favourite child',
        ]);

        // 7. Seed FAQs (Requirement 6)
        $faqs = [
            [
                'question' => 'What details do I need to personalise a book?',
                'answer' => 'You will need the child\'s name, their preferred gender/character appearance (hair color, skin tone), and optional fields like custom dedication text.',
            ],
            [
                'question' => 'How do I make a personalised book?',
                'answer' => 'Simply click "PERSONALISE NOW" on any book page, enter the custom configurations in the interactive builder, preview the book in real-time, and add it to your cart.',
            ],
            [
                'question' => 'How do I get a discount code?',
                'answer' => 'You can subscribe to our newsletter by entering your email at the bottom of the page to receive a 10% discount on your first order.',
            ],
            [
                'question' => 'What is your delivery time?',
                'answer' => 'Our production takes 1–2 business days. Shipping times vary from 2–5 business days for standard delivery depending on your location.',
            ]
        ];
        foreach ($faqs as $faq) {
            Faq::create($faq);
        }

        // 8. Seed Newsletter Text Settings (Requirement 7)
        Setting::updateOrCreate(
            ['key' => 'newsletter_title'],
            ['value' => 'Get 10% off your first order']
        );
        Setting::updateOrCreate(
            ['key' => 'newsletter_description'],
            ['value' => 'Join our community and create magical moments for the children you love.']
        );

        // 8b. Seed Footer Brand Description, Logo & Social Links (Requirement 8 - brand column)
        Setting::updateOrCreate(
            ['key' => 'footer_brand_description'],
            ['value' => 'Crafting personalized stories that celebrate the magic of childhood and the bonds of family.']
        );
        Setting::updateOrCreate(['key' => 'footer_logo_path'],  ['value' => '']);
        Setting::updateOrCreate(['key' => 'footer_copyright'],  ['value' => '© ' . date('Y') . ' Lymetales HQ, Inc. All Rights Reserved.']);
        Setting::updateOrCreate(['key' => 'social_instagram'], ['value' => '']);
        Setting::updateOrCreate(['key' => 'social_tiktok'],    ['value' => '']);
        Setting::updateOrCreate(['key' => 'social_facebook'],  ['value' => '']);

        // 9. Seed Footer Sections & Items (Requirement 8)
        $footerData = [
            [
                'title' => 'Shop',
                'items' => [
                    ['label' => 'Our Books', 'url' => '/books'],
                    ['label' => 'Gift Cards', 'url' => '/books?type=gift'],
                    ['label' => 'New Arrivals', 'url' => '/books?sort=new'],
                ],
            ],
            [
                'title' => 'Support',
                'items' => [
                    ['label' => 'Help Center', 'url' => '/help'],
                    ['label' => 'Contact Us', 'url' => '/contact'],
                ],
            ],
            [
                'title' => 'Company',
                'items' => [
                    ['label' => 'Our Story', 'url' => '/our-story'],
                    ['label' => 'Privacy Policy', 'url' => '/privacy-policy'],
                    ['label' => 'Terms of Service', 'url' => '/terms'],
                ],
            ],
        ];

        foreach ($footerData as $sIdx => $sectionData) {
            $section = FooterSection::create([
                'title' => $sectionData['title'],
                'sort_order' => $sIdx,
            ]);

            foreach ($sectionData['items'] as $iIdx => $itemData) {
                FooterItem::create([
                    'footer_section_id' => $section->id,
                    'label' => $itemData['label'],
                    'url' => $itemData['url'],
                    'sort_order' => $iIdx,
                ]);
            }
        }
    }
}
