

<?php
  // debug($forms);
?>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <a href="../../index2.html"><?php echo TITLE;?></a>
  </div>

  <div class="register-box-body">
    <p class="login-box-msg">Register a new membership</p>


    <form action="<?php echo base_url();?>/ajax/inserttable" method="post">
      <input hidden="hidden" name='veryCustom' value='registration'/>
      <input hidden="hidden" name='redirect' value='<?php echo base_url()."/register";?>'/>
      <input hidden="hidden" name='table' value='person'/>


      <?php
      $i=0;

      foreach($forms as $form){

        if($form->type == "select"){
            $input = "<select required='required' name='$form->name' class='form-control'>";
            foreach($form->options as $key=>$value){
              $input .= "<option value='$key'>
                $value
              </option>";
            }
            $input .= "</select>";
        }else if ($form->type == ""){

        }else{
            $input = "<input required='required' type='$form->type' class='form-control' placeholder='$form->placeholder' name ='$form->name'>";
        }

        echo "
        <div class='form-group has-feedback input-group'>
          <div class='input-group-btn'>
            <button  class=' btn mbackground-$i' type='button'>$form->placeholder</button>
          </div>
          $input


          <span class='glyphicon $form->icon form-control-feedback'></span>
        </div>

        ";
        $i++;
      }



      ?>
      <div class="row">

        <!-- /.col -->
        <div class="col-xs-12">
          <button type="submit" class="mCenter btn btn-primary btn-block btn-flat">Register</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
  </div>
  <!-- /.form-box -->
</div>
<!-- /.register-box -->

<!-- jQuery 3.1.1 -->
<script src="../../plugins/jQuery/jquery-3.1.1.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="../../plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
