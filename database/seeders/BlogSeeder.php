<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $destDir = public_path('uploads/blog');
        if (!file_exists($destDir)) {
            mkdir($destDir, 0755, true);
        }

        $sourceImages = [
            'featured' => 'C:\\Users\\Sabbir\\.gemini\\antigravity-ide\\brain\\9ce36e36-7f4e-4843-a358-fbb5371523a3\\blog_featured_1781928770826.png',
            'parenting' => 'C:\\Users\\Sabbir\\.gemini\\antigravity-ide\\brain\\9ce36e36-7f4e-4843-a358-fbb5371523a3\\blog_parenting_1781928785890.png',
            'behind' => 'C:\\Users\\Sabbir\\.gemini\\antigravity-ide\\brain\\9ce36e36-7f4e-4843-a358-fbb5371523a3\\blog_behind_1781928798190.png',
            'gifts' => 'C:\\Users\\Sabbir\\.gemini\\antigravity-ide\\brain\\9ce36e36-7f4e-4843-a358-fbb5371523a3\\blog_gifts_1781928813517.png',
        ];

        $imagePaths = [];
        foreach ($sourceImages as $key => $srcPath) {
            $destName = "blog_{$key}.png";
            $destPath = $destDir . DIRECTORY_SEPARATOR . $destName;
            if (file_exists($srcPath)) {
                copy($srcPath, $destPath);
                $imagePaths[$key] = "uploads/blog/{$destName}";
            } else {
                $imagePaths[$key] = "https://images.unsplash.com/photo-1544947950-fa07a98d237f"; // Fallback URL
            }
        }

        $posts = [
            [
                'title' => 'The Quiet Magic of Personalized Stories',
                'slug' => 'the-quiet-magic-of-personalized-stories',
                'category' => 'Storytelling',
                'excerpt' => 'Why seeing their own name on the page changes the way children fall in love with reading.',
                'content' => "<p>There is a particular hush that falls over a child the first time they recognize their own name on a printed page. It is the sound of a small world tilting—a moment where story stops being something that happens to other children, and becomes something that belongs to them.</p><p>At Lymetales, we have spent years studying that moment. We have watched it happen in living rooms and on hospital beds, at bedtime and on long flights. And every time, the response is the same: a slow smile, a finger pressed against the letters, a quiet 'that's me.'</p><p>Personalization is not a gimmick. Done thoughtfully, it is an invitation. When a child sees themselves as the hero, they read more carefully. They ask questions. They imagine futures. They learn, almost without noticing, that they are capable of being brave, kind, curious—because the story they hold in their hands tells them so.</p><p>This is the philosophy behind every Lymetales book. Not just a name on a cover, but a story shaped around the small details that make a child who they are.</p>",
                'cover_image' => $imagePaths['featured'],
                'reading_time' => '6 min read',
                'is_featured' => true,
                'is_active' => true,
                'published_at' => '2026-06-12 10:00:00',
            ],
            [
                'title' => 'Bedtime Rituals That Actually Stick',
                'slug' => 'bedtime-rituals-that-actually-stick',
                'category' => 'Parenting',
                'excerpt' => 'Simple, gentle routines that turn the last twenty minutes of the day into something children remember forever.',
                'content' => "<p>Establishing bedtime routines can be challenging, but they are crucial for a child's development. Simple, gentle routines that turn the last twenty minutes of the day into something children remember forever can set them up for sweet dreams.</p><p>Reading a personalized book together at bedtime builds deep family bonds and sparks imagination just before sleep. It provides comfort, helps process the day's experiences, and makes transitions easier for children of all ages.</p>",
                'cover_image' => $imagePaths['parenting'],
                'reading_time' => '5 min read',
                'is_featured' => false,
                'is_active' => true,
                'published_at' => '2026-05-28 20:30:00',
            ],
            [
                'title' => "Inside the Art of Children's Illustration",
                'slug' => 'inside-the-art-of-childrens-illustration',
                'category' => 'Behind the Scenes',
                'excerpt' => "A look behind the watercolors, the dried flowers, and the quiet hours that make a Lymetales page.",
                'content' => "<p>Behind every Lymetales illustration lies hours of sketching, water-coloring, and creative reflection. Our artists work meticulously to create vibrant worlds that feel warm and inviting to children.</p><p>By blending traditional watercolor techniques with digital touch-ups, we ensure each book captures a soft, handmade feel while retaining the sharpness needed for printing names and characters dynamically.</p>",
                'cover_image' => $imagePaths['behind'],
                'reading_time' => '8 min read',
                'is_featured' => false,
                'is_active' => true,
                'published_at' => '2026-05-14 14:15:00',
            ],
            [
                'title' => 'Thoughtful Gifts for the Little Ones in Your Life',
                'slug' => 'thoughtful-gifts-for-the-little-ones-in-your-life',
                'category' => 'Gift Guide',
                'excerpt' => 'Beyond the toys and the plastic—how to choose a gift that a child will still remember in twenty years.',
                'content' => "<p>Choosing gifts for children often leads to plastic toys that get forgotten in a few weeks. But finding a gift that a child will still remember in twenty years is about choosing something personal and timeless.</p><p>A personalized storybook is more than a gift; it's a keepsake that becomes a part of their childhood memory box, keeping your love close to them as they grow up.</p>",
                'cover_image' => $imagePaths['gifts'],
                'reading_time' => '4 min read',
                'is_featured' => false,
                'is_active' => true,
                'published_at' => '2026-04-30 09:00:00',
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::updateOrCreate(
                ['slug' => $post['slug']],
                $post
            );
        }
    }
}
