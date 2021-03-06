var temp_images;
var croped_object;
var yt_video_index=0;
var images=[];
var croped_images_ul=$("#croped-images-list");
var image_counter=0;
var deleted_images = [];
var deleted_videos = [];

var cover_crop_obj;

//array to store images
var files=[];


	$(document).ready(function(){
		var updating = $("#updating");

		$(function() {
			$('#publish-date').datetimepicker({
				language: 'pt-BR'
			});
		});

		$('#date-timepicker1').hide();

	    $('#editing').click(function() {
	        $('#date-timepicker1').hide();
	    });

	    $('#published').click(function() {
	        $('#date-timepicker1').hide();
	    });

	    $('#scheduled').click(function() {
	        $('#date-timepicker1').show();
	    });

	    if($('#scheduled').is(':checked'))
	    {
	        $('#date-timepicker1').show();
	    }

	    function showErrorAlert (reason, detail) {
	        var msg='';

	        if (reason==='unsupported-file-type')
	        {
	            msg = "Unsupported format " +detail;
	        } else {
	            console.log("error uploading file", reason, detail);
	        }
	        $('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>' +
	          '<strong>File upload error</strong> ' + msg + ' </div>').prependTo('#alerts');
	    }

	    $('#editor').ace_wysiwyg({
					toolbar:
					[
						null,
						null,
						null,
						null,
						{name:'bold', title:'Custom tooltip'},
						{name:'italic', title:'Custom tooltip'},
						{name:'strikethrough', title:'Custom tooltip'},
						{name:'underline', title:'Custom tooltip'},
						null,
						'insertunorderedlist',
						'insertorderedlist',
						'outdent',
						'indent',
						null,
						{name:'justifyleft'},
						{name:'justifycenter'},
						{name:'justifyright'},
						{name:'justifyfull'},
						null,
						{name:'createLink'},
						{name:'unlink'},
						null,
						null,
						null,
						{ name:'foreColor'},
						null,
						{name:'undo'},
						{name:'redo'},
						null,
						null
					],
					speech_button:false,

					'wysiwyg': {
						hotKeys : {} //disable hotkeys
					}
		}).prev().addClass('wysiwyg-style2');

	    $('[data-toggle="buttons"] .btn').on('click', function(e){
	        var target = $(this).find('input[type=radio]');
	        var which = parseInt(target.val());
	        var toolbar = $('#editor1').prev().get(0);
	        if(which == 1 || which == 2 || which == 3) {
	            toolbar.className = toolbar.className.replace(/wysiwyg\-style(1|2)/g , '');
	            if(which == 1) $(toolbar).addClass('wysiwyg-style1');
	            else if(which == 2) $(toolbar).addClass('wysiwyg-style2');
	        }
	    });

	    if ( typeof jQuery.ui !== 'undefined' && /applewebkit/.test(navigator.userAgent.toLowerCase()) ) {

	        var lastResizableImg = null;

	        function destroyResizable() {
	            if(lastResizableImg == null) return;
	            lastResizableImg.resizable( "destroy" );
	            lastResizableImg.removeData('resizable');
	            lastResizableImg = null;
	        }

	        var enableImageResize = function() {
	            $('.wysiwyg-editor')
	            .on('mousedown', function(e) {
	                var target = $(e.target);
	                if( e.target instanceof HTMLImageElement ) {
	                    if( !target.data('resizable') ) {
	                        target.resizable({
	                            aspectRatio: e.target.width / e.target.height,
	                        });
	                        target.data('resizable', true);

	                        if( lastResizableImg != null ) {//disable previous resizable image
	                            lastResizableImg.resizable( "destroy" );
	                            lastResizableImg.removeData('resizable');
	                        }
	                        lastResizableImg = target;
	                    }
	                }
	            })
	            .on('click', function(e) {
	                if( lastResizableImg != null && !(e.target instanceof HTMLImageElement) ) {
	                    destroyResizable();
	                }
	            })
	            .on('keydown', function() {
	                destroyResizable();
	            });
	        }

	        enableImageResize();
	    }

	});

//Add document.ready
//
//we could just set the data-provide="tag" of the element inside HTML, but IE8 fails!
var tag_input = $('#form-field-tags');

var tags="";


