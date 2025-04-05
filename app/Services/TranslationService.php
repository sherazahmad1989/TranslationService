<?php

namespace App\Services;

use App\Repositories\TranslationRepository;
use App\Models\Translation;

class TranslationService
{
    public function getTranslations(array $filters = [], int $perPage = 20)
    {
        $query = Translation::query();

        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (!empty($filters['tag'])) {
            $query->byTag($filters['tag']);
        }

        if (!empty($filters['group'])) {
            $query->byGroup($filters['group']);
        }

        if (!empty($filters['locale'])) {
            $query->byLocale($filters['locale']);
        }

        return $query->paginate($perPage);
    }

    public function createTranslation(array $data)
    {
        return Translation::create($data);
    }

    public function getTranslation(Translation $translation)
    {
        return $translation;
    }

    public function updateTranslation(Translation $translation, array $data)
    {
        $translation->update($data);
        return $translation;
    }

    public function deleteTranslation(Translation $translation)
    {
        $translation->delete();
    }

    public function exportTranslations()
    {
        return Cache::remember('translations.export', now()->addMinutes(5), function () {
            return Translation::all()
                ->groupBy('group')
                ->map(function ($group) {
                    return $group->mapWithKeys(function ($item) {
                        return [$item->key => $item->text];
                    });
                });
        });
    }
}
