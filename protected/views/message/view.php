<section class="actions">
    <div id = "selectUserDialog">

    </div>
<!--    <a href="#" class="outline-btn">Найти пользователей</a>
--></section>
<section id="dialogs">
</section>
</div>
<div class="rightside clearfix">
    <section id="messages">
    </section>
</div>
<div class="bottomform clearfix">
<form action="sendMessage"  METHOD = "POST" id="sendmessage" onsubmit="sendMessage(); return false">
    <input type="hidden" name = "idUser">
    <input type="hidden" name = "startDialog" value = "<?php echo $startDialog; ?>">
    <textarea id = "messageTextArea" name="text" placeholder="Введите сообщение..."></textarea>
    <div class="right"><button type="submit" class="btn"> Отправить</button></div>
</form>
</div>