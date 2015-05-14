/*! MrUploader - v0.1.0 - 2015-03-18
* http://github.com/vinelab/mr-uploader
* Copyright (c) 2015 Vinelab; Licensed MIT */
(function(a){a.Jcrop=function(b,c){function i(a){return Math.round(a)+"px"}function j(a){return d.baseClass+"-"+a}function k(){return a.fx.step.hasOwnProperty("backgroundColor")}function l(b){var c=a(b).offset();return[c.left,c.top]}function m(a){return[a.pageX-e[0],a.pageY-e[1]]}function n(b){typeof b!="object"&&(b={}),d=a.extend(d,b),a.each(["onChange","onSelect","onRelease","onDblClick"],function(a,b){typeof d[b]!="function"&&(d[b]=function(){})})}function o(a,b,c){e=l(D),bc.setCursor(a==="move"?a:a+"-resize");if(a==="move")return bc.activateHandlers(q(b),v,c);var d=_.getFixed(),f=r(a),g=_.getCorner(r(f));_.setPressed(_.getCorner(f)),_.setCurrent(g),bc.activateHandlers(p(a,d),v,c)}function p(a,b){return function(c){if(!d.aspectRatio)switch(a){case"e":c[1]=b.y2;break;case"w":c[1]=b.y2;break;case"n":c[0]=b.x2;break;case"s":c[0]=b.x2}else switch(a){case"e":c[1]=b.y+1;break;case"w":c[1]=b.y+1;break;case"n":c[0]=b.x+1;break;case"s":c[0]=b.x+1}_.setCurrent(c),bb.update()}}function q(a){var b=a;return bd.watchKeys
(),function(a){_.moveOffset([a[0]-b[0],a[1]-b[1]]),b=a,bb.update()}}function r(a){switch(a){case"n":return"sw";case"s":return"nw";case"e":return"nw";case"w":return"ne";case"ne":return"sw";case"nw":return"se";case"se":return"nw";case"sw":return"ne"}}function s(a){return function(b){return d.disabled?!1:a==="move"&&!d.allowMove?!1:(e=l(D),W=!0,o(a,m(b)),b.stopPropagation(),b.preventDefault(),!1)}}function t(a,b,c){var d=a.width(),e=a.height();d>b&&b>0&&(d=b,e=b/a.width()*a.height()),e>c&&c>0&&(e=c,d=c/a.height()*a.width()),T=a.width()/d,U=a.height()/e,a.width(d).height(e)}function u(a){return{x:a.x*T,y:a.y*U,x2:a.x2*T,y2:a.y2*U,w:a.w*T,h:a.h*U}}function v(a){var b=_.getFixed();b.w>d.minSelect[0]&&b.h>d.minSelect[1]?(bb.enableHandles(),bb.done()):bb.release(),bc.setCursor(d.allowSelect?"crosshair":"default")}function w(a){if(d.disabled)return!1;if(!d.allowSelect)return!1;W=!0,e=l(D),bb.disableHandles(),bc.setCursor("crosshair");var b=m(a);return _.setPressed(b),bb.update(),bc.activateHandlers(x,v,a.type.substring
(0,5)==="touch"),bd.watchKeys(),a.stopPropagation(),a.preventDefault(),!1}function x(a){_.setCurrent(a),bb.update()}function y(){var b=a("<div></div>").addClass(j("tracker"));return g&&b.css({opacity:0,backgroundColor:"white"}),b}function be(a){G.removeClass().addClass(j("holder")).addClass(a)}function bf(a,b){function t(){window.setTimeout(u,l)}var c=a[0]/T,e=a[1]/U,f=a[2]/T,g=a[3]/U;if(X)return;var h=_.flipCoords(c,e,f,g),i=_.getFixed(),j=[i.x,i.y,i.x2,i.y2],k=j,l=d.animationDelay,m=h[0]-j[0],n=h[1]-j[1],o=h[2]-j[2],p=h[3]-j[3],q=0,r=d.swingSpeed;c=k[0],e=k[1],f=k[2],g=k[3],bb.animMode(!0);var s,u=function(){return function(){q+=(100-q)/r,k[0]=Math.round(c+q/100*m),k[1]=Math.round(e+q/100*n),k[2]=Math.round(f+q/100*o),k[3]=Math.round(g+q/100*p),q>=99.8&&(q=100),q<100?(bh(k),t()):(bb.done(),bb.animMode(!1),typeof b=="function"&&b.call(bs))}}();t()}function bg(a){bh([a[0]/T,a[1]/U,a[2]/T,a[3]/U]),d.onSelect.call(bs,u(_.getFixed())),bb.enableHandles()}function bh(a){_.setPressed([a[0],a[1]]),_.setCurrent([a[2],
a[3]]),bb.update()}function bi(){return u(_.getFixed())}function bj(){return _.getFixed()}function bk(a){n(a),br()}function bl(){d.disabled=!0,bb.disableHandles(),bb.setCursor("default"),bc.setCursor("default")}function bm(){d.disabled=!1,br()}function bn(){bb.done(),bc.activateHandlers(null,null)}function bo(){G.remove(),A.show(),A.css("visibility","visible"),a(b).removeData("Jcrop")}function bp(a,b){bb.release(),bl();var c=new Image;c.onload=function(){var e=c.width,f=c.height,g=d.boxWidth,h=d.boxHeight;D.width(e).height(f),D.attr("src",a),H.attr("src",a),t(D,g,h),E=D.width(),F=D.height(),H.width(E).height(F),M.width(E+L*2).height(F+L*2),G.width(E).height(F),ba.resize(E,F),bm(),typeof b=="function"&&b.call(bs)},c.src=a}function bq(a,b,c){var e=b||d.bgColor;d.bgFade&&k()&&d.fadeTime&&!c?a.animate({backgroundColor:e},{queue:!1,duration:d.fadeTime}):a.css("backgroundColor",e)}function br(a){d.allowResize?a?bb.enableOnly():bb.enableHandles():bb.disableHandles(),bc.setCursor(d.allowSelect?"crosshair":"default"),bb
.setCursor(d.allowMove?"move":"default"),d.hasOwnProperty("trueSize")&&(T=d.trueSize[0]/E,U=d.trueSize[1]/F),d.hasOwnProperty("setSelect")&&(bg(d.setSelect),bb.done(),delete d.setSelect),ba.refresh(),d.bgColor!=N&&(bq(d.shade?ba.getShades():G,d.shade?d.shadeColor||d.bgColor:d.bgColor),N=d.bgColor),O!=d.bgOpacity&&(O=d.bgOpacity,d.shade?ba.refresh():bb.setBgOpacity(O)),P=d.maxSize[0]||0,Q=d.maxSize[1]||0,R=d.minSize[0]||0,S=d.minSize[1]||0,d.hasOwnProperty("outerImage")&&(D.attr("src",d.outerImage),delete d.outerImage),bb.refresh()}var d=a.extend({},a.Jcrop.defaults),e,f=navigator.userAgent.toLowerCase(),g=/msie/.test(f),h=/msie [1-6]\./.test(f);typeof b!="object"&&(b=a(b)[0]),typeof c!="object"&&(c={}),n(c);var z={border:"none",visibility:"visible",margin:0,padding:0,position:"absolute",top:0,left:0},A=a(b),B=!0;if(b.tagName=="IMG"){if(A[0].width!=0&&A[0].height!=0)A.width(A[0].width),A.height(A[0].height);else{var C=new Image;C.src=A[0].src,A.width(C.width),A.height(C.height)}var D=A.clone().removeAttr("id").
css(z).show();D.width(A.width()),D.height(A.height()),A.after(D).hide()}else D=A.css(z).show(),B=!1,d.shade===null&&(d.shade=!0);t(D,d.boxWidth,d.boxHeight);var E=D.width(),F=D.height(),G=a("<div />").width(E).height(F).addClass(j("holder")).css({position:"relative",backgroundColor:d.bgColor}).insertAfter(A).append(D);d.addClass&&G.addClass(d.addClass);var H=a("<div />"),I=a("<div />").width("100%").height("100%").css({zIndex:310,position:"absolute",overflow:"hidden"}),J=a("<div />").width("100%").height("100%").css("zIndex",320),K=a("<div />").css({position:"absolute",zIndex:600}).dblclick(function(){var a=_.getFixed();d.onDblClick.call(bs,a)}).insertBefore(D).append(I,J);B&&(H=a("<img />").attr("src",D.attr("src")).css(z).width(E).height(F),I.append(H)),h&&K.css({overflowY:"hidden"});var L=d.boundary,M=y().width(E+L*2).height(F+L*2).css({position:"absolute",top:i(-L),left:i(-L),zIndex:290}).mousedown(w),N=d.bgColor,O=d.bgOpacity,P,Q,R,S,T,U,V=!0,W,X,Y;e=l(D);var Z=function(){function a(){var a={},b=["touchstart"
,"touchmove","touchend"],c=document.createElement("div"),d;try{for(d=0;d<b.length;d++){var e=b[d];e="on"+e;var f=e in c;f||(c.setAttribute(e,"return;"),f=typeof c[e]=="function"),a[b[d]]=f}return a.touchstart&&a.touchend&&a.touchmove}catch(g){return!1}}function b(){return d.touchSupport===!0||d.touchSupport===!1?d.touchSupport:a()}return{createDragger:function(a){return function(b){return d.disabled?!1:a==="move"&&!d.allowMove?!1:(e=l(D),W=!0,o(a,m(Z.cfilter(b)),!0),b.stopPropagation(),b.preventDefault(),!1)}},newSelection:function(a){return w(Z.cfilter(a))},cfilter:function(a){return a.pageX=a.originalEvent.changedTouches[0].pageX,a.pageY=a.originalEvent.changedTouches[0].pageY,a},isSupported:a,support:b()}}(),_=function(){function h(d){d=n(d),c=a=d[0],e=b=d[1]}function i(a){a=n(a),f=a[0]-c,g=a[1]-e,c=a[0],e=a[1]}function j(){return[f,g]}function k(d){var f=d[0],g=d[1];0>a+f&&(f-=f+a),0>b+g&&(g-=g+b),F<e+g&&(g+=F-(e+g)),E<c+f&&(f+=E-(c+f)),a+=f,c+=f,b+=g,e+=g}function l(a){var b=m();switch(a){case"ne":return[
b.x2,b.y];case"nw":return[b.x,b.y];case"se":return[b.x2,b.y2];case"sw":return[b.x,b.y2]}}function m(){if(!d.aspectRatio)return p();var f=d.aspectRatio,g=d.minSize[0]/T,h=d.maxSize[0]/T,i=d.maxSize[1]/U,j=c-a,k=e-b,l=Math.abs(j),m=Math.abs(k),n=l/m,r,s,t,u;return h===0&&(h=E*10),i===0&&(i=F*10),n<f?(s=e,t=m*f,r=j<0?a-t:t+a,r<0?(r=0,u=Math.abs((r-a)/f),s=k<0?b-u:u+b):r>E&&(r=E,u=Math.abs((r-a)/f),s=k<0?b-u:u+b)):(r=c,u=l/f,s=k<0?b-u:b+u,s<0?(s=0,t=Math.abs((s-b)*f),r=j<0?a-t:t+a):s>F&&(s=F,t=Math.abs(s-b)*f,r=j<0?a-t:t+a)),r>a?(r-a<g?r=a+g:r-a>h&&(r=a+h),s>b?s=b+(r-a)/f:s=b-(r-a)/f):r<a&&(a-r<g?r=a-g:a-r>h&&(r=a-h),s>b?s=b+(a-r)/f:s=b-(a-r)/f),r<0?(a-=r,r=0):r>E&&(a-=r-E,r=E),s<0?(b-=s,s=0):s>F&&(b-=s-F,s=F),q(o(a,b,r,s))}function n(a){return a[0]<0&&(a[0]=0),a[1]<0&&(a[1]=0),a[0]>E&&(a[0]=E),a[1]>F&&(a[1]=F),[Math.round(a[0]),Math.round(a[1])]}function o(a,b,c,d){var e=a,f=c,g=b,h=d;return c<a&&(e=c,f=a),d<b&&(g=d,h=b),[e,g,f,h]}function p(){var d=c-a,f=e-b,g;return P&&Math.abs(d)>P&&(c=d>0?a+P:a-P),Q&&Math.abs
(f)>Q&&(e=f>0?b+Q:b-Q),S/U&&Math.abs(f)<S/U&&(e=f>0?b+S/U:b-S/U),R/T&&Math.abs(d)<R/T&&(c=d>0?a+R/T:a-R/T),a<0&&(c-=a,a-=a),b<0&&(e-=b,b-=b),c<0&&(a-=c,c-=c),e<0&&(b-=e,e-=e),c>E&&(g=c-E,a-=g,c-=g),e>F&&(g=e-F,b-=g,e-=g),a>E&&(g=a-F,e-=g,b-=g),b>F&&(g=b-F,e-=g,b-=g),q(o(a,b,c,e))}function q(a){return{x:a[0],y:a[1],x2:a[2],y2:a[3],w:a[2]-a[0],h:a[3]-a[1]}}var a=0,b=0,c=0,e=0,f,g;return{flipCoords:o,setPressed:h,setCurrent:i,getOffset:j,moveOffset:k,getCorner:l,getFixed:m}}(),ba=function(){function f(a,b){e.left.css({height:i(b)}),e.right.css({height:i(b)})}function g(){return h(_.getFixed())}function h(a){e.top.css({left:i(a.x),width:i(a.w),height:i(a.y)}),e.bottom.css({top:i(a.y2),left:i(a.x),width:i(a.w),height:i(F-a.y2)}),e.right.css({left:i(a.x2),width:i(E-a.x2)}),e.left.css({width:i(a.x)})}function j(){return a("<div />").css({position:"absolute",backgroundColor:d.shadeColor||d.bgColor}).appendTo(c)}function k(){b||(b=!0,c.insertBefore(D),g(),bb.setBgOpacity(1,0,1),H.hide(),l(d.shadeColor||d.bgColor,1),bb.
isAwake()?n(d.bgOpacity,1):n(1,1))}function l(a,b){bq(p(),a,b)}function m(){b&&(c.remove(),H.show(),b=!1,bb.isAwake()?bb.setBgOpacity(d.bgOpacity,1,1):(bb.setBgOpacity(1,1,1),bb.disableHandles()),bq(G,0,1))}function n(a,e){b&&(d.bgFade&&!e?c.animate({opacity:1-a},{queue:!1,duration:d.fadeTime}):c.css({opacity:1-a}))}function o(){d.shade?k():m(),bb.isAwake()&&n(d.bgOpacity)}function p(){return c.children()}var b=!1,c=a("<div />").css({position:"absolute",zIndex:240,opacity:0}),e={top:j(),left:j().height(F),right:j().height(F),bottom:j()};return{update:g,updateRaw:h,getShades:p,setBgColor:l,enable:k,disable:m,resize:f,refresh:o,opacity:n}}(),bb=function(){function k(b){var c=a("<div />").css({position:"absolute",opacity:d.borderOpacity}).addClass(j(b));return I.append(c),c}function l(b,c){var d=a("<div />").mousedown(s(b)).css({cursor:b+"-resize",position:"absolute",zIndex:c}).addClass("ord-"+b);return Z.support&&d.bind("touchstart.jcrop",Z.createDragger(b)),J.append(d),d}function m(a){var b=d.handleSize,e=l(a,c++
).css({opacity:d.handleOpacity}).addClass(j("handle"));return b&&e.width(b).height(b),e}function n(a){return l(a,c++).addClass("jcrop-dragbar")}function o(a){var b;for(b=0;b<a.length;b++)g[a[b]]=n(a[b])}function p(a){var b,c;for(c=0;c<a.length;c++){switch(a[c]){case"n":b="hline";break;case"s":b="hline bottom";break;case"e":b="vline right";break;case"w":b="vline"}e[a[c]]=k(b)}}function q(a){var b;for(b=0;b<a.length;b++)f[a[b]]=m(a[b])}function r(a,b){d.shade||H.css({top:i(-b),left:i(-a)}),K.css({top:i(b),left:i(a)})}function t(a,b){K.width(Math.round(a)).height(Math.round(b))}function v(){var a=_.getFixed();_.setPressed([a.x,a.y]),_.setCurrent([a.x2,a.y2]),w()}function w(a){if(b)return x(a)}function x(a){var c=_.getFixed();t(c.w,c.h),r(c.x,c.y),d.shade&&ba.updateRaw(c),b||A(),a?d.onSelect.call(bs,u(c)):d.onChange.call(bs,u(c))}function z(a,c,e){if(!b&&!c)return;d.bgFade&&!e?D.animate({opacity:a},{queue:!1,duration:d.fadeTime}):D.css("opacity",a)}function A(){K.show(),d.shade?ba.opacity(O):z(O,!0),b=!0}function B
(){F(),K.hide(),d.shade?ba.opacity(1):z(1),b=!1,d.onRelease.call(bs)}function C(){h&&J.show()}function E(){h=!0;if(d.allowResize)return J.show(),!0}function F(){h=!1,J.hide()}function G(a){a?(X=!0,F()):(X=!1,E())}function L(){G(!1),v()}var b,c=370,e={},f={},g={},h=!1;d.dragEdges&&a.isArray(d.createDragbars)&&o(d.createDragbars),a.isArray(d.createHandles)&&q(d.createHandles),d.drawBorders&&a.isArray(d.createBorders)&&p(d.createBorders),a(document).bind("touchstart.jcrop-ios",function(b){a(b.currentTarget).hasClass("jcrop-tracker")&&b.stopPropagation()});var M=y().mousedown(s("move")).css({cursor:"move",position:"absolute",zIndex:360});return Z.support&&M.bind("touchstart.jcrop",Z.createDragger("move")),I.append(M),F(),{updateVisible:w,update:x,release:B,refresh:v,isAwake:function(){return b},setCursor:function(a){M.css("cursor",a)},enableHandles:E,enableOnly:function(){h=!0},showHandles:C,disableHandles:F,animMode:G,setBgOpacity:z,done:L}}(),bc=function(){function f(b){M.css({zIndex:450}),b?a(document).bind("touchmove.jcrop"
,k).bind("touchend.jcrop",l):e&&a(document).bind("mousemove.jcrop",h).bind("mouseup.jcrop",i)}function g(){M.css({zIndex:290}),a(document).unbind(".jcrop")}function h(a){return b(m(a)),!1}function i(a){return a.preventDefault(),a.stopPropagation(),W&&(W=!1,c(m(a)),bb.isAwake()&&d.onSelect.call(bs,u(_.getFixed())),g(),b=function(){},c=function(){}),!1}function j(a,d,e){return W=!0,b=a,c=d,f(e),!1}function k(a){return b(m(Z.cfilter(a))),!1}function l(a){return i(Z.cfilter(a))}function n(a){M.css("cursor",a)}var b=function(){},c=function(){},e=d.trackDocument;return e||M.mousemove(h).mouseup(i).mouseout(i),D.before(M),{activateHandlers:j,setCursor:n}}(),bd=function(){function e(){d.keySupport&&(b.show(),b.focus())}function f(a){b.hide()}function g(a,b,c){d.allowMove&&(_.moveOffset([b,c]),bb.updateVisible(!0)),a.preventDefault(),a.stopPropagation()}function i(a){if(a.ctrlKey||a.metaKey)return!0;Y=a.shiftKey?!0:!1;var b=Y?10:1;switch(a.keyCode){case 37:g(a,-b,0);break;case 39:g(a,b,0);break;case 38:g(a,0,-b);break;
case 40:g(a,0,b);break;case 27:d.allowSelect&&bb.release();break;case 9:return!0}return!1}var b=a('<input type="radio" />').css({position:"fixed",left:"-120px",width:"12px"}).addClass("jcrop-keymgr"),c=a("<div />").css({position:"absolute",overflow:"hidden"}).append(b);return d.keySupport&&(b.keydown(i).blur(f),h||!d.fixedSupport?(b.css({position:"absolute",left:"-20px"}),c.append(b).insertBefore(D)):b.insertBefore(D)),{watchKeys:e}}();Z.support&&M.bind("touchstart.jcrop",Z.newSelection),J.hide(),br(!0);var bs={setImage:bp,animateTo:bf,setSelect:bg,setOptions:bk,tellSelect:bi,tellScaled:bj,setClass:be,disable:bl,enable:bm,cancel:bn,release:bb.release,destroy:bo,focus:bd.watchKeys,getBounds:function(){return[E*T,F*U]},getWidgetSize:function(){return[E,F]},getScaleFactor:function(){return[T,U]},getOptions:function(){return d},ui:{holder:G,selection:K}};return g&&G.bind("selectstart",function(){return!1}),A.data("Jcrop",bs),bs},a.fn.Jcrop=function(b,c){var d;return this.each(function(){if(a(this).data("Jcrop")){if(
b==="api")return a(this).data("Jcrop");a(this).data("Jcrop").setOptions(b)}else this.tagName=="IMG"?a.Jcrop.Loader(this,function(){a(this).css({display:"block",visibility:"hidden"}),d=a.Jcrop(this,b),a.isFunction(c)&&c.call(d)}):(a(this).css({display:"block",visibility:"hidden"}),d=a.Jcrop(this,b),a.isFunction(c)&&c.call(d))}),this},a.Jcrop.Loader=function(b,c,d){function g(){f.complete?(e.unbind(".jcloader"),a.isFunction(c)&&c.call(f)):window.setTimeout(g,50)}var e=a(b),f=e[0];e.bind("load.jcloader",g).bind("error.jcloader",function(b){e.unbind(".jcloader"),a.isFunction(d)&&d.call(f)}),f.complete&&a.isFunction(c)&&(e.unbind(".jcloader"),c.call(f))},a.Jcrop.defaults={allowSelect:!0,allowMove:!0,allowResize:!0,trackDocument:!0,baseClass:"jcrop",addClass:null,bgColor:"black",bgOpacity:.6,bgFade:!1,borderOpacity:.4,handleOpacity:.5,handleSize:null,aspectRatio:0,keySupport:!0,createHandles:["n","s","e","w","nw","ne","se","sw"],createDragbars:["n","s","e","w"],createBorders:["n","s","e","w"],drawBorders:!0,dragEdges
:!0,fixedSupport:!0,touchSupport:null,shade:null,boxWidth:0,boxHeight:0,boundary:2,fadeTime:400,animationDelay:20,swingSpeed:3,minSelect:[0,0],maxSize:[0,0],minSize:[0,0],onChange:function(){},onSelect:function(){},onDblClick:function(){},onRelease:function(){}}})(jQuery);

