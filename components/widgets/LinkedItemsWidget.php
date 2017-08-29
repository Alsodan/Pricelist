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
    //Массив строк для текстов ссылок
    public $links;
    //Дополнительные аттрибуты для div-обертки
    public $options = [];
    //Индекс активного элемента, начиная с 0
    public $selectedKey = 0;
    //Ссылки
    //если значение элемента массива ссылки равно 'key', вместо 'key' будет подставлен ключ соответствующего элемента из $links
    //'linkRoute' => ['products', 'id' => $group->id, 'wh' => 'key'],
    public $linkRoute = [];
    
    private $html;

    public function init() 
    {
        parent::init();

        $keyField = array_search('key', $this->linkRoute);
        
        if (is_array($this->links)){
            foreach ($this->links as $key => $value){
                if ($keyField) {
                    $this->linkRoute[$keyField] = $key;
                }
                $this->html .= Html::a($value, empty($this->linkRoute) ? '#' : Url::to($this->linkRoute), ['class' => $key === $this->selectedKey ? 'list-group-item active' : 'list-group-item']);
            }
        }

        $this->html = Html::tag('div', $this->html, array_merge(['class' => 'list-group'], $this->options));
    }
    
    public function run()
    {
        return $this->html;
    }
}