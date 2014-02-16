<?php
 $updating = isset($content); 
?>

{{ Form::open([
    'url'    => $updating ?
                    URL::route('cms.content.update', $content->id) :
                    URL::route('cms.content.store'),
    'method' => $updating ? 'PUT' : 'POST',
    'class'  => 'form-horizontal',
    'role'   =>'form',
    'id'     => 'content-form'
]) }}


		<div class="center">
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Title </label>

				<div class="col-sm-9">
					{{Form::text(
						"title",
						$updating ?
							$content->title :
							"",
						["class"=>"col-xs-10 col-sm-4"]
						)}}
				</div>
			</div>
			
			<div class="space-4"></div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Parent Section </label>

				<div class="col-sm-3">
					<select class="form-control" name="parent_id" id="form-field-select-1">
						@foreach($contents as $subsection)
							<option  @if($updating)
										@if($subsection["id"]==$content->parent_id)
											 selected 
										@endif
									@endif

								value={{$subsection["id"]}}>{{$subsection["title"]}}
							</option>
						@endforeach
						
					</select>
				</div>
			</div>



    <div class="space-12"></div>

	    {{Form::button("Submit",["onclick"=>"submitForm()"])}}

{{Form::close()}}


