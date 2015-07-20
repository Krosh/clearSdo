(function ( $ ) {

    // Лоадер - анимашка загрузки
    $.fn.loader = function(callback) {
        var self = this;
        
        self.hide();

        var htmlLoader = '<div class="stml-logo"> <div class="rnd-1"> <div class="rnd-1-inner"></div></div><div class="rnd-2"> <div class="rnd-2-inner"></div></div><div class="rnd-3"> <div class="rnd-3-inner"></div></div><div class="rnd-4"> <div class="rnd-4-inner"> <div class="runner"></div></div></div></div>';
        $("body").append('<div class="loader-center fadeIn">'+htmlLoader+'</div>');
        
        setTimeout(function(){
            $(".loader-center").fadeOut(200, function(){
                $(this).remove(); 
                
                self.show().addClass("zoomIn");
                
                if (typeof callback == 'function') {
                    callback.call(self);
                }
                
            });
        }, 1800);
    };

    $.fn.showLoader = function(callback) {
        var htmlLoader = '<div class="stml-logo"> <div class="rnd-1"> <div class="rnd-1-inner"></div></div><div class="rnd-2"> <div class="rnd-2-inner"></div></div><div class="rnd-3"> <div class="rnd-3-inner"></div></div><div class="rnd-4"> <div class="rnd-4-inner"> <div class="runner"></div></div></div></div>';
        $(this).append('<div class="loader-overflow fadeIn"><div class="loader-center fadeIn">'+htmlLoader+'</div></div>');
    };

    $.fn.hideLoader = function(callback) {
        $(".loader-overflow").removeClass("fadeIn");
        $(".loader-overflow").fadeOut(200, function(){
            $(this).remove();
        });  
    };
    
    
    // Контроллер Дропдаунов
    $.fn.dropdown = function() {
        var t = this;
        
        $("body").click(function() {
            $(".dropdown").removeClass("active");
            $(".dropdown-container").removeClass("fadeInDown").fadeOut(150);
        });

        this.click(function(e) {
            
            if($(e.target).is("a")) {
                return true;
            } else {
                e.preventDefault();
                
                var self = $(this);
                var dContainer = self.find(".dropdown-container");
                
                $(".dropdown").not(self).removeClass("active");
                $(".dropdown-container").not(dContainer).removeClass("fadeInDown").fadeOut(150);
                
                if(!self.hasClass("active")) {
                    dContainer.show().addClass("fadeInDown");
                    self.addClass("active");
                } else {
                    self.removeClass("active");
                    dContainer.removeClass("fadeInDown").fadeOut(150);
                }
                return false;   
            }
        });
    };
    
    // Навигация
    $.fn.nav = function() {
        
        this.click(function(){
            $(".big-nav").toggleClass("active");
        });
    };
    
    // Футер
    $.fn.footer = function() {
        var docHeight = $(window).height();
        var footerHeight = this.height();
        var footerTop = this.position().top + footerHeight;
        
        if (footerTop < docHeight) {
            //this.css('margin-top', (docHeight - footerTop) + 'px');
            this.addClass("fixed");
        } else {
            this.removeClass("fixed");
        }
        
        //console.log(footerTop + " < " + docHeight);
    };
    
    // Лого
    $.fn.logo = function() {
        
        this.hover(function() {
            $(".logo .small").fadeOut(100, function() {
                $(".logo .big").fadeIn(200);
            });
        }, function() {
            $(".logo .big").fadeOut(100, function() {
                $(".logo .small").fadeIn(200);
            });
        });
    };

    // детектор ретины
    $.fn.retinizr = function() {
        if('devicePixelRatio' in window && window.devicePixelRatio == 2){
            $(this).attr({"src": $(this).attr("src").substr(0, $(this).attr("src").length - 4) + "@2x" + $(this).attr("src").substr(-4)});
        }
    };

    // всплывашка при драг'н'дропе
    $.fn.uploaderInfo = function(action) {

        var self = $(this),
            textDefault = "Перетащите файл сюда, чтобы загрузить его",
            textActive = "Отпустите файл для начала загрузки";

        self.css({"position": "relative"});
 
        if (action === "show") {
            self.append('<div class="uploader-info"><span>'+textDefault+'</span></div>');
        }
 
        if (action === "hide") {
            self.find(".uploader-info").remove();
        }

        if (action === "setActive") {
            self.find(".uploader-info").addClass("active");
            self.find(".uploader-info").find("span").text(textActive);
        }

        if (action === "unsetActive") {
            self.find(".uploader-info").removeClass("active");
            self.find(".uploader-info").find("span").text(textDefault);
        }
 
    };

}( jQuery ));