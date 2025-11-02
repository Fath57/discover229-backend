<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Cache;

class AITranslationService
{
    private array $targetLanguages = ['en']; // Langues cibles (hors FR source)

    /**
     * Traduit un modèle complet vers toutes les langues
     */
    public function translateModel($model, string $sourceLang = 'fr'): void
    {
        $sourceTranslation = $model->translate($sourceLang);

        if (!$sourceTranslation) {
            throw new \Exception("Traduction source '{$sourceLang}' introuvable");
        }

        foreach ($this->targetLanguages as $targetLang) {
            $this->translateToLanguage($model, $sourceTranslation, $targetLang);
        }

        $model->save();
    }

    /**
     * Traduit vers une langue spécifique
     */
    private function translateToLanguage($model, $sourceTranslation, string $targetLang): void
    {
        $translation = $model->translateOrNew($targetLang);

        // Traduire chaque champ
        foreach ($model->translatedAttributes as $field) {
            $sourceValue = $sourceTranslation->$field;

            if (!$sourceValue) continue;

            // Cache pour éviter re-traduire le même contenu
            $cacheKey = "translation:{$targetLang}:{$field}:" . md5(
                    is_array($sourceValue) ? json_encode($sourceValue) : $sourceValue
                );

            $translation->$field = Cache::remember($cacheKey, now()->addMonths(3), function () use ($sourceValue, $targetLang, $field, $model) {
                return $this->translate($sourceValue, $targetLang, $field, $model);
            });
        }
    }

    /**
     * Appel API pour traduction
     */
    private function translate($value, string $targetLang, string $field, $model): mixed
    {
        if (is_array($value)) {
            return $this->translateArray($value, $targetLang, $field);
        }

        $context = $this->buildContext($model, $field);

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'Tu es un traducteur expert en tourisme africain. Réponds UNIQUEMENT avec la traduction, sans guillemets ni explications.'],
                ['role' => 'user', 'content' => $this->buildPrompt($value, $targetLang, $context)]
            ],
            'temperature' => 0.3,
        ]);

        return trim($response->choices[0]->message->content);
    }

    /**
     * Traduction de tableau
     */
    private function translateArray(array $items, string $targetLang, string $field): array
    {
        $translated = [];

        foreach ($items as $item) {
            $translated[] = $this->translate($item, $targetLang, $field, null);
        }

        return $translated;
    }

    /**
     * Contexte pour meilleure traduction
     */
    private function buildContext($model, string $field): string
    {
        $modelName = class_basename($model);

        $contexts = [
            'Agency' => [
                'name' => "Nom d'une agence touristique au Bénin",
                'description' => "Description marketing d'une agence de voyage",
                'services' => "Liste de services touristiques offerts"
            ],
            'Vehicle' => [
                'name' => "Nom commercial d'un véhicule de location",
                'description' => "Description d'un véhicule pour touristes",
                'features' => "Liste d'équipements du véhicule"
            ]
        ];

        return $contexts[$modelName][$field] ?? "Champ {$field} d'un {$modelName}";
    }

    private function buildPrompt(string $text, string $targetLang, string $context): string
    {
        $langNames = ['en' => 'anglais', 'fr' => 'français'];

        return "Contexte : {$context}\n\nTexte en français :\n{$text}\n\nTraduction naturelle en {$langNames[$targetLang]} :";
    }

    /**
     * Traduction batch optimisée (plusieurs modèles à la fois)
     */
    public function translateBatch(array $models, string $sourceLang = 'fr'): void
    {
        foreach ($models as $model) {
            try {
                $this->translateModel($model, $sourceLang);

                // Rate limiting
                usleep(200000); // 200ms entre chaque
            } catch (\Exception $e) {
                logger()->error("Erreur traduction {$model->id}: {$e->getMessage()}");
            }
        }
    }
}
