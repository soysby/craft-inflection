<?php
namespace soysby\inflection\controllers;

use soysby\inflection\Inflection;
use soysby\inflection\models\Inflection as InfModel;

use Craft;
use craft\web\Controller;
use craft\helpers\UrlHelper;

class DefaultController extends Controller
{
    // Public Methods
    // =========================================================================

    public function actionIndex()
    {
        $result = Inflection::$plugin->inflection->getAllItems();

        // TODO: add pagination
        return $this->renderTemplate('inflection/_index', [
            'items' => $result
        ]);
    }

    public function actionEdit(int $inflectionId = null, InfModel $inflection = null)
    {
        if ($inflection === null) {
            if ($inflectionId !== null) {
                $inflection = Inflection::$plugin->inflection->getById($inflectionId);

                if (!$inflection) {
                    throw new NotFoundHttpException('Inflection not found');
                }
            } else {
                $inflection = new InfModel();
            }
        }

        return $this->renderTemplate('inflection/_edit', [
            'inflectionId' => $inflectionId,
            'inflection' => $inflection,
        ]);
    }

    public function actionSave()
    {
        $this->requirePostRequest();
        $request = Craft::$app->getRequest();
        $session = Craft::$app->getSession();

        $model = new InfModel();
        $model->id       = $request->getBodyParam('inflectionId');
        $model->type     = $request->getBodyParam('type');
        $model->section  = $request->getBodyParam('section');
        $model->value    = $request->getBodyParam('value');

        $success = Inflection::$plugin->inflection->save($model);

        if (!$success) {
            Craft::$app->getUrlManager()->setRouteParams([
                'inflection' => $model
            ]);

            return null;
        }

        $session->setNotice(Craft::t('inflection', 'Inflection saved.'));

        return $this->redirect(UrlHelper::cpUrl('inflection'));
    }

    public function actionDelete()
    {
        $this->requirePostRequest();
        $this->requireAcceptsJson();

        $id = Craft::$app->getRequest()->getRequiredBodyParam('id');

        Inflection::$plugin->inflection->deleteById($id);

        return $this->asJson(['success' => true]);
    }
}