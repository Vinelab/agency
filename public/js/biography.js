$(function() {
  var min_label_input      = 1; // input boxes allowed.
  var labels_wrapper  = $("#labels_wrapper");
  var add_button      = $("#add-label");

  var added_labels_count = labels_wrapper.length; //initial labels count.
  var labels_count  = 0; //to keep track of the labels added.
  
  var start_date_placeholder = $('span #label_start').attr("placeholder");
  var end_date_placeholder = $('span #label_end_0').attr("placeholder");
  var label_present_cbs_text = $('#label_cbx').text();

  var options = $("#labels option");
  //values contains an array of all the options in the labels select list.
  var values = $.map(options, function(option) {
    return option.text;
  });

  var options ='';
  for(i=0; i<values.length; i++)
  {
    options += '<option value="'+ values[i] +'">'+values[i]+'</option>';
  }

  $(add_button).click(function (e)  //on add input button click
  {
    if(added_labels_count >= min_label_input) //minimum input box allowed
    {
      labels_count++; //increment labels added
     //add the html required for the labels
      $(labels_wrapper).append(
        '<div class="form-inline label_head increase-padding-bottom" id="labels_head_'+ labels_count + '">'+
          '<div class="form-group increase-padding-right label-select-width-js">'+
            '<select class="form-control" id="labels_'+ labels_count + '" name="label['+ labels_count +'][title]">'+
              options +
            '</select>'+
          '</div>' +
          '<div class="form-group">'+
            '<div id="label_start_date_'+ labels_count + '" class="input-append date increase-padding-right">'+
              '<span class="add-on">'+
                '<input type="text" id="label_start_'+ labels_count + '" name="label['+ labels_count +'][start_date]" '+
                  'data-format="yyyy-MM-dd" class="form-control date-width label_start" placeholder="'+start_date_placeholder +'" />'+
              '</span>'+
            '</div>'+
          '</div>' +
          '<div class="form-group">'+
            '<div id="label_end_date_'+ labels_count + '" class="input-append label_end date increase-padding-right">'+
              '<span class="add-on">'+
                '<input type="text" id="label_end_'+ labels_count + '" name="label['+ labels_count +'][end_date]" '+
                  'data-format="yyyy-MM-dd" class="form-control date-width label_end" placeholder="'+end_date_placeholder +'" />'+
              '</span>'+
            '</div>'+
          '</div>'+
          '<label>'+
            '<input type="hidden" value="0" name="label['+labels_count+'][present]">'+
            '<input value="1" name="label['+labels_count+'][present]" type="checkbox" id="label_present_'+ labels_count + '" class="label_present ace">'+
            '<span class="lbl" id="label_cbx_'+labels_count+'"> '+label_present_cbs_text+ '</span>'+
          '</label>' +
          '<div class="form-group">'+
            '<a href="javascript:void(0)" class="" id="removeButton"> '+
              '<i class="icon-trash btn-lg icon-only"></i>'+
            '</a>'+
          '</div>'+
        '</div>'
      );
      $('#label_start_date_'+ labels_count + '').datetimepicker({
        pickTime: false
      });
      $('#label_end_date_'+ labels_count + '').datetimepicker({
        pickTime: false
      });

      added_labels_count++; // increment the initial labels count
    }
    return false;
  });
  
  $("body").on("click","#removeButton", function(e){ //click on remove button
    if( added_labels_count > 1 ) {
      $(this).parents('.label_head').remove();
      added_labels_count--;
    }
    return false;
  });

  $("body").on("click","label .label_present", function(e){ 
      $(this).closest('.label_head').find('.label_end').toggle();
  });

});

$(function() {
  var min_award_inputs      = 1;
  var awards_wrapper  = $("#awards_wrapper");
  var add_award      = $("#add-award");

  var added_awards_count             = awards_wrapper.length;
  var awards_count  = 0;

  var award_title_placeholder = $(' #award_title').attr("placeholder");
  var award_year_placeholder = $(' #award_year').attr("placeholder");

  $(add_award).click(function (e)
  {
    if(added_awards_count >= min_award_inputs)
    {
      awards_count++;
  
      $(awards_wrapper).append(
          '<div class="form-inline increase-padding-bottom" id="award_head">'+
            '<div class="form-group increase-padding-right">'+
              '<input type="text" name="award['+ awards_count +'][title]" class="form-control" id="award_title_'+ awards_count + '" placeholder="'+award_title_placeholder+'">'+
            '</div>'+
            '<div class="form-group fix-padding">'+
              '<input type="text" name="award['+ awards_count +'][year]" class="form-control year-width" id="award_year_'+ awards_count + '" placeholder="'+award_year_placeholder+'">'+
            '</div>'+
            '<div class="form-group">'+
              '<a href="javascript:void(0)" id="removeAward"> '+
                '<i class="icon-trash btn-lg icon-only"></i>'+
              '</a>'+
            '</div>'+
          '</div>'
      );

      added_awards_count++;
    }
    return false;
  });

  $("body").on("click","#removeAward", function(e){
    if( added_awards_count > 1 ) {
      $(this).parents('#award_head').remove();
      added_awards_count--;
    }
    return false;
  });

});


$(function() {
  $('#date_of_birth').datetimepicker({
    pickTime: false
  });
  $('#career_inception').datetimepicker({
    pickTime: false
  });
  $('#label_start_date').datetimepicker({
    pickTime: false
  });
  $('#label_end_date').datetimepicker({
    pickTime: false
  });
  $("#label_present_0").click(function(){
        $("#label_end_0").toggle();
    });
});

function removeLabel(label_id)
{
  var obj = {label_id:label_id};
  $.ajax({
      url: URL.delete_label,
      type: "POST",
      data: obj,
      success: function(){
          window.location.href = window.location;
      },
      error: function(xhr) {
          if(xhr.status == 400 || xhr.status == 403 || xhr.status == 408 || xhr.status == 500 || xhr.status == 504)
          {
              location.reload();
          }
      }
  });
}


function removeAward(award_id)
{
  var obj = {award_id: award_id};
  $.ajax({
      url: URL.delete_award,
      type: "POST",
      data: obj,
      success: function(){
          window.location.href = window.location;
      },
      error: function(xhr) {
          if(xhr.status == 400 || xhr.status == 403 || xhr.status == 408 || xhr.status == 500 || xhr.status == 504)
          {
              location.reload();
          }
      }
  });
}

