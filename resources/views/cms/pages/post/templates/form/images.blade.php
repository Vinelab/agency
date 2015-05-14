{{-- photos section --}}
@include('cms/pages/templates/_images-uploader', ['model' => (isset($edit_post)) ? $edit_post : null])
