<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Post;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
        });

        // Generate slugs for existing posts
        Post::whereNull('slug')->each(function ($post) {
            $post->slug = Str::slug($post->title) . '-' . uniqid();
            $post->save();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