var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
  __slice = [].slice;

(function($, window) {
  var MrUploader;
  MrUploader = (function() {
    MrUploader.prototype.defaults = {
      multiple: true,
      cropping: true,
      onClick: true,
      uploadUrl: '/upload.php',
      aspectRatio: 'landscape',
      crop: {
        boxWidth: 800,
        aspectRatio: 3 / 2,
        keySupport: false,
        allowSelect: false,
        minSize: [300, 200],
        setSelect: [0, 0, 600, 400]
      }
    };

    function MrUploader(el, options) {
      this.hideFullscreen = __bind(this.hideFullscreen, this);
      this.setAspectRatio = __bind(this.setAspectRatio, this);
      this.setLandscapeAspectRatio = __bind(this.setLandscapeAspectRatio, this);
      this.setPortraitAspectRatio = __bind(this.setPortraitAspectRatio, this);
      this.setSquareAspectRatio = __bind(this.setSquareAspectRatio, this);
      this.show = __bind(this.show, this);
      this.showFullscreen = __bind(this.showFullscreen, this);
      this.onCloseClick = __bind(this.onCloseClick, this);
      this.getStagedFileMeta = __bind(this.getStagedFileMeta, this);
      this.changePreview = __bind(this.changePreview, this);
      this.getPreviewHeight = __bind(this.getPreviewHeight, this);
      this.getPreviewWidth = __bind(this.getPreviewWidth, this);
      this.onReaderLoad = __bind(this.onReaderLoad, this);
      this.onUploaderFileChanged = __bind(this.onUploaderFileChanged, this);
      this.onUploadClick = __bind(this.onUploadClick, this);
      this.onCancelClick = __bind(this.onCancelClick, this);
      this.resetCroppingArea = __bind(this.resetCroppingArea, this);
      this.setStaged = __bind(this.setStaged, this);
      this.onCroppingSelected = __bind(this.onCroppingSelected, this);
      this.showPhotoActionElements = __bind(this.showPhotoActionElements, this);
      this.hidePhotoActionElements = __bind(this.hidePhotoActionElements, this);
      this.getCroppingAreaContent = __bind(this.getCroppingAreaContent, this);
      this.getRatioOptions = __bind(this.getRatioOptions, this);
      this.getHeaderContent = __bind(this.getHeaderContent, this);
      this.onElementClick = __bind(this.onElementClick, this);
      this.on = __bind(this.on, this);
      this.$el = $(el);
      this.$options = $.extend(true, {}, this.defaults, options);
      this.photoActionsElements = [];
      this.addContent();
      if (this.$options.onClick === true) {
        this.$el.click(this.onElementClick);
      }
      this.setStaged(null);
      this.uploads = [];
    }

    MrUploader.prototype.on = function(event, callback) {
      return $(this).on(event, callback);
    };

    MrUploader.prototype.onElementClick = function(e) {
      e.preventDefault();
      return this.showFullscreen();
    };

    MrUploader.prototype.getHeaderContent = function() {
      var close, header, ratioOptions, title;
      header = $('<div/>');
      close = $('<h1><a href="#" class="mr-uploader-fullscreen-close">&times</a></h1>');
      close.click(this.onCloseClick);
      title = $('<h2/>').text('Choose & Crop');
      ratioOptions = this.getRatioOptions();
      this.photoActionsElements.push(ratioOptions);
      header.append(close, title, ratioOptions);
      return header;
    };

    MrUploader.prototype.getRatioOptions = function() {
      var landscape, landscapeLabel, portrait, portraitLabel, square, squareLabel;
      squareLabel = $(' <label for="mr-uploader-square-ratio">Square</label> ');
      this.squareInput = $(' <input type="radio" id="mr-uploader-square-ratio" name="mr-uploader-ratio" value="square"> ');
      this.squareInput.click((function(_this) {
        return function() {
          return _this.setSquareAspectRatio();
        };
      })(this));
      if (this.$options.aspectRatio === 'square') {
        this.squareInput.attr('checked', true);
      }
      square = $('<div />').append(squareLabel, this.squareInput);
      portraitLabel = $(' <label for="mr-uploader-portrait-ratio">Portrait</label> ');
      this.portraitInput = $(' <input type="radio" id="mr-uploader-portrait-ratio" name="mr-uploader-ratio" value="portrait"> ');
      if (this.$options.aspectRatio === 'portrait') {
        this.portraitInput.attr('checked', true);
      }
      this.portraitInput.click((function(_this) {
        return function() {
          return _this.setPortraitAspectRatio();
        };
      })(this));
      portrait = $('<div />').append(portraitLabel, this.portraitInput);
      landscapeLabel = $(' <label for="mr-uploader-landscape-ratio">Landscape</label> ');
      this.landscapeInput = $(' <input type="radio" id="mr-uploader-landscape-ratio" name="mr-uploader-ratio" value="landscape"> ');
      if (this.$options.aspectRatio === 'landscape') {
        this.landscapeInput.attr('checked', true);
      }
      this.landscapeInput.click((function(_this) {
        return function() {
          return _this.setLandscapeAspectRatio();
        };
      })(this));
      landscape = $('<div />').append(landscapeLabel, this.landscapeInput);
      return $('<div class="mr-uploader-ratio-options"></div>').append(square).append(portrait).append(landscape);
    };

    MrUploader.prototype.getCroppingAreaContent = function() {
      var cancel, crop, upload;
      crop = $('<div />');
      this.$input = $('<input id="mr-uploader-file-input" type="file" accept="image/*" />');
      this.$input.change(this.onUploaderFileChanged);
      crop.append(this.$input);
      this.$photos = $('<div id="mr-uploader-images">&nbsp;</div>');
      crop.append(this.$photos);
      upload = $('<button class="btn">Upload</button>');
      upload.click(this.onUploadClick);
      cancel = $('<button class="btn">Cancel</button>');
      cancel.click(this.onCancelClick);
      this.photoActionsElements.push(upload, cancel);
      crop.append(upload);
      crop.append(cancel);
      return crop;
    };

    MrUploader.prototype.addContent = function() {
      this.$container = $('<div/>').hide().addClass('mr-uploader-fullscreen-mode').css('text-align', 'center');
      this.$container.append(this.getHeaderContent());
      this.$croppingArea = this.getCroppingAreaContent();
      this.$container.append(this.$croppingArea);
      this.$container.append('<hr />');
      this.$previews = $('<div />');
      this.$container.append(this.$previews);
      this.hidePhotoActionElements();
      return $('body').append(this.$container);
    };

    MrUploader.prototype.hidePhotoActionElements = function() {
      var element, _i, _len, _ref, _results;
      _ref = this.photoActionsElements;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        element = _ref[_i];
        _results.push(element.hide());
      }
      return _results;
    };

    MrUploader.prototype.showPhotoActionElements = function() {
      var element, _i, _len, _ref, _results;
      _ref = this.photoActionsElements;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        element = _ref[_i];
        _results.push(element.show());
      }
      return _results;
    };

    MrUploader.prototype.onCroppingSelected = function(crop, image, meta) {
      crop = {
        x: Math.round(crop.x),
        y: Math.round(crop.y),
        x2: Math.round(crop.x2),
        y2: Math.round(crop.y2),
        width: Math.round(crop.w),
        height: Math.round(crop.h)
      };
      meta.width = image.width();
      meta.height = image.height();
      return this.setStaged({
        $image: image,
        meta: meta,
        crop: crop
      });
    };

    MrUploader.prototype.setStaged = function(staged) {
      this.staged = staged;
    };

    MrUploader.prototype.resetCroppingArea = function() {
      this.$croppingArea.html(this.getCroppingAreaContent());
      return this.hidePhotoActionElements();
    };

    MrUploader.prototype.onCancelClick = function(e) {
      this.resetCroppingArea();
      this.setStaged(null);
      return this.$preview.remove();
    };

    MrUploader.prototype.onUploadClick = function(e) {
      var $overlay, crop, meta, photo, request, url;
      e.preventDefault();
      if (this.staged == null) {
        return alert('Please choose a photo to upload');
      }
      url = this.$options.uploadUrl;
      photo = this.staged.$image.attr('src');
      meta = this.staged.meta;
      crop = this.staged.crop;
      $overlay = this.getPreviewOverlay();
      request = $.ajax({
        type: 'POST',
        url: url,
        cache: false,
        dataType: 'json',
        data: {
          photo: photo,
          meta: meta,
          crop: crop
        },
        beforeSend: (function(_this) {
          return function(xhr, settings) {
            _this.$preview.find('.mr-uploader-preview-overlay').each(function() {
              return this.remove();
            });
            _this.$preview.prepend($overlay);
            return _this.Jcrop.disable();
          };
        })(this)
      });
      request.done((function(_this) {
        return function(response, status, xhr) {
          _this.staged.response = response;
          _this.uploads.push(_this.staged);
          _this.resetCroppingArea();
          $overlay.html('&#10003');
          $(_this).trigger('upload', _this.staged);
          return _this.setStaged(null);
        };
      })(this));
      return request.fail((function(_this) {
        return function(xhr, status, error) {
          return $overlay.addClass('error').html('&times; Upload failed, please retry');
        };
      })(this));
    };

    MrUploader.prototype.getPreviewOverlay = function() {
      return $('<div class="mr-uploader-preview-overlay" />').append('<div class="mr-uploader-spinner"><div class="mr-uploader-spinner-bounce1"></div><div class="mr-uploader-spinner-bounce2"></div><div class="mr-uploader-spinner-bounce3"></div></div>');
    };

    MrUploader.prototype.onUploaderFileChanged = function(e) {
      var file, input, reader, _i, _len, _ref;
      input = this.$input[0];
      if ((input.files != null) && input.files.length > 0) {
        reader = new FileReader();
        reader.onload = this.onReaderLoad;
        _ref = input.files;
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          file = _ref[_i];
          reader.readAsDataURL(file);
        }
        this.$input.hide();
        return this.showPhotoActionElements();
      }
    };

    MrUploader.prototype.onReaderLoad = function(e) {
      var crop, img, meta, previewImage, self;
      img = $('<img src="' + e.target.result + '" />');
      crop = this.$options.crop;
      meta = this.getStagedFileMeta();
      this.$preview = $('<div class="mr-uploader-preview mr-uploader-ar-' + this.$options.aspectRatio + '"/>');
      previewImage = $('<img />').attr('src', e.target.result);
      this.$preview.html(previewImage);
      this.$previews.prepend(this.$preview);
      crop.onSelect = (function(_this) {
        return function(crop) {
          return _this.onCroppingSelected(crop, img, meta);
        };
      })(this);
      crop.onChange = (function(_this) {
        return function(crop) {
          return _this.changePreview(crop, previewImage);
        };
      })(this);
      this.$photos.html(img);
      self = this;
      return img.Jcrop(crop, function() {
        return self.Jcrop = this;
      });
    };

    MrUploader.prototype.getPreviewWidth = function() {
      switch (this.$options.aspectRatio) {
        case 'square':
        case 'portrait':
          return 200;
        case 'landscape':
          return 300;
      }
    };

    MrUploader.prototype.getPreviewHeight = function() {
      switch (this.$options.aspectRatio) {
        case 'square':
        case 'landscape':
          return 200;
        case 'portrait':
          return 300;
      }
    };

    MrUploader.prototype.changePreview = function(crop, $thumbnail) {
      var height, rx, ry, width;
      if (this.staged != null) {
        width = this.getPreviewWidth();
        height = this.getPreviewHeight();
        rx = width / crop.w;
        ry = height / crop.h;
        return $thumbnail.css({
          marginTop: '-' + Math.round(ry * crop.y) + 'px',
          marginLeft: '-' + Math.round(rx * crop.x) + 'px',
          width: Math.round(rx * this.staged.meta.width) + 'px',
          height: Math.round(ry * this.staged.meta.height) + 'px'
        });
      }
    };

    MrUploader.prototype.getStagedFileMeta = function() {
      var file, input;
      input = this.$input[0];
      file = input.files[0];
      return {
        name: file.name,
        size: file.size,
        type: file.type
      };
    };

    MrUploader.prototype.onCloseClick = function() {
      return this.hideFullscreen();
    };

    MrUploader.prototype.showFullscreen = function() {
      return this.$container.fadeIn();
    };

    MrUploader.prototype.show = function() {
      return this.showFullscreen();
    };

    MrUploader.prototype.setSquareAspectRatio = function() {
      var _ref, _ref1, _ref2, _ref3;
      if (this.$options.aspectRatio !== 'square') {
        if ((_ref = this.$preview) != null) {
          _ref.removeClass('mr-uploader-ar-' + this.$options.aspectRatio);
        }
        this.$options.aspectRatio = 'square';
        this.$options.crop.aspectRatio = 2 / 2;
        this.$options.crop.minSize = [200, 200];
        if ((_ref1 = this.Jcrop) != null) {
          _ref1.setOptions(this.$options.crop);
        }
        if ((_ref2 = this.$preview) != null) {
          _ref2.addClass('mr-uploader-ar-' + this.$options.aspectRatio);
        }
        return (_ref3 = this.squareInput) != null ? _ref3.attr('checked', true) : void 0;
      }
    };

    MrUploader.prototype.setPortraitAspectRatio = function() {
      var _ref, _ref1, _ref2, _ref3;
      if (this.$options.aspectRatio !== 'portrait') {
        if ((_ref = this.$preview) != null) {
          _ref.removeClass('mr-uploader-ar-' + this.$options.aspectRatio);
        }
        this.$options.aspectRatio = 'portrait';
        this.$options.crop.aspectRatio = 2 / 3;
        this.$options.crop.minSize = [200, 300];
        if ((_ref1 = this.Jcrop) != null) {
          _ref1.setOptions(this.$options.crop);
        }
        if ((_ref2 = this.$preview) != null) {
          _ref2.addClass('mr-uploader-ar-' + this.$options.aspectRatio);
        }
        return (_ref3 = this.portraitInput) != null ? _ref3.attr('checked', true) : void 0;
      }
    };

    MrUploader.prototype.setLandscapeAspectRatio = function() {
      var _ref, _ref1, _ref2, _ref3;
      if (this.$options.aspectRatio !== 'landscape') {
        if ((_ref = this.$preview) != null) {
          _ref.removeClass('mr-uploader-ar-' + this.$options.aspectRatio);
        }
        this.$options.aspectRatio = 'landscape';
        this.$options.crop.aspectRatio = 3 / 2;
        this.$options.crop.minSize = [300, 200];
        if ((_ref1 = this.Jcrop) != null) {
          _ref1.setOptions(this.$options.crop);
        }
        if ((_ref2 = this.$preview) != null) {
          _ref2.addClass('mr-uploader-ar-' + this.$options.aspectRatio);
        }
        return (_ref3 = this.landscapeInput) != null ? _ref3.attr('checked', true) : void 0;
      }
    };

    MrUploader.prototype.setAspectRatio = function(aspectRatio) {
      switch (aspectRatio) {
        case 'square':
          return this.setSquareAspectRatio();
        case 'portrait':
          return this.setPortraitAspectRatio();
        case 'landscape':
          return this.setLandscapeAspectRatio();
      }
    };

    MrUploader.prototype.hideFullscreen = function() {
      return this.$container.fadeOut();
    };

    return MrUploader;

  })();
  return $.fn.extend({
    mrUploader: function() {
      var $this, args, data, option, upload;
      option = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
      $this = this.first();
      data = $this.data('mrUploader');
      if (!data) {
        upload = new MrUploader(this, option);
        $this.data('mrUploader', (data = upload));
        return upload;
      }
      if (typeof option === 'string') {
        return data[optiokn].apply(data, args);
      }
    }
  });
})(window.jQuery, window);
