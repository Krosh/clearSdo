/**
 * Created with JetBrains PhpStorm.
 * User: БОСС
 * Date: 15.04.15
 * Time: 14:14
 * To change this template use File | Settings | File Templates.
 */
function getDialogWithUser(idUser, isConf)
{
    $(".dialog.active").removeClass("active");
    $(".dialog[data-idUser="+idUser+"][data-isConf="+isConf+"]").addClass("active").removeClass("noread");
    $.ajax({
        type: 'POST',
        url: '/message/getDialogWithUser',
        data: {idUser: idUser, isConf: isConf},
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
            $("input[name=isConference]").val(isConf);
        }
    });
}

function updateConferenceUsers()
{
    $.ajax({
        type: 'POST',
        url: '/message/ajaxGetConferenceUsers',
        data: $("#sendmessage").serialize(),
        success: function(data)
        {
            $("#conferenceUsersList").html(data);
        }
    });
}

function deleteFromConference(idDeletedUser)
{
    var idConference = $("input[name=idUser]").val();
    $.ajax({
        type: 'POST',
        url: '/message/ajaxDeleteFromConference',
        data: {idConference: idConference, idUser: idDeletedUser},
        success: function()
        {
            updateConferenceUsers();
            updateDialogs();
        }
    });
}

function addToConference(idAddedUser)
{
    var isConference = $("input[name=isConference]").val();
    var idConference = $("input[name=idUser]").val();
    console.log("idCOnf:"+idConference);
    $.ajax({
        type: 'GET',
        url: '/message/ajaxAddToConference',
        data: {isConference: isConference, idConference: idConference, idUser: idAddedUser},
        dataType: 'JSON',
        error: function()
        {
        },

        success: function(data)
        {
            console.log("result:");
            console.log(data);
            $("input[name=isConference]").val(1);
            $("input[name=idUser]").val(data.idConference);
            updateConferenceUsers();
            updateDialogs();
        }
    });
}

function startNewConference(idUser)
{
    $("input[name=isConference]").val(0);
    $("input[name=idUser]").val(idUser);
    addToConference(idUser);
}

function addGroupToConference(idAddedGroup)
{
    var isConference = $("input[name=isConference]").val();
    var idConference = $("input[name=idUser]").val();
    $.ajax({
        type: 'GET',
        url: '/message/ajaxAddGroupToConference',
        data: {isConference: isConference, idConference: idConference, idGroup: idAddedGroup},
        dataType: 'JSON',
        success: function(data)
        {
            $("input[name=isConference]").val(1);
            $("input[name=idUser]").val(data.idConference);
            updateConferenceUsers();
            updateDialogs();
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
            $('.dialog').click(function()
            {
                getDialogWithUser($(this).attr("data-idUser"), $(this).attr("data-isConf"));

            });
            console.log(data);
            if (data.idDialog>0)
                $(".dialog[data-idUser="+data.idDialog+"][data-isConf="+data.isConf+"]").click();
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
            console.log(data);
            $("#messageTextArea").val("");
            getDialogWithUser($("input[name=idUser]").val(),$("input[name=isConference]").val());
        }
    });
}

function addSelectUserDialog()
{
    if ($("#addGroupToConferenceDialog").length)
    {
        $.ajax({
            type: 'GET',
            dataType: 'JSON',
            url: '/message/ajaxGetGroups',
            height: 40,
            success: function(data)
            {
                console.log(data);
                $('#addGroupToConferenceDialog').ddslick({
                    data:data,
                    width:274,
                    selectText: '<input type = "text" value = "" placeholder="Добавить группу..." onfocus="updateSelectUserDialog(this.value)" onkeyup="updateSelectUserDialog(this.value)">',
                    imagePosition:"left",
                    onSelected: function(selectedData){
                        if (selectedData.selectedData.value < 0)
                            return;
                        addGroupToConference(selectedData.selectedData.value);
                    }
                });
                updateSelectUserDialog("");
            }
        });
    }
    if ($("#addUserToConferenceDialog").length)
    {
        $.ajax({
            type: 'GET',
            dataType: 'JSON',
            url: '/message/ajaxGetUsers',
            height: 40,
            success: function(data)
            {
                $('#addUserToConferenceDialog').ddslick({
                    data:data,
                    width:274,
                    selectText: '<input type = "text" value = "" placeholder="Найти пользователя" onfocus="updateSelectUserDialog(this.value)" onkeyup="updateSelectUserDialog(this.value)">',
                    imagePosition:"left",
                    onSelected: function(selectedData){
                        if (selectedData.selectedData.value < 0)
                            return;
                        addToConference(selectedData.selectedData.value);
                    }
                });
                updateSelectUserDialog("");
            }
        });
    }
}

function updateSelectUserDialog(text)
{
    $(".dd-container").each(function(){
        var i = 0;
        var showCount = 5;
        $(this).find(".dd-options li a").each(function()
        {
            var val = $($(this).children(".dd-option-value")[0]).val();
            if (i < showCount && val>=0)
            {
                var currentText = $(this).children("label")[0].innerText;
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
        if (i == 0)
        {
            // Сообщаем, что никого не нашли
            $(this).find(".dd-options li a").each(function()
            {
                var val = $($(this).children(".dd-option-value")[0]).val();
                if (val < 0)
                {
                    $(this).show();
                }
            });
        }
    })
}

$(document).ready(function(){
    updateDialogs();
    addSelectUserDialog();
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
                    width:274,
                    selectText: '<input type = "text" value = "" placeholder="Найти пользователя" onfocus="updateSelectUserDialog(this.value)" onkeyup="updateSelectUserDialog(this.value)">',
                    imagePosition:"left",
                    onSelected: function(selectedData){
                        if (selectedData.selectedData.value < 0)
                            return;
                        $("input[name=startDialog]").val(selectedData.selectedData.value);
                        updateDialogs();
                    }
                });
                updateSelectUserDialog("");
            }
        });
    }
});
