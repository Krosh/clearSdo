/**
 * Created with JetBrains PhpStorm.
 * User: БОСС
 * Date: 24.01.14
 * Time: 20:32
 * To change this template use File | Settings | File Templates.
 */

function onTimer() {
    var endDate =  new Date(window.endTime);
    var startDate = new Date();
    var date = new Date();
    if (endDate.valueOf()<startDate.valueOf())
    {
        window.location = "endTest?reason=2";
    }
    var seconds = Math.floor((endDate.getTime()-startDate.getTime())/1000);
    var text = "";
    if (seconds>3600)
    {
        text+=Math.floor(seconds/3600)+":";
        seconds = seconds % 3600;
    }
    if (seconds<600)
    {
        text+="0"+Math.floor(seconds/60)+":";
        seconds = seconds % 60;
    }
    else
    {
        text+=Math.floor(seconds/60)+":";
        seconds = seconds % 60;
    }
    if (seconds<10)
        text+="0"+seconds; else text+=seconds;
    window.timerObj.innerHTML = text;
    setTimeout(onTimer, 1000);
}

function startTimer(obj, time)
{
    window.endTime = time;
    window.timerObj = obj;
    setTimeout(onTimer, 1);
}
