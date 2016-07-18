/**
 *
 * Плагин для редактирования шаблонов письма
 *
 */
(function ($) {
    var pluginName = "templateeditor",
        oTemplate = null, // наш элемент с шаблоном, чтобы не искать его в функциях
        oCurrent = null; // текущий выбранный блок, выбирается при клике на нем

    $.fn[pluginName] = function (method) {

        var defaults = {
                ontextselect: null,
                onimageselect: null,
                sourcefield: "",
                imageblockselector: '.image-block',
                textblockselector: '.text-block',
                blockcontainer: "",
                blocksarea: "",
                blockselector: ""
            };

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
            //console.log("unbindSelectEvents(): ", oSetting);
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

            var bSelected = setCurrentBlock(ob);

            if( isBlockImage(ob) ) { // .hasClass(oSetting.imageblockselector) ) {
                if( oSetting.onimageselect !== null ) {
                    oSetting.onimageselect(ob, bSelected);
                }
                var oImg = getBlockImage(ob);
                if( oImg.length == 0 ) {
                    ob.append('<img src="/tmp-local/no-image.png" />');
                }
            }
            else {
                //console.log("Select text");
                if( oSetting.ontextselect !== null ) {
                    oSetting.ontextselect(ob, bSelected);
                }
            }

        };

        /**
         * Текущий блок - текстовый?
         *
         * @returns object
         */
        var isBlockText = function(ob) {
            var oSetting = oTemplate[pluginName]("settings"),
                sSelector = oSetting.textblockselector;

            sSelector = sSelector.replace('.', '');
            ob = ob || getCurrentBlock();

            return (ob !== null) && ob.hasClass(sSelector);
        };

        /**
         * Текущий блок - картиночный?
         *
         * @returns object
         */
        var isBlockImage = function(ob) {
            var oSetting = oTemplate[pluginName]("settings"),
                sSelector = oSetting.imageblockselector;
            sSelector = sSelector.replace('.', '');

            ob = ob || getCurrentBlock();

            return (ob !== null) && ob.hasClass(sSelector);
        };

        /**
         * Перенос html в поле ввода, откуда этот html брали
         */
        var setDataToSourceField = function() {
            var oSetting = oTemplate[pluginName]("settings");

            if( oSetting.sourcefield != "") {
                var ob = oTemplate.clone();
                ob.find(".current-block-border").remove();
                ob.find(".over-block-border").remove();
                jQuery(oSetting.sourcefield).val(ob.html());
            }
        };

        /**
         * Получение html из поля ввода
         */
        var getDataFromSourceField = function() {
            var oSetting = oTemplate[pluginName]("settings"),
                s = "";

            if(  oSetting.sourcefield != "") {
                s = jQuery(oSetting.sourcefield).val();
            }
            return s;
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
            var bSelected = true;
            clearBlockBorder(oCurrent, "current-block-border");

            if( ! oBlock.is(oCurrent) ) {
                oCurrent = oBlock;
                setBlockBorder(oBlock, "current-block-border");
            }
            else {
                oCurrent = null;
                bSelected = false;
            }

            //console.log('setCurrentBlock() bSelected = ' + (bSelected ? 'true' : 'false'));
            //console.log('setCurrentBlock() oCurrent = ', oCurrent);
            //console.log('setCurrentBlock() return oCurrent = ', getCurrentBlock());

            return bSelected;
        };

        /**
         * Получаем текущий блок
         *
         * @returns object
         */
        var getCurrentBlock = function() {
            //console.log("getCurrentBlock() : ", oCurrent);
            return oCurrent;
        };

        /**
         * Перерисовка границ блока
         *
         * @param oBlock
         */
        var refreshBlockBorder = function(oBlock) {
            if( oBlock === null ) {
                return;
            }

            var a = [
                "current-block-border",
                "over-block-border"
            ];

            for(var i = 0; i < a.length; i++) {
                var sClass = a[i],
                    oBorder = oBlock.find("." + sClass);
                console.log("refreshBlockBorder(): oBorder class = " + sClass);
                if( oBorder.length > 0 ) {
                    setBlockBorder(oBlock, sClass);
                    console.log("refreshBlockBorder(): set border " + sClass);
                }
            }
        };

        /**
         * Рисуем границу выбраному блоку
         *
         * @param oBlock
         */
        var setBlockBorder = function(oBlock, sClass) {
            var sStyle = "";
            sClass = sClass || "overblockborder";

            if( isBlockImage(oBlock) ) { //.hasClass(oSetting.imageblockselector) ) {
                var oImg = getBlockImage(oBlock),
                    pos = oImg.position();

                sStyle = "width: "+oImg.width()+"px; height: "+oImg.height()+"px; top: "+pos.top+"px; left: "+pos.left+"px;";
            }
            else {
                sStyle = "width: "+oBlock.width()+"px; height: "+oBlock.height()+"px; top: 0; left: 0;";
            }

            if( sStyle != "" ) {
                var oldBorder = oBlock.find("."+sClass);
                if( oldBorder.length > 0 ) {
                    oldBorder.attr("style", sStyle);
                }
                else {
                    oBlock.append('<div class="'+sClass+'" style="'+sStyle+'" />');
                }
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

                    //if(  settings.sourcefield != "") {
                    //    elArea.html(jQuery(settings.sourcefield).val());
                    //}
                    elArea.html(getDataFromSourceField());

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

            getBlockData: function () {
                var oCur = getCurrentBlock();
                //console.log('getBlockData oCurrent = ', oCur, oCurrent);
                if( oCur === null ) {
                    return "";
                }

                if( isBlockImage(oCur) ) {
                    var oImg = getBlockImage(oCur),
                        sImg = jQuery("<div></div>").append(oImg.clone()).html();

                    return sImg; // oImg.attr("src");
                }
                else {
                    var oTmp = oCur.clone();
                    oTmp.find(".over-block-border").remove();
                    oTmp.find(".current-block-border").remove();
                    return oTmp.html();
                }
            },

            setBlockData: function (sData) {
                var oCur = getCurrentBlock();

                if( oCur === null ) {
                    return;
                }

                if( isBlockImage(oCur) ) {
                    //console.log("setBlockData() : image block = " + sData);
                    var oImg = getBlockImage(oCurrent),
                        ob = jQuery("<div></div>").append(sData),
                        src = ob.find("img:first").attr("src");
                    //console.log("setBlockData() : ob = ", ob);
                    //console.log("setBlockData() : src = " + src);

                    //oImg.attr("src", ob.text());
                    oImg.attr("src", src);
                }
                else if( isBlockText(oCur) ) {
                    //console.log("setBlockData() : text block");
                    var oTmp = oCur.clone();
                    oCur.html(sData);
                    oTmp.find(".current-block-border").appendTo(oCur);
                }

                setDataToSourceField();
                setTimeout(function(){refreshBlockBorder(oCur);}, 200)
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