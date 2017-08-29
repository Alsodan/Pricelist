<?php

namespace app\components\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Bootstrap Accordion - accordion with the panel component
 * see https://getbootstrap.com/docs/3.3/javascript/#collapse-example-accordion
 *
 * @property string $panelClass CSS class for panels
 * @property bool|integer $defaultOpenItem Key of default open item (from $this->items)
 * @property array $items Array of panel items
 * 
 * @author chebotarevae
 */

class AccordionWidget extends Widget
{
    /**
     * CSS class for panels
     * Variants:
     * panel-default|panel-primary|panel-success|panel-infopanel-warning|panel-danger
     */
    public $panelClass = 'panel-default';
    /**
     * Key of default open item (from $this->items). By default open will be first item.
     * If defaultOpenItem === false, than will be no open items.
     */
    public $defaultOpenItem = 0;
    /**
     * Array of panel items. Every item is an array:
     * ['title' => string, 'content' => string]
     */
    public $items = [];
    /**
     * Result HTML
     */
    private $_html = '';

    /**
     * Widget initializing
     */
    public function init() 
    {
        parent::init();

        $this->_html = $this->generateAccordion();
    }
    
    /**
     * Returning result
     */
    public function run()
    {
        return $this->_html;
    }
    
    /**
     * Generate Accordion HTML
     */
    private function generateAccordion()
    {
        //Generate panels
        $panels = '';
        foreach ($this->items as $key => $item) {
            $panels .= Html::tag('div', $this->generatePanelHeader($key, $item['title']) . $this->generatePanelContent($key, $item['content']), ['class' => 'panel ' . $this->panelClass]);
        }
        //Generate Accordion container
        $result = Html::tag('div', $panels, [
            'class' => 'panel-group',
            'id' => 'accordion',
            'role' => 'tablist',
        ]);

        return $result;
    }
    /**
     * Generate Accordion panel header
     * 
     * @param int $key Panel index
     * @param string $title Panel title
     * @return string Html
     */
    private function generatePanelHeader($key, $title)
    {
        //Generate link in panel header
        $panelLink = Html::a($title, '#collapse' . $key, [
            'role' => 'button',
            'data-toggle' => 'collapse',
            'data-parent' => '#accordion',
        ]);
        //Generate panel title
        $panelTitle = Html::tag('h4', $panelLink, ['class' => 'panel-title']);
        //Generate panel header
        $result = Html::tag('div', $panelTitle, [
            'class' => 'panel-heading',
            'role' => 'tab',
            'id' => 'heading' . $key,
        ]);
        
        return $result;
    }
    /**
     * Generate Accordion panel content
     * 
     * @param int $key Panel index
     * @param string $content Panel content
     * @return string Html
     */
    private function generatePanelContent($key, $content)
    {
        //Generate panel content body
        $panelBody = Html::tag('div', $content, ['class' => 'panel-body']);
        //Generate panel content
        $result = Html::tag('div', $panelBody, [
            'id' => 'collapse' . $key,
            //If panel is open, add css class "in"
            'class' => 'panel-collapse collapse' . ($this->defaultOpenItem === $key ? ' in' : ''),
            'role' => 'tabpanel',
        ]);

        return $result;
    }
}