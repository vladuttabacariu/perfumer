<?php
$startYear = 2011;
$thisYear = date('Y');
if ($thisYear > $startYear) {
	$thisYear = date('y');
	$copyright = "$startYear&ndash;$thisYear";
} else {
	$copyright = $startYear;
}
?>

<div id="footer">
	<p id="copyright" class="reset pull_out padding" role="contentinfo"><a href="">© <?php echo $copyright; ?> Perfumer</a></p>
     
</div>

</body>
</html>


