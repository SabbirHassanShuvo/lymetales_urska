<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    // Map slug → specific view
    private array $specificViews = [
        'our-story'           => 'admin.pages.specific.our-story',
        'privacy-policy'      => 'admin.pages.specific.privacy-policy',
        'terms-and-conditions'=> 'admin.pages.specific.terms',
        'faq'                 => 'admin.pages.specific.faq',
        'contact-us'          => 'admin.pages.specific.contact-us',
    ];

    public function index()
    {
        $pages = Page::orderBy('id', 'asc')->get();
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug'  => 'required|string|max:255|unique:pages,slug',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'is_active' => 'boolean',
            'content' => 'nullable|string',
        ]);

        $content = $request->input('content');
        if (!empty($content)) {
            $decoded = json_decode($content, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $content = $decoded;
            }
        }

        Page::create([
            'title' => $request->input('title'),
            'slug'  => Str::slug($request->input('slug')),
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'is_active' => $request->boolean('is_active', true),
            'content'   => $content,
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        $content = is_array($page->content) ? $page->content : (json_decode($page->content, true) ?? []);

        // Load slug-specific view if it exists
        if (isset($this->specificViews[$page->slug])) {
            return view($this->specificViews[$page->slug], compact('page', 'content'));
        }

        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        // Dispatch to specific handler based on slug
        return match ($page->slug) {
            'our-story'            => $this->updateOurStory($request, $page),
            'privacy-policy'       => $this->updateLegalPage($request, $page),
            'terms-and-conditions' => $this->updateLegalPage($request, $page),
            'faq'                  => $this->updateFaq($request, $page),
            'contact-us'           => $this->updateContactUs($request, $page),
            default                => $this->updateGeneric($request, $page),
        };
    }

    // ─── Our Story ────────────────────────────────────────────────────────────
    private function updateOurStory(Request $request, Page $page)
    {
        $content = [
            'hero' => [
                'badge'       => $request->input('hero_badge'),
                'title'       => $request->input('hero_title'),
                'subtitle'    => $request->input('hero_subtitle'),
                'button_text' => $request->input('hero_button_text'),
                'button_url'  => $request->input('hero_button_url'),
                'image_url'   => $request->input('hero_image_url'),
            ],
            'mission' => [
                'title'       => $request->input('mission_title'),
                'paragraph_1' => $request->input('mission_paragraph_1'),
                'paragraph_2' => $request->input('mission_paragraph_2'),
            ],
            'quality_section' => [
                'left' => [
                    'badge'       => $request->input('quality_left_badge'),
                    'title'       => $request->input('quality_left_title'),
                    'paragraph_1' => $request->input('quality_left_p1'),
                    'paragraph_2' => $request->input('quality_left_p2'),
                    'image_url'   => $request->input('quality_left_image'),
                ],
                'right' => [
                    'badge'       => $request->input('quality_right_badge'),
                    'title'       => $request->input('quality_right_title'),
                    'paragraph_1' => $request->input('quality_right_p1'),
                    'paragraph_2' => $request->input('quality_right_p2'),
                    'image_url'   => $request->input('quality_right_image'),
                ],
            ],
            'steps' => [
                'title' => $request->input('steps_title'),
                'items' => collect($request->input('steps_step', []))->map(fn($s, $i) => [
                    'step'        => $s,
                    'title'       => $request->input('steps_item_title')[$i] ?? '',
                    'description' => $request->input('steps_item_desc')[$i] ?? '',
                ])->values()->toArray(),
            ],
            'difference' => [
                'title' => $request->input('difference_title'),
                'items' => collect($request->input('diff_title', []))->map(fn($t, $i) => [
                    'title'       => $t,
                    'description' => $request->input('diff_desc')[$i] ?? '',
                ])->values()->toArray(),
            ],
            'stats' => [
                'number' => $request->input('stats_number'),
                'label'  => $request->input('stats_label'),
                'quote'  => $request->input('stats_quote'),
            ],
            'gallery' => [
                'title'  => $request->input('gallery_title'),
                'images' => array_filter(explode("\n", $request->input('gallery_images', ''))),
            ],
            'cta' => [
                'title'       => $request->input('cta_title'),
                'description' => $request->input('cta_description'),
                'button_text' => $request->input('cta_button_text'),
                'button_url'  => $request->input('cta_button_url'),
                'image_url'   => $request->input('cta_image_url'),
            ],
        ];

        $page->update([
            'title'            => $request->input('title'),
            'meta_title'       => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'is_active'        => $request->boolean('is_active', true),
            'content'          => $content,
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Our Story page updated.');
    }

    // ─── Legal Pages (Privacy + Terms) ─────────────────────────────────────────
    private function updateLegalPage(Request $request, Page $page)
    {
        $sections = [];
        $titles   = $request->input('section_title', []);
        $bodies   = $request->input('section_body', []);
        foreach ($titles as $i => $title) {
            if (!empty($title)) {
                $sections[] = ['title' => $title, 'body' => $bodies[$i] ?? ''];
            }
        }

        $content = [
            'header' => [
                'badge'        => $request->input('header_badge'),
                'title'        => $request->input('header_title'),
                'last_updated' => $request->input('last_updated'),
            ],
            'sections' => $sections,
        ];

        $page->update([
            'title'            => $request->input('title'),
            'meta_title'       => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'is_active'        => $request->boolean('is_active', true),
            'content'          => $content,
        ]);

        return redirect()->route('admin.pages.index')->with('success', $page->title . ' page updated.');
    }

    // ─── FAQ ──────────────────────────────────────────────────────────────────
    private function updateFaq(Request $request, Page $page)
    {
        $categories = [];
        $catNames   = $request->input('cat_name', []);
        $questions  = $request->input('question', []);
        $answers    = $request->input('answer', []);
        $catIndexes = $request->input('cat_index', []);

        foreach ($catNames as $ci => $catName) {
            if (!empty($catName)) {
                $catQuestions = [];
                foreach ($catIndexes as $qi => $idx) {
                    if ((int)$idx === $ci && !empty($questions[$qi])) {
                        $catQuestions[] = [
                            'question' => $questions[$qi],
                            'answer'   => $answers[$qi] ?? '',
                        ];
                    }
                }
                $categories[] = ['name' => $catName, 'questions' => $catQuestions];
            }
        }

        $content = [
            'header' => [
                'badge'    => $request->input('header_badge'),
                'title'    => $request->input('header_title'),
                'subtitle' => $request->input('header_subtitle'),
            ],
            'categories' => $categories,
            'cta' => [
                'title'       => $request->input('cta_title'),
                'subtitle'    => $request->input('cta_subtitle'),
                'button_text' => $request->input('cta_button_text'),
                'button_url'  => $request->input('cta_button_url'),
            ],
        ];

        $page->update([
            'title'            => $request->input('title'),
            'meta_title'       => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'is_active'        => $request->boolean('is_active', true),
            'content'          => $content,
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'FAQ page updated.');
    }

    // ─── Contact Us ───────────────────────────────────────────────────────────
    private function updateContactUs(Request $request, Page $page)
    {
        $contactInfo = [];
        $types   = $request->input('ci_type', []);
        $labels  = $request->input('ci_label', []);
        $values  = $request->input('ci_value', []);
        $notes   = $request->input('ci_note', []);
        $icons   = $request->input('ci_icon', []);

        foreach ($types as $i => $type) {
            if (!empty($labels[$i])) {
                $contactInfo[] = [
                    'type'  => $type,
                    'label' => $labels[$i],
                    'value' => $values[$i] ?? '',
                    'note'  => $notes[$i] ?? '',
                    'icon'  => $icons[$i] ?? '',
                ];
            }
        }

        $content = [
            'header' => [
                'badge'    => $request->input('header_badge'),
                'title'    => $request->input('header_title'),
                'subtitle' => $request->input('header_subtitle'),
            ],
            'contact_info' => $contactInfo,
            'form' => [
                'title'              => $request->input('form_title'),
                'subtitle'           => $request->input('form_subtitle'),
                'submit_button_text' => $request->input('form_submit_text'),
                'privacy_note'       => $request->input('form_privacy_note'),
            ],
        ];

        $page->update([
            'title'            => $request->input('title'),
            'meta_title'       => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'is_active'        => $request->boolean('is_active', true),
            'content'          => $content,
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Contact Us page updated.');
    }

    // ─── Generic ──────────────────────────────────────────────────────────────
    private function updateGeneric(Request $request, Page $page)
    {
        $content = $request->input('content');
        if (!empty($content)) {
            $decoded = json_decode($content, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $content = $decoded;
            }
        }

        $page->update([
            'title'            => $request->input('title'),
            'meta_title'       => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'is_active'        => $request->boolean('is_active', true),
            'content'          => $content,
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully.');
    }
}
