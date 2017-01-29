// FastClick: removes 300ms delay from tap on mobile devices
function FastClick(f,c){var g;c=c||{};this.trackingClick=false;this.trackingClickStart=0;this.targetElement=null;this.touchStartX=0;this.touchStartY=0;this.lastTouchIdentifier=0;this.touchBoundary=c.touchBoundary||10;this.layer=f;this.tapDelay=c.tapDelay||200;if(FastClick.notNeeded(f)){return}function h(j,i){return function(){return j.apply(i,arguments)}}var b=["onMouse","onClick","onTouchStart","onTouchMove","onTouchEnd","onTouchCancel"];var e=this;for(var d=0,a=b.length;d<a;d++){e[b[d]]=h(e[b[d]],e)}if(deviceIsAndroid){f.addEventListener("mouseover",this.onMouse,true);f.addEventListener("mousedown",this.onMouse,true);f.addEventListener("mouseup",this.onMouse,true)}f.addEventListener("click",this.onClick,true);f.addEventListener("touchstart",this.onTouchStart,false);f.addEventListener("touchmove",this.onTouchMove,false);f.addEventListener("touchend",this.onTouchEnd,false);f.addEventListener("touchcancel",this.onTouchCancel,false);if(!Event.prototype.stopImmediatePropagation){f.removeEventListener=function(j,l,i){var k=Node.prototype.removeEventListener;if(j==="click"){k.call(f,j,l.hijacked||l,i)}else{k.call(f,j,l,i)}};f.addEventListener=function(k,l,j){var i=Node.prototype.addEventListener;if(k==="click"){i.call(f,k,l.hijacked||(l.hijacked=function(m){if(!m.propagationStopped){l(m)}}),j)}else{i.call(f,k,l,j)}}}if(typeof f.onclick==="function"){g=f.onclick;f.addEventListener("click",function(i){g(i)},false);f.onclick=null}}var deviceIsAndroid=navigator.userAgent.indexOf("Android")>0;var deviceIsIOS=/iP(ad|hone|od)/.test(navigator.userAgent);var deviceIsIOS4=deviceIsIOS&&(/OS 4_\d(_\d)?/).test(navigator.userAgent);var deviceIsIOSWithBadTarget=deviceIsIOS&&(/OS ([6-9]|\d{2})_\d/).test(navigator.userAgent);var deviceIsBlackBerry10=navigator.userAgent.indexOf("BB10")>0;FastClick.prototype.needsClick=function(a){switch(a.nodeName.toLowerCase()){case"button":case"select":case"textarea":if(a.disabled){return true}break;case"input":if((deviceIsIOS&&a.type==="file")||a.disabled){return true}break;case"label":case"video":return true}return(/\bneedsclick\b/).test(a.className)};FastClick.prototype.needsFocus=function(a){switch(a.nodeName.toLowerCase()){case"textarea":return true;case"select":return !deviceIsAndroid;case"input":switch(a.type){case"button":case"checkbox":case"file":case"image":case"radio":case"submit":return false}return !a.disabled&&!a.readOnly;default:return(/\bneedsfocus\b/).test(a.className)}};FastClick.prototype.sendClick=function(b,c){var a,d;if(document.activeElement&&document.activeElement!==b){document.activeElement.blur()}d=c.changedTouches[0];a=document.createEvent("MouseEvents");a.initMouseEvent(this.determineEventType(b),true,true,window,1,d.screenX,d.screenY,d.clientX,d.clientY,false,false,false,false,0,null);a.forwardedTouchEvent=true;b.dispatchEvent(a)};FastClick.prototype.determineEventType=function(a){if(deviceIsAndroid&&a.tagName.toLowerCase()==="select"){return"mousedown"}return"click"};FastClick.prototype.focus=function(a){var b;if(deviceIsIOS&&a.setSelectionRange&&a.type.indexOf("date")!==0&&a.type!=="time"){b=a.value.length;a.setSelectionRange(b,b)}else{a.focus()}};FastClick.prototype.updateScrollParent=function(b){var c,a;c=b.fastClickScrollParent;if(!c||!c.contains(b)){a=b;do{if(a.scrollHeight>a.offsetHeight){c=a;b.fastClickScrollParent=a;break}a=a.parentElement}while(a)}if(c){c.fastClickLastScrollTop=c.scrollTop}};FastClick.prototype.getTargetElementFromEventTarget=function(a){if(a.nodeType===Node.TEXT_NODE){return a.parentNode}return a};FastClick.prototype.onTouchStart=function(c){var a,d,b;if(c.targetTouches.length>1){return true}a=this.getTargetElementFromEventTarget(c.target);d=c.targetTouches[0];if(deviceIsIOS){b=window.getSelection();if(b.rangeCount&&!b.isCollapsed){return true}if(!deviceIsIOS4){if(d.identifier&&d.identifier===this.lastTouchIdentifier){c.preventDefault();return false}this.lastTouchIdentifier=d.identifier;this.updateScrollParent(a)}}this.trackingClick=true;this.trackingClickStart=c.timeStamp;this.targetElement=a;this.touchStartX=d.pageX;this.touchStartY=d.pageY;if((c.timeStamp-this.lastClickTime)<this.tapDelay){c.preventDefault()}return true};FastClick.prototype.touchHasMoved=function(a){var c=a.changedTouches[0],b=this.touchBoundary;if(Math.abs(c.pageX-this.touchStartX)>b||Math.abs(c.pageY-this.touchStartY)>b){return true}return false};FastClick.prototype.onTouchMove=function(a){if(!this.trackingClick){return true}if(this.targetElement!==this.getTargetElementFromEventTarget(a.target)||this.touchHasMoved(a)){this.trackingClick=false;this.targetElement=null}return true};FastClick.prototype.findControl=function(a){if(a.control!==undefined){return a.control}if(a.htmlFor){return document.getElementById(a.htmlFor)}return a.querySelector("button, input:not([type=hidden]), keygen, meter, output, progress, select, textarea")};FastClick.prototype.onTouchEnd=function(c){var e,d,b,g,f,a=this.targetElement;if(!this.trackingClick){return true}if((c.timeStamp-this.lastClickTime)<this.tapDelay){this.cancelNextClick=true;return true}this.cancelNextClick=false;this.lastClickTime=c.timeStamp;d=this.trackingClickStart;this.trackingClick=false;this.trackingClickStart=0;if(deviceIsIOSWithBadTarget){f=c.changedTouches[0];a=document.elementFromPoint(f.pageX-window.pageXOffset,f.pageY-window.pageYOffset)||a;a.fastClickScrollParent=this.targetElement.fastClickScrollParent}b=a.tagName.toLowerCase();if(b==="label"){e=this.findControl(a);if(e){this.focus(a);if(deviceIsAndroid){return false}a=e}}else{if(this.needsFocus(a)){if((c.timeStamp-d)>100||(deviceIsIOS&&window.top!==window&&b==="input")){this.targetElement=null;return false}this.focus(a);this.sendClick(a,c);if(!deviceIsIOS||b!=="select"){this.targetElement=null;c.preventDefault()}return false}}if(deviceIsIOS&&!deviceIsIOS4){g=a.fastClickScrollParent;if(g&&g.fastClickLastScrollTop!==g.scrollTop){return true}}if(!this.needsClick(a)){c.preventDefault();this.sendClick(a,c)}return false};FastClick.prototype.onTouchCancel=function(){this.trackingClick=false;this.targetElement=null};FastClick.prototype.onMouse=function(a){if(!this.targetElement){return true}if(a.forwardedTouchEvent){return true}if(!a.cancelable){return true}if(!this.needsClick(this.targetElement)||this.cancelNextClick){if(a.stopImmediatePropagation){a.stopImmediatePropagation()}else{a.propagationStopped=true}a.stopPropagation();a.preventDefault();return false}return true};FastClick.prototype.onClick=function(a){var b;if(this.trackingClick){this.targetElement=null;this.trackingClick=false;return true}if(a.target.type==="submit"&&a.detail===0){return true}b=this.onMouse(a);if(!b){this.targetElement=null}return b};FastClick.prototype.destroy=function(){var a=this.layer;if(deviceIsAndroid){a.removeEventListener("mouseover",this.onMouse,true);a.removeEventListener("mousedown",this.onMouse,true);a.removeEventListener("mouseup",this.onMouse,true)}a.removeEventListener("click",this.onClick,true);a.removeEventListener("touchstart",this.onTouchStart,false);a.removeEventListener("touchmove",this.onTouchMove,false);a.removeEventListener("touchend",this.onTouchEnd,false);a.removeEventListener("touchcancel",this.onTouchCancel,false)};FastClick.notNeeded=function(b){var a;var d;var c;if(typeof window.ontouchstart==="undefined"){return true}d=+(/Chrome\/([0-9]+)/.exec(navigator.userAgent)||[,0])[1];if(d){if(deviceIsAndroid){a=document.querySelector("meta[name=viewport]");if(a){if(a.content.indexOf("user-scalable=no")!==-1){return true}if(d>31&&document.documentElement.scrollWidth<=window.outerWidth){return true}}}else{return true}}if(deviceIsBlackBerry10){c=navigator.userAgent.match(/Version\/([0-9]*)\.([0-9]*)/);if(c[1]>=10&&c[2]>=3){a=document.querySelector("meta[name=viewport]");if(a){if(a.content.indexOf("user-scalable=no")!==-1){return true}if(document.documentElement.scrollWidth<=window.outerWidth){return true}}}}if(b.style.msTouchAction==="none"){return true}return false};FastClick.attach=function(b,a){return new FastClick(b,a)};if(typeof define=="function"&&typeof define.amd=="object"&&define.amd){define(function(){return FastClick})}else{if(typeof module!=="undefined"&&module.exports){module.exports=FastClick.attach;module.exports.FastClick=FastClick}else{window.FastClick=FastClick}};
window.addEventListener('load', function(){ FastClick.attach(document.body); }, false);
// mqGenie: adjusts media queries to stop adding the scrollbar width
;(function(e,t){function n(e,t){var n=e.cssRules?e.cssRules:e.media,r,i=[],s=0,o=n.length;for(s;s<o;s++){r=n[s];if(t(r))i.push(r)}return i}function r(e){return n(e,function(e){return e.constructor===CSSMediaRule})}function i(n){var r=e.location,i=t.createElement("a");i.href=n;return i.hostname===r.hostname&&i.protocol===r.protocol}function s(e){return e.ownerNode.constructor===HTMLStyleElement}function o(e){return e.href&&i(e.href)}function u(){var e=t.styleSheets,n,r=e.length,i=0,u=[];for(i;i<r;i++){n=e[i];if(o(n)||s(n))u.push(n)}return u}if(!t.addEventListener)return;t.addEventListener("DOMContentLoaded",function(){e.mqGenie=function(){var n=t.documentElement;n.style.overflowY="scroll";var i=e.innerWidth-n.clientWidth,s={adjusted:i>0,fontSize:parseFloat(e.getComputedStyle(n).getPropertyValue("font-size")),width:i};if(s.adjusted){if("WebkitAppearance"in n.style){var o=/Chrome\/(\d*?\.\d*?\.\d*?\.\d*?)\s/g,a=navigator.userAgent.match(o),f;if(a){a=a[0].replace(o,"$1");f=a.split(".");f[0]=parseInt(f[0]);f[2]=parseInt(f[2]);f[3]=parseInt(f[3]);if(f[0]<=29){if(f[0]===29&&f[2]<1548&&f[3]<57){s.adjusted=false}else if(f[0]<29){s.adjusted=false}}}else{s.adjusted=false}if(!s.adjusted)return s}var l=u(),c=l.length,h=0,p,d;for(h;h<c;h++){p=r(l[h]);d=p.length;for(var v=0;v<d;v++){p[v].media.mediaText=p[v].media.mediaText.replace(/m(in|ax)-width:\s*(\d|\.)+(px|em)/gi,function(e){if(e.match("px")){return e.replace(/\d+px/gi,function(e){return parseInt(e,10)+s.width+"px"})}else{return e.replace(/\d.+?em/gi,function(e){return(parseFloat(e)*s.fontSize+s.width)/s.fontSize+"em"})}})}}}return s}();e.mqAdjust=function(e){if(!mqGenie.adjusted)return e;var t=e.replace(/\d+px/gi,function(e){return parseInt(e,10)+mqGenie.width+"px"});t=t.replace(/\d.+?em/gi,function(e){return(parseFloat(e)*mqGenie.fontSize+mqGenie.width)/mqGenie.fontSize+"em"});return t}})})(window,document);
      
