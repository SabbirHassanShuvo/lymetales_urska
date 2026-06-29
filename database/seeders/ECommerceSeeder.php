<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\ProductReview;
use App\Models\Gift;
use Illuminate\Support\Str;

class ECommerceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 0. Disable foreign key constraints and truncate tables to ensure a clean slate
        Schema::disableForeignKeyConstraints();
        DB::table('products')->truncate();
        DB::table('categories')->truncate();
        DB::table('subcategories')->truncate();
        DB::table('site_categories')->truncate();
        DB::table('site_subcategories')->truncate();
        DB::table('coupons')->truncate();
        DB::table('product_reviews')->truncate();
        DB::table('gifts')->truncate();
        DB::table('product_images')->truncate();
        DB::table('product_upsells')->truncate();
        Schema::enableForeignKeyConstraints();

        $languages = ['SL', 'HR', 'EN'];

        foreach ($languages as $lang) {
            $suffix = strtolower($lang) === 'sl' ? '' : '-' . strtolower($lang);

            // 0. Seed Site Category & Subcategories
            $siteCat = \App\Models\SiteCategory::create([
                'name' => 'Age Group',
                'slug' => 'age-group' . $suffix,
                'description' => 'Filter books by age group',
                'is_special' => false,
                'status' => true,
                'language_type' => $lang,
            ]);

            // siteCat automatically populates site_subcategories, fetch OTROCI for this siteCat
            $subKids = $siteCat->subcategories()->where('name', 'OTROCI')->first();

            // 1. Seed Categories & Subcategories
            $cat1 = Category::create([
                'name' => 'Personalised Books',
                'slug' => 'personalised-books' . $suffix,
                'description' => 'Beautiful stories where your child becomes the main character.',
                'is_special' => true,
                'status' => true,
                'language_type' => $lang,
            ]);

            $sub1_1 = Subcategory::create([
                'category_id' => $cat1->id,
                'name' => 'Easter Stories',
                'slug' => 'easter-stories' . $suffix,
                'description' => 'Magical adventures themed around egg hunts and spring.',
                'status' => true,
            ]);

            $sub1_2 = Subcategory::create([
                'category_id' => $cat1->id,
                'name' => 'Birthday Books',
                'slug' => 'birthday-books' . $suffix,
                'description' => 'The perfect personalised keepsake for birthdays.',
                'status' => true,
            ]);

            $cat2 = Category::create([
                'name' => 'Educational & Activities',
                'slug' => 'educational-activities' . $suffix,
                'description' => 'Fun learning materials and activity bundles.',
                'is_special' => false,
                'status' => true,
                'language_type' => $lang,
            ]);

            $sub2_1 = Subcategory::create([
                'category_id' => $cat2->id,
                'name' => 'Coloring & Drawing',
                'slug' => 'coloring-drawing' . $suffix,
                'description' => 'Coloring pages tailored to your kid\'s preferences.',
                'status' => true,
            ]);

            // 2. Seed Products (Personalised Books)
            $product1 = Product::create([
                'category_id' => $cat1->id,
                'subcategory_id' => $sub1_1->id,
                'site_category_id' => $siteCat->id,
                'site_subcategory_id' => $subKids ? $subKids->id : null,
                'title' => 'My First Easter Egg Hunt',
                'slug' => 'my-first-easter-egg-hunt' . $suffix,
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
                'is_bestseller' => true,
                'is_recommended' => true,
                'status' => true,
                'language_type' => $lang,
            ]);

            $product1->images()->create([
                'image_path' => 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&q=80&w=400',
                'is_main' => true,
                'sort_order' => 0,
            ]);

            foreach (['https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&q=80&w=400', 'https://images.unsplash.com/photo-1476275466078-4007374efbbe?auto=format&fit=crop&q=80&w=400'] as $index => $url) {
                $product1->images()->create([
                    'image_path' => $url,
                    'is_main' => false,
                    'sort_order' => $index + 1,
                ]);
            }

            $product2 = Product::create([
                'category_id' => $cat1->id,
                'subcategory_id' => $sub1_2->id,
                'site_category_id' => $siteCat->id,
                'site_subcategory_id' => $subKids ? $subKids->id : null,
                'title' => 'The Birthday Adventure Kept Safe',
                'slug' => 'the-birthday-adventure-kept-safe' . $suffix,
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
                'is_bestseller' => true,
                'is_recommended' => false,
                'status' => true,
                'language_type' => $lang,
            ]);

            $product2->images()->create([
                'image_path' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&q=80&w=400',
                'is_main' => true,
                'sort_order' => 0,
            ]);

            foreach (['https://images.unsplash.com/photo-1516979187457-637abb4f9353?auto=format&fit=crop&q=80&w=400'] as $index => $url) {
                $product2->images()->create([
                    'image_path' => $url,
                    'is_main' => false,
                    'sort_order' => $index + 1,
                ]);
            }

            $product3 = Product::create([
                'category_id' => $cat2->id,
                'subcategory_id' => $sub2_1->id,
                'site_category_id' => $siteCat->id,
                'site_subcategory_id' => $subKids ? $subKids->id : null,
                'title' => 'Coloring My Wild Kingdom',
                'slug' => 'coloring-my-wild-kingdom' . $suffix,
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
                'is_bestseller' => false,
                'is_recommended' => true,
                'status' => true,
                'language_type' => $lang,
            ]);

            $product3->images()->create([
                'image_path' => 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?auto=format&fit=crop&q=80&w=400',
                'is_main' => true,
                'sort_order' => 0,
            ]);

            // 4. Seed Product Reviews
            ProductReview::create([
                'product_id' => $product1->id,
                'reviewer_name' => 'Alice Smith',
                'reviewer_email' => 'alice@example.com',
                'title' => 'Absolutely Magical!',
                'reviewer_location' => 'New York, USA',
                'rating' => 5,
                'comment' => 'My son loved seeing his name in the story! The illustrations are stunning.',
                'is_approved' => true,
            ]);

            ProductReview::create([
                'product_id' => $product1->id,
                'reviewer_name' => 'John Doe',
                'reviewer_email' => 'john@example.com',
                'title' => 'Great Easter Gift',
                'reviewer_location' => 'London, UK',
                'rating' => 4,
                'comment' => 'Very nice quality paper and colors. Arrived just in time for Easter.',
                'is_approved' => true,
            ]);

            ProductReview::create([
                'product_id' => $product2->id,
                'reviewer_name' => 'Emma Watson',
                'reviewer_email' => 'emma@example.com',
                'title' => 'Beautiful Birthday Keepsake',
                'reviewer_location' => 'Sydney, AU',
                'rating' => 5,
                'comment' => 'This is the most thoughtful birthday gift ever. Highly recommend.',
                'is_approved' => true,
            ]);

            // 5. Seed Gifts Category
            $cat3 = Category::create([
                'name' => 'Gifts',
                'slug' => 'gifts' . $suffix,
                'description' => 'Beautiful gift items for children and families.',
                'is_special' => false,
                'status' => true,
                'language_type' => $lang,
            ]);

            // Seed gift products for Product 1 (First-grader book: timetable, stickers, t-shirt)
            $gift1 = Product::create([
                'category_id' => $cat3->id,
                'site_category_id' => $siteCat->id,
                'site_subcategory_id' => $subKids ? $subKids->id : null,
                'title' => 'Personalized School Timetable',
                'slug' => 'personalized-school-timetable' . $suffix,
                'description' => 'A gorgeous colorful school timetable customized with your kid\'s name.',
                'price' => 9.99,
                'pages' => null,
                'age_range' => '5-8 Years',
                'status' => true,
                'type' => 'gift',
                'language_type' => $lang,
            ]);
            $gift1->images()->create([
                'image_path' => 'https://images.unsplash.com/photo-1506784983877-45594efa4cbe?auto=format&fit=crop&q=80&w=400',
                'is_main' => true,
                'sort_order' => 0,
            ]);

            $gift2 = Product::create([
                'category_id' => $cat3->id,
                'site_category_id' => $siteCat->id,
                'site_subcategory_id' => $subKids ? $subKids->id : null,
                'title' => 'Fun Stickers Pack',
                'slug' => 'fun-stickers-pack' . $suffix,
                'description' => 'A pack of 20 high-quality waterproof stickers featuring story characters.',
                'price' => 4.99,
                'pages' => null,
                'age_range' => '3-10 Years',
                'status' => true,
                'type' => 'gift',
                'language_type' => $lang,
            ]);
            $gift2->images()->create([
                'image_path' => 'https://images.unsplash.com/photo-1572375995501-4b0894dbe5d8?auto=format&fit=crop&q=80&w=400',
                'is_main' => true,
                'sort_order' => 0,
            ]);

            $gift3 = Product::create([
                'category_id' => $cat3->id,
                'site_category_id' => $siteCat->id,
                'site_subcategory_id' => $subKids ? $subKids->id : null,
                'title' => 'Personalized Adventure T-Shirt',
                'slug' => 'personalized-adventure-t-shirt' . $suffix,
                'description' => '100% premium cotton t-shirt with story artwork and custom name.',
                'price' => 19.99,
                'pages' => null,
                'age_range' => '4-8 Years',
                'status' => true,
                'type' => 'gift',
                'language_type' => $lang,
            ]);
            $gift3->images()->create([
                'image_path' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&q=80&w=400',
                'is_main' => true,
                'sort_order' => 0,
            ]);

            // Seed gift products for Product 2 (Baby book: greeting card, toy)
            $gift4 = Product::create([
                'category_id' => $cat3->id,
                'site_category_id' => $siteCat->id,
                'site_subcategory_id' => $subKids ? $subKids->id : null,
                'title' => 'Custom Keepsake Greeting Card',
                'slug' => 'custom-keepsake-greeting-card' . $suffix,
                'description' => 'A heavy-cardstock greeting card with a personalized dedication message.',
                'price' => 3.99,
                'pages' => null,
                'age_range' => '0-2 Years',
                'status' => true,
                'type' => 'gift',
                'language_type' => $lang,
            ]);
            $gift4->images()->create([
                'image_path' => 'https://images.unsplash.com/photo-1513151233558-d860c5398176?auto=format&fit=crop&q=80&w=400',
                'is_main' => true,
                'sort_order' => 0,
            ]);

            $gift5 = Product::create([
                'category_id' => $cat3->id,
                'site_category_id' => $siteCat->id,
                'site_subcategory_id' => $subKids ? $subKids->id : null,
                'title' => 'Plush Rabbit Toy',
                'slug' => 'plush-rabbit-toy' . $suffix,
                'description' => 'An ultra-soft cuddly organic plush toy bunny.',
                'price' => 14.50,
                'pages' => null,
                'age_range' => '0-3 Years',
                'status' => true,
                'type' => 'gift',
                'language_type' => $lang,
            ]);
            $gift5->images()->create([
                'image_path' => 'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?auto=format&fit=crop&q=80&w=400',
                'is_main' => true,
                'sort_order' => 0,
            ]);

            // Seed actual Gift models for book upsells
            $uGift1 = Gift::create([
                'title' => 'Personalized School Timetable',
                'short_description' => 'A gorgeous colorful school timetable customized with your kid\'s name.',
                'price' => 9.99,
                'image_path' => 'https://images.unsplash.com/photo-1506784983877-45594efa4cbe?auto=format&fit=crop&q=80&w=400',
                'language_type' => $lang,
            ]);

            $uGift2 = Gift::create([
                'title' => 'Fun Stickers Pack',
                'short_description' => 'A pack of 20 high-quality waterproof stickers featuring story characters.',
                'price' => 4.99,
                'image_path' => 'https://images.unsplash.com/photo-1572375995501-4b0894dbe5d8?auto=format&fit=crop&q=80&w=400',
                'language_type' => $lang,
            ]);

            $uGift3 = Gift::create([
                'title' => 'Personalized Adventure T-Shirt',
                'short_description' => '100% premium cotton t-shirt with story artwork and custom name.',
                'price' => 19.99,
                'image_path' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&q=80&w=400',
                'language_type' => $lang,
            ]);

            $uGift4 = Gift::create([
                'title' => 'Custom Keepsake Greeting Card',
                'short_description' => 'A heavy-cardstock greeting card with a personalized dedication message.',
                'price' => 3.99,
                'image_path' => 'https://images.unsplash.com/photo-1513151233558-d860c5398176?auto=format&fit=crop&q=80&w=400',
                'language_type' => $lang,
            ]);

            $uGift5 = Gift::create([
                'title' => 'Plush Rabbit Toy',
                'short_description' => 'An ultra-soft cuddly organic plush toy bunny.',
                'price' => 14.50,
                'image_path' => 'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?auto=format&fit=crop&q=80&w=400',
                'language_type' => $lang,
            ]);

            // Link upsells to Product 1 ("My First Easter Egg Hunt")
            $product1->upsells()->attach([$uGift1->id, $uGift2->id, $uGift3->id]);

            // Link upsells to Product 2 ("The Birthday Adventure Kept Safe")
            $product2->upsells()->attach([$uGift4->id, $uGift5->id]);

            // 3. Seed Coupons for current language
            Coupon::create([
                'code' => 'EASTER25' . ($lang === 'SL' ? '' : '-' . $lang),
                'type' => 'percent',
                'value' => 25,
                'description' => 'Easter campaign 25% discount (' . $lang . ')',
                'expiry_date' => now()->addDays(30),
                'usage_limit' => 500,
                'used_count' => 45,
                'status' => true,
                'language_type' => $lang,
            ]);

            Coupon::create([
                'code' => 'WELCOME5' . ($lang === 'SL' ? '' : '-' . $lang),
                'type' => 'fixed',
                'value' => 5.00,
                'description' => 'First purchase fixed discount (' . $lang . ')',
                'expiry_date' => null,
                'usage_limit' => null,
                'used_count' => 129,
                'status' => true,
                'language_type' => $lang,
            ]);

            Coupon::create([
                'code' => 'EXPIRED10' . ($lang === 'SL' ? '' : '-' . $lang),
                'type' => 'percent',
                'value' => 10,
                'description' => 'Old seasonal coupon (' . $lang . ')',
                'expiry_date' => now()->subDays(5),
                'usage_limit' => 100,
                'used_count' => 100,
                'status' => true,
                'language_type' => $lang,
            ]);
        }
    }
}