var cover_photo = {
    photo: $('#cover_photo'),
    crop_x: $('#cover_crop_x'),
    crop_y: $('#cover_crop_y'),
    crop_width: $('#cover_crop_width'),
    crop_height: $('#cover_crop_height'),
    photo_width: $('#cover_photo_width'),
    photo_height: $('#cover_photo_height')
}

$.ajax({
	url: Routes.cms_tags,
	type: "get",
	processData: false,
	contentType: false,
	success: function (res) {
		tags = JSON.parse(res.tags);

		if(! ( /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase())) )
		{
			tag_input.tag(
			  {
				placeholder:tag_input.attr('placeholder'),
				//enable typeahead by specifying the source array
				source: tags,//defined in ace.js >> ace.enable_search_ahead
			  }
			);
		}
		else {
			//display a textarea for old IE, because it doesn't support this plugin or another one I tried!
			tag_input.after('<textarea id="'+tag_input.attr('id')+'" name="'+tag_input.attr('name')+'" rows="3">'+tag_input.val()+'</textarea>').remove();
			//$('#form-field-tags').autosize({append: "\n"});
		}
	}
});


function coverUploadChange()
{

    var cover = $("#cover").prop('files')[0];
    form_data = new FormData();
    form_data.append('images[]', cover);

    var cover_photo_crop = $("#cover-crop");
    var path = StoreImage(form_data,cover_photo_crop);
}

function StoreImage(form_data,photo){

    $("#loader-container").show();
    $.ajax({
        url: Routes.cms_post_tmp,
        type: "POST",
        data: form_data,
        processData: false,
        contentType: false,
        success: function (res) {
            $("#loader-container").hide();
            path = JSON.parse(res);
            displayCoverView(path,photo);
        },
        error: function(xhr, status, error) {
            if(xhr.status == 400 || xhr.status == 403 || xhr.status == 408 || xhr.status == 500 || xhr.status == 504)
            {
                alert(xhr.statusText);
            }
        }
    });
}

function displayCoverView(path,photo)
{
    if($(photo).attr('id')=="cover-crop")
    {
        if(cover_crop_obj!==undefined)
        {
            cover_crop_obj.destroy();
        }

        photo.attr('src',"/"+Routes.photo_location+"/"+path);



       photo.Jcrop({
        aspectRatio: 3/2,
        allowSelect: false,
        keySupport: false,
        setSelect: [0, 0, 600, 400],
        minSize: [155, 245],
        boxWidth: 900,
        onChange: function(crop){

        }

    },function(){
        cover_crop_obj=this;
    });

    }
}




function filesUploadChange()
{
	temp_images=$("#images").prop("files");

	formdata = new FormData();

	for(var i=0; i < temp_images.length; i++)
	{
		formdata.append("images[]", temp_images[i]);
		images.push(temp_images[i]);
	}
	displayLoading();
	$.ajax({
		url: Routes.cms_post_tmp,
		type: "POST",
		data: formdata,
		processData: false,
		contentType: false,
		success: function (res) {
			temp_images = JSON.parse(res);
			displayCropView(temp_images);
			$("#loader-container").hide();
		}
	});
}

function displayCropView(temp_images)
{
	var temp_images_lengh=temp_images.length;
	var updating= $('#updating').val();
	for(var i=0; i < temp_images_lengh; i++)
	{

		files.push(temp_images[i]);
		templateHTML=$('#image_item').html();
	    template=Handlebars.compile(templateHTML);
	    compiledHtml=template({
	    						data:"/"+Routes.photo_location+"/"+temp_images[i],
	    						index:image_counter+i,
	    						url:temp_images[i]
	    					});
	    croped_images_ul.append(compiledHtml);

		$("#croped-img-"+(image_counter+i)).Jcrop({
			aspectRatio: 3/2,
            allowSelect: false,
            keySupport: false,
            setSelect: [0, 0, 600, 400],
            minSize: [300, 200],
            boxWidth: 800
		});
	}

	image_counter = image_counter + temp_images_lengh;
}

var croped_images_array = [];

