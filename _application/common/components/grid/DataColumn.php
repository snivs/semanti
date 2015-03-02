<?php

namespace common\components\grid;

use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class DataColumn extends \yii\grid\DataColumn
{
    /**
     * @var array
     */
    public static $filterOptionsForChosenSelect = [
        'class'  => 'form-control chosen-select',
        'id'     => null,
        'prompt' => ' All ',
    ];

    public function init()
    {
        $this->headerOptions = ArrayHelper::merge([
            'class' => 'text-align-center',
        ], $this->headerOptions);
        $this->footerOptions = ArrayHelper::merge([
            'class' => 'text-align-center font-weight-bold th',
        ], $this->footerOptions);
    }

    /**
     * Used to render footer like header
     */
    protected function renderFooterCellContent()
    {
        return parent::renderHeaderCellContent();
    }
}
