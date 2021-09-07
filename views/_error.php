<?php
/** @var $oException \Exception */
/** @var $this \app\core\View */
$this->sTitle = 'Error';
?>
<h3><?= $oException->getCode() . ' - ' . $oException->getMessage();?></h3>