function submitForm()
{
	var title = $("#title").val();
	var body = $("#editor").html();
	var section = $("#section").val();
	var tags=$("#form-field-tags").val();

	croped_images_array = getCropedImagesArray();
	videos_array = getVideosArray();
	croped_cover = JSON.stringify(getCoverImage());

	if($('#updating').val()=="1")
	{
		var old_tags=$(".tag-value");

		for(var i=0; i < old_tags.length; i++)
		{
			tags=tags + ", " + (old_tags[i].innerText);
		}

		if(croped_cover == undefined)
		{
			croped_cover = 'croped_cover';
		}

	}

	var featured = $('#featured').is(":checked");

	var publish_state=$(".publish_state");

	for (var i = 0; i < publish_state.length; i++) {
		if(publish_state[i].checked)
		{
			publish_state = $(".publish_state")[i].value;
		}
	};


	console.log(croped_cover);


	var publish_date = $("#date-timepicker1").val();

	formdata = new FormData();


	if(title!="" && croped_cover != undefined)
	{

		croped_images_array = JSON.stringify(croped_images_array);
		formdata.append("croped_images_array",croped_images_array);

		for(var i=0; i < files.length; i++)
		{
			formdata.append("images[]", files[i]);
		}

		formdata.append('deleted_images',deleted_images);
		formdata.append('deleted_videos',deleted_videos);
		formdata.append("title",title);
		formdata.append("body",body);
		formdata.append("section",section);
		formdata.append("tags",tags);
		formdata.append("publish_state",publish_state);
		formdata.append("publish_date",publish_date);
		formdata.append("featured",featured);

		cover = $("#cover-crop").attr('src');

		formdata.append("cover", cover);
		formdata.append("croped_cover",croped_cover);


		videos_array = JSON.stringify(videos_array);

		formdata.append("videos",videos_array);


		if($('#updating').val()=="1")
		{
			post_id = $('#post_id').val();
			submitUpdatedForm(formdata,post_id,section);

		}else{
			 submitNewForm(formdata,section);
		}

		$('#submitBtn').attr('disabled','disabled');
		displayLoading();

	}else{
		if(title=="")
		{
			displayErrorMessage('title_error');
		} else if (croped_cover == undefined) {

			displayErrorMessage('cover_image_error');

		} else{

			displayErrorMessage('empty_post_msg');
		}
	}
}

function getCropedImagesArray()
{
	jQuery('.image_to_crop').each(function() {
	  	image_to_crop = $(this);
	  	image_to_crop.Jcrop({
	    onChange: function(coords){
	    	croped_images_array.push(showPreview(image_to_crop, coords));
	    },
		});
	});

	return croped_images_array;
}

function getCoverImage()
{

  	image_to_crop = $("#cover-crop");

  	image_to_crop.Jcrop({
    onChange: function(coords){
    	cover_crop_obj = showPreview(image_to_crop, coords);
    },
	});

	return cover_crop_obj;
}




function showPreview(image_to_crop, coords) {

	var croped_image_object = {

		"name":image_to_crop.prop("src"),
		crop_x : Math.round(coords.x),
		crop_y : Math.round(coords.y),
		crop_width  : Math.round(coords.w),
		crop_height  : Math.round(coords.h),
		width : $("#cover-crop").width(),
		height : $("#cover-crop").height(),
	};

	return croped_image_object;
}

function getImageWidth()
{
	// return $('.jcrop-holder img:eq(0)').width();
	return $(".image_to_crop").width();
}

