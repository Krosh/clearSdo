(function ( $ ) {

    // Ð›Ð¾Ð°Ð´ÐµÑ€ - Ð°Ð½Ð¸Ð¼Ð°ÑˆÐºÐ° Ð·Ð°Ð³Ñ€ÑƒÐ·ÐºÐ¸
    $.fn.loader = function(callback) {
        var self = this;
        
        self.hide();
        $("body").append('<div class="loader-center fadeIn"><img src="img/loader.gif" alt=""></div>');
        
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
    
    
    // ÐšÐ¾Ð½Ñ‚Ñ€Ð¾Ð»Ð»ÐµÑ€ Ð”Ñ€Ð¾Ð¿Ð´Ð°ÑƒÐ½Ð¾Ð²
    $.fn.dropdown = function() {
        var t = this;
        
        $("body").click(function() {
            $(".dropdown").removeClass("active");
            $(".dropdown-container").removeClass("fadeInDown").fadeOut(150);
        });

        this.click(function(e) {
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
        });
    };
    
    // ÐÐ°Ð²Ð¸Ð³Ð°Ñ†Ð¸Ñ
    $.fn.nav = function() {
        
        this.click(function(){
            $(".big-nav").toggleClass("active");
        });
    };
    
    // Ð¤ÑƒÑ‚ÐµÑ€
    $.fn.footer = function() {
        var docHeight = $(window).height();
        var footerHeight = this.height();
        var footerTop = this.position().top + footerHeight;
        
        if (footerTop < docHeight) {
            //this.css('margin-top', (docHeight - footerTop) + 'px');
            this.addClass("fixed");
        }
    };
    
    // Ð›Ð¾Ð³Ð¾
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

}( jQuery ));