<?php

namespace Database\Factories;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Factories\Factory;

class TranslationFactory extends Factory {
    protected $model = Translation::class;

    public function definition()
    {
        $locales = ['en', 'fr', 'es', 'de'];
        $text = [];

        foreach ($locales as $locale) {
            $text[$locale] = $this->faker->sentence;
        }

        return [
            'group' => $this->faker->randomElement(['frontend', 'backend', 'mobile', 'desktop']),
            'key' => 'key_' . $this->faker->unique()->uuid,
            'text' => $text,
            'tags' => $this->faker->randomElements(
                ['web', 'mobile', 'desktop', 'admin', 'user'],
                $this->faker->numberBetween(1, 3)
            ),
        ];
    }
}
