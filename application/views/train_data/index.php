<div class="container" id="main">

    <h1>Training data by Admin</h1>

                    
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
                <input type="submit" name="submit_dealership_excel" class="btn btn-default" value="&nbsp;&nbsp;Upload&nbsp;&nbsp;" style="float: right; margin: 0 10px 0 10px;" />
                <?php echo form_dropdown('class', $class_options, $selected_class, $class_style);?>
                <input type="file" name='userfile' style="margin: 0 10px 0 10px;" required/>
            </div>
          <?php echo "</form>"; ?>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Initial Training Params (Test data to estimate precision)</div>
        <div class="panel-body">
          <form method="post" action="" role="form">

                    <?php if(!empty(@$notif['initial_params'])){ ?>
                    <div class="alert alert-<?php echo @$notif['initial_params']['type'];?>">
                        <p><?php echo @$notif['initial_params']['message'];?></p>
                        <span></span>
                    </div>
                    <?php } ?>

              <div class="form-group  col-md-6">
                <label for="positive_portion label-default" style="text-align: left;">Positive portion for test (0~0.05):</label>
                <input type="decimal" class="form-control" required name='positive_portion' value="<?php echo isset($positive_portion) ? $positive_portion : 0;?>"></input>
              </div>

              <div class="form-group col-md-6">
                <label for="negative_portion label-default" style="text-align: left;">Negative portion for test (0~0.05):</label>
                <input type="decimal" class="form-control" required name='negative_portion' value="<?php echo isset($negative_portion) ? $negative_portion : 0;?>"></input>
              </div>

              <div>
                <div class="form-group col-md-2" style="margin-bottom: 0;">
                  <input type="submit" class="btn btn-default" name="submit_initial_params" value="&nbsp Save Portions&nbsp">
                </div>
                <div class="form-group col-md-10" style="margin-bottom: 0; text-align: right;">
                  <input type="submit" class="btn btn-default" name="submit_remove_trained_domains" value="&nbsp Remove already trained domains &nbsp">
                  <input type="submit" class="btn btn-default" name="submit_move_train2test" value="&nbsp Move training data into Test &nbsp">
                  <input type="submit" class="btn btn-default" name="submit_move_test2train" value="&nbsp Move test data to Training &nbsp">
                </div>
              </div>
            </form>
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">Added Data by Trainers</div>
        <div class="panel-body">
                    <?php if(!empty(@$notif['trainers_data'])){ ?>
                    <div class="alert alert-<?php echo @$notif['trainers_data']['type'];?>">
                        <p><?php echo @$notif['trainers_data']['message'];?></p>
                    </div>
                    <?php } ?>
          <div class="col-md-6">
            <label for="label-default" style="text-align: left;">Positive dealership count:</label>
            <input type="number" class="form-control" value="<?php echo isset($trainers_positive_count) ? $trainers_positive_count : 0;?>" readonly/>
          </div>
          <div class="col-md-6">
            <label for="label-default" style="text-align: left;">Negative dealership count:</label>
            <input type="number" class="form-control" value="<?php echo isset($trainers_negative_count) ? $trainers_negative_count : 0;?>" readonly/>
          </div>
          <div class="col-md-12" style="margin-top: 15px;">
            <form method="post" action="" role="form">
                  <input type="submit" name="submit_move_trainers_data" class="btn btn-default" value="&nbsp;&nbsp;Move trainers data to Main&nbsp;&nbsp;" />
            </form>
          </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Current Training Data Status</div>
        <div class="panel-body">

          <div class="col-md-3">
            <label for="label-default" style="text-align: left;">Positive dealership count:</label>
            <input type="number" class="form-control" value="<?php echo isset($status_positive_count) ? $status_positive_count : 0;?>" readonly/>
          </div>
          <div class="col-md-3">
            <label for="label-default" style="text-align: left;">Negative dealership count:</label>
            <input type="number" class="form-control" value="<?php echo isset($status_negative_count) ? $status_negative_count : 0;?>" readonly/>
          </div>
          <div class="col-md-6">
  
            <label for="label-default" style="text-align: left;">Test dealership count (positive, negative):</label>
  
            <div class="col-md-6">
              <input type="number" class="form-control" value="<?php echo isset($status_test_positive_count) ? $status_test_positive_count : 0;?>" readonly/>
            </div>
            <div class="col-md-6">
              <input type="number" class="form-control" value="<?php echo isset($status_test_negative_count) ? $status_test_negative_count : 0;?>" readonly/>
            </div>

          </div>

          <div class="col-md-12" style="margin-top: 15px;">
            <?php if($is_running_train){ ?>
              <div class="alert alert-warning" style="margin-bottom: 0">
                  <p>Training cron job is running now. Don't change anything !</p>
                  <span></span>
              </div>
            <?php } else { ?>
              <div class="alert alert-success" style="margin-bottom: 0">
                  <p>Training cron job is not running now. You can change anything !</p>
                  <span></span>
              </div>
            <?php } ?>
          </div>

        </div>
    </div>

</div>