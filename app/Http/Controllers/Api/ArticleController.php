<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    // Список статей с пагинацией и фильтром по категории
    public function index(Request $request)
    {
        $perPage = max(1, (int) $request->get('per_page', 6));
        $category = $request->get('category');

        $query = Article::published()->orderBy('date', 'desc');

        // Фильтр по категории (см. ниже)
        if ($category && $category !== 'Все статьи') {
            $query->where('code', 'like', $this->categoryToPrefix($category) . '%');
        }

        $items = $query->paginate($perPage);

        return response()->json([
            'data' => $items->map(fn($item) => $this->formatArticle($item)),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'total' => $items->total(),
            ],
        ]);
    }

    // Детальная статья
    public function show($code)
    {
        $article = Article::published()->where('code', $code)->firstOrFail();
        return response()->json($this->formatArticle($article, true));
    }

    // Формат ответа
    private function formatArticle(Article $article, $full = false)
    {
        return [
            'id' => $article->id,
            'title' => $article->name,
            'slug' => $article->code,
            'excerpt' => $article->anons,
            'content' => $full ? $article->text : '',
            'date' => $article->date->format('d.m.Y'),
            'date_iso' => $article->date->toIso8601String(),
            'category' => $this->detectCategory($article->code),
            'emoji' => $this->categoryEmoji($article->code) ?? '📰',
            'image' => $article->img_id ? '/storage/images/' . $article->img_id . '.jpg' : null, // поправить под вашу систему
        ];
    }

    // Временное определение категории по коду (slug)
    private function detectCategory($code)
    {
        $map = [
            'novinki' => 'Новинки',
            'manometry' => 'Манометры',
            'termometry' => 'Термометры',
            'rashodomery' => 'Расходомеры',
            'sovety' => 'Советы по выбору',
            'obsluzhivanie' => 'Обслуживание',
        ];
        foreach ($map as $prefix => $category) {
            if (str_starts_with($code, $prefix)) {
                return $category;
            }
        }
        return 'Общее';
    }

    private function categoryToPrefix($category)
    {
        $map = array_flip([
            'Новинки' => 'novinki',
            'Манометры' => 'manometry',
            'Термометры' => 'termometry',
            'Расходомеры' => 'rashodomery',
            'Советы по выбору' => 'sovety',
            'Обслуживание' => 'obsluzhivanie',
        ]);
        return $map[$category] ?? '';
    }

    private function categoryEmoji($code)
    {
        $map = [
            'novinki' => '🆕',
            'manometry' => '🌡️',
            'termometry' => '🌡️',
            'rashodomery' => '💧',
            'sovety' => '💡',
            'obsluzhivanie' => '🔧',
        ];
        foreach ($map as $prefix => $emoji) {
            if (str_starts_with($code, $prefix)) return $emoji;
        }
        return null;
    }
}