function getImageHeight()
{
	// return $('.jcrop-holder img:eq(0)').height();
	return $(".image_to_crop").height();
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////Youtube Videos//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function addYoutubeVideo()
{
	yt_url=$("#yt_video_txt").val();
	yt_id=validYT(yt_url);
	if(yt_id!=false)
	{
		yt_data = ajaxYoutube(yt_url,function(data){
			appendVideo(data,yt_url);
		});

		$("#yt_video_txt").val("");

	} else {
		displayErrorMessage('invalid_youtube_url')
	}
}

function validYT(url)
{
	var p = /^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/;
	return (url.match(p)) ? RegExp.$1 : false;
}

function ajaxYoutube(url,callback)
{
	$.ajax({
        url: "http://gdata.youtube.com/feeds/api/videos/"+getYoutubeId(url)+"?v=2&alt=json",
        dataType: "jsonp",
        success: function (data) {
  			return callback(data)
        }
    });
}

function getYoutubeId(url)
{
	var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
    var match = url.match(regExp);
    if (match&&match[7].length==11)
        return match[7];
}

function appendVideo(yt_data,url)
{

	var title = yt_data.entry.title.$t;
	var description = yt_data.entry.media$group.media$description.$t;
	var img = yt_data.entry.media$group.media$thumbnail[0].url;
		templateHTML = $('#video_item_template').html();
	    template = Handlebars.compile(templateHTML);
	    compiledHtml = template({
	    	img:img,
	    	title:title,
	    	description:description,
	    	url:url,
	    	index: yt_video_index
	    });

		$("#videos_list").append(compiledHtml);

	yt_video_index = yt_video_index + 1;
}

function deleteYt(id,video_id)
{
	$("#video_item_"+id).remove();
		yt_video_index--;
	if(video_id!='')
	{
		deleted_videos.push(parseInt(video_id));
	}
}

function getVideosArray()
{
	var videos_title = $(".new-title"),
	videos_desc = $(".new-desc"),
	videos_thumbnail = $(".new-thumbnail");
	videos_url = $(".new-url");


	var videos_array=[];

	for (var i = 0; i < videos_title.length; i++) {
		var video_obj = {
			"id":getYoutubeId($(videos_url[i]).val()),
			"title" : $(videos_title[i]).val(),
			"desc" : $(videos_desc[i]).val(),
			"src" : $(videos_thumbnail[i]).prop("src"),
			"url" : "http://youtube.com/embed/"+getYoutubeId($(videos_url[i]).val()),

		}

		console.log("http://youtube.com/embed/"+getYoutubeId($(videos_url[i]).val()));

		videos_array.push(video_obj);
	};

	return videos_array;
}


function removeTag(tag)
{

	$(tag).parent().remove();
}

function getTags()
{
	tags=$("#form-field-tags").val();
	tags_values=$(".tag-value");
	old_tags="";

	for(var i=0;i<tags_values.length;i++)
	{
		old_tags=old_tags+($(tags_values[i]).html())+",";
	}
	return old_tags+tags;
}

function submitUpdatedForm(formdata,id,section)
{
	$.ajax({
		url: Routes.cms_post_update+"/"+id,
		type: "POST",
		data: formdata,
		processData: false,
		contentType: false,
		success: function (res) {
			top.location=Routes.cms_content_show+"/"+section;
		}
	});
}


function submitNewForm(formdata,section)
{
	$.ajax({
		url: Routes.cms_post_create,
		type: "POST",
		data: formdata,
		processData: false,
		contentType: false,
		success: function (res) {
			top.location=Routes.cms_content_show+"/"+section;
		}
	});
}


function deleteImage(id,element)
{

	$.ajax({
		url:Routes.cms_post_photos_destroy,
		type:"POST",
		data:{image:id},
		success:function(res)
		{
			if(res.result==true)
			{
				files = jQuery.grep(files, function(value) {
				  return value != id;
				});

				$(element).parent().remove();

			}
		}
	});


}


function removePhotos(id, post_id)
{

   	deleted_images.push(id);
   	$("#img-"+id).hide();

}




//////////////////////////
//////////////////////////
//////////////////////////
//////////////////////////

jQuery(function($) {
	$("#bootbox-options").on(ace.click_event, function() {

	});
});

/////////////////////////////////////////
//////////Error Messages////////////////
///////////////////////////////////////

function displayErrorMessage(msg)
{

	bootbox.dialog({
		message: "<span class='bigger-110'>"+Lang[msg]+"</span>",
		buttons:
		{
			"default" :
			 {
				"label" : Lang['ok'],
				"className" : "btn-sm btn-success",
				"callback": function() {
				}
			}
		}
	});
}

function displayLoading()
{

	$("#loader-container").show();
}

function deletePost()
{
	bootbox.dialog({
		message: "<span class='bigger-110'>"+Lang['conf_messasge_delete_post']+"</span>",
		buttons:
		{
			"default" :
			 {
				"label" : Lang['cancel'],
				"className" : "btn-sm btn-grey",
				"callback": function() {
				}
			},
			"danger" :
			{
				"label" : "<i class='icon-trash'></i>"+Lang['delete']+"!",
				"className" : "btn-sm btn-danger",
				"callback": function() {
					$("#delete-post-form").submit();
				}
			}
		}
	});
}

function cancel()
{
	top.location = Routes.cms_content_show;
}














