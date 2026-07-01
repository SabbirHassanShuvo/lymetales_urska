<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteTranslation;

class SiteTranslationSeeder extends Seeder
{
    public function run(): void
    {
        SiteTranslation::query()->delete();

        $langs = ['SL', 'EN'];

        $translations = [
            // ─────────────────── GLOBAL ───────────────────
            [
                'key'          => 'global.error_loading',
                'group'        => 'global',
                'display_name' => 'Page Data Load Error',
                'input_type'   => 'textarea',
                'value'        => 'This page\'s data could not be loaded. Please try again later.',
            ],
            [
                'key'          => 'global.our_books',
                'group'        => 'global',
                'display_name' => 'Our Books (global label)',
                'input_type'   => 'text',
                'value'        => 'Our Books',
            ],
            [
                'key'          => 'global.your_name',
                'group'        => 'global',
                'display_name' => 'Your Name (global label)',
                'input_type'   => 'text',
                'value'        => 'Your Name',
            ],

            // ─────────────────── HOME PAGE ───────────────────
            [
                'key'          => 'home.story_title',
                'group'        => 'home',
                'display_name' => 'Home: Story Title',
                'input_type'   => 'text',
                'value'        => 'Gifts for all your favourite people',
            ],
            [
                'key'          => 'home.error_text',
                'group'        => 'home',
                'display_name' => 'Home: Error Text',
                'input_type'   => 'textarea',
                'value'        => 'This section data could not be loaded. Please try again later.',
            ],
            [
                'key'          => 'home.feature_title',
                'group'        => 'home',
                'display_name' => 'Home: Feature Section Title',
                'input_type'   => 'text',
                'value'        => 'Our Most Loved Tales',
            ],
            [
                'key'          => 'home.review_title',
                'group'        => 'home',
                'display_name' => 'Home: Review Section Title',
                'input_type'   => 'text',
                'value'        => 'Loved by Families',
            ],
            [
                'key'          => 'home.review_subtitle',
                'group'        => 'home',
                'display_name' => 'Home: Review Section Subtitle',
                'input_type'   => 'text',
                'value'        => 'Real stories from real parents and gift-givers.',
            ],
            [
                'key'          => 'home.faq_title',
                'group'        => 'home',
                'display_name' => 'Home: FAQ Section Title',
                'input_type'   => 'text',
                'value'        => 'Everything you need to know',
            ],
            [
                'key'          => 'home.subscribe_email_placeholder',
                'group'        => 'home',
                'display_name' => 'Home: Subscribe Email Placeholder',
                'input_type'   => 'text',
                'value'        => 'Enter your email',
            ],
            [
                'key'          => 'home.subscribe_btn',
                'group'        => 'home',
                'display_name' => 'Home: Subscribe Button Text',
                'input_type'   => 'text',
                'value'        => 'Subscribe',
            ],
            [
                'key'          => 'home.subscribe_error_invalid',
                'group'        => 'home',
                'display_name' => 'Home: Subscribe Invalid Email Error',
                'input_type'   => 'text',
                'value'        => 'Please enter a valid email address.',
            ],
            [
                'key'          => 'home.subscribe_error_required',
                'group'        => 'home',
                'display_name' => 'Home: Subscribe Required Email Error',
                'input_type'   => 'text',
                'value'        => 'An email address is required.',
            ],
            [
                'key'          => 'home.footer_copyright_suffix',
                'group'        => 'home',
                'display_name' => 'Home: Footer Copyright Text',
                'input_type'   => 'text',
                'value'        => 'All rights reserved.',
            ],

            // ─────────────────── COUPON DIALOG ───────────────────
            [
                'key'          => 'coupon.dialog_title',
                'group'        => 'coupon',
                'display_name' => 'Coupon Dialog: Title',
                'input_type'   => 'text',
                'value'        => 'You\'re in!',
            ],
            [
                'key'          => 'coupon.dialog_text',
                'group'        => 'coupon',
                'display_name' => 'Coupon Dialog: Description Text',
                'input_type'   => 'text',
                'value'        => 'Your welcome coupon',
            ],
            [
                'key'          => 'coupon.dialog_btn',
                'group'        => 'coupon',
                'display_name' => 'Coupon Dialog: Shop Button Text',
                'input_type'   => 'text',
                'value'        => 'Shop All Books',
            ],

            // ─────────────────── BOOKS PAGE ───────────────────
            [
                'key'          => 'books.filters',
                'group'        => 'books',
                'display_name' => 'Books: Filter Tab Labels',
                'input_type'   => 'json',
                'value'        => json_encode(['All', 'Newborns', 'Kids', 'Adults']),
            ],
            [
                'key'          => 'books.subtitle',
                'group'        => 'books',
                'display_name' => 'Books: Subtitle',
                'input_type'   => 'textarea',
                'value'        => 'Discover magical, personalized adventures designed perfectly for their reading level.',
            ],
            [
                'key'          => 'books.title',
                'group'        => 'books',
                'display_name' => 'Books: Main Title',
                'input_type'   => 'text',
                'value'        => 'Perfect stories for',
            ],
            [
                'key'          => 'books.empty_fallback',
                'group'        => 'books',
                'display_name' => 'Books: Empty Category Message',
                'input_type'   => 'textarea',
                'value'        => 'There are no books available for this category. Please check back later.',
            ],

            // ─────────────────── BOOK DETAILS PAGE ───────────────────
            [
                'key'          => 'book_details.badge_bestseller',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Best Selling Badge',
                'input_type'   => 'text',
                'value'        => 'BEST SELLING',
            ],
            [
                'key'          => 'book_details.price_from',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Price Prefix',
                'input_type'   => 'text',
                'value'        => 'From',
            ],
            [
                'key'          => 'book_details.recommended_gift',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Recommended Gift Label',
                'input_type'   => 'text',
                'value'        => 'Recommended gift',
            ],
            [
                'key'          => 'book_details.customize_btn',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Customize Now Button',
                'input_type'   => 'text',
                'value'        => 'Customize Now',
            ],
            [
                'key'          => 'book_details.preview_note',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Preview Note Under Button',
                'input_type'   => 'text',
                'value'        => 'Preview before payment • Takes less than 2 minutes',
            ],
            [
                'key'          => 'book_details.trust_badge',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Trust Badge Text',
                'input_type'   => 'json',
                'value'        => json_encode([
                    'secure'       => 'Secure Payment',
                    'delivery'     => 'Delivery in 3-5 days',
                    'satisfaction' => '100% Satisfaction',
                ]),
            ],
            [
                'key'          => 'book_details.features',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Features List',
                'input_type'   => 'json',
                'value'        => json_encode([
                    [
                        'icon'  => 'User',
                        'label' => 'Fully Personalized',
                        'desc'  => 'Name, skin tone, hair color — every detail is uniquely theirs.',
                    ],
                    [
                        'icon'  => 'Smile',
                        'label' => 'Choose Their Character',
                        'desc'  => 'A character that looks and feels just like your child.',
                    ],
                    [
                        'icon'  => 'BookOpen',
                        'label' => 'Premium Hardcover',
                        'desc'  => '40 beautifully illustrated pages with archival-quality printing.',
                    ],
                    [
                        'icon'  => 'Truck',
                        'label' => 'Fast Delivery',
                        'desc'  => 'Order by April 12 for guaranteed Easter delivery.',
                    ],
                ]),
            ],
            [
                'key'          => 'book_details.story_feature_desc',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Story Feature Description',
                'input_type'   => 'textarea',
                'value'        => 'This isn\'t just a book. It\'s the first time your child will ever point to a page and say — "That\'s me!"',
            ],
            [
                'key'          => 'book_details.story_feature_title',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Story Feature Title',
                'input_type'   => 'text',
                'value'        => 'A story written just for them',
            ],
            [
                'key'          => 'book_details.story_feature_subtitle',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Story Feature Subtitle',
                'input_type'   => 'text',
                'value'        => 'What makes this special',
            ],
            [
                'key'          => 'book_details.show_all_btn',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Show All Button',
                'input_type'   => 'text',
                'value'        => 'Show All',
            ],
            [
                'key'          => 'book_details.show_less_btn',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Show Less Button',
                'input_type'   => 'text',
                'value'        => 'Show Less',
            ],
            [
                'key'          => 'book_details.review_heading',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Write Review Heading',
                'input_type'   => 'text',
                'value'        => 'Share Your Experience',
            ],
            [
                'key'          => 'book_details.review_rating_label',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Click to Rate Label',
                'input_type'   => 'text',
                'value'        => 'Click to rate',
            ],
            [
                'key'          => 'book_details.review_name_label',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Review Name Label',
                'input_type'   => 'text',
                'value'        => 'YOUR NAME',
            ],
            [
                'key'          => 'book_details.review_name_placeholder',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Review Name Placeholder',
                'input_type'   => 'text',
                'value'        => 'Enter your name',
            ],
            [
                'key'          => 'book_details.review_location_label',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Review Location Label',
                'input_type'   => 'text',
                'value'        => 'YOUR LOCATION',
            ],
            [
                'key'          => 'book_details.review_location_placeholder',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Review Location Placeholder',
                'input_type'   => 'text',
                'value'        => 'Enter your location',
            ],
            [
                'key'          => 'book_details.review_text_label',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Review Text Label',
                'input_type'   => 'text',
                'value'        => 'YOUR REVIEW',
            ],
            [
                'key'          => 'book_details.review_text_placeholder',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Review Textarea Placeholder',
                'input_type'   => 'text',
                'value'        => 'Share your experience…',
            ],
            [
                'key'          => 'book_details.review_submit_btn',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Review Submit Button',
                'input_type'   => 'text',
                'value'        => 'SUBMIT REVIEW',
            ],
            [
                'key'          => 'book_details.review_validation',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Review Form Validation Messages',
                'input_type'   => 'json',
                'value'        => json_encode([
                    'name_too_short'       => 'Name is too short',
                    'location_required'    => 'Location is required',
                    'review_too_short'     => 'Review must contain at least 10 characters',
                    'rating_required'      => 'Please select a rating',
                ]),
            ],
            [
                'key'          => 'book_details.you_may_also_love',
                'group'        => 'book_details',
                'display_name' => 'Book Details: You May Also Love Title',
                'input_type'   => 'text',
                'value'        => 'You may also love',
            ],
            [
                'key'          => 'book_details.subscribe_title',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Subscribe Section Title',
                'input_type'   => 'text',
                'value'        => 'Get 10% off your first order',
            ],
            [
                'key'          => 'book_details.subscribe_desc',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Subscribe Section Description',
                'input_type'   => 'textarea',
                'value'        => 'Join our community and create magical moments for the children you love.',
            ],
            [
                'key'          => 'book_details.subscribe_placeholder',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Subscribe Email Placeholder',
                'input_type'   => 'text',
                'value'        => 'Enter your email',
            ],
            [
                'key'          => 'book_details.subscribe_btn',
                'group'        => 'book_details',
                'display_name' => 'Book Details: Subscribe Button',
                'input_type'   => 'text',
                'value'        => 'Subscribe',
            ],
            [
                'key'          => 'book_details.faq_subtitle',
                'group'        => 'book_details',
                'display_name' => 'Book Details: FAQ Subtitle',
                'input_type'   => 'text',
                'value'        => 'Got questions?',
            ],
            [
                'key'          => 'book_details.faq_title',
                'group'        => 'book_details',
                'display_name' => 'Book Details: FAQ Title',
                'input_type'   => 'text',
                'value'        => 'Everything you need to know',
            ],

            // ─────────────────── SEARCH ───────────────────
            [
                'key'          => 'search.label',
                'group'        => 'search',
                'display_name' => 'Search: Input Label',
                'input_type'   => 'text',
                'value'        => 'Search',
            ],
            [
                'key'          => 'search.placeholder',
                'group'        => 'search',
                'display_name' => 'Search: Input Placeholder',
                'input_type'   => 'text',
                'value'        => 'Search for a book…',
            ],
            [
                'key'          => 'search.results_heading',
                'group'        => 'search',
                'display_name' => 'Search: Results Section Heading',
                'input_type'   => 'text',
                'value'        => 'Books',
            ],
            [
                'key'          => 'search.no_results',
                'group'        => 'search',
                'display_name' => 'Search: No Results Message Prefix',
                'input_type'   => 'text',
                'value'        => 'No Books found for',
            ],

            // ─────────────────── CONTACT PAGE ───────────────────
            [
                'key'          => 'contact.first_name_placeholder',
                'group'        => 'contact',
                'display_name' => 'Contact: First Name Placeholder',
                'input_type'   => 'text',
                'value'        => 'Enter your first name',
            ],
            [
                'key'          => 'contact.last_name_placeholder',
                'group'        => 'contact',
                'display_name' => 'Contact: Last Name Placeholder',
                'input_type'   => 'text',
                'value'        => 'Enter your last name',
            ],
            [
                'key'          => 'contact.email_placeholder',
                'group'        => 'contact',
                'display_name' => 'Contact: Email Placeholder',
                'input_type'   => 'text',
                'value'        => 'Enter your email address',
            ],
            [
                'key'          => 'contact.order_number_placeholder',
                'group'        => 'contact',
                'display_name' => 'Contact: Order Number Placeholder',
                'input_type'   => 'text',
                'value'        => 'e.g. LYM-XXXXXXXX',
            ],
            [
                'key'          => 'contact.message_placeholder',
                'group'        => 'contact',
                'display_name' => 'Contact: Message Placeholder',
                'input_type'   => 'text',
                'value'        => 'Tell us how we can help you...',
            ],
            [
                'key'          => 'contact.validation',
                'group'        => 'contact',
                'display_name' => 'Contact: Form Validation Messages',
                'input_type'   => 'json',
                'value'        => json_encode([
                    'first_name_required'   => 'First name is required',
                    'last_name_required'    => 'Last name is required',
                    'email_invalid'         => 'Please enter a valid email address',
                    'order_number_required' => 'An order number is required',
                    'message_too_short'     => 'Message must be at least 10 characters',
                ]),
            ],

            // ─────────────────── COOKIE MODAL ───────────────────
            [
                'key'          => 'cookie.modal',
                'group'        => 'cookie',
                'display_name' => 'Cookie Modal: All Texts',
                'input_type'   => 'json',
                'value'        => json_encode([
                    'title'         => 'Cookie Policy',
                    'description'   => 'We use cookies to enhance your browsing experience. By continuing, you agree to their use.',
                    'cookie_policy' => 'Cookie Policy →',
                    'show_details'  => 'More information',
                    'hide_details'  => 'Hide details',
                    'necessary'     => 'Necessary cookies',
                    'necessary_desc'=> '— required for the website to function',
                    'analytics'     => 'Analytics cookies',
                    'analytics_desc'=> '— help us understand visits',
                    'marketing'     => 'Marketing cookies',
                    'marketing_desc'=> '— personalized content',
                    'decline'       => 'Decline',
                    'accept'        => 'Accept all',
                ]),
            ],

            // ─────────────────── ORDER CONFIRMED ───────────────────
            [
                'key'          => 'order_confirmed.content',
                'group'        => 'orders',
                'display_name' => 'Order Confirmed: All Page Content',
                'input_type'   => 'json',
                'value'        => json_encode([
                    'steps' => [
                        [
                            'icon'  => 'Package',
                            'title' => 'Order Confirmed',
                            'desc'  => 'Confirmation sent to your email',
                        ],
                        [
                            'icon'  => 'Truck',
                            'title' => 'Book Personalization',
                            'desc'  => 'Personalization within 24 hours',
                        ],
                        [
                            'icon'  => 'Home',
                            'title' => 'Estimated Delivery',
                            'desc'  => 'Delivery in 3-5 business days',
                        ],
                    ],
                    'heading' => [
                        'title'       => 'Thank you, your order is confirmed!',
                        'description' => 'We have received your order, and our little printing elves are already hard at work. We\'ve sent a confirmation to your email address.',
                    ],
                    'order_card' => [
                        'status_label' => 'Order Status',
                        'status_badge' => 'Confirmed',
                    ],
                    'actions' => [
                        'back_home'  => 'Back to Home',
                        'more_books' => 'More Books',
                    ],
                    'alt_text' => [
                        'checked_icon' => 'checked',
                    ],
                ]),
            ],

            // ─────────────────── ORDER FAILED ───────────────────
            [
                'key'          => 'order_failed.content',
                'group'        => 'orders',
                'display_name' => 'Order Failed: All Page Content',
                'input_type'   => 'json',
                'value'        => json_encode([
                    'reasons' => [
                        [
                            'icon'  => 'ShieldAlert',
                            'title' => 'Payment Declined',
                            'desc'  => 'Your card has not been charged',
                            'href'  => null,
                        ],
                        [
                            'icon'  => 'Package',
                            'title' => 'Order Not Placed',
                            'desc'  => 'Nothing has been reserved',
                            'href'  => null,
                        ],
                        [
                            'icon'  => 'HeadphonesIcon',
                            'title' => 'Need Help?',
                            'desc'  => 'Contact our support team',
                            'href'  => '/help',
                        ],
                    ],
                    'heading' => [
                        'title'       => 'Oops! We couldn\'t place your order.',
                        'description' => 'There was an error processing your payment. Don\'t worry — your cart has been saved and you haven\'t been charged.',
                    ],
                    'order_card' => [
                        'status_label' => 'Order Status',
                        'status_badge' => 'Failed',
                    ],
                    'actions' => [
                        'back_home' => 'Back to Home',
                        'try_again' => 'Try Again',
                    ],
                ]),
            ],
        ];

        foreach ($langs as $lang) {
            foreach ($translations as $t) {
                SiteTranslation::updateOrCreate(
                    ['key' => $t['key'], 'language_type' => $lang],
                    [
                        'group'        => $t['group'],
                        'display_name' => $t['display_name'],
                        'input_type'   => $t['input_type'],
                        'value'        => $t['value'],
                    ]
                );
            }
        }
    }
}
