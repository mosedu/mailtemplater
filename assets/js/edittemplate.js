/**
 *
 * Плагин для редактирования шаблонов письма
 *
 */
(function ($) {
    var pluginName = "templateeditor";
    $.fn[pluginName] = function (method) {

        var defaults = {
                ontextselect: null,
                sourcefield: "",
                imageblockselector: '.image-block',
                textblockselector: '.text-block',
                blockcontainer: "",
                blocksarea: "",
                blockselector: ""
            },
            oCurrent = null, // текущий выбраный блок, выбирается при клике на нем
            oTemplate = null; // наш элемент с шаблоном, чтобы не искать его в функциях

        var bindSelectEvents = function(el) {
            var oSetting = oTemplate[pluginName]("settings"),
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
            var oSetting = oTemplate[pluginName]("settings");
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
                console.log("Select text");
                if( oSetting.ontextselect !== null ) {
                    oSetting.ontextselect(ob.html());
                }
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

        /**
         * Получение размеров изображения
         *
         * @param imgSrc
         * @param loadFunction
         */
        function getImgSize(imgSrc, loadFunction) {
            var newImg = new Image();

            newImg.onload = function() {
                var height = newImg.height;
                var width = newImg.width;
                loadFunction(width, height);
            };

            newImg.src = imgSrc;
        }

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

                    if(  settings.sourcefield != "") {
                        elArea.html(jQuery(settings.sourcefield).val());
                    }

                    bindSelectEvents(elArea);

                    if( (settings.blocksarea != "") && (settings.blockselector != "") ) {
                        var sDragSelector = settings.blocksarea + " " + settings.blockselector;
                        jQuery(sDragSelector)
                            .draggable({
                                helper: "clone"
                                //drag: function( event, ui ) {
                                //    console.log("drag", ui.offset, ui.position);
                                //}
                            });

                        elArea.droppable({
                            accept: sDragSelector,
                            tolerance: "pointer",
                            //classes: {
                            //    "ui-droppable-active": "ui-state-highlight"
                            //},
                            drop: function( event, ui ) {
                                var oNew = ui.draggable.clone();
                                bindSelectEvents(oNew);
                                elArea.append(oNew);
                                var pos = ui.draggable.offset(), dPos = $(this).offset();
                                console.log(
                                    "Top: " + (pos.top - dPos.top) +
                                    ", Left: " + (pos.left - dPos.left)
                                );
                            },
                            over: function( event, ui ) {
                                console.log("over", ui.offset, ui.position);
                            }
                        });
                    }
                });
            },

            destroy:function() {
                methods.reset.apply(this);
                unbindSelectEvents($(this));
            },

            data: function () {
                return this.data(pluginName);
            },

            updatetext: function (stext) {
                console.log("Upadtetext: " + stext);
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