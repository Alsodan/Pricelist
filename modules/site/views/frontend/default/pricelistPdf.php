<?php

/* @var $this yii\web\View */

$this->params['title'] = 'Прайслист ООО "КРАСНОДАРЗЕРНОПРОДУКТ-ЭКСПО"';
?>

<h3 style="text-align: center;">Прайслист ООО "КРАСНОДАРЗЕРНОПРОДУКТ-ЭКСПО"</h3>
<br>
<table style="line-height: 1.5; border-collapse: collapse; border: 1px solid black;" width="100%" border="1">
    <?= $site->generatePdfPriceTable($region, $warehouse, $crop); ?>
</table>


