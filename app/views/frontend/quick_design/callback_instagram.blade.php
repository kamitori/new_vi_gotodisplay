<script>
	access_token = (window.location.hash.substr(1));
	window.location  = '{{ Session::get("current_url") }}'+'?instagram_'+access_token;
</script>