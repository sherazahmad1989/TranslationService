<?php

namespace App\Http\Controllers;

use App\Services\TranslationService;
use App\Models\Translation;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    /**
     * @var TranslationService
     */
    protected $translationService;

    /**
     * @param TranslationService $translationService
     */
    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'tag', 'group', 'locale']);
        $perPage = $request->input('per_page', 20);

        $translations = $this->translationService->getTranslations($filters, $perPage);
        return response()->json($translations);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'group' => 'required|string|max:255',
            'key' => 'required|string|max:255',
            'text' => 'required|json',
            'tags' => 'nullable|json',
        ]);

        $translation = $this->translationService->createTranslation($validated);
        return response()->json($translation, 201);
    }

    /**
     * @param Translation $translation
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Translation $translation)
    {
        $translation = $this->translationService->getTranslation($translation);
        return response()->json($translation);
    }

    /**
     * @param Request $request
     * @param Translation $translation
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Translation $translation)
    {
        $validated = $request->validate([
            'text' => 'sometimes|json',
            'tags' => 'sometimes|json',
        ]);

        $translation = $this->translationService->updateTranslation($translation, $validated);
        return response()->json($translation);
    }

    /**
     * @param Translation $translation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Translation $translation)
    {
        $this->translationService->deleteTranslation($translation);
        return response()->noContent();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function export()
    {
        $translations = $this->translationService->exportTranslations();
        return response()->json($translations);
    }
}
