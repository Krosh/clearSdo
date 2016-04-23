<table class="all-courses">
    <?php foreach ($threads as $thread):?>
        <tr>
            <td width="77%">
                <div class="page-title"><?php echo $thread->subject;?> <small><?php echo $thread->posts[0]->author->name; ?></small></div><br>
                <?php echo $thread->posts[0]->content; ?>
            </td>
            <td>
                <div class="right">
                    <img style="border-radius: 50%; width: 70px; height: 70px; max-height: 70px; max-width: 70px" src="<?php echo ($thread->posts[0]->author->sdoUser != null) ? $thread->posts[0]->author->sdoUser->getAvatarPath(AVATAR_SIZE_MEDIUM) : "/img/avatar-default.png"; ?>">
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