(function($)
{
   var $window, $body, $show_menu, $menu, $pagewrap, $scroller, $spotlight, $spotlight_img, dragX, dragY;
   var supportsTouch = 'ontouchstart' in window || navigator.msMaxTouchPoints;
   
   /*
      Menu
   */
   
   $(function()
   {
      $window = $(window);
      $body = $('body');
      $show_menu = $('#show-menu');
      $menu = $('#menu');
      menu_height();
      $window.load(function(){ menu_height(); });
      $window.resize(function(){ menu_height(); });
      $show_menu.change(function(){ menu_scroll(); });
      menu_scroll();
   });

   // In phone widths, make nav menu exact height to window
   function menu_height()
   {
      if ($window.width() <= 800){ $('#menu').height(parseInt($window.height()) - 48); }
      else { $('#menu').css('height', 'auto'); }
   }
   
   // Prevent scrolls when menu is opened
   function menu_scroll()
   {
      if ($show_menu.prop('checked') == true)
      {
         var start;
         $body.on('touchmove mousewheel DOMMouseScroll', function(e){ e.preventDefault(); });
         if (!supportsTouch)
         {
            $menu.on('mousewheel DOMMouseScroll', function(e)
            { 
               e.preventDefault();
               var e0 = e.originalEvent;
               var delta = e0.wheelDelta || -e0.detail;
               $menu[0].scrollTop += ( delta < 0 ? 1 : -1 ) * 30;
            });
         }
         else
         {
            $menu.on('touchstart', function(e){ menu_drag(e); });
            $menu.on('touchend', function(e){ menu_drop(e); });
         }
      }
      else
      { 
         $menu.unbind('touchstart touchmove mousewheel DOMMouseScroll');
         $body.unbind('touchmove mousewheel DOMMouseScroll');
      }
   }
   
   function menu_drag(e)
   {
      dragY = e.pageY;
      if (!dragY) { dragY = e.originalEvent.touches[0].pageY; }
      $menu.on('touchmove', function(e)
      {
         var moveY;
         moveY = e.pageY;
         if (!moveY) { moveY = e.originalEvent.touches[0].pageY; }
         var delta = moveY - dragY;
         $menu[0].scrollTop -= delta;
         dragY = moveY;
      });
   }
   
   function menu_drop(e)
   {
      $menu.unbind('touchmove');
   }
      
   /*
      Images and Spotlight
   */
   
   $(function()
   {
      // Bind elements
      $pagewrap = $('#pagewrap');
      $scroller = $('#scroller');
      $spotlight = $('#spotlight');
      $spotlight_img = $('#spotlight-img');
      // Spotlight
      $window.on('load', function(){ page_preload(1); });
      $pagewrap.on('click', '.grid img', function(){ show_spotlight(this); });
      $spotlight.on('click', function(){ hide_spotlight(); });
      $spotlight_img.on('click', function(){ return false; })
      if (!supportsTouch)
      {
         $spotlight_img.on('mousedown', function(e){ spotlight_drag(e); });
         $spotlight.on('mouseup', function(e){ spotlight_drop(e); });
      }
      else
      {
         $spotlight_img.on('touchstart', function(e){ spotlight_drag(e); });
         $spotlight.on('touchend', function(e){ spotlight_drop(e); });
      }
      // Scroller
      $window.on('scroll', function(){ if ($body.children('.browser').length == 0) { get_next_page(); } });
      $window.trigger('scroll');
   });
   
   // Preloading of large imgs by page
   function page_preload(page)
   {
      $page_img = $('#page-' + page).find('img');
      img_html = '';
      for (x = 0; x < $page_img.length; x++)
      {
         if ($page_img[x].getAttribute('large')) { img_html += '<img src="' + $page_img[x].getAttribute('large') + '"/>'; }
      }
      $('#preloader').append(img_html);
   }
   
   // Show spotlight
   function show_spotlight(img)
   { 
      $spotlight_img[0].setAttribute('thumb', img.getAttribute('thumb'));
      $spotlight_img[0].setAttribute('src', img.getAttribute('large'));
      $spotlight.addClass('jump hide');
      $spotlight[0].offsetHeight; // Trigger a reflow
      $spotlight.removeClass('jump hide').addClass('down');
      $body.on('touchmove mousewheel DOMMouseScroll', function(e){ e.preventDefault(); });
   }
   
   // Hide spotlight
   function hide_spotlight()
   {
      $spotlight.addClass('hide').bind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd", function()
      {
         $spotlight.removeClass('down').unbind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd");
         $body.unbind('touchmove mousewheel DOMMouseScroll');
      });
   }
   
   // Drag spotlight image
   function spotlight_drag(e)
   {
      e.preventDefault(); // disable selection
      dragX = e.pageX;
      if (!dragX) { dragX = e.originalEvent.touches[0].pageX; }
      $spotlight.on('mousemove touchmove', function(e)
      {
         var moveX;
         moveX = e.pageX;
         if (!moveX) { moveX = e.originalEvent.touches[0].pageX; }
         var moved = moveX - dragX;
         $spotlight_img.removeClass();
         $spotlight_img.css({ '-webkit-transform': 'translate3d(' + moved + 'px, 0, 0)',
                              '-moz-transform': 'translate3d(' + moved + 'px, 0, 0)',
                              '-ms-transform': 'translate3d(' + moved + 'px, 0, 0)',
                              '-o-transform': 'translate3d(' + moved + 'px, 0, 0)',
                              'transform': 'translate3d(' + moved + 'px, 0, 0)' });
      });
   }
   function spotlight_drop(e)
   {
      $spotlight.unbind('mousemove touchmove');
      var dropX;
      if (dragX)
      {
         dropX = e.pageX;
         if (!dropX) { dropX = e.originalEvent.changedTouches[0].pageX; }
      }
      var moved = dropX - dragX;
      dragX = 0;
      if (moved > ($spotlight.width() * 0.15)) { spotlight_prev(); }
      else if (-moved > ($spotlight.width() * 0.15)) { spotlight_next(); }
      else
      {
         $spotlight_img.removeClass().addClass('easeout');
         $spotlight_img.css({ '-webkit-transform': 'translate3d(0, 0, 0)',
                              '-moz-transform': 'translate3d(0, 0, 0)',
                              '-ms-transform': 'translate3d(0, 0, 0)',
                              '-o-transform': 'translate3d(0, 0, 0)',
                              'transform': 'translate3d(0, 0, 0)' });
      }
   }
   
   // Slide image right (previous)
   function spotlight_prev()
   {
      // Old image slide out
      var width = ((parseInt($spotlight.width()) - parseInt($spotlight_img.width())) / 2) + parseInt($spotlight_img.width());
      $spotlight_img.removeClass().addClass('linear');
      $spotlight_img.css({ '-webkit-transform': 'translate3d(' + width + 'px, 0, 0)',
                           '-moz-transform': 'translate3d(' + width + 'px, 0, 0)',
                           '-ms-transform': 'translate3d(' + width + 'px, 0, 0)',
                           '-o-transform': 'translate3d(' + width + 'px, 0, 0)',
                           'transform': 'translate3d(' + width + 'px, 0, 0)' });
      // Search for new img
      var thumb = parseInt($spotlight_img.attr('thumb'));
      var $prev_img = $('#thumb-' + (thumb - 1));
      if (!$prev_img.length) { $prev_img = $pagewrap.find('img').last(); }
      // New img slide in
      $spotlight_img.bind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd", function()
      {
         $spotlight_img[0].setAttribute('thumb', $prev_img[0].getAttribute('thumb'));
         $spotlight_img[0].setAttribute('src', $prev_img[0].getAttribute('large'));
         $spotlight_img.removeClass().addClass('jump');
         $spotlight_img.css({ '-webkit-transform': 'translate3d(-' + width + 'px, 0, 0)',
                              '-moz-transform': 'translate3d(-' + width + 'px, 0, 0)',
                              '-ms-transform': 'translate3d(-' + width + 'px, 0, 0)',
                              '-o-transform': 'translate3d(-' + width + 'px, 0, 0)',
                              'transform': 'translate3d(-' + width + 'px, 0, 0)' });
         $spotlight_img[0].offsetHeight; // Trigger a reflow
         $spotlight_img.removeClass().addClass('easeout');
         $spotlight_img.css({ '-webkit-transform': 'translate3d(0, 0, 0)',
                              '-moz-transform': 'translate3d(0, 0, 0)',
                              '-ms-transform': 'translate3d(0, 0, 0)',
                              '-o-transform': 'translate3d(0, 0, 0)',
                              'transform': 'translate3d(0, 0, 0)' });
         $spotlight_img.unbind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd");
      });
   }
   
   // Slide image left (next)
   function spotlight_next()
   {
      // Old image slide out
      var width = ((parseInt($spotlight.width()) - parseInt($spotlight_img.width())) / 2) + parseInt($spotlight_img.width());
      $spotlight_img.removeClass().addClass('linear');
      $spotlight_img.css({ '-webkit-transform': 'translate3d(-' + width + 'px, 0, 0)',
                           '-moz-transform': 'translate3d(-' + width + 'px, 0, 0)',
                           '-ms-transform': 'translate3d(-' + width + 'px, 0, 0)',
                           '-o-transform': 'translate3d(-' + width + 'px, 0, 0)',
                           'transform': 'translate3d(-' + width + 'px, 0, 0)' });
      // Search for new img
      var thumb = parseInt($spotlight_img.attr('thumb'));
      var $next_img = $('#thumb-' + (thumb + 1));
      if (!$next_img.length) { $next_img = $pagewrap.find('.grid img').first(); }
      // New image slide in
      $spotlight_img.bind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd", function()
      {
         $spotlight_img[0].setAttribute('thumb', $next_img[0].getAttribute('thumb'));
         $spotlight_img[0].setAttribute('src', $next_img[0].getAttribute('large'));
         $spotlight_img.removeClass().addClass('jump');
         $spotlight_img.css({ '-webkit-transform': 'translate3d(' + width + 'px, 0, 0)',
                              '-moz-transform': 'translate3d(' + width + 'px, 0, 0)',
                              '-ms-transform': 'translate3d(' + width + 'px, 0, 0)',
                              '-o-transform': 'translate3d(' + width + 'px, 0, 0)',
                              'transform': 'translate3d(' + width + 'px, 0, 0)' });
         $spotlight_img[0].offsetHeight; // Trigger a reflow
         $spotlight_img.removeClass().addClass('easeout');
         $(this).css({ '-webkit-transform': 'translate3d(0, 0, 0)',
                       '-moz-transform': 'translate3d(0, 0, 0)',
                       '-ms-transform': 'translate3d(0, 0, 0)',
                       '-o-transform': 'translate3d(0, 0, 0)',
                       'transform': 'translate3d(0, 0, 0)' });
         $spotlight_img.unbind("transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd");
      });
   }
   
   // Get next page
   function get_next_page()
   {
      var $last_page;
      var $last_post;
      var $last_img;
      if (($scroller.offset().top < $window.scrollTop() + $window.height()) && !$scroller.hasClass('disabled'))
      {
         // Search for offset params
         $last_page = $pagewrap.children().last();
         $last_post = $last_page.children().last();
         $last_img = $last_post.find('img.thumb').last();
         if (!$last_img.length)
         {
            var $prev_post = $last_post.prev();
            while (!$last_img.length)
            {
               if (!$prev_post.length) { break; }
               $last_img = $prev_post.find('img.thumb').last();
               $prev_post = $prev_post.prev();
            }
         }
         // Execute page request by ajax
         $scroller.addClass('disabled');
         var data;
         if ($last_img.length) data =
         {
            action: 'next_page',
            clase: $pagewrap[0].getAttribute('clase'), 
            page: $last_page[0].getAttribute('page'), 
            offset: $last_post[0].getAttribute('offset'), 
            thumb: $last_img[0].getAttribute('thumb')
         };
         console.log(data);
         $.post(ajaxurl, data, function(response)
         {
            if (response != 0)
            {
               // Draw new page
               $pagewrap.append(response);
               page_preload($pagewrap.children().last()[0].getAttribute('page'));
               $scroller.removeClass('disabled');
            }
            else
            { 
               $scroller.children('div').html('No more journals!');
            }
         });
      }
   }
   
   /*
      Content Editor
   */
   
   $(function()
   {
      $pagewrap.on('click', '#addnew', function(){ show_newpost(); });
      $pagewrap.on('click', '#newpost', function(e){ hide_newpost(e); });
      $pagewrap.on('click', '.edit-button', function(){ show_editor(this); });
      $pagewrap.on('click', 'input[name=delete]', function(){ this.setAttribute('clicked', 1); });
      $pagewrap.on('submit', 'form.edit-content', function(){ insert_post(this); return false; });
   });
   
   // Show new post editor
   function show_newpost()
   {
      $('#addnew').hide(0).next().show(0);
      $('#newpost').find('textarea').focus();
   }
   
   // Close new post editor
   function hide_newpost(e)
   {
      var container = $("#addnew-form");
      if ((!container.is(e.target) && container.has(e.target).length === 0) || $(e.target).attr('class') == 'close')
      {
         $('#newpost').hide(0);
         $('#addnew').show(0);
      }
   }
   
   // Switch from post content to editor
   function show_editor(el)
   {
      var $button = $(el);
      var $form = $button.parent().parent().next().next();
      var $textarea = $form.find('textarea');
      $textarea.height($form.prev().outerHeight());
      $button.parent().hide(0);
      $form.show(0).prev().hide(0);
      $textarea.focus();
      moveCursorToEnd($textarea[0]);
   }
   function moveCursorToEnd(el) 
   {
      if (typeof el.selectionStart == "number")
      {
         el.selectionStart = el.selectionEnd = el.value.length;
      }
      else if (typeof el.createTextRange != "undefined")
      {
         el.focus();
         var range = el.createTextRange();
         range.collapse(false);
         range.select();
      }
   }
   
   // Submit edited content
   function insert_post(el)
   {
      var $form = $(el);
      // Confirm for deletion
      var $delete = $form.find('input[name=delete]');
      if ($delete[0].getAttribute('clicked') == 1)
      {
         var conf = confirm('Are you sure you want to delete this post?');
         if (!conf)
         {
            $delete[0].setAttribute('clicked', 0);
            return false;
         }
      }
      // Post changes
      var content = $form.find('textarea').val();
      $form.find('input[type=submit]').hide(0);
      $form.next().show(0);
      var data =
      {
         action: 'insert_post', 
         del: $form.find('input[name=delete]').attr('clicked'),
         content: content, 
         title: $form.find('input[name=title]').val(), 
         clase: $form.find('select[name=clase]').val(), 
         id: $form.find('input[name=id]').val()
      };
      $.post(ajaxurl, data, function(response)
      {
         if (response == 'DELETED')
         {
            $form.parent().remove();
         }
         else if (response != 0)
         {
            if ($form[0].getAttribute('id') == 'addnew-form') { window.location.href = document.URL; return false; }
            $form.prev().html(content);
            $form.hide(0).find('input[type=submit]').show(0);
            $form.prev().show(0);
            $form.parent().find('h1 .right-buttons').show(0);
            $form.next().hide(0);
         }
         else
         { 
            alert('There was an error submitting the post.\n\nPlease contact the network administrator.');
            if ($form[0].getAttribute('id') == 'addnew-form') { window.location.href = document.URL; return false; }
            $form.hide(0).find('input[type=submit]').show(0);
            $form.prev().show(0);
            $form.parent().find('h1 .right-buttons').show(0);
            $form.next().hide(0);
         }
      });
   }
   
   /*
      Photo Upload
   */
   
   $(function()
   {
      $pagewrap.on('click', '.upload', function(){ open_file_frame(this); });
   });
   
   function open_file_frame(el)
   {
      var post_id = el.getAttribute('grid');
      var $grid = $('#grid-' + post_id);
      var $loading = $grid.find('div');
      // Create and open file frame with correct post id attached
      wp.media.model.settings.post.id = post_id;
      var file_frame = wp.media.frames.file_frame = wp.media(
      {
         title: 'Drag Photos to Insert',
         button: { text: 'Close' },
         multiple: false,
         state: 'library',
         library: { type: 'image', uploadedTo: wp.media.model.settings.post.id }
      });
      file_frame.open();
      // On closing frame refresh grid
      file_frame.on('close', function()
      {
         $body.children('.browser').remove(); 
         $body.children('.supports-drag-drop').remove();
         $loading.show(0);
         // Fetch new grid
         var data =
         {
            action: 'fetch_grid', 
            postid: post_id
         };
         $.post(ajaxurl, data, function(response)
         {
            if (response != 0)
            {
               $grid.html(response);
               var $thumbs = $pagewrap.find('.grid img');
               for (x = 0; x < $thumbs.length; x++)
               {
                  $thumbs[x].setAttribute('thumb', x + 1);
                  $thumbs[x].setAttribute('id', 'thumb-' + (x + 1));
               }
               $loading.hide(0);
            }
            else
            { 
               alert('There was an error submitting the post.\n\nPlease contact the network administrator.');
            }
         });
      });
   }
   
})(jQuery);