/* Author: 
 * @author Matthew Doll
 * 
 * /* jQuery.contentEditable Plugin
Copyright © 2011 FreshCode
http://www.freshcode.co.za/

*/ 
if (typeof Object.create === 'undefined') {
    Object.create = function (o) { 
        function F() {} 
        F.prototype = o; 
        return new F(); 
    };
}



(function($) {
   
    // The jQuery.aj namespace will automatically be created if it doesn't exist
    $.widget("cms.wysiwyg", {
        options: {
            toolbar: "bold italic strike removeformat | insertLink insertImage insertGallery insertHtml "+
        "| unorderedlist orderedlist indent outdent superscript subscript " +
        "section paragraph h2 h3 h4 fontcolor" //h1 //h5//blockquote code
        },
        actions: {
            bold: {
                title: "Bold",
                iconClass: 'cms-icon-bold',
                hotkey: 'ctrl+b',
                action: function(){
                    document.execCommand("bold", false, null);
                }
            },
            italic: {
                title: 'Italicize',
                iconClass: 'cms-icon-italic',
                hotkey: 'ctrl+i',
                action: function(){
                    document.execCommand('italic', false, null);
                }
            },
            underline: {
                title: 'Underline',
                iconClass: 'cms-icon-underline',
                hotkey: 'ctrl+u',
                action: function(){
                    document.execCommand('underline', false, null);
                }
            },
            strike: {
                title: "Strike",
                iconClass: 'cms-icon-strike',
                action: function(){
                    document.execCommand('strikeThrough', false, null);
                }
            },
            removeformat: {
                title: 'Remove Formating',
                iconClass: 'cms-icon-erase',
                hotkey: 'ctrl+m',
                action: function(){
                    document.execCommand('removeFormat', false, null);
                }
            },
            /////////////////////////////////
            insertLink: {
                title: "Insert Link to a web page",
                iconClass: 'cms-icon-link',
                hotkey: 'ctrl+l',
                action: function(){
                    //   var urlPrompt = prompt("Enter URL:", "http://");
                    // document.execCommand("createLink", false, urlPrompt);
                    alert(this.getSelectionContainerElement());
                    return false;
                }
            },
            insertImage: {
                title: "Insert Image",
                iconClass: 'cms-icon-image',
                hotkey: 'ctrl+g',
                init: function(){
                    console.log('init Image');
                    this.editor.filemanager({
                        maxItems: 20, 
                        folder: this.element.data('folder'), 
                        rest: this.element.data('rest'), 
                        hash: this.element.data('hash')
                        });
                    var self = this;
                    this.editor.bind("filemanagerselected",function(event,data){
                        try{
                            document.execCommand("enableObjectResizing", false, false);
                        } catch (e){}
                        
                        //console.log(data);

                        self.insertBlock('cms.image', data);
                    });
                },
                action: function(){
                    this.editor.filemanager("show");
                //  var urlPrompt = prompt("Enter Image URL:", "http://");
                //document.execCommand("InsertImage", false, urlPrompt); 
                // this._insertHtml('<div style="width: 100px; height:100px; background: #f00; float:left">dfsdsfsf</div>');
                }
            },
            blockquote: {
                title: "Blockquote",
                iconClass: 'cms-icon-quote',
                hotkey: 'ctrl+q',
                action: function(){
                    document.execCommand("FormatBlock", null, '<blockquote>');
                }
            },
            code: {
                title: "Code",
                iconClass: 'cms-icon-code',
                hotkey: 'ctrl+alt+k',
                action: function(){
                    document.execCommand("FormatBlock", null, '<pre>');
                }
            },
            ///////////////////////////////
            unorderedlist: {
                title: "Unordered List",
                iconClass: 'cms-icon-ul',
                hotkey: 'ctrl+alt+u',
                action: function(){
                    document.execCommand("InsertUnorderedList", false, null);
                }
            },
            orderedlist: {
                title: "Ordered List",
                iconClass: 'cms-icon-ol',
                hotkey: 'ctrl+alt+o',
                action: function(){
                    document.execCommand("InsertOrderedList", false, null);
                }
            },
            indent: {
                title: "Indent",
                iconClass: 'cms-icon-indent',
                hotkey: 'tab',
                action: function(){
                    document.execCommand("indent", false, null);
                }
            },
            outdent: {
                title: "Outdent",
                iconClass: 'cms-icon-outdent',
                hotkey: 'shift+tab',
                action: function(){
                    document.execCommand("outdent", false, null);
                }
            },
            superscript: {
                title: "Superscript",
                iconClass: 'cms-icon-superscript',
                hotkey: 'ctrl+.',
                action: function(){
                    document.execCommand("superscript", false, null);
                }
            },
            subscript: {
                title: "Subscript",
                iconClass: 'cms-icon-subscript',
                hotkey: 'ctrl+shift+.',
                action: function(){
                    document.execCommand("subscript", false, null);
                }
            },
            //////////////////////////////
            section: {
                title: "Section",
                iconClass: 'cms-icon-section',
                action: function(){ 
                    // document.execCommand("FormatBlock", null, '<div>');
             
                   // console.log('wrapping');
                  //  console.log($().wrapSelection().parentsUntil( this.editor));
                    document.createElement('section');//this fixes html5 issue in ie8
                    $().wrapSelection().parentsUntil('.editor').filter('p, h1, h2, h3, h4, h5, hr, ul, ol').wrapAll('<div class="section" />'); //
                    //  $().wrapSelection().parentsUntil('.editor').wrapAll('<div class="section" />');
                    this.editor.find('.selection').replaceWith(function() {
                        return $(this).contents();
                    }); //remove selection tags
                }
            },
            paragraph: {
                title: "Paragraph",
                iconClass: 'cms-icon-paragraph',
                hotkey: 'ctrl+alt+0',
                action: function(){
                    document.execCommand("FormatBlock", null, '<p>');
                }
            },
            h1: {
                title: "Heading 1",
                iconClass: 'cms-icon-h1',
                hotkey: 'ctrl+alt+1',
                action: function(){
                    document.execCommand("FormatBlock", null, '<h1>');
                }
            },
            h2: {
                title: "Heading 2",
                iconClass: 'cms-icon-h2',
                hotkey: 'ctrl+alt+2',
                action: function(){
                    document.execCommand("FormatBlock", null, '<h2>');
                }
            },
            h3: {
                title: "Heading 3",
                iconClass: 'cms-icon-h3',
                hotkey: 'ctrl+alt+3',
                action: function(){
                    document.execCommand("FormatBlock", null, '<h3>');
                }
            },
            h4: {
                title: "Heading 4",
                iconClass: 'cms-icon-h4',
                hotkey: 'ctrl+alt+4',
                action: function(){
                    document.execCommand("FormatBlock", null, '<h4>');
                }
            },
            h5: {
                title: "Heading 5",
                iconClass: 'cms-icon-h5',
                hotkey: 'ctrl+alt+5',
                action: function(){
                    document.execCommand("FormatBlock", null, '<h5>');
                }
            },
            ////////////////////////
            undo: {
                title: "Undo",
                iconClass: 'cms-icon-undo',
                action: function(){
                    document.execCommand("FormatBlock", null, '<h4>');
                }
                
            },
            redo: {
                title: "Redo",
                iconClass: 'cms-icon-redo',
                action: function(){
                    document.execCommand("FormatBlock", null, '<h5>');
                }
            },
            
            fontcolor: {
                title: "Font Color",
                iconClass: 'cms-icon-fontcolor',
                action: function(){
                   // $().wrapSelection().removeClass('selection').css('color','#f00');
                // this.editor.find('.selection').unwrap();
                    this.insertBlock('cms.html', {}, true)
                    
                }
            },
            insertGallery: {
                title: "Insert Gallery",
                iconClass: 'cms-icon-gallery',
                action: function(){
                    this.insertBlock('cms.html', {}, true)  
                }
            },
            insertHtml: {
                title: "Insert HTML",
                iconClass: 'cms-icon-html',
                action: function(){
                    this.insertBlock('cms.html', {}, true)  
                }
            }
        },
        
        defaultBlock: {
            title: "CMS Block",
            className: "cms-block",
            data: {},
            
            init: function(data){
                this.data = $.extend(this.data,data);
              //  this.element = $(this);
         //       element.data('block',this);
               // this.$self.data(this);
            },
            
            
            compact: function(e, block){
               // var block = $(this).data('block');
                $(this).empty();
            },
            expand: function(e, block){
               var $this =$(this);  
               //  var block = $(this).data('block');
               //     block.element = $(this);
               //     $(this).data('block', block);
                    
               // console.log(['default expand', this, block]);    

                // this.create.call(this);
                block.create.call(this, null, block);
                
      
                $this.children().wrapAll('<div class="ui-widget-content ui-state-default"/>');
                
                $this.prepend('<div class="ui-widget-header handle"><span class="ui-icon ui-icon-carat-2-n-s"></span> '+block.title+'</div>');
            
                //data.order = this.itemCount;
                //self.data(block.data);
            
                $this.bind('edit',   block.edit);
                $this.bind('update', block.update);
                $this.bind('save',   block.save);
                $this.bind('remove', block.remove);
                $this.bind('collapse',block.collapse);
                //$self.unbind('expand');
                //$self.bind('expand', block.expand);


                var edit = $('<a class="ui-state-default ui-corner-all edit ui-button"><span class="ui-icon ui-icon-pencil"></span></a>');
                var del = $('<a class="ui-state-default ui-corner-all delete ui-button"><span class="ui-icon ui-icon-closethick"></span></a>');
                $this.append(edit,del);
 
                edit.bind("click.block", function(e){
                  //  console.log(['click edit', this, block]);
                    $this.trigger('edit', block);
                });
                
                del.bind("click.selectimage", function(e){
                    $('<div>Are you sure you want to delete<br /> &quot;'+block.title+'&quot;</div>')
                    .dialog({
                        resizable: false,
                        title: "Delete",
                        modal: true,
                        buttons: {
                            Delete: function(){
                                $this.trigger('remove',block);
                                $(this).dialog( "close" );
                            },
                            Cancel: function() {
                                $(this).dialog( "close" );
                            }
                        }
                    });                    
                });
                
                $this.addClass('expanded');
                $this.find('*').andSelf().attr('contentEditable',false);
                $this.disableSelection();
              //  console.log(['default expand2', block, block.data]);    

            },
            create: function(){ },
            edit: function(){},
            save: function(e, block){
               // var block = $(this).data('block');
               // $.extend(block.data,data);
                var $this = $(this);
              //  console.log(['default save', this, block]);
                for(var i in block.data){
                    $this.attr('data-'+i, block.data[i]);
                }
              //  $this.data('block',block);
                    
            },
            update: function(e, block, data){
              // var block = $(this).data('block');
              // console.log(['default update', this]);
               $(this).trigger('save',block);
            },
            remove: function(e, block){
              // var block = $(this).data('block');
              // console.log(['default remove', this, block]);
               $(this).unbind();
               $(this).empty().remove();
            }
        },   
        
        blocks: {
            'cms.image': {
                title: "Image Block",
                className: "cms-block-image",
                data: {
                    name:"",
                    type:"",
                    source:"",
                    thumbnail:"",
                    preview:"",
                    
                    title:"", 
                    description:"", 
                    source:"",
                    url:"",
                    copyright:"", 
                    width:"", 
                    height:"", 
                    owner:"", 
                    fullname:""
                },
                options: {
                    
                },
                create: function(e, block){                    
                   
                   // block = $(this).data('block');
                   // console.log(['img create', this, block]);
                    var data = block.data;
                    $(this).append('<img src="'+data.preview+'" alt="'+data.title+'" />\n\
                        <div class="overlay"><div class="properties">\n\
                            <div><label>Title:</label><span class="title">'+data.title+'</span></div>\n\
                            <div><label>Description:</label><span class="description">'+data.description+'</span></div>\n\
                            <div><label>Source:</label><span class="source">'+data.source+'</span></div>\n\
                            <div><label>Url:</label><span class="url">'+data.url+'</span></div>\n\
                            <div><label>Copyright:</label><span class="copyright">'+data.copyright+'</span></div>\n\
                        </div></div>');
                },
                edit: function(e, block){
                   // var block = $(this).data('block');
                   var $this = $(this);
                   // console.log(['img edit', this, block]);
                    
                    $this.imageeditor({
                        image: block.data,
                        save: function(e, data){ 
                            $this.trigger('update',[block, data]);
                        }
                    });
                
//                function(e, data){ 
//                            console.log(['imgeditor save',block, this, data, block.element.get(0)]);
//                            block.element.css('border','solid red')}
                        
                   // $(this).unbind('imageeditorsave');
                   // $(this).bind('imageeditorsave', function(){ $(this).css('border','solid red'); });//$.proxy(block.update, this)
                },
                update: function(e, block, data){
                    
                    var $this = $(this);
                    
                    if(data){
                        block.data = $.extend(block.data, data);
                    }
                    
                   // var block = $(this).data('block');
                   // console.log(['img update',this, block]);

                    $this.trigger('save',  block.data);
                    $this.find('.title').text(block.data.title);
                    $this.find('.description').text(block.data.description);
                    $this.find('.source').text(block.data.source);
                    $this.find('.url').text(block.data.url);
                    $this.find('.copyright').text(block.data.copyright); 
                }
            },
            'cms.html': {
                title: "HTML Block",
                className: "cms-block-html",
                data: {contents:""},
                collapase: function(e, block){
                    var contents = $(this).find('.contents').html();
                    $(this).html(contents);
                },
                create: function(e, block){
                    var $this = $(this);
                    
                  //  console.log($this.children().length);
                    if($this.children().length > 0){
                        $this.children().wrapAll('<div class="contents"/>');
                    } else {
                        $this.append('<div class="contents"></div>');
                    }
                },
               // create: function(e, block){  },
                edit: function(e,block){
                    var $this = $(this);
                    $('<div class="cms-block-html"><textarea>'+block.data.contents+'</textarea></div>').dialog({
                    autoOpen: true,
                    resizable: false,
                    width:550,
                    height:600,
                    title: "Edit HTML",
                    modal: true,
                    buttons: {
                        Save: function(){ 
                            console.log(this);
                           var c = $(this).find('textarea').val();
                           $this.trigger('update', [block, {contents: c }]);
                           $(this).dialog('close');
                       },
                        Cancel: function(){$(this).dialog('close')}
                    }
                })},
                update: function(e, block, data){                   
                    if(data){
                        block.data = $.extend(block.data, data);
                    }
                    $(this).trigger('save',  block.data);
                    $(this).find('.contents').html(block.data.contents);
                }
            },
            'cms.gallery': {
                title: "Gallery Block",
                className: "cms-block-gallery",
                data: {contents:""},
                collapase: function(e, block){
                    var contents = $(this).find('.contents').html();
                    $(this).html(contents);
                },
                create: function(e, block){
                    var $this = $(this);
                    
                  //  console.log($this.children().length);
                    if($this.children().length > 0){
                        $this.children().wrapAll('<div class="contents"/>');
                    } else {
                        $this.append('<div class="contents"><ul></ul></div>');
                    }
                },
               // create: function(e, block){  },
                edit: function(e,block){
                    var $this = $(this);
                    $('<ul class="cms-block-html cms-filemanager"></div>').dialog({
                    autoOpen: true,
                    resizable: false,
                    width:550,
                    height:600,
                    title: "Edit HTML",
                    modal: true,
                    buttons: {
                        Save: function(){ 
                            console.log(this);
                           var c = $(this).find('textarea').val();
                           $this.trigger('update', [block, {contents: c }]);
                           $(this).dialog('close');
                       },
                        Cancel: function(){$(this).dialog('close')}
                    }
                })},
                update: function(e, block, data){                   
                    if(data){
                        block.data = $.extend(block.data, data);
                    }
                    $(this).trigger('save',  block.data);
                    $(this).find('.contents').html(block.data.contents);
                }
            },
    
        _create: function() {
            
            //disable style with css; would rather use existing style sheet to style strong/em, and fix b-> strong later
            try { //http://stackoverflow.com/questions/536132/stylewithcss-for-ie
                document.execCommand("styleWithCSS", 0, false);
            } catch (e) {
                try {
                    document.execCommand("useCSS", 0, true);
                } catch (e) {
                    try {
                        document.execCommand('styleWithCSS', false, false);
                    }
                    catch (e) {
                    }
                }
            }

            
            
            
            
            
            var that = this;
            this.container = this.element.wrap('<div class="ui-widget cms-wysiwyg" />');
            
            if(this.element.is("textarea")){
                this.element.hide();
                this.editor = $('<div />');
           
                this.parent('form').bind('submit',function(){
                    that.element.val(that.editor.html());
                });
                
            } else {
                this.editor = this.element;
            }
            
            
            this.editor.addClass("ui-widget-content");
 
            //build toolbar
            var sets = this.options.toolbar.split("|");
            
            this.menubar = $('<div class="toolbar" class="" />');
            for(var i in sets){
                var seg = $('<span class="ui-buttonset" />').appendTo(this.menubar);
                var bits = sets[i].split(" ");
                for(var j in bits){
                    var item = this.actions[bits[j]];
                    if(item != undefined){
                        //call init
                        if(item.init){
                            $.proxy(item.init,this)();
                        }
                        
                        
                        $('<a href="#" class="ui-button ui-widget ui-state-default ui-state-disabled ui-button-icon-only" title="'+(item.title?item.title:'')+(item.hotkey?' ('+item.hotkey+')':'')+'"><span class="cms-icon '+(item.iconClass?item.iconClass:'')+'"></span></a>')
                        .bind("click", bits[j],$.proxy(this._action,this))
                        .appendTo(seg);
                    }
                }
                

                
            }

            // = $(bar);
            
            this.menubar.children().each(function(index, element){
                var items = $(element).children();
                // items.disableSelection();
                items.first().addClass("ui-corner-left");
                items.last().addClass("ui-corner-right");
            });//.//wrapAll('</div>');
            this.menubar.disableSelection();
            // 
            this.editor.before(this.menubar.wrap('<div class="toolbar-wrapper"></div>'));
            this.menubar.wrap('<div class="toolbar-wrapper"></div>')
            // this.container.prepend(this.menubar);

            $(window).scroll(function () {
                var docTop = $(window).scrollTop();

                var toolbarTop = that.menubar.offset().top;
                if (docTop > toolbarTop) {
                    that.menubar.css({
                        "position": "fixed", 
                        "top": "0"
                    });
                } else if(toolbarTop == 0) {
                    that.menubar.css("position", "relative");
                }
            });
            this.enable();
              
            this.editor.sortable({ 
                //containment:this.editor, 
                items: 'p, h2, h3, ul, ol, div.container', 
                placeholder: 'ui-state-highlight', 
                forcePlaceholderSize: true,
                handle: '.handle',
                axis: 'y',
                // cursorAt: 'left',
                cursor: 'move'
            });
        //              this.editor.find('p, h2, h3, ul, ol').live({
        //                  load: function() {
        //                  },
        //                  
        //                  mouseenter: function() {
        //                   $(this).append('<div class="handle">handle</div>')
        //                  },
        //                  mouseleave: function() {
        //                     $(this).find('.handle').remove();
        //                  }});
                      
        },
        enabled: false,
        enable : function(){
            
            var items = this.menubar.find('a');
            items.bind("mouseover.wysiwyg", function(){
                $(this).addClass('ui-state-hover')
                });
            items.bind("mouseout.wysiwyg", function(){
                $(this).removeClass('ui-state-hover ui-state-active')
                });
            items.bind("mousedown.wysiwyg", function(){
                $(this).addClass('ui-state-active')
                });
            items.bind("mouseup.wysiwyg", function(){
                $(this).removeClass('ui-state-active')
                });
            
            
            items.removeClass('ui-state-disabled');
            this.editor.attr("contentEditable", "true").addClass("editor");
            this.enabled = true;
        },
        disable : function(){
            this.enabled = false;
            this.menubar.find('a').addClass('ui-state-disabled').unbind('.wysiwyg');
            this.editor.attr("contentEditable", false).removeClass("editor");
        },
        _init: function() {
        // this.dialog.dialog("open");
        },
        
       
            
        _action: function(event){
            event.stopPropagation();
            event.preventBubble = true;
            ;
            event.preventDefault();
            this.editor.focus();
            if(this.enabled){
                this.actions[event.data].action.call(this);
            }
            return false;
        },
        
        //        _getSelection: function() {
        //            console.log(document.createRange());
        //            if (this.getRangeAt)
        //		return this.getRangeAt(0);
        //            else { // Safari!
        //		var range = document.createRange();
        //		range.setStart(this.anchorNode,selectionObject.anchorOffset);
        //		range.setEnd(this.focusNode,selectionObject.focusOffset);
        //		return range;
        //            }
        //            
        //            
        //            //           if ($.browser.msie) return document.selection;
        //            //            return document.getSelection();
        //        },

     
        


        insertBlock: function(name, data, edit){
           // var def = $.extend({},this.defaultBlock);
           var base = Object.create(this.blocks[name]);
           var block = $.extend(true,{},this.defaultBlock,base);
           
        //   block.init(data);
           
            //$.extend(true, block.data, data);
            
            //  console.log(['insertBlock',block, base, this.defaultBlock]);
 
         //   b.data('block', block);
           var b = $('<div class="ui-widget container '+block.className+'" />');

            console.log(["insert block",b]);
                      
            block.init(data);
            
           // b.data('block', block);
            b.bind('expand', block.expand); 
            this._insertBlock(b);
            b.trigger('expand', block);
            
            if(edit){
                b.trigger('edit', block);
            }
            
            
            //  img.append();
           
          //  return b;
        },
        
        _insertBlock: function(block, prepend){
            this.editor.focus();
           // console.log('insert block');
            var element = this._getSelectionContainerElement();
            //console.log('insert block');
            //console.log(element.parentNode);
            //$(element.parentNode).css('border','solid red');
            if($.browser.msie){
               // element = element.parentNode;
              //  document.selection
                document.execCommand('insertImage', false, '#replace');
               // this.editor.find('img[src="#replace"]').parentsUntil('.editor,section').css('border','solid red');
                this.editor.find('img[src="#replace"]').parentsUntil('.editor,section').last().after(block);
                this.editor.find('img[src="#replace"]').remove();
                return
            }

            if(element == this.editor.get(0)){
                return this.editor.prepend(block);
            }

            if(prepend){
                return $(element).parentsUntil('.editor,section').last().before(block); //last() fixes issues with inserting into lists
            } else {
                return $(element).parentsUntil('.editor,section').last().after(block);
            }
        },


        _insertHtml: function(html){
            this.editor.focus(); //fixes insert bug in IE
            if($.browser.msie){
               
                var range = document.selection.createRange();
                    range.collapse();

                if ($(range.parentElement()).parents('.editor').is('*')) {
                    try {
                        // Overwrite selection with provided html
                        range.pasteHTML(html);
                    } catch (e) { }
                } else {
                    this.editor.append(html);
                }
            //document.selection.createRange().collapse(); 

            //  
            //  document.createRange().pasteHTML(html);
            } else {
                window.getSelection().collapseToStart();
                document.execCommand("insertHtml", false, html)
            }
        },
    
        _getSelectionContainerElement: function() {
            var range, sel, container;
            if ($.browser.msie) {
                // IE case
                range = document.selection.createRange();
                range.collapse();
                return range.parentElement();
            //   return range.parentElement();
            } else if (window.getSelection) {
                sel = window.getSelection();
                sel.collapseToStart();
                if (sel.getRangeAt) {
                    if (sel.rangeCount > 0) {
                        range = sel.getRangeAt(0);
                    }
                } else {
                    // Old WebKit selection object has no getRangeAt, so
                    // create a range from other selection properties
                    range = document.createRange();
                    range.setStart(sel.anchorNode, sel.anchorOffset);
                    range.setEnd(sel.focusNode, sel.focusOffset);

                    // Handle the case when the selection was selected backwards (from the end to the start in the document)
                    if (range.collapsed !== sel.isCollapsed) {
                        range.setStart(sel.focusNode, sel.focusOffset);
                        range.setEnd(sel.anchorNode, sel.anchorOffset);
                    }
                }
                return range.commonAncestorContainer;
            }
        },
        
        _getDomTree: function(){
            var range = this. _getSelectionContainerElement();
            var tree = [];
            if (range) {
                

                // Check if the container is a text node and return its parent if so
                if(container.nodeType === 3){
                    container =  container.parentNode
                }
                while (container != this.editor.get(0)){
                    tree.push(container.tagName);
                    container =  container.parentNode;  
                } 
   
                var path = tree.pop();
                tree = tree.reverse()
                for(var i in tree){
                    path += ' > '+tree[i];
                }
                return path;
            } 
            return null;
        },
        _collapseAll: function(){
            
        },
        _expandAll: function(){
            
        },


        //        replaceSelection: function(text) {
        //
        //            if($.browser.msie){
        //                this.focus();
        //                document.selection.createRange().text = text;
        //                return this;
        //            } else {
        //                this.innerHTML = this.value.substr(0, this.selectionStart) + text + this.value.substr(this.selectionEnd, this.value.length);
        //                return this;
        //            }
        //            
        //            
        //            
        //        },
        
        
        // getRange - gets the current text range object
        //  function getRange(editor) {
        //    if (ie) return getSelection(editor).createRange();
        //    return getSelection(editor).getRangeAt(0);
        //  }
        //
        //  // getSelection - gets the current text range object
        //  function getSelection(editor) {
        //    if (ie) return editor.doc.selection;
        //    return editor.$frame[0].contentWindow.getSelection();
        //  }
        // selectedHTML - returns the current HTML selection or and empty string
        //  function selectedHTML(editor) {
        //    restoreRange(editor);
        //    var range = getRange(editor);
        //    if (ie)
        //      return range.htmlText;
        //    var layer = $("<layer>")[0];
        //    layer.appendChild(range.cloneContents());
        //    var html = layer.innerHTML;
        //    layer = null;
        //    return html;
        //  }
        //
        //  // selectedText - returns the current text selection or and empty string
        //  function selectedText(editor) {
        //    restoreRange(editor);
        //    if (ie) return getRange(editor).text;
        //    return getSelection(editor).toString();
        //  }

        
        

        destroy: function() {
            //this.dialog.dialog("destroy");
            $.Widget.prototype.destroy.call(this);
        }
    });
})(jQuery);