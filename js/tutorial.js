var tutorial = function(params)
{
    this.steps = params.steps;
    this.shimClass = params.shimClass || "shim";
    this.contentClass = params.contentClass || "shimContent";
    this.targetClass = params.targetClass || "tutorialTarget";
    this.curStepCount = 0;
    this.stepCompleted = function(event)
    {
        console.log(this);
        console.log(this.curStepCount);
        console.log(this.steps);
        var curStep = this.steps[this.curStepCount];
        if (curStep.needStopPropagation)
        {
            event.preventDefault();
            event.stopPropagation();
        }
        $(curStep.obj).removeClass(this.targetClass).unbind(curStep.event,this.stepCompleted);
        // TODO :: По идее unbind не выполняентся, т.к. функция указывается не та
        // Нужно тщательно протестировать
        if ( ++this.curStepCount == this.steps.length)
            this.end();
        else
            this.nextStep();
    }
    this.nextStep = function()
    {
        var curStep = this.steps[this.curStepCount];
        $('.'+this.contentClass).html(curStep.startText);
        var self = this;
        var b = function(event)
        {
            self.stepCompleted(event);
        }
        $(curStep.obj).addClass(this.targetClass).bind(curStep.event,b);
    }
    this.end = function()
    {
        $(this.targetClass).removeClass(this.targetClass);
        $("."+this.shimClass).remove();
    }
    this.start = function()
    {
        $("body").append("<div class='"+this.shimClass+"'><div class = '"+this.contentClass+"'></div></div>");
        this.nextStep();
    }
    return this;
}