<?php

namespace backend\modules\backend\controllers;

use Yii;
use yii\base\Model;
use common\modules\i18n\models\search\SourceMessageSearch;
use common\helpers\AppHelper;

class TranslationsController extends \Zelenin\yii\modules\I18n\controllers\DefaultController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionRescan()
    {
        // ------------------------- RESCAN MESSAGES ---------------------------
        SourceMessageSearch::getInstance()->extract('@common/config/messages.php');

        // ----------------------- SHOW RESCAN RESULT --------------------------
        AppHelper::showSuccessMessage(Yii::t('backend', 'Rescan successfully completed.'));

        // ---------------------------- REDIRECT -------------------------------
        if ( ($referrer = Yii::$app->getRequest()->referrer) ) {
            return $this->redirect($referrer);
        } else {
            return $this->redirect(['/backend/translations/index']);
        }
    }

    public function actionClearCache()
    {
        // ---------------------- CHECK IS AJAX REQUEST ------------------------
        if ( !Yii::$app->getRequest()->isAjax ) {
            return $this->redirect(['/backend/translations/index']);
        }

        // ------------------ SET JSON FORMAT FOR RESPONSE ---------------------
        // @see https://github.com/samdark/yii2-cookbook/blob/master/book/response-formats.md
        Yii::$app->getResponse()->format = \yii\web\Response::FORMAT_JSON;

        // ---------------------- SET DEFAULT RESPONSE -------------------------
        $response = array(
            'status'  => 'error',
            'message' => Yii::t('backend', 'An unexpected error occured!'),
        );

        // -------------------------- CLEAR CACHE ------------------------------
        if ( SourceMessageSearch::cacheFlush() ) {
            $response['status']  = 'success';
            $response['message'] = Yii::t('backend', 'Translations cache successfully cleared.');
        }

        return $response;
    }

    public function actionSave($id)
    {
        // ---------------------- CHECK IS AJAX REQUEST ------------------------
        if ( !Yii::$app->getRequest()->isAjax ) {
            return $this->redirect(['/backend/translations/index']);
        }

        // ------------------ SET JSON FORMAT FOR RESPONSE ---------------------
        // @see https://github.com/samdark/yii2-cookbook/blob/master/book/response-formats.md
        Yii::$app->getResponse()->format = \yii\web\Response::FORMAT_JSON;

        // --------------------- SET DEFAULT RESPONSE --------------------------
        $response = array(
            'status'  => 'error',
            'message' => Yii::t('backend', 'An unexpected error occured!'),
        );

        // --------------------- SAVE TRANSLATION BY ID ------------------------
        // @see vendor\zelenin\yii2-i18n-module\controllers\DefaultController::actionUpdate
        $model = $this->findModel($id);
        $model->initMessages();

        if ( Model::loadMultiple($model->messages, Yii::$app->getRequest()->post())
             && Model::validateMultiple($model->messages) )
        {
            $model->saveMessages();

            // clear translation cache
            if ( ($categories = AppHelper::getRequestParam('categories')) ) {
                foreach ( $categories as $language => $category ) {
                    Yii::$app->cache->delete([
                        'yii\i18n\DbMessageSource',
                        $category,
                        $language,
                    ]);
                }
            }

            $response['status']  = 'success';
            $response['message'] = 'Translation successfuly saved.';
            $response['params']  = AppHelper::getRequestParams();
        }

        return $response;
    }

    public function actionDelete($id)
    {
        // ---------------------- CHECK IS AJAX REQUEST ------------------------
        if ( !Yii::$app->getRequest()->isAjax ) {
            return $this->redirect(['/translations']);
        }

        // ------------------ SET JSON FORMAT FOR RESPONSE ---------------------
        // @see https://github.com/samdark/yii2-cookbook/blob/master/book/response-formats.md
        Yii::$app->getResponse()->format = \yii\web\Response::FORMAT_JSON;

        // --------------------- SET DEFAULT RESPONSE --------------------------
        $response = array(
            'status'  => 'error',
            'message' => Yii::t('backend', 'An unexpected error occured!'),
        );

        // -------------------- DELETE TRANSLATION BY ID -----------------------
        $model = $this->findModel($id);
        $model->message = '@@' . $model->message . '@@';
        if ( $model->save() ) {
            // clear cache
            foreach ( Yii::$app->i18n->languages as $language ) {
                Yii::$app->cache->delete([
                    'yii\i18n\DbMessageSource',
                    $model->category,
                    $language,
                ]);
            }

            // set response
            $response['status']   = 'success';
            $response['message']  = 'Translation successfully deleted.';
        }

        return $response;
    }

    public function actionRestore($id)
    {
        // ---------------------- CHECK IS AJAX REQUEST ------------------------
        if ( !Yii::$app->getRequest()->isAjax ) {
            return $this->redirect(['/translations']);
        }

        // ------------------ SET JSON FORMAT FOR RESPONSE ---------------------
        // @see https://github.com/samdark/yii2-cookbook/blob/master/book/response-formats.md
        Yii::$app->getResponse()->format = \yii\web\Response::FORMAT_JSON;

        // --------------------- SET DEFAULT RESPONSE --------------------------
        $response = array(
            'status'  => 'error',
            'message' => Yii::t('backend', 'An unexpected error occured!'),
        );

        // -------------------- RESTORE TRANSLATION BY ID ----------------------
        $model = $this->findModel($id);
        $model->message = trim($model->message, '@@');
        if ( $model->save() ) {
            // clear cache
            foreach ( Yii::$app->i18n->languages as $language ) {
                Yii::$app->cache->delete([
                    'yii\i18n\DbMessageSource',
                    $model->category,
                    $language,
                ]);
            }

            // set response
            $response['status']   = 'success';
            $response['message']  = 'Translation successfully restored.';
        }

        return $response;
    }
}
