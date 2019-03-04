<?php
namespace soysby\inflection\services;

use soysby\inflection\models\Inflection as InfModel;
use soysby\inflection\records\Inflection as InfRecord;

use Craft;
use craft\base\Component;

class Inflection extends Component
{
    // Public Methods
    // =========================================================================

    public function getAllItems($indexBy = null)
    {
        $records = InfRecord::find()
            ->indexBy($indexBy)
            ->all();

        $models = [];

        foreach ($records as $record) {
            $models[] = InfModel::createFromRecord($record);
        }

        return $models;
    }

    public function getById(int $id)
    {
        $record = InfRecord::find()
            ->where(['id' => $id])
            ->one();

        return InfModel::createFromRecord($record);
    }

    public function save(InfModel $model, bool $runValidation = true): bool
    {
        $isNew = !$model->id;

        if ($runValidation && !$model->validate()) {
            Craft::info('Inflection not saved due to validation error.', __METHOD__);
            return false;
        }

        if (!$isNew) {
            $modelRecord = InfRecord::find()
                ->where(['id' => $model->id])
                ->one();

            if (!$modelRecord) {
                throw new NavNotFoundException("No inflection exists with the ID '{$model->id}'");
            }
        } else {
            $modelRecord = new InfRecord();
        }

        $modelRecord->type    = $model->type;
        $modelRecord->section = $model->section;
        $modelRecord->value   = $model->value;

        $db = Craft::$app->getDb();
        $transaction = $db->beginTransaction();

        try {
            $modelRecord->save(false);
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }

        return true;
    }

    public function deleteById(int $id): bool
    {
        $model = $this->getById($id);

        if (!$model) {
            return false;
        }

        $transaction = Craft::$app->getDb()->beginTransaction();

        try {
            Craft::$app->getDb()->createCommand()
                ->delete('{{%inflection}}', ['id' => $id])
                ->execute();

            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }

        return true;
    }
}
