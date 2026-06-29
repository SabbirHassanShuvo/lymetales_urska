<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

use App\Models\SiteCategory;
use App\Models\Page;
use App\Models\Product;
use App\Models\Offer;
use App\Models\HomePromo;
use App\Models\HeroSection;
use App\Models\HomeFeature;
use App\Models\GiftGiver;
use App\Models\GiftCard;
use App\Models\FooterSection;
use App\Models\Gift;
use App\Models\FooterItem;
use App\Models\Faq;
use App\Models\BlogPost;
use App\Models\Coupon;
use App\Models\Category;

class FilterByLanguageType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get language from query param 'lang', 'language_type', header, or default to 'SL'
        $lang = strtoupper($request->input('lang', 'SL'));
        if ($request->has('language_type')) {
            $lang = strtoupper($request->input('language_type'));
        }

        $models = [
            SiteCategory::class,
            Page::class,
            Product::class,
            Offer::class,
            HomePromo::class,
            HeroSection::class,
            HomeFeature::class,
            GiftGiver::class,
            GiftCard::class,
            FooterSection::class,
            Gift::class,
            FooterItem::class,
            Faq::class,
            BlogPost::class,
            Coupon::class,
            Category::class
        ];

        foreach ($models as $model) {
            if (class_exists($model)) {
                $model::addGlobalScope('language_type', function (Builder $builder) use ($lang, $model) {
                    $table = (new $model)->getTable();
                    $builder->where($table . '.language_type', $lang);
                });
            }
        }

        return $next($request);
    }
}
