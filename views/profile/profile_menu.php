<?php
use yii\helpers\Url;

$profile_id = Yii::$app->main->getProfileId();

?>
<table class="table table-bordered menu-table">
    <tbody>
    <tr>
        <td colspan="2">
            <form action="/profile/set" method="post">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                <input type="hidden" name="back" value="<?= Url::current() ?>"/>
                <select name="profile_id" onchange="this.form.submit()" style="width: 100%" class=" form-control" id="">
                    <?php
                    $list = Yii::$app->main->getProfileList();
                    if (!$profile_id) {
                        echo '<option value="">---Select person---</option>';
                    }
                    foreach ($list as $id => $item) {
                        if ($id == $profile_id) {
                            echo '<option selected value="' . $id . '">' . $item . '</option>';
                        } else {
                            echo '<option  value="' . $id . '">' . $item . '</option>';
                        }
                    }

                    ?>
                </select>
                <input type="submit" style="display: none;">
            </form>

        </td>
    </tr>
    <?php
    if ($profile_id) {
        ?>
        <tr>
            <td class="text-center"><i id="fa1" class="fa fa-lg fa-user" aria-hidden="true"></i></td>
            <td><a data-target="#fa1"
                   onmouseleave="faColorLeave(this)"
                   onmouseover="faColorOver(this)"
                   href="<?= Url::toRoute('profile/view/' . $profile_id) ?>">Profile</a></td>
        </tr>
        <tr>
            <td class="text-center"><i id="fa2" class="fa fa-lg fa-image" aria-hidden="true"></i></td>
            <td><a data-target="#fa2"
                   onmouseleave="faColorLeave(this)"
                   onmouseover="faColorOver(this)"
                   href="<?= Url::toRoute('profile/image/' . $profile_id) ?>">Image</a></td>
        </tr>
        <tr>
            <td class="text-center"><i id="fa3" class="fa fa-lg fa-graduation-cap" aria-hidden="true"></i></td>
            <td><a data-target="#fa3"
                   onmouseleave="faColorLeave(this)"
                   onmouseover="faColorOver(this)"
                   href="<?= Url::toRoute('profile/education/' . $profile_id) ?>">Education</a></td>
        </tr>
        <tr>
            <td class="text-center"><i id="fa4" class="fa fa-lg fa-map-marker" aria-hidden="true"></i></td>
            <td><a data-target="#fa4"
                   onmouseleave="faColorLeave(this)"
                   onmouseover="faColorOver(this)"
                   href="<?= Url::toRoute('profile/contact/' . $profile_id) ?>">Contacts</a></td>
        </tr>

        <?php
    }
    ?>
    </tbody>
</table>


