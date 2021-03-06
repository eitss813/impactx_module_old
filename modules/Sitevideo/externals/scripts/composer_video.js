
/* $Id: composer_video.js 10258 2014-06-04 16:07:47Z lucas $ */



(function () { // START NAMESPACE
    var $ = 'id' in document ? document.id : window.$;



    Composer.Plugin.SiteVideo = new Class({
        Extends: Composer.Plugin.Interface,
        name: 'video',
        options: {
            title: 'Add Video',
            lang: {},
            // Options for the link preview request
            requestOptions: {},
            // Various image filtering options
            imageMaxAspect: (10 / 3),
            imageMinAspect: (3 / 10),
            imageMinSize: 48,
            imageMaxSize: 5000,
            imageMinPixels: 2304,
            imageMaxPixels: 1000000,
            imageTimeout: 5000,
            // Delay to detect links in input
            monitorDelay: 250
        },
        initialize: function (options) {
            this.elements = new Hash(this.elements);
            this.params = new Hash(this.params);
            this.parent(options);
        },
        attach: function () {
            this.parent();
            this.makeActivator();
            return this;
        },
        detach: function () {
            this.parent();
            return this;
        },
        activate: function () {
            if (this.active)
                return;
            this.parent();

            this.makeMenu();
            this.makeBody();

            // Generate body contents
            // Generate form

            this.elements.formInput = new Element('select', {
                'id': 'compose-video-form-type',
                'class': 'compose-form-input',
                'option': 'test',
                'events': {
                    'change': this.updateVideoFields.bind(this)
                }
            }).inject(this.elements.body);
            var options = 0;
            $('compose-video-form-type').options[options++] = new Option(this._lang('Choose Source'), '0');
            if (this.options.youtubeEnabled == 1) {
                $('compose-video-form-type').options[options++] = new Option(this._lang('YouTube'), 'youtube');
            }

            if (this.options.vimeoEnabled == 1) {
                $('compose-video-form-type').options[options++] = new Option(this._lang('Vimeo'), 'vimeo');
            }
            if (this.options.dailymotionEnabled == 1) {
                $('compose-video-form-type').options[options++] = new Option(this._lang('Dailymotion'), 'dailymotion');
            }

            this.elements.formInput = new Element('input', {
                'id': 'compose-video-form-input',
                'class': 'compose-form-input',
                'type': 'text',
                'style': 'display:none;'
            }).inject(this.elements.body);
            additionalAttr = "";
            additionalEmbedAttr = "";
            if (this.options.openVideosInLightboxMode) {
                additionalAttr = 'class="seao_smoothbox" onclick="showVideoInLightboxInFeed(3);return false;" data-SmoothboxSEAOClass="seao_add_video_lightbox" ';
                additionalEmbedAttr = 'class="seao_smoothbox" onclick="showVideoInLightboxInFeed(5);return false;" data-SmoothboxSEAOClass="seao_add_video_lightbox" ';
            }
            if (this.options.embedcodeEnabled == 1) {
                $('compose-video-form-type').options[options++] = new Option(this._lang('Embed Code'), 'embedcode');
            }
            this.elements.embedcodePreviewDescription = new Element('div', {
                'id': 'compose-embed-video',
                'class': 'compose-video-upload',
                'html': this._lang('To embed your video <a href="' + this.options.embedVideoHref + '" ' + additionalEmbedAttr + '>click here</a>.'),
                'style': 'display:none;'
            }).inject(this.elements.body);

            if (DetectMobileQuick() || DetectIpad()) {
                if (this.options.allowed == 1 && this.options.type != 'message') {
                    $('compose-video-form-type').options[options++] = new Option(this._lang('My Device'), 'upload');
                }
                this.elements.previewDescription = new Element('div', {
                    'id': 'compose-video-upload',
                    'class': 'compose-video-upload',
                    'html': this._lang('To upload a video from your computer, please use our full uploader.'),
                    'style': 'display:none;'
                }).inject(this.elements.body);
            }
            else {
                if (this.options.allowed == 1 && this.options.type != 'message') {
                    $('compose-video-form-type').options[options++] = new Option(this._lang('My Computer'), 'upload');
                }
                this.elements.previewDescription = new Element('div', {
                    'id': 'compose-video-upload',
                    'class': 'compose-video-upload',
                    'html': this._lang('To upload a video from your computer, please use our <a href="' + this.options.videoHref + '" ' + additionalAttr + '>full uploader</a>.'),
                    'style': 'display:none;'
                }).inject(this.elements.body);
            }

            this.elements.formSubmit = new Element('button', {
                'id': 'compose-video-form-submit',
                'class': 'compose-form-submit',
                'style': 'display:none;',
                'html': this._lang('Attach'),
                'events': {
                    'click': function (e) {
                        e.stop();
                        this.doAttach();
                    }.bind(this)
                }
            }).inject(this.elements.body);

            this.elements.formInput.focus();
        },
        deactivate: function () {
            // clean video out if not attached
            if (this.params.video_id)
                new Request.JSON({
                    url: en4.core.basePath + 'sitevideo/video/delete',
                    data: {
                        format: 'json',
                        video_id: this.params.video_id
                    }
                }).send();
            if (!this.active)
                return;
            this.parent();
        },
        // Getting into the core stuff now

        doAttach: function (e) {
            var val = this.elements.formInput.value;
            if (!val)
            {
                return;
            }
            if (!val.match(/^[a-zA-Z]{1,5}:\/\//))
            {
                val = 'http://' + val;
            }
            this.params.set('uri', val)
            // Input is empty, ignore attachment
            if (val == '') {
                e.stop();
                return;
            }

            var video_element = document.getElementById("compose-video-form-type");
            var type = video_element.value;
            // Send request to get attachment
            var options = $merge({
                'data': {
                    'format': 'json',
                    'uri': val,
                    'type': type
                },
                'onComplete': this.doProcessResponse.bind(this)
            }, this.options.requestOptions);

            // Inject loading
            this.makeLoading('empty');

            // Send request
            this.request = new Request.JSON(options);
            this.request.send();
        },
        doProcessResponse: function (responseJSON, responseText) {
            // Handle error
            if (($type(responseJSON) != 'hash' && $type(responseJSON) != 'object') || $type(responseJSON.src) != 'string' || $type(parseInt(responseJSON.video_id)) != 'number') {
                //this.elements.body.empty();
                if (this.elements.loading)
                    this.elements.loading.destroy();
                //this.makeaError(responseJSON.message, 'empty');
                this.makeError(responseJSON.message);

                //compose-video-error
                //ignore test
                this.elements.ignoreValidation = new Element('a', {
                    'href': this.params.uri,
                    'html': this.params.title,
                    'events': {
                        'click': function (e) {
                            e.stop();
                            self.doAttach(this);
                        }
                    }
                }).inject(this.elements.previewTitle);

                return;
                //throw "unable to upload image";
            }

            var title = responseJSON.title || this.params.get('uri').replace('http://', '');


            this.params.set('title', responseJSON.title);
            this.params.set('description', responseJSON.description);
            this.params.set('photo_id', responseJSON.photo_id);
            this.params.set('video_id', responseJSON.video_id);
            this.elements.preview = Asset.image(responseJSON.src, {
                'id': 'compose-video-preview-image',
                'class': 'compose-preview-image',
                'onload': this.doImageLoaded.bind(this)
            });
        },
        doImageLoaded: function () {
            var self = this;

            if (this.elements.loading)
                this.elements.loading.destroy();
            this.elements.preview.erase('width');
            this.elements.preview.erase('height');
            this.elements.preview.inject(this.elements.body);

            this.elements.previewInfo = new Element('div', {
                'id': 'compose-video-preview-info',
                'class': 'compose-preview-info'
            }).inject(this.elements.body);

            this.elements.previewTitle = new Element('div', {
                'id': 'compose-video-preview-title',
                'class': 'compose-preview-title'
            }).inject(this.elements.previewInfo);

            this.elements.previewTitleLink = new Element('a', {
                'href': this.params.uri,
                'html': this.params.title,
                'events': {
                    'click': function (e) {
                        e.stop();
                        self.handleEditTitle(this);
                    }
                }
            }).inject(this.elements.previewTitle);

            this.elements.previewDescription = new Element('div', {
                'id': 'compose-video-preview-description',
                'class': 'compose-preview-description',
                'html': this.params.description,
                'events': {
                    'click': function (e) {
                        e.stop();
                        self.handleEditDescription(this);
                    }
                }
            }).inject(this.elements.previewInfo);
            this.makeFormInputs();
        },
        makeFormInputs: function () {
            this.ready();
            this.parent({
                'photo_id': this.params.photo_id,
                'video_id': this.params.video_id,
                'title': this.params.title,
                'description': this.params.description
            });
        },
        updateVideoFields: function (element) {
            var video_element = document.getElementById("compose-video-form-type");
            var url_element = document.getElementById("compose-video-form-input");
            var post_element = document.getElementById("compose-video-form-submit");
            var upload_element = document.getElementById("compose-video-upload");
            var compose_desc_element = document.getElementById("compose-embed-video");
            // clear url if input field on change
            $('compose-video-form-input').value = "";

            // If video source is empty
            if (video_element.value == 0)
            {
                upload_element.style.display = "none";
                post_element.style.display = "none";
                url_element.style.display = "none";
            }

            // If video source is youtube or vimeo
            if (video_element.value == 'youtube' || video_element.value == 'vimeo' || video_element.value == 'dailymotion') {
                upload_element.style.display = "none";
                compose_desc_element.style.display = "none";
                post_element.style.display = "block";
                url_element.style.display = "block";
                url_element.focus();
            } else if (video_element.value == 'upload') {
                upload_element.style.display = "block";
                post_element.style.display = "none";
                url_element.style.display = "none";
                compose_desc_element.style.display = "none";
            } else if (video_element.value == 'embedcode') {
                compose_desc_element.style.display = "block";
                post_element.style.display = "none";
                url_element.style.display = "none";
                upload_element.style.display = "none";
            }
        },
        handleEditTitle: function (element) {
            element.setStyle('display', 'none');
            var input = new Element('input', {
                'type': 'text',
                'value': htmlspecialchars_decode(element.get('html').trim()),
                'events': {
                    'blur': function () {
                        if (input.value.trim() != '') {
                            this.params.title = input.value;
                            element.set('html', this.params.title);
                            this.setFormInputValue('title', this.params.title);
                        }
                        element.setStyle('display', '');
                        input.destroy();
                    }.bind(this)
                }
            }).inject(element, 'after');
            input.focus();
        },
        handleEditDescription: function (element) {
            element.setStyle('display', 'none');
            var input = new Element('textarea', {
                'html': htmlspecialchars_decode(element.get('html').trim()),
                'events': {
                    'blur': function () {
                        if (input.value.trim() != '') {
                            this.params.description = input.value;
                            element.set('html', this.params.description);
                            this.setFormInputValue('description', this.params.description);
                        }
                        else {
                            this.params.description = '';
                            element.set('html', '');
                            this.setFormInputValue('description', '');
                        }
                        element.setStyle('display', '');
                        input.destroy();
                    }.bind(this)
                }
            }).inject(element, 'after');
            input.focus();
        }
    });



})(); // END NAMESPACE
