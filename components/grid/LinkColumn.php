<?php

namespace app\components\grid;
 
use Closure;
use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\helpers\Url;
 
class LinkColumn extends DataColumn
{
    /**
     * @var callable
     */
    public $url;
    
    /**
     * @var bool
     */
    public $targetBlank = false;
    
    /**
     * @var string
     */
    public $controller;
    
    /**
     * @inheritdoc
     */
    public $format = 'raw';
    
    public $defaultAction = 'view';
    
    public $icon = '';
    
    public $params = [];

    protected function renderDataCellContent($model, $key, $index)
    {
        $value = $this->getDataCellValue($model, $key, $index);
        $text = $this->grid->formatter->format($value, $this->format);
        //Add icon
        $text .= $this->icon ? '&nbsp;' . $this->generateIcon() : '';
        $url = $this->createUrl($model, $key, $index);
        $options = $this->targetBlank ? ['target' => '_blank'] : [];
        return $value === null ? $this->grid->emptyCell : Html::a($text, $url, $options);
    }
 
    public function createUrl($model, $key, $index)
    {
        if ($this->url instanceof Closure) {
            return call_user_func($this->url, $model, $key, $index);
        } else {
            $params = is_array($key) ? $key : ['id' => (string) $key];
            if (!empty($this->params)) {
                $params = array_merge($params, $this->params);
            }
            $params[0] = $this->controller ? $this->controller . '/' . $this->defaultAction : $this->defaultAction;
            return Url::toRoute($params);
        }
    }
    
    private function generateIcon()
    {
        return Html::tag('span', '', ['class' => 'glyphicon ' . $this->icon]);
    }
}