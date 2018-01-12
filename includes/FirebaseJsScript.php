<script src="https://www.gstatic.com/firebasejs/4.5.1/firebase.js">
</script>
<script src='<?php echo ( plugins_url( PLUGIN_JS_BASE_REPOSITORY . 'ScriptFirebase.js' ) ); ?>'>
</script>
<script>
  var ref_test = db.ref('schoolNames/<?php echo PERSONNAL_UID ; ?>');

  ref_test.once('value')
    .then(function(data) {
      if (data.val() == null) {
	console.log("create value here");
	ref_test.set("new school");
      }
      else
	console.log("already exist");
    });
</script>
