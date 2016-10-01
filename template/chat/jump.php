<?php include ('header.php');?>
<div class="container">
    <div class="alert alert-warning">正在跳转至：<a href="<?php echo $url; ?>"><?php echo $url; ?></a></div>
</div> <!-- /container -->
<script type="text/javascript">
    window.location.href= '<?php echo $url;?>';
</script>
<?php include ('footer.php');?>