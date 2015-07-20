<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 22.09.14
 * Time: 20:39
 * To change this template use File | Settings | File Templates.
 */
/* @var $model Course */
?>

<div class="wrapper">
    <div class="container">
        <div class="col-group">
            <div class="col-9">

                <div class="content">
                    <div class="page-heading">
                        <div class="page-title">Результаты поиска: <?php echo $query; ?></div>
                    </div>
                    <div>
                        <table class="all-courses">
                            <?php foreach ($users as $user):?>
                                <!--                      <tr data-href = "/profile?idUser=<?php /*echo $user->id; */?>">
          -->                      <tr>
                                    <td width="77%">
                                        <?php
                                        if ($user->role == ROLE_TEACHER)
                                            $role = "Преподаватель";
                                        if ($user->role == ROLE_STUDENT)
                                        {
                                            $role = "Студент ";
                                            $groups = StudentGroup::model()->findAll("idStudent = :id", array(':id' => $user->id));
                                            $flag = true;
                                            foreach ($groups as $item)
                                            {
                                                if (!$flag)
                                                    $role.=", ";
                                                $group = Group::model()->findByPk($item->idGroup);
                                                $role.= $group->Title;
                                                $flag = false;
                                            }
                                        }
                                        if ($user->role == ROLE_ADMIN)
                                            $role = "Администратор";
                                        ?>
                                        <div class="page-title"><?php echo $user->fio;?> <small><?php echo $role?></small></div><br>
                                        <i class="fa fa-phone"></i><phone> <?php echo $user->phone; ?></phone><br>
                                        <a href = "mailto:<?php echo $user->email; ?>"><?php echo $user->email; ?></a><br>
                                        <a href = '<?php echo $this->createUrl("/message/index", array("startDialog" => $user->id))?> '><i class="fa fa-envelope"></i> Написать сообщение</a>
                                    </td>
                                    <td>
                                        <div class="right">
                                            <img style="border-radius: 50%; width: 70px; height: 70px; max-height: 70px; max-width: 70px" src="<?php echo $user->getAvatarPath(AVATAR_SIZE_MEDIUM); ?>">
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>

                    </div>
                </div>
            </div>
