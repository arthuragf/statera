<?php 
    /** @var $clsUser \statera\models\User */
    /** @var $this \statera\core\View */
    $this->sTitle = 'Login';
?>

<h1>Login</h1>

<?php $oForm = \statera\core\form\Form::begin('', 'post'); ?>
    <?= $oForm->InputField($clsModel, 'email'); ?>
    <?= $oForm->InputField($clsModel, 'password')->passwordField(); ?>
    <button type="submit" class="btn btn-primary">Submit</button>
<?= \statera\core\form\Form::end(); ?>