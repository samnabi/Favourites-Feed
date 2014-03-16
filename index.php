<? include('header.php') ?>

<h1>Favourites Feed</h1>
<h2>Your favourited tweets and saved reddit links all in one place. No need for a separate read-it-later app.</h2>

<p>Start by adding an account:</p>

<form method="GET" action="feed.php">
	<a class="add" name="addTwitter">Twitter</a>
	<a class="add" name="addReddit">Reddit</a>
	<fieldset>
		<!-- Generated content goes here -->
	</fieldset>
	<ul class="staging">
		<!-- Generated content goes here -->
	</ul>
	<input type="submit" value="Submit" />
</form>

<div class="hidden">
	<!-- Twitter -->
	<label class="addTwitter">
		<span>Username</span>
		<input type="text" name="twitter[]" />
	</label>
	<!-- Reddit -->
	<label class="addReddit">
		<ol class="reddit-help">
			<li>Log in and go to <a href="https://ssl.reddit.com/prefs/feeds/">https://ssl.reddit.com/prefs/feeds/</a></li>
			<li>Copy the JSON feed URL for your saved links:<br /><img src="reddit-help.gif" /></li>
		</ol>
		<span>Saved links feed URL (<a class="reddit-help-link">?</a>)</span>
		<input type="text" name="reddit[]" />
	</label>
	<!-- Staging link -->
	<a class="stageField">Add</a>
	<!-- Remove link -->
	<a class="unStageField">Remove</a>
</div>

<script>
	var addButtons = document.getElementsByClassName('add');
	for (var i = 0; i < addButtons.length; i++) {
		addButtons[i].addEventListener('click', newField);
	}

	function newField() {
		// Set active class and clear the fieldset
		var addLinks = document.getElementsByClassName('add');
		for (var i = 0; i < addLinks.length; i++) { addLinks[i].className = 'add'; }
		this.className = 'add selected';
		document.querySelector('fieldset').innerHTML = '';

		// Copy the field to the staging area
		var className = this.getAttribute('name');
		var clone = document.getElementsByClassName(className)[0].cloneNode(true);
		document.querySelector('fieldset').appendChild(clone);

		// Copy the "add" link to the staging area and give it an event handler
		var clone = document.getElementsByClassName('stageField')[0].cloneNode(true);
		document.querySelector('fieldset').appendChild(clone);
		document.querySelector('fieldset .stageField').addEventListener('click', stageField);

		// Give the reddit help link an event handler
		document.getElementsByClassName('reddit-help-link')[0].addEventListener('click', showHelp);
	};

	function stageField() {
		// Copy the field to the staging area
		var clone = document.querySelector('fieldset label input').cloneNode(true);
		clone.setAttribute('readonly','true');
		var removelink = document.getElementsByClassName('unStageField')[0].cloneNode(true);
		removelink.addEventListener('click', unStageField);
		var li = document.createElement('li');
		li.appendChild(clone);
		li.appendChild(removelink);
		document.getElementsByClassName('staging')[0].appendChild(li);

		// Clear the fieldset
		document.querySelector('fieldset').innerHTML = '';

		// Clear the selected class
		document.getElementsByClassName('selected')[0].className = 'add';
	}

	function unStageField() {
		var li = this.parentNode;
		li.parentNode.removeChild(li);
	}

	function showHelp(){
		document.getElementsByClassName('reddit-help')[0].style.display = 'block';
	}
</script>

<p class="credit">Brought to you by <a href="http://samnabi.com">Sam Nabi</a> &middot; Twitter and reddit icons by <a href="http://martz90.deviantart.com/art/Circle-Icons-Pack-371172325">Martz90</a> &middot; <a href="https://github.com/samnabi/Favourites-Feed">Fork this on GitHub</a></p>

<? include('footer.php') ?>