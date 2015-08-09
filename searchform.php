<form role="search" method="get" class="search-form" action="<?php echo home_url(); ?>">
	<label for="search-form-field">
		<span class="screen-reader-text">Search for:</span>
		<input type="text" class="search-field" placeholder="Search ..." value="<?php
			echo get_search_query() ?>" name="s" id="search-form-field">
	</label>
	<button type="submit" class="search-submit"><i class="icon-search"></i></button>
</form>