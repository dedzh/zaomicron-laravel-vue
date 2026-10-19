<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $perPage = max(1, (int) $request->get('per_page', 6));
        $category = $request->get('category');

        $query = Article::published()->orderBy('date', 'desc');

        if ($category && $category !== 'Все статьи') {
            $query->where('code', 'like', $this->categoryToPrefix($category) . '%');
        }

        $items = $query->paginate($perPage);

        return response()->json([
            'data' => $items->map(fn($item) => $this->formatArticle($item)),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'total'        => $items->total(),
            ],
        ]);
    }

    public function show($code)
    {
        $article = Article::published()
            ->where('code', $code)
            ->firstOrFail();

        return response()->json(
            $this->formatArticle($article, true)
        );
    }

    private function formatArticle(Article $article, bool $full = false): array
    {
        return [
            'id'       => $article->id,
            'title'    => $article->name,
            'slug'     => $article->code,
            'excerpt'  => $article->anons,
            'content'  => $full ? $this->normalizeArticleContent($article->text) : '',
            'date'     => $article->date->format('d.m.Y'),
            'date_iso' => $article->date->toIso8601String(),
            'category' => $this->detectCategory($article->code),
            'emoji'    => $this->categoryEmoji($article->code) ?? '📰',
            'image'    => $this->resolveArticleImage($article->img_id),
        ];
    }

    /**
     * Исправляет пути к изображениям внутри HTML статьи.
     */
    private function normalizeArticleContent(string $html): string
    {
        return preg_replace_callback(
            '/src=["\']([^"\']+)["\']/i',
            function ($matches) {
                $src = trim($matches[1]);

                if (preg_match('/^https?:\/\//i', $src)) {
                    return $matches[0];
                }

                if (str_starts_with($src, '/uploads/')) {
                    return 'src="' . $src . '"';
                }

                if (str_starts_with($src, 'uploads/')) {
                    return 'src="/' . $src . '"';
                }

                if (str_starts_with($src, '/images/')) {
                    return 'src="' . $src . '"';
                }

                if (str_starts_with($src, 'images/')) {
                    return 'src="/' . $src . '"';
                }

                if (str_starts_with($src, '/')) {
                    return 'src="' . $src . '"';
                }

                return $matches[0];
            },
            $html
        );
    }

    /**
     * Главное изображение статьи (карточка).
     */
    private function resolveArticleImage(?string $imgId): ?string
    {
        if (!$imgId) {
            return null;
        }

        $imgId = trim($imgId);
        $extensions = ['', '.jpg', '.jpeg', '.png', '.webp', '.gif'];
        $bases = [

            'uploads/content/articles/thumbs/',
            'uploads/content/articles/temp/',
            'uploads/content/articles/khabn/',
        ];

        foreach ($bases as $base) {
            foreach ($extensions as $ext) {
                $relativePath = $base . $imgId . $ext;
                if (file_exists(public_path($relativePath))) {
                    return '/' . $relativePath;
                }
            }
        }

        $thumbDir = public_path('uploads/content/articles/thumbs');
        if (is_dir($thumbDir)) {
            foreach (scandir($thumbDir) as $file) {
                if ($file === '.' || $file === '..') {
                    continue;
                }

                $filename = pathinfo($file, PATHINFO_FILENAME);
                if (strcasecmp($filename, $imgId) === 0 || str_starts_with(strtolower($file), strtolower($imgId))) {
                    return '/uploads/content/articles/thumbs/' . $file;
                }
            }
        }

        return null;
    }

    private function detectCategory($code): string
    {
        $map = [
            'novinki'       => 'Новинки',
            'manometry'     => 'Манометры',
            'termometry'    => 'Термометры',
            'rashodomery'   => 'Расходомеры',
            'sovety'        => 'Советы по выбору',
            'obsluzhivanie' => 'Обслуживание',
        ];

        foreach ($map as $prefix => $category) {
            if (str_starts_with($code, $prefix)) {
                return $category;
            }
        }

        return 'Общее';
    }

    private function categoryToPrefix($category): string
    {
        $map = [
            'Новинки'          => 'novinki',
            'Манометры'        => 'manometry',
            'Термометры'       => 'termometry',
            'Расходомеры'      => 'rashodomery',
            'Советы по выбору' => 'sovety',
            'Обслуживание'     => 'obsluzhivanie',
        ];

        return $map[$category] ?? '';
    }

    private function categoryEmoji($code): ?string
    {
        $map = [
            'novinki'       => '🆕',
            'manometry'     => '🌡️',
            'termometry'    => '🌡️',
            'rashodomery'   => '💧',
            'sovety'        => '💡',
            'obsluzhivanie' => '🔧',
        ];

        foreach ($map as $prefix => $emoji) {
            if (str_starts_with($code, $prefix)) {
                return $emoji;
            }
        }

        return null;
    }
}
