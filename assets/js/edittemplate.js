/*
var onOver = function(event) {
//    console.log("mouseover");
//    console.log(event);
    var ob = jQuery(this),
        oParent = ob.parent(),
        sStyle = "";
    if( ob.hasClass("image-block") ) {
        var oImg = ob.find("img:first");
        if( oImg.length > 0 ) {
            var pos = oImg.position();
            sStyle = "width: "+oImg.width()+"px; height: "+oImg.height()+"px; top: "+pos.top+"px; left: "+pos.left+"px;";
//            console.log('Image ' + oImg.width() + " * " + oImg.height() + " : " + sStyle);
        }
        else {
            var pos = ob.position();
            ob.append('<img src="/tmp-local/no-image.png" />');
            sStyle = "width: "+ob.width()+"px; height: "+ob.height()+"px; top: 0; left: 0;";
        }
    }
    else {
//        console.log('Text');
        sStyle = "width: "+ob.width()+"px; height: "+ob.height()+"px; top: 0; left: 0;";
    }

    if( sStyle != "" ) {
        ob.append('<div class="border" style="'+sStyle+'" />');
    }
};

var onOut = function(event) {
//    console.log("mouseout");
//    console.log(event);
//    console.log(jQuery(this));
    var ob = jQuery(this),
        oParent = ob.parent();
    ob.find(".border").remove();
};

jQuery('.text-block')
    .on(
    'mouseenter',
    onOver
)
    .on(
    'mouseleave',
    onOut
);

jQuery('.image-block')
    .on(
    'mouseenter',
    onOver
)
    .on(
    'mouseleave',
    onOut
);


jQuery('.site-about div').each(
    function(index, el) {
        var ob = jQuery(this),
            sStyle = ob.attr("style");
        console.log(sStyle + " = ", ob.css("width"));
    }
);
*/

(function ($) {
    var pluginName = "templateeditor";
    $.fn[pluginName] = function (method) {

        var defaults = {
                imageblockselector: '.image-block',
                textblockselector: '.text-block'
            },
            oCurrent = null, // текущий выбраный блок, выбирается при клике на нем
            oTemplate = null; // наш элемент с шаблоном, чтобы не искать его в функциях

        var bindSelectEvents = function(el) {
            var oSetting = el[pluginName]("settings"),
                aSelectors = [
                    "imageblockselector",
                    "textblockselector"
                ];
            //console.log("bindSelectEvents(): ", oSetting);
            for(var i = 0; i < aSelectors.length; i++) {
                var sSelector = aSelectors[i];
                el
                    .find(oSetting[sSelector])
                    .on(
                        'click.' + pluginName,
                        onClick
                    )
                    .on(
                        'mouseenter.' + pluginName,
                        onOver
                    )
                    .on(
                    'mouseleave.' + pluginName,
                    onOut
                );
            }
        };

        var unbindSelectEvents = function(el) {
            var oSetting = el.setting("settings");
            //console.log("unbindSelectEvents(): ", oSetting);
            aSelectors = [
                "imageblockselector",
                "textblockselector"
            ];
            console.log("bindSelectEvents(): ", oSetting);
            for(var i = 0; i < aSelectors.length; i++) {
                var sSelector = aSelectors[i];
                el
                    .find(oSetting[sSelector])
                    .off('.' + pluginName);
            }
        };

        var onOver = function(event) {
            var ob = jQuery(this);

            setBlockBorder(ob, "over-block-border");

        };

        var onOut = function(event) {
            var ob = jQuery(this);
            clearBlockBorder(ob, "over-block-border");
        };

        var onClick = function(event) {
            var ob = jQuery(this),
                oSetting = oTemplate[pluginName]("settings");

            if( ob.hasClass(oSetting.imageblockselector) ) {
                var oImg = getBlockImage(ob);
                if( oImg.length == 0 ) {
                    ob.append('<img src="/tmp-local/no-image.png" />');
                }
            }
            else {
            }

            setCurrentBlock(ob);
        };

        /**
         * Получаем картинку из блока с изображением
         *
         * @param oBlock
         * @returns {*}
         */
        var getBlockImage = function(oBlock) {
            return oBlock.find("img:first");
        };

        /**
         * Выделяем блок при клике на нем
         *
         * @param oBlock
         */
        var setCurrentBlock = function(oBlock) {
            clearBlockBorder(oCurrent, "current-block-border");

            if( ! oBlock.is(oCurrent) ) {
                oCurrent = oBlock;
                setBlockBorder(oBlock, "current-block-border");
            }
            else {
                oCurrent = null;
            }

        };

        /**
         * Рисуем границу выбраному блоку
         *
         * @param oBlock
         */
        var setBlockBorder = function(oBlock, sClass) {
            var sStyle = "",
                oSetting = oTemplate[pluginName]("settings");
            sClass = sClass || "overblockborder";

            if( oBlock.hasClass(oSetting.imageblockselector) ) {
                var oImg = getBlockImage(ob),
                    pos = oImg.position();

                sStyle = "width: "+oImg.width()+"px; height: "+oImg.height()+"px; top: "+pos.top+"px; left: "+pos.left+"px;";
            }
            else {
                sStyle = "width: "+oBlock.width()+"px; height: "+oBlock.height()+"px; top: 0; left: 0;";
            }

            if( sStyle != "" ) {
                oBlock.append('<div class="'+sClass+'" style="'+sStyle+'" />');
            }
        };

        /**
         * Убираем границу выбраному блоку
         *
         * @param oBlock
         */
        var clearBlockBorder = function(oBlock, sClass) {
            if( oBlock !== null ) {
                sClass = sClass || "overblockborder";
                oBlock.find("." + sClass).remove();
            }
        };

        var methods = {
            init: function (options) {
                return this.each(function () {
                    var elArea = $(this);
                    if (elArea.data(pluginName)) {
                        return this;
                    }

                    oTemplate = elArea;

                    var settings = $.extend({}, defaults, options || {});

                    elArea.data(pluginName, {
                        settings: settings
                    });

                    bindSelectEvents(elArea);
                });
            },

            destroy:function() {
                methods.reset.apply(this);
                unbindSelectEvents($(this));
            },

            data: function () {
                return this.data(pluginName);
            },

            settings: function (setname) {
                var oData = this.data(pluginName);
                //console.log('settings() oData = ', oData);
                return (setname in oData.settings) ? oData.settings[setname] : oData.settings;
            }
        };

        if ( method && methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.' + pluginName);
            return false;
        }

    };
})(window.jQuery);