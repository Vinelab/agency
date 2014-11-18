var cover_photo;
var path;
var cropped_cover_photo_array=[];
var jcrop_api;
$(function() {
  $("#change_photo").hide();
});
function coverPhotoFilesUploadChange()
{
	var cover_photo = $("#cover_photo").prop('files'); 
	formdata = new FormData();
	formdata.append("coverphoto", cover_photo[0]);
	$.ajax({
		url: URL.upload_photo,
		type: "POST",
		data: formdata,
		processData: false,
		contentType: false,
		success: function (res) {
			cover_photo = res.photoname;
			path = res.path; 
			displayCropPhotoView(cover_photo, path);
			$("#cover_photo").hide();
			$("#change_photo").show();
			$('#change_photo').removeAttr('disabled');
		},
		error: function(xhr, status, error) {
			if(xhr.status == 400 || xhr.status == 403 || xhr.status == 408 || xhr.status == 500 || xhr.status == 504)
			{
				alert(xhr.statusText);
			}
		}
	});
}

function displayCropPhotoView(cover_photo, path)
{
	var cropped_images_element=$("#cover_photo_preview");
	cropped_images_element.prop('src',path+'/'+cover_photo); 
	cropped_images_element.Jcrop({
		aspectRatio: 3/2,
		setSelect: [0, 0, 600, 400],
		allowSelect: false,
        keySupport: false,
		minSize: [300, 200],
		boxWidth: 800
	},function(){ jcrop_api=this });
}

function getCroppedCoverPhoto()
{
	jQuery('.cover_photo_to_crop').each(function() {
		cover_photo_to_crop = $(this);
		cover_photo_to_crop.Jcrop({
			onChange: function(coords){
				cropped_cover_photo_array.push(showCoverPhotoPreview(cover_photo_to_crop, coords));
			},
		});
	});

	return cropped_cover_photo_array;
}

function showCoverPhotoPreview(cover_photo_to_crop, coords) {
	return {
		"name":cover_photo_to_crop.prop("src"),
		crop_x : Math.round(coords.x),
		crop_y : Math.round(coords.y),
		crop_width  : Math.round(coords.w),
		crop_height  : Math.round(coords.h),
		width : getImageWidth(), 
		height : getImageHeight(),
	};
}

function changeCoverPhoto()
{

	var src = $("#cover_photo_preview").attr('src');

	var data = {src: src};
	$.ajax({
		url: URL.change_photo,
		type: "POST",
		data: data,
		dataType: 'json',
		success: function (res) {
			$("#cover_photo").show();
			$("#change_photo").hide();
			photo_preview = $("#cover_photo_preview");
			photo_preview.attr('src', '');
			photo_preview.remove();
			$("#cover_photo_holder").append('<img id="cover_photo_preview" id="change_photo" class="cover_photo_to_crop">');
			$('#cover_photo').prop('disabled', false);
			$('#cover_photo').val('');
			$('#change_photo').attr('disabled', 'disabled');
			if(jcrop_api != null)
			{
				jcrop_api.destroy();
			}
		},
		error: function(xhr, status, error) {
			if(xhr.status == 400 || xhr.status == 403 || xhr.status == 408 || xhr.status == 500 || xhr.status == 504)
			{
				alert(xhr.statusText);
			}
		}
	});


}
