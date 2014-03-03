<script type="text/x-handlebars-template" id="image_item" />
	<li id="croped-image-item-{{index}}" class="croped-image-item">
		<span  onclick="deleteImage('{{data}}',this)">
			<i class="icon-trash bigger-130"></i>
		</span>
		<img class="image_to_crop" src="/tmp/{{data}}" id="croped-img-{{index}}">
	</li>

</script>