<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AITranslationService;


class TranslateModels extends Command
{
    protected $signature = 'translate:models
                            {model? : Agency ou Vehicle}
                            {--missing : Traduire seulement ce qui manque}
                            {--id= : ID spécifique}';

    protected $description = 'Traduit automatiquement avec table séparée';

    public function handle(AITranslationService $translator)
    {
        $modelName = $this->argument('model');
        $onlyMissing = $this->option('missing');
        $specificId = $this->option('id');

        $modelClass = $modelName ? "App\\Models\\{$modelName}" : null;

        if (!$modelClass || !class_exists($modelClass)) {
            $this->error("Modèle invalide");
            return 1;
        }

        $query = $modelClass::query();

        // Filtre : seulement ceux sans traduction EN
        if ($onlyMissing) {
            $query->whereDoesntHave('translations', function ($q) {
                $q->where('locale', 'en');
            });
        }

        if ($specificId) {
            $query->where('id', $specificId);
        }

        $models = $query->get();
        $total = $models->count();

        if ($total === 0) {
            $this->info("Aucun modèle à traduire");
            return 0;
        }

        $this->info("Traduction de {$total} {$modelName}(s)...");
        $bar = $this->output->createProgressBar($total);

        foreach ($models as $model) {
            try {
                $translator->translateModel($model);
                $bar->advance();
            } catch (\Exception $e) {
                $this->error("\nErreur ID {$model->id}: {$e->getMessage()}");
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ Terminé !");

        return 0;
    }
}
