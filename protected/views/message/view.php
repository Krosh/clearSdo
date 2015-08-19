<section class="actions">
    <div id = "selectUserDialog">

    </div>
</section>

<section id="dialogs" class="clearfix" style="overflow: hidden">
</section>

</div>
<div class="rightside clearfix">
    <section id="messages">
    </section>
</div>
<div class="bottomform clearfix">
    <form action="sendMessage"  METHOD = "POST" id="sendmessage" onsubmit="sendMessage(); return false">
        <textarea id = "messageTextArea" name="text" placeholder="Введите сообщение..."></textarea>

        <div class="col-group">
            <div class="col-6">
                <a href="#" data-toggle="modal" data-target="#confusers" class="outline-btn conferenceButton has-tip" onclick="updateConferenceUsers(); return false" title="Участники конференции"><i class="fa fa-users"></i></a>
                <a href="#" class="outline-btn has-tip" onclick="startNewConference(<?php echo Yii::app()->user->getId(); ?>); return false" title="Создать конференцию"><i class="fa fa-plus"></i></a>
            </div>
            <div class="col-6">
                <input type="hidden" name = "idUser">
                <input type="hidden" name = "isConference" value = "0">
                <input type="hidden" name = "startDialog" value = "<?php echo $startDialog; ?>">
                <div class="right">
                    <button type="submit" class="btn small">Отправить</button>

                    <style>
                        .picker {
                            padding-top: 10px;
                        }

                        .picker .picker-handle {
                            float: right;
                            margin-left: 20px;
                        }
                    </style>
                    <span class="sendAsAdmin">
    			    	<label for="ad" class="picker-label">Отправить как объявление</label>
    			    	<input type="checkbox" value="1" name="ad" id="ad" class="picker-element">
                    </span>
                </div>

            </div>
        </div>
    </form>
</div>




<!-- Участники конференции -->
<div class="modal fade" id="confusers" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 500px;">
        <div class="modal-content" style="text-align: center">
            <div class="modal-header" >
                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
                <h4 class="modal-title" id="myModalLabel" style="text-align: left !important;"><i class="fa fa-users"></i> Участники конференции</h4>
            </div>
            <div class="modal-body">
                <span id = "conferenceUsersList">

                </span>
                <hr>
                <div id = "addUserToConferenceDialog">
                </div>
                <div id = "addGroupToConferenceDialog">
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    window.isAdmin = <?php echo Yii::app()->user->isTeacher() ? "1" : Yii::app()->user->isAdmin() ? "1" : "0"; ?>;
</script>
