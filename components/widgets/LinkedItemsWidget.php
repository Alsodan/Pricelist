<?php

namespace app\components\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

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
 * @property array $links Array of list items
 * @property array $options Html options for <div class="list-group">
 * @property int|string $selectedKey Key from $links, that will add css class "active"
 * @property array $linkRoute Params for Url::to(), if param value is "key", then it will be changed to key of $links
 * 
 * @author chebotarevae
 */
class LinkedItemsWidget extends Widget
{
    public $links;
    public $options = [];
    public $selectedKey = 0;
    public $linkRoute = [];
    
    private $_html;

    public function init() 
    {
        parent::init();

        $keyField = array_search('key', $this->linkRoute);
        
        if (is_array($this->links)){
            foreach ($this->links as $key => $value){
                if ($keyField) {
                    $this->linkRoute[$keyField] = $key;
                }
                $this->_html .= Html::a($value, empty($this->linkRoute) ? '#' : Url::to($this->linkRoute), ['class' => $key == $this->selectedKey ? 'list-group-item active' : 'list-group-item']);
            }
        }

        $this->_html = Html::tag('div', $this->_html, array_merge(['class' => 'list-group'], $this->options));
    }
    
    public function run()
    {
        return $this->_html;
    }
}