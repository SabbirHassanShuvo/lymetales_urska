<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Support\Str;

class ECommerceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Categories & Subcategories
        $cat1 = Category::create([
            'name' => 'Personalised Books',
            'slug' => 'personalised-books',
            'description' => 'Beautiful stories where your child becomes the main character.',
            'is_special' => true,
            'status' => true,
        ]);

        $sub1_1 = Category::create([
            'parent_id' => $cat1->id,
            'name' => 'Easter Stories',
            'slug' => 'easter-stories',
            'description' => 'Magical adventures themed around egg hunts and spring.',
            'is_special' => false,
            'status' => true,
        ]);

        $sub1_2 = Category::create([
            'parent_id' => $cat1->id,
            'name' => 'Birthday Books',
            'slug' => 'birthday-books',
            'description' => 'The perfect personalised keepsake for birthdays.',
            'is_special' => true,
            'status' => true,
        ]);

        $cat2 = Category::create([
            'name' => 'Educational & Activities',
            'slug' => 'educational-activities',
            'description' => 'Fun learning materials and activity bundles.',
            'is_special' => false,
            'status' => true,
        ]);

        $sub2_1 = Category::create([
            'parent_id' => $cat2->id,
            'name' => 'Coloring & Drawing',
            'slug' => 'coloring-drawing',
            'description' => 'Coloring pages tailored to your kid\'s preferences.',
            'is_special' => false,
            'status' => true,
        ]);

        // 2. Seed Products (Personalised Books)
        Product::create([
            'category_id' => $sub1_1->id,
            'title' => 'My First Easter Egg Hunt',
            'slug' => 'my-first-easter-egg-hunt',
            'description' => 'A magical personalised Easter adventure where your child hunts for colorful eggs in the enchanted forest, meeting friendly bunnies and solving fun spring puzzles.',
            'price' => 29.99,
            'pages' => 24,
            'age_range' => '3-5 Years',
            'size' => '21cm X 29.7cm',
            'characters' => '1 Customizable',
            'cover_type' => 'Premium Hardcover',
            'print_type' => 'Archival-quality ink',
            'paper_type' => 'Thick matte pages',
            'rating' => 4.9,
            'reviews_count' => 2847,
            'image' => 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&q=80&w=400',
            'gallery' => [
                'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&q=80&w=400',
                'https://images.unsplash.com/photo-1476275466078-4007374efbbe?auto=format&fit=crop&q=80&w=400'
            ],
            'is_bestseller' => true,
            'is_recommended' => true,
            'status' => true,
        ]);

        Product::create([
            'category_id' => $sub1_2->id,
            'title' => 'The Birthday Adventure Kept Safe',
            'slug' => 'the-birthday-adventure-kept-safe',
            'description' => 'Celebrate your child\'s special day with an action-packed journey across stars and oceans to retrieve the missing candles. Beautifully illustrated and deeply memorable.',
            'price' => 34.50,
            'pages' => 32,
            'age_range' => '4-8 Years',
            'size' => '26cm X 26cm',
            'characters' => '1 Main, 2 Friends',
            'cover_type' => 'Premium Hardcover',
            'print_type' => 'High-density color print',
            'paper_type' => 'Laminated gloss pages',
            'rating' => 4.8,
            'reviews_count' => 952,
            'image' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&q=80&w=400',
            'gallery' => [
                'https://images.unsplash.com/photo-1516979187457-637abb4f9353?auto=format&fit=crop&q=80&w=400'
            ],
            'is_bestseller' => true,
            'is_recommended' => false,
            'status' => true,
        ]);

        Product::create([
            'category_id' => $sub2_1->id,
            'title' => 'Coloring My Wild Kingdom',
            'slug' => 'coloring-my-wild-kingdom',
            'description' => 'A customizable coloring book featuring wild animals matching your child\'s name initials. Over 40 unique drawings of lions, elephants, and magical birds.',
            'price' => 19.99,
            'pages' => 48,
            'age_range' => '2-6 Years',
            'size' => '21cm X 29.7cm',
            'characters' => 'None',
            'cover_type' => 'Premium Softcover',
            'print_type' => 'Standard black ink outline',
            'paper_type' => 'Heavyweight coloring paper',
            'rating' => 5.0,
            'reviews_count' => 143,
            'image' => 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?auto=format&fit=crop&q=80&w=400',
            'gallery' => [],
            'is_bestseller' => false,
            'is_recommended' => true,
            'status' => true,
        ]);

        // 3. Seed Coupons
        Coupon::create([
            'code' => 'EASTER25',
            'type' => 'percent',
            'value' => 25,
            'description' => 'Easter campaign 25% discount',
            'expiry_date' => now()->addDays(30),
            'usage_limit' => 500,
            'used_count' => 45,
            'status' => true,
        ]);

        Coupon::create([
            'code' => 'WELCOME5',
            'type' => 'fixed',
            'value' => 5.00,
            'description' => 'First purchase fixed discount',
            'expiry_date' => null,
            'usage_limit' => null,
            'used_count' => 129,
            'status' => true,
        ]);

        Coupon::create([
            'code' => 'EXPIRED10',
            'type' => 'percent',
            'value' => 10,
            'description' => 'Old seasonal coupon',
            'expiry_date' => now()->subDays(5),
            'usage_limit' => 100,
            'used_count' => 100,
            'status' => true,
        ]);
    }
}
