<?php 
    /** @var $clsUser statera\models\User */
    /** @var $this statera\core\View */
    $this->sTitle = 'Register';
?>

<h1>Create an account</h1>

<?php $oForm = statera\core\form\Form::begin('', 'post'); ?>
    <div class="row">
        <div class="col">
            <?= $oForm->InputField($clsUser, 'firstname'); ?>
        </div>
        <div class="col">
            <?= $oForm->InputField($clsUser, 'lastname'); ?>
        </div>
    </div>
    <?= $oForm->InputField($clsUser, 'email'); ?>
    <?= $oForm->InputField($clsUser, 'password')->passwordField(); ?>
    <?= $oForm->InputField($clsUser, 'confirmPassword')->passwordField(); ?>
    <button type="submit" class="btn btn-primary">Submit</button>
<?= statera\core\form\Form::end(); ?>