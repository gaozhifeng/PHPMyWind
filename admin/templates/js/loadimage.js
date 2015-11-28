/*
**************************
(C)2010-2015 phpMyWind.com
update: 2012-6-11 15:44:16
person: Feng
**************************
*/

(function($) {
    jQuery.fn.LoadImage = function(settings) {
        settings = jQuery.extend({
            scaling: true,
            width: 80,
            height: 60,
            loadpic: "templates/images/loading2.gif"
        },settings);

        return this.each(function() {
            $.fn.LoadImage.Showimg($(this), settings)
        })
    };


    $.fn.LoadImage.Showimg = function($this, settings) {

		var loading = $("<img alt=\"图片加载中..\" title=\"图片加载中...\" src=\"" + settings.loadpic + "\" />");
		$this.hide();
        $this.after(loading);

        var autoScaling = function() {
            if (settings.scaling) {
                if (img.width > 0 && img.height > 0) {
                    if (img.width / img.height >= settings.width / settings.height) {
                        if (img.width > settings.width) {
                            $this.width(settings.width);
                            $this.height((img.height * settings.width) / img.width)
                        } else {
                            $this.width(img.width);
                            $this.height(img.height)
                        }
                    } else {
                        if (img.height > settings.height) {
                            $this.height(settings.height);
                            $this.width((img.width * settings.height) / img.height)
                        } else {
                            $this.width(img.width);
                            $this.height(img.height)
                        }
                    }
                }
            }
        }

		var src = $this.attr("alt");
        var img = new Image();

		img.onload = function(){
			autoScaling();
			$this.attr("src", src);
        	$this.show();
			loading.remove();
		};

		img.src = src;

		//$.ajaxSetup ({ cache: false });
       /* $(img).load(function() {*/
       // })
    }
})(jQuery);
