<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagesSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Our Story',
                'slug' => 'our-story',
                'meta_title' => 'Our Story - Lymetales',
                'meta_description' => 'Learn about the origins of Lymetales and our mission to create personalised stories for children.',
                'is_active' => true,
                'content' => json_encode([
                    'hero' => [
                        'badge' => 'OUR STORY',
                        'title' => 'Stories That Stay Forever.',
                        'subtitle' => 'We believe every child deserves to be the hero of their own universe.',
                        'button_text' => 'See Our Books',
                        'button_url' => '/books',
                        'image_url' => '',
                    ],
                    'mission' => [
                        'title' => 'We believe every child deserves to be the hero of their own universe.',
                        'paragraph_1' => 'Lymetales was born from a simple idea: the most magical stories are the ones where your child is the star. We create fully personalised picture books that weave a child\'s name and likeness into every page.',
                        'paragraph_2' => 'Each book is crafted with care, combining beautiful illustration with a timeless story designed to spark imagination, build confidence, and become a cherished memory.',
                    ],
                    'quality_section' => [
                        'left' => [
                            'badge' => 'A gap on the bookshelf',
                            'title' => 'A gap on the bookshelf',
                            'paragraph_1' => 'We noticed something missing from children\'s bookshelves — books that truly reflect the child holding them.',
                            'paragraph_2' => 'So we set out to close that gap. Every Lymetales book is made with that mission in mind.',
                            'image_url' => '',
                        ],
                        'right' => [
                            'badge' => 'Uncompromising Quality',
                            'title' => 'Uncompromising Quality',
                            'paragraph_1' => 'From printing to binding, we partner with premium producers who share our standards.',
                            'paragraph_2' => 'Every book is printed on thick, child-safe pages built to survive the adventures of childhood.',
                            'image_url' => '',
                        ],
                    ],
                    'steps' => [
                        'title' => 'Creating Magic In 3 Steps',
                        'items' => [
                            ['step' => '1', 'title' => 'Personalise Your Book', 'description' => 'Enter your child\'s name, choose their character and select the language of the story.'],
                            ['step' => '2', 'title' => 'We Print & Bind', 'description' => 'Our production team crafts your unique book with premium materials.'],
                            ['step' => '3', 'title' => 'Delivered to Your Door', 'description' => 'Your personalised book arrives beautifully packaged and ready to gift.'],
                        ],
                    ],
                    'difference' => [
                        'title' => 'The Lymetales Difference',
                        'items' => [
                            ['title' => 'Child-Centred Design', 'description' => 'Every illustration and story beat is crafted to place your child at the heart of the adventure.'],
                            ['title' => 'Instant Gifts', 'description' => 'Order today and receive your book in just a few working days — perfect for last-minute gifts.'],
                            ['title' => 'Real Little Magic', 'description' => 'Watch your child\'s eyes light up when they see themselves as the hero of their very own story.'],
                            ['title' => 'Built to Last', 'description' => 'Our books are made to be kept, re-read, and passed down as a treasured family memory.'],
                        ],
                    ],
                    'stats' => [
                        'number' => '1M+',
                        'label' => 'Personalised stories bringing magic to families around the world.',
                        'quote' => '"A proven result, every Lymetales book has been made for exactly the little one."',
                    ],
                    'gallery' => [
                        'title' => 'From our studio, to their hands.',
                        'images' => [],
                    ],
                    'cta' => [
                        'title' => 'Create something they\'ll never forget.',
                        'description' => 'Begin your personalised story today.',
                        'button_text' => 'Start Now',
                        'button_url' => '/personalised-gifts',
                        'image_url' => '',
                    ],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'meta_title' => 'Privacy Policy - Lymetales',
                'meta_description' => 'Read how Lymetales handles your personal data and privacy.',
                'is_active' => true,
                'content' => json_encode([
                    'header' => [
                        'badge' => 'LEGAL',
                        'title' => 'Privacy Policy',
                        'last_updated' => 'April 2025',
                    ],
                    'sections' => [
                        ['title' => '1. Who we are', 'body' => 'Lymetales is a personalised children\'s book company operated by Lymetales HQ, Inc. Where you can contact us, we put all the info available on this page for your personal information. You can also reach us at hello@lymetales.com.'],
                        ['title' => '2. What data we collect', 'body' => 'We collect information you provide directly, your name, email address, shipping address, phone number, and personalisation details (such as a child\'s name and character choices). We also collect payment information, though this is processed securely by our payment providers and never stored on our servers.'],
                        ['title' => '3. How we use your data', 'body' => 'We use your data to process and fulfil your orders, send order confirmations and shipping updates, respond to your support requests, and improve our products and services. With your consent, we may also send you marketing emails about new books and promotions.'],
                        ['title' => '4. Personalisation data', 'body' => 'Details you enter for book personalisation (such as a child\'s name, age, and character traits) are used solely to produce your order. We do not share this data with third parties for marketing purposes and delete it from active systems within 30 days of order completion.'],
                        ['title' => '5. Cookies & tracking', 'body' => 'We use essential cookies to keep our site functioning, and optional analytics cookies (via tools like Google Analytics) to understand how visitors are using our site. You can manage your cookie preferences at any time through our cookie banner or browser settings.'],
                        ['title' => '6. Sharing your data', 'body' => 'We share your data only with trusted service providers who help us operate our business — including our print and fulfilment partners, payment processors (Stripe, PayPal), and shipping carriers. These partners are contractually bound to protect your data and use it only for the services they provide to us.'],
                        ['title' => '7. Data retention', 'body' => 'We retain your order and account data for up to 5 years for legal and accounting purposes. You may request earlier deletion by contacting us.'],
                        ['title' => '8. Your rights', 'body' => 'Depending on your location, you may have the right to access, correct, delete, or export your personal data, as well as the right to object to or restrict certain processing. To exercise any of these rights, contact us at hello@lymetales.com and we will respond within 30 days.'],
                        ['title' => '9. Children\'s privacy', 'body' => 'Our site is intended for adults purchasing on and off of children. We do not knowingly collect personal data directly from children under 13. Personalisation details about a child (such as their name) are provided by the purchasing adult and used solely to produce the ordered product.'],
                        ['title' => '10. Security', 'body' => 'We use industry-standard encryption (TLS/SSL) to protect data in transit and apply appropriate technical and organisational measures to keep your data secure. In the unlikely event of a data breach affecting your rights, we will notify you and the relevant authorities as required by law.'],
                        ['title' => '11. Changes to this policy', 'body' => 'We may update this Privacy Policy from time to time. When we do, we will revise the \'Last updated\' date at the top of the page. Significant changes will be communicated to you by email or via a notice on our website.'],
                        ['title' => '12. Contact', 'body' => 'For any privacy-related questions or requests, please contact us at hello@lymetales.com. We are happy to help and aim to respond within one working day.'],
                    ],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-and-conditions',
                'meta_title' => 'Terms of Service - Lymetales',
                'meta_description' => 'Read the terms and conditions for using Lymetales services.',
                'is_active' => true,
                'content' => json_encode([
                    'header' => [
                        'badge' => 'LEGAL',
                        'title' => 'Terms of Service',
                        'last_updated' => 'April 2025',
                    ],
                    'sections' => [
                        ['title' => '1. Welcome', 'body' => 'By using Lymetales, and/or placing an order, you agree to these Terms. They exist to make shopping with us simple and fair.'],
                        ['title' => '2. Orders', 'body' => 'All orders are subject to availability and confirmation. We reserve the right to refuse or cancel any order, for example, if a personalisation request is unlawful or contains offensive content.'],
                        ['title' => '3. Personalisation', 'body' => 'You are responsible for the accuracy of personalisation details (names, spelling, character traits). Please review your preview carefully — once production starts (within 2 hours of ordering), changes can no longer be made.'],
                        ['title' => '4. Pricing & payment', 'body' => 'Prices are shown in euros and include applicable VAT. Payment is taken at checkout via Stripe, PayPal, Apple Pay or Google Pay. Your order is confirmed when payment is successful.'],
                        ['title' => '5. Shipping', 'body' => 'We aim to dispatch within 1–2 working days. Delivery times depend on your destination and chosen carrier.'],
                        ['title' => '6. Returns', 'body' => 'Because each book is uniquely personalised, we cannot accept returns for change of mind. If your order arrives damaged or contains a printing error caused by us, contact us within 14 days for a free replacement.'],
                        ['title' => '7. Intellectual property', 'body' => 'All stories, illustrations and designs are the property of Lymetales or our partners. You may not reproduce, resell or distribute our content without written permission.'],
                        ['title' => '8. Limitation of liability', 'body' => 'To the maximum extent permitted by law, Lymetales is not liable for indirect or consequential damages arising from your use of the site or our products.'],
                        ['title' => '9. Governing law', 'body' => 'These Terms are governed by the laws of Portugal. Any disputes will be resolved in the courts of Lisbon, without prejudice to mandatory consumer-protection rights.'],
                        ['title' => '10. Contact', 'body' => 'Questions? Write to hello@lymetales.com. We\'re happy to help and usually respond within one working day.'],
                    ],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Questions & Answers',
                'slug' => 'faq',
                'meta_title' => 'FAQ - Lymetales',
                'meta_description' => 'Everything you need to know about creating, ordering, and gifting a Lymetales book.',
                'is_active' => true,
                'content' => json_encode([
                    'header' => [
                        'badge' => 'FREQUENTLY ASKED',
                        'title' => 'Questions, Answered',
                        'subtitle' => 'Everything you need to know about creating, ordering, and gifting a Lymetales book.',
                    ],
                    'categories' => [
                        [
                            'name' => 'Personalisation',
                            'questions' => [
                                ['question' => 'How does personalisation work?', 'answer' => 'Enter your child\'s name and choose their character during the ordering process. Our system weaves these details into every page of the story.'],
                                ['question' => 'Can I edit my personalisation after ordering?', 'answer' => 'Changes can be made within 2 hours of placing your order. After that, production begins and changes are no longer possible.'],
                                ['question' => 'Is the story rewritten with my child\'s name?', 'answer' => 'Yes! The child\'s name is woven into the narrative, not just the cover. Every mention of the hero in the story uses the name you provide.'],
                            ],
                        ],
                        [
                            'name' => 'Shipping & delivery',
                            'questions' => [
                                ['question' => 'How long does it take?', 'answer' => 'We produce and dispatch within 1–2 working days. Delivery then takes 2–5 working days for Europe, and 5–10 days for the rest of the world.'],
                                ['question' => 'Do you ship internationally?', 'answer' => 'Yes! We ship worldwide. Shipping costs and times vary by destination and are shown at checkout.'],
                                ['question' => 'Is shipping free?', 'answer' => 'We offer free shipping on orders over €60. Otherwise, shipping costs are calculated at checkout based on your location.'],
                            ],
                        ],
                        [
                            'name' => 'Returns & quality',
                            'questions' => [
                                ['question' => 'Can I return a personalised book?', 'answer' => 'Because each book is uniquely made, we cannot accept returns for change of mind. If there is a defect or printing error, we will replace it for free — just contact us within 14 days.'],
                                ['question' => 'What if there\'s a typo or mistake?', 'answer' => 'If the typo was caused by us (e.g., the name was printed differently from what you entered), we\'ll send a replacement at no cost. If the typo came from the information you provided, we can offer a discounted reprint.'],
                            ],
                        ],
                        [
                            'name' => 'Gifts & vouchers',
                            'questions' => [
                                ['question' => 'Do you offer gift cards?', 'answer' => 'Yes! You can purchase digital gift cards in various amounts from our website. They\'re delivered instantly by email.'],
                                ['question' => 'Can I include a gift message?', 'answer' => 'Absolutely. You can add a personalised gift message during checkout, and it will be printed on a card included with your order.'],
                            ],
                        ],
                    ],
                    'cta' => [
                        'title' => 'Still have questions?',
                        'subtitle' => 'Our team usually replies within a few hours.',
                        'button_text' => 'CONTACT US',
                        'button_url' => '/contact',
                    ],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Contact With Us',
                'slug' => 'contact-us',
                'meta_title' => 'Contact Us - Lymetales',
                'meta_description' => 'Get in touch with the Lymetales team.',
                'is_active' => true,
                'content' => json_encode([
                    'header' => [
                        'badge' => 'WE\'D LOVE TO HEAR FROM YOU',
                        'title' => 'Contact With Us',
                        'subtitle' => 'Questions about a book, an order, or a custom request? Our little team reads every message.',
                    ],
                    'contact_info' => [
                        [
                            'type' => 'email',
                            'label' => 'EMAIL',
                            'value' => 'hello@lymetales.com',
                            'note' => 'We reply within 24h',
                            'icon' => 'email',
                        ],
                        [
                            'type' => 'chat',
                            'label' => 'LIVE CHAT',
                            'value' => 'Available in-app',
                            'note' => 'Mon–Fri, 9–18 CET',
                            'icon' => 'chat',
                        ],
                        [
                            'type' => 'production',
                            'label' => 'PRODUCTION',
                            'value' => '1–2 working days',
                            'note' => 'Then shipped worldwide',
                            'icon' => 'clock',
                        ],
                    ],
                    'form' => [
                        'title' => 'Send us a message',
                        'subtitle' => 'We answer every message — usually within a working day.',
                        'submit_button_text' => 'Send message',
                        'privacy_note' => 'By sending you agree to our privacy policy.',
                    ],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Cookie Policy',
                'slug' => 'cookie-policy',
                'meta_title' => 'Cookie Policy - Lymetales',
                'meta_description' => 'Read how Lymetales uses cookies to improve your experience.',
                'is_active' => true,
                'content' => json_encode([
                    'header' => [
                        'badge' => 'LEGAL',
                        'title' => 'Cookie Policy',
                        'last_updated' => 'April 2025',
                    ],
                    'sections' => [
                        ['title' => '1. What are cookies', 'body' => 'Cookies are small text files that are stored on your browser or device when you visit our website. They help us provide a better experience, remember your preferences, and understand how you interact with our site.'],
                        ['title' => '2. How we use cookies', 'body' => 'We use cookies to keep our website running securely, remember your language choices, analyze website traffic, and show you personalized content. Some cookies are essential for our site to work, while others are optional.'],
                    ],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $languages = ['SL', 'HR', 'EN'];
        foreach ($languages as $lang) {
            foreach ($pages as $page) {
                $pageData = $page;
                $pageData['language_type'] = $lang;

                DB::table('pages')->updateOrInsert(
                    ['slug' => $page['slug'], 'language_type' => $lang],
                    $pageData
                );
            }
        }
    }
}
