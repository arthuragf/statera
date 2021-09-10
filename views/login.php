<?php 
    /** @var $clsUser \statera\models\User */

use statera\core\Application;

/** @var $this \statera\core\View */
    $this->sTitle = 'Login';
?>

<h1>Login</h1>

<?php $oForm = \statera\core\form\Form::begin('/login', 'post'); ?>
    <?= $oForm->InputField($clsModel, 'email'); ?>
    <?= $oForm->InputField($clsModel, 'password')->passwordField(); ?>
    <button type="submit" class="btn btn-primary">Submit</button>
    <button type="button" class="btn btn-primary" 
        onclick="window.location='<?= 
            "http://" . Application::$COMMON_URL . DIRECTORY_SEPARATOR . "register"; 
        ?>'"
    >
            Create an Account
    </button>
<?= \statera\core\form\Form::end(); ?>