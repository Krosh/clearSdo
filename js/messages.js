/**
 * Created with JetBrains PhpStorm.
 * User: БОСС
 * Date: 15.04.15
 * Time: 14:14
 * To change this template use File | Settings | File Templates.
 */
function getDialogWithUser(idUser)
{
    $(".dialog.active").removeClass("active");
    $(".dialog[data-idUser="+idUser+"]").addClass("active");
    $(".dialog[data-idUser="+idUser+"]").removeClass("noread");
    $.ajax({
        type: 'POST',
        url: '/message/getDialogWithUser',
        data: {idUser: idUser},
        dataType:'json',
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        },
        success: function(data)
        {
            $("#messages").html(data.text);
            var block = document.getElementById("messages");
            block.scrollTop = block.scrollHeight;
            $("input[name=idUser]").val(idUser);
        }
    });
}

function updateDialogs()
{
    console.log($("input[name=startDialog]").val());
    $.ajax({
        type: 'POST',
        url: '/message/getDialogs',
        data: {startDialog: $("input[name=startDialog]").val()},
        dataType: 'json',
        error: function(jqXHR, textStatus, errorThrown){
            alert(errorThrown);
            console.error('Ajax request failed', jqXHR, textStatus, errorThrown, 1);
        },
        success: function(data)
        {
            $("input[name=startDialog]").val(-1);
            $("#dialogs").html(data.text);
            if (data.idDialog>0)
                getDialogWithUser(data.idDialog);
        }
    });
}

function sendMessage()
{
    $.ajax({
        type: 'POST',
        url: '/message/sendMessage',
        data: $("#sendmessage").serialize(),
        error: function(jqXHR, textStatus, errorThrown){
        },
        success: function(data)
        {
            $("#messageTextArea").val("");
            getDialogWithUser($("input[name=idUser]").val());
        }
    });
}

function updateSelectUserDialog(text)
{
    var i = 0;
    var showCount = 5;
    $(".dd-options li a").each(function()
    {
        if (i < showCount)
        {
            var currentText = $(this).children("label")[0].innerText;
            console.log(currentText);
            if (currentText.toUpperCase().indexOf(text.toUpperCase()) < 0)
            {
                $(this).hide();
            } else
            {
                i++;
                $(this).show();
            }
        } else
        {
            $(this).hide();
        }
    });
}

$(document).ready(function(){
    updateDialogs();
    if ($("#selectUserDialog").length)
    {

        $.ajax({
            type: 'GET',
            dataType: 'JSON',
            url: '/message/ajaxGetUsers',
            height: 40,
            success: function(data)
            {
                $('#selectUserDialog').ddslick({
                    data:data,
                    width:300,
                    selectText: '<input type = "text" value = "" placeholder="Найти пользователя" onfocusout = "$(\'#selectUserData\').ddslick(\'close\');" onkeyup="updateSelectUserDialog(this.value)">',
                    imagePosition:"left",
                    onSelected: function(selectedData){
                        console.log(selectedData);
                        $("input[name=startDialog]").val(selectedData.selectedData.value);
                        updateDialogs();
                    }
                });
                updateSelectUserDialog("");
            }
        });
    }
});
