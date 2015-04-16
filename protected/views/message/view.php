<div class="inner">

    <table class="mrkp-tbl">
        <tr>
            <td class="leftside clearfix" rowspan="2">
                <header>
                    <div class="logo">
                        <a href="/">
                            <img src="../../img/logo-small@2x.png" height="40" width="40" alt="" data-retina="">
                            <span>Стимул</span>
                        </a>
                    </div>
                </header>
                <section id="dialogs">


                </section>
                <section class="actions">
                    <a href="#" class="outline-btn">Найти пользователей</a>
                </section>
            </td>
            <td class="rightside clearfix">
                <section id="messages">


                </section>
            </td>
        </tr>
        <tr>
            <td class="bottomform">
                <form action="sendMessage"  METHOD = "POST" id="sendmessage" onsubmit="sendMessage(); return false">
                    <input type="hidden" name = "idUser" value="3">
                    <textarea name="text" placeholder="Введите сообщение..."></textarea>
                    <div class="right"><button type="submit" class="btn"> Отправить</button></div>
                </form>
            </td>
        </tr>
    </table>

</div>
