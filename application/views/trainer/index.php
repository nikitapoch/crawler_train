<div class="container" id="main">

    <h1>Training data by trainers</h1>
                
    <div class="panel panel-default">
        <div class="panel-heading">Please add training data</div>
        <div class="panel-body">

                    <?php if(!empty(@$notif['dealership_add'])){ ?>
                    <div class="alert alert-<?php echo @$notif['dealership_add']['type'];?>">
                        <p><?php echo @$notif['dealership_add']['message'];?></p>
                        <span></span>
                    </div>
                    <?php } ?>

          <form method="post" action="" role="form">
            <div class="row">
              <div class="col-md-7">
                <input type="text" class="form-control" name='domain' placeholder="Please input domain to add" required>
              </div>
              <div class="col-md-3">
                <?php echo form_dropdown('class', $class_options, $selected_class, $class_style);?>
              </div>
              <div class="col-md-2" style="text-align: right;">
                <input type="submit" name="submit_dealership_add" class="btn btn-default" value="&nbsp;&nbsp;Add&nbsp;&nbsp;" />
              </div>
              
            </div>
          </form>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Current Data by Trainers</div>
        <div class="panel-body">

          <div class="col-md-6">
            <label for="label-default" style="text-align: left;">Positive dealership count:</label>
            <input type="number" class="form-control" value="<?php echo isset($status_positive_count) ? $status_positive_count : 0;?>" readonly/>
          </div>
          <div class="col-md-6">
            <label for="label-default" style="text-align: left;">Negative dealership count:</label>
            <input type="number" class="form-control" value="<?php echo isset($status_negative_count) ? $status_negative_count : 0;?>" readonly/>
          </div>
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">Please choose csv file to upload</div>
        <div class="panel-body">

                    <?php if(!empty(@$notif['dealership_csv'])){ ?>
                    <div class="alert alert-<?php echo @$notif['dealership_csv']['type'];?>">
                        <p><?php echo @$notif['dealership_csv']['message'];?></p>
                        <span></span>
                    </div>
                    <?php } ?>


          <?php echo form_open_multipart('');?>
          <!-- <form method="post" action="" role="form"> -->
            <div class="row">
                <input type="submit" name="submit_dealership_csv" class="btn btn-default" value="&nbsp;&nbsp;Upload&nbsp;&nbsp;" style="float: right; margin: 0 10px 0 10px;" />
                <?php echo form_dropdown('class', $class_options, $selected_class, $class_style);?>
                <input type="file" name='userfile' style="margin: 0 10px 0 10px;" required/>
            </div>
          <?php echo "</form>"; ?>
        </div>
    </div>
    
</div>