@section('head')
    @parent
    <link rel="stylesheet" href="{{ Cdn::asset('/css/content/images-picker.css') }}" />
@stop


<!-- Large modal -->
<div class="modal fade selectExistingPhotosModal" id="photosModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">{{Lang::get('photos.choose_photos')}}</h4>
			</div>
			<div class="modal-body">

				<select multiple="multiple" class="image-picker show-html" id="photos-selector">

					{{-- options gets injected from JS after loading using AJAX --}}

				</select>

				<button type="button" id="load-more-photos" class="btn btn-primary">{{Lang::get('photos.load_more')}}</button>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">{{Lang::get('photos.cancel')}}</button>
				<button type="button" class="btn btn-primary" id="done-selecting-photos">{{Lang::get('photos.done')}}</button>
			</div>
		</div>
	</div>
</div>


@section('scripts')

    @parent

@stop
