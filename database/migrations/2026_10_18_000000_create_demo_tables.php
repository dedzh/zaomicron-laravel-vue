<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('old_code')->nullable();
            $table->string('name');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('old_code')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->string('name');
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('weight', 8, 3)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('anons')->nullable();
            $table->longText('text')->nullable();
            $table->date('date');
            $table->string('img_id')->nullable();
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('trash')->default(false);
            $table->timestamps();
        });

        Schema::create('jam_catalog_params', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('type')->nullable();
            $table->boolean('show_filter')->default(false);
            $table->boolean('show_item')->default(false);
            $table->boolean('trash')->default(false);
            $table->integer('sort')->default(0);
            $table->string('unit_name')->nullable();
            $table->timestamps();
        });

        Schema::create('jam_catalog_params_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('param_id');
            $table->string('value')->nullable();
            $table->boolean('trash')->default(false);
            $table->timestamps();
        });

        Schema::create('jam_catalog_items', function (Blueprint $table) {
            $table->id();
            $table->string('article')->nullable();
            $table->string('name')->nullable();
            $table->decimal('price_1', 12, 2)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->boolean('in_stock')->default(false);
            $table->text('descr_short')->nullable();
            $table->longText('descr_1')->nullable();
            $table->timestamps();
        });

        Schema::create('jam_catalog_items_params', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('param_id');
            $table->string('value')->nullable();
            $table->unsignedBigInteger('value_int')->nullable();
            $table->decimal('value_dec', 12, 4)->nullable();
            $table->text('value_text')->nullable();
            $table->boolean('trash')->default(false);
            $table->timestamps();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('image_path');
            $table->timestamps();
        });

        $this->seedDemoData();
    }

    protected function seedDemoData(): void
    {
        DB::table('brands')->insert([
            ['id' => 1, 'name' => 'Micron', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Kraft', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'TechLine', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('units')->insert([
            ['id' => 1, 'name' => 'шт', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'компл', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'м', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('categories')->insert([
            ['id' => 1, 'parent_id' => null, 'old_code' => 'catalog', 'name' => 'Каталог', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'parent_id' => 1, 'old_code' => 'calibers', 'name' => 'Калибры', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'parent_id' => 1, 'old_code' => 'measurement', 'name' => 'Измерительный инструмент', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'parent_id' => 1, 'old_code' => 'metal', 'name' => 'Металлорежущий инструмент', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('products')->insert([
            ['id' => 1, 'old_code' => 'A1001', 'category_id' => 2, 'brand_id' => 1, 'unit_id' => 1, 'name' => 'Калибр плоский 100 мм', 'price' => 1280.00, 'weight' => 0.250, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'old_code' => 'A1002', 'category_id' => 2, 'brand_id' => 1, 'unit_id' => 1, 'name' => 'Калибр резьбовой М12', 'price' => 1540.00, 'weight' => 0.310, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'old_code' => 'A1003', 'category_id' => 3, 'brand_id' => 2, 'unit_id' => 1, 'name' => 'Микрометр наружный', 'price' => 2450.00, 'weight' => 0.820, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'old_code' => 'A1004', 'category_id' => 3, 'brand_id' => 2, 'unit_id' => 1, 'name' => 'Манометр цифровой', 'price' => 3180.00, 'weight' => 0.620, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'old_code' => 'A1005', 'category_id' => 4, 'brand_id' => 3, 'unit_id' => 1, 'name' => 'Фреза концевая 6 мм', 'price' => 680.00, 'weight' => 0.140, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('articles')->insert([
            ['id' => 1, 'name' => 'Новые калибры для проверки размеров', 'code' => 'novinki-kalibry', 'anons' => 'Расширили линейку контрольных инструментов.', 'text' => '<p>В наличии обновлённая линейка инструментов.</p>', 'date' => now()->toDateString(), 'img_id' => 'demo', 'description' => 'Новинки', 'active' => true, 'trash' => false, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Как выбрать манометр для линии', 'code' => 'sovety-vybor-manometra', 'anons' => 'Пошаговая инструкция.', 'text' => '<p>Выбор манометра зависит от диапазона и среды.</p>', 'date' => now()->subDay()->toDateString(), 'img_id' => 'demo', 'description' => 'Советы', 'active' => true, 'trash' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('jam_catalog_params')->insert([
            ['id' => 1, 'name' => 'Диаметр', 'code' => 'diameter', 'type' => 'select', 'show_filter' => true, 'show_item' => true, 'trash' => false, 'sort' => 1, 'unit_name' => 'мм', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Материал', 'code' => 'material', 'type' => 'select', 'show_filter' => true, 'show_item' => true, 'trash' => false, 'sort' => 2, 'unit_name' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('jam_catalog_params_values')->insert([
            ['id' => 1, 'param_id' => 1, 'value' => '6', 'trash' => false, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'param_id' => 1, 'value' => '12', 'trash' => false, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'param_id' => 2, 'value' => 'Сталь', 'trash' => false, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'param_id' => 2, 'value' => 'Латунь', 'trash' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('jam_catalog_items')->insert([
            ['id' => 1, 'article' => 'A1001', 'name' => 'Калибр плоский 100 мм', 'price_1' => 1280.00, 'stock_quantity' => 5, 'in_stock' => true, 'descr_short' => 'Калибр плоский', 'descr_1' => 'Описание для калибра', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'article' => 'A1002', 'name' => 'Калибр резьбовой М12', 'price_1' => 1540.00, 'stock_quantity' => 3, 'in_stock' => true, 'descr_short' => 'Резьбовой калибр', 'descr_1' => 'Описание для резьбового калибра', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'article' => 'A1003', 'name' => 'Микрометр наружный', 'price_1' => 2450.00, 'stock_quantity' => 2, 'in_stock' => true, 'descr_short' => 'Микрометр', 'descr_1' => 'Описание микрометра', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('jam_catalog_items_params')->insert([
            ['id' => 1, 'item_id' => 1, 'param_id' => 1, 'value' => '100', 'value_int' => null, 'value_dec' => null, 'value_text' => null, 'trash' => false, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'item_id' => 1, 'param_id' => 2, 'value_int' => 3, 'value' => null, 'value_dec' => null, 'value_text' => null, 'trash' => false, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'item_id' => 2, 'param_id' => 1, 'value' => '12', 'value_int' => 2, 'value_dec' => null, 'value_text' => null, 'trash' => false, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'item_id' => 2, 'param_id' => 2, 'value_int' => 3, 'value' => null, 'value_dec' => null, 'value_text' => null, 'trash' => false, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'item_id' => 3, 'param_id' => 1, 'value' => '25', 'value_int' => null, 'value_dec' => null, 'value_text' => null, 'trash' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('product_images')->insert([
            ['id' => 1, 'product_id' => 1, 'image_path' => 'images/foto/demo/1.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'product_id' => 2, 'image_path' => 'images/foto/demo/2.jpg', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('jam_catalog_items_params');
        Schema::dropIfExists('jam_catalog_items');
        Schema::dropIfExists('jam_catalog_params_values');
        Schema::dropIfExists('jam_catalog_params');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('units');
        Schema::dropIfExists('brands');
    }
};
