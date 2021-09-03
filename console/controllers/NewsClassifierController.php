<?php

namespace console\controllers;

use Phpml\ModelManager;
use Yii;
use yii\console\Controller;

use Phpml\Dataset\CsvDataset;
use Phpml\Dataset\ArrayDataset;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WordTokenizer;
use Phpml\CrossValidation\StratifiedRandomSplit;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Metric\Accuracy;
use Phpml\Classification\SVC;
use Phpml\SupportVectorMachine\Kernel;

class NewsClassifierController extends Controller
{
    public function actionGenerate()
    {
        $ai_path = Yii::getAlias("@ai");
        $dataset_path = $ai_path.'/dataset/dataset.csv';
        $dataset = new CsvDataset($dataset_path, 1);
        $vectorizer = new TokenCountVectorizer(new WordTokenizer());
        $tfIdfTransformer = new TfIdfTransformer();

        $samples = [];
        foreach ($dataset->getSamples() as $sample) {
            $samples[] = $sample[0];
        }

        $vectorizer->fit($samples);
        $vectorizer->transform($samples);

        $tfIdfTransformer->fit($samples);
        $tfIdfTransformer->transform($samples);

        $dataset = new ArrayDataset($samples, $dataset->getTargets());

        $randomSplit = new StratifiedRandomSplit($dataset, 0.1);

        $classifier = new SVC(Kernel::RBF, 10000);
        $classifier->train($randomSplit->getTrainSamples(), $randomSplit->getTrainLabels());

        $modelManager = new ModelManager();
        $modelManager->saveToFile($classifier, $ai_path . "/model/model.ai");

        echo "Модель успешно обучена и сохранена на диске.\r\n";
    }
}