<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<footer>
    <div class="container">
        <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
    </div>
</footer>
<script src="<?php echo site_url('assets/js/jquery-2.2.2.min.js');?>"></script>
<script src="<?php echo site_url('assets/js/bootstrap.min.js');?>"></script>
<script src="<?php echo site_url('assets/js/jquery.knob.js');?>"></script>
<script src="<?php echo site_url('assets/js/bootstrap.datepicker.js');?>"></script>
<?php echo $before_closing_body;?>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
        $('.datepick').datepicker({
            format: "dd-mm-yyyy",
            weekStart: 1,
            todayBtn: "linked",
            autoclose: true,
            todayHighlight: true,
            orientation: "bottom left",
            clearBtn: true
        });
    })
</script>
</body>
</html>