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
      this.setStaged = __bind(this.setStaged, this);
      this.onCroppingSelected = __bind(this.onCroppingSelected, this);
      this.getCroppingAreaContent = __bind(this.getCroppingAreaContent, this);
      this.getHeaderContent = __bind(this.getHeaderContent, this);
      this.onElementClick = __bind(this.onElementClick, this);
      this.on = __bind(this.on, this);
      this.$el = $(el);
      this.$options = $.extend({}, this.defaults, options);
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
      var close, header, title;
      header = $('<div/>');
      close = $('<h1><a href="#" class="mr-uploader-fullscreen-close">&times</a></h1>');
      close.click(this.onCloseClick);
      title = $('<h2/>').text('Choose & Crop');
      header.append(close);
      header.append(title);
      return header;
    };

    MrUploader.prototype.getCroppingAreaContent = function() {
      var crop, upload;
      crop = $('<div />');
      this.$input = $('<input id="mr-uploader-file-input" type="file" accept="image/*" />');
      this.$input.change(this.onUploaderFileChanged);
      crop.append(this.$input);
      this.$photos = $('<div id="mr-uploader-images">&nbsp;</div>');
      crop.append(this.$photos);
      upload = $('<button class="btn">Upload</button>');
      upload.click(this.onUploadClick);
      crop.append(upload);
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
      return $('body').append(this.$container);
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
          _this.$croppingArea.html(_this.getCroppingAreaContent());
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
        return this.$input.hide();
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
      this.$options.aspectRatio = 'square';
      this.$options.crop.aspectRatio = 2 / 2;
      return this.$options.crop.minSize = [200, 200];
    };

    MrUploader.prototype.setPortraitAspectRatio = function() {
      this.$options.aspectRatio = 'portrait';
      this.$options.crop.aspectRatio = 2 / 3;
      return this.$options.crop.minSize = [200, 300];
    };

    MrUploader.prototype.setLandscapeAspectRatio = function() {
      this.$options.aspectRatio = 'landscape';
      this.$options.crop.aspectRatio = 3 / 2;
      return this.$options.crop.minSize = [300, 200];
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
