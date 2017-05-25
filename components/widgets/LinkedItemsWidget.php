<?php

namespace app\components\widgets;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * Bootstrap List group - Linked Items Widget
 * 
 * <div class="list-group" id="users">
 *  <a href="#" class="list-group-item active">Cras justo odio</a>
 *  <a href="#" class="list-group-item">Dapibus ac facilisis in</a>
 *  <a href="#" class="list-group-item">Morbi leo risus</a>
 *  <a href="#" class="list-group-item">Porta ac consectetur ac</a>
 *  <a href="#" class="list-group-item">Vestibulum at eros</a>
 * </div>
 *
 * @author chebotarevae
 */
class LinkedItemsWidget extends Widget
{
    public $links;
    public $options = [];
    public $selectedKey = 0;
    private $html;

    public function init() 
    {
        parent::init();

        if (is_array($this->links)){
            foreach ($this->links as $key => $value){
                $this->html .= Html::a($value, '#', ['class' => $key == $this->selectedKey ? 'list-group-item active' : 'list-group-item']);
            }
        }
        
        $this->html = Html::tag('div', $this->html, array_merge(['class' => 'list-group'], $this->options));
    }
    
    public function run()
    {
        return $this->html;
    }
}