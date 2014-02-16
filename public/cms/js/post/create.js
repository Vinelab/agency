var temp_images;
var croped_object;
var yt_video_index=0;

function FilesUploadChange()
{
	var images=$("#images").prop("files");


	
	formdata = new FormData();

	for(var i=0;i<images.length;i++)
	{
		formdata.append("images[]", images[i]);
	}

	$.ajax({
		url: "/cms/tmp",
		type: "POST",
		data: formdata,
		processData: false,
		contentType: false,
		success: function (res) {
			temp_images = JSON.parse(res);
			displayCropView(temp_images);
		}
	});
}

function displayCropView(temp_images)
{
	var croped_images_ul=$("#croped-images-list");
	var temp_images_lengh=temp_images.length;
	for(var i=0; i<temp_images_lengh;i++)
	{
		var element='<li id="croped-image-item-'+i+'"><img class="image_to_crop" src="/tmp/'+temp_images[i]+'" id="croped-img-'+i+'"></li>';
		croped_images_ul.append(element);
		$("#croped-img-"+i).Jcrop({
			aspectRatio: 3/2,
			//trueSize:[500,500]
		});
	}
}

var croped_images_array=[];

function submitForm()
{
	formdata = new FormData();

	croped_images_array=JSON.stringify(getCropedImagesArray());
	formdata.append("croped_images_array",croped_images_array);


	var files = $("#images").prop("files");

	for(var i=0;i<files.length;i++)
	{
		formdata.append("images[]", files[i]);
	}


	var title = $("#title").val();
	var body = $("#body").val();
	var section = $("#section").val();

	formdata.append("title",title);
	formdata.append("body",body);
	formdata.append("section",section);


	videos_array=JSON.stringify(getVideosArray());
	formdata.append("videos",videos_array);

	$.ajax({
		url: "/cms/post",
		type: "POST",
		data: formdata,
		processData: false,
		contentType: false,
		success: function (res) {
			top.location="/cms/content/post/assign?post="+res.id;
		}
	});

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



function showPreview(image_to_crop, coords) {
	var croped_image_object={
		"name":image_to_crop.prop("src"),
		crop_x : Math.round(coords.x),
		crop_y : Math.round(coords.y),
		crop_width  : Math.round(coords.w),
		crop_height  : Math.round(coords.h),
		width : getImageWidth(),
		height : getImageHeight(),

	};
	return croped_image_object;
}

function getImageWidth()
{
	return $('.jcrop-holder img:eq(0)').width();
}

function getImageHeight()
{
	return $('.jcrop-holder img:eq(0)').height();

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
		yt_data = ajax_youtube(yt_url,function(data){
			append_video(data,yt_url);
		});

		$("#yt_video_txt").val("");

	}
}

function validYT(url) 
{
	var p = /^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/;
	return (url.match(p)) ? RegExp.$1 : false;
}

function ajax_youtube(url,callback)
{
	$.ajax({
        url: "http://gdata.youtube.com/feeds/api/videos/"+get_youtube_id(url)+"?v=2&alt=json",
        dataType: "jsonp",
        success: function (data) {
  			return callback(data)
        }
    });
}

function get_youtube_id(url)
{
	var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
    var match = url.match(regExp);
    if (match&&match[7].length==11)
        return match[7];
}

function append_video(yt_data,url)
{
	console.log(yt_data);

	var title = yt_data.entry.title.$t;
	var description = yt_data.entry.media$group.media$description.$t;
	var img = yt_data.entry.media$group.media$thumbnail[0].url;

	$("#videos_list").append(yt_template(yt_video_index,img,title,description,url));
	yt_video_index = yt_video_index + 1;
	
}

function yt_template(i,img,title,description,url)
{
	return '<li class="video_item" id="video_item_'+i+'"><div class="video-container"><div class="yt-img"><img src="'+img+'" class="yt-img-thumbnail"></div><div class="yt-data"><input type="text" id="yt-title-'+i+'" class="yt-title" value="'+title+'"><textarea id="yt-desc-'+i+'" class="yt-desc">'+description+'</textarea><input type="hidden" class="yt-url" id="yt-url-'+i+'" value="'+url+'"></div><div class="yt-delete"><button type="button" class="btn btn-xs btn-info yt-delete-btn" onclick="delete_yt('+i+')"><i class="icon-trash"></i></button></div></div></li>'
}

function delete_yt(id)
{
	$("#video_item_"+id).remove();
		yt_video_index--;
}

function getVideosArray()
{
	var videos_title = $(".yt-title"),
	videos_desc = $(".yt-desc"),
	videos_thumbnail = $(".yt-img-thumbnail");
	videos_url = $(".yt-url");


	var videos_array=[];

	for (var i = 0; i < videos_title.length; i++) {
		var video_obj = {
			"title" : $(videos_title[i]).val(),
			"desc" : $(videos_desc[i]).val(),
			"src" : $(videos_thumbnail[i]).prop("src"),
			"url" : $(videos_url).val()
		}

		videos_array.push(video_obj);	
	};

	return videos_array;

}

