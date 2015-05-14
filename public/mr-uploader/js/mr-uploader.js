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
