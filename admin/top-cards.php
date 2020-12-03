<?php

require_once '../api/getLists.php';

?>


          <div class="row">
            <div class="col-sm-3 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h5>Doctors</h5>
                  <div class="row">
                    <div class="col-8 col-sm-12 col-xl-8 my-auto">
                      <div class="d-flex d-sm-block d-md-flex align-items-center">
                        <h2 class="mb-0"><?php echo $doctor_count; ?></h2>
                      </div>
                    </div>
                    <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                      <i class="icon-lg mdi mdi-medical-bag text-primary ml-auto"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-3 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h5>Nurses</h5>
                  <div class="row">
                    <div class="col-8 col-sm-12 col-xl-8 my-auto">
                      <div class="d-flex d-sm-block d-md-flex align-items-center">
                        <h2 class="mb-0"><?php echo $nurse_count; ?></h2>
                      </div>
                    </div>
                    <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                      <i class="icon-lg mdi mdi-hospital text-danger ml-auto"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-3 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h5>Staff</h5>
                  <div class="row">
                    <div class="col-8 col-sm-12 col-xl-8 my-auto">
                      <div class="d-flex d-sm-block d-md-flex align-items-center">
                        <h2 class="mb-0"><?php echo $staff_count; ?></h2>
                      </div>
                    </div>
                    <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                      <i class="icon-lg mdi mdi-hospital-building text-warning ml-auto"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-3 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h5>Patients</h5>
                  <div class="row">
                    <div class="col-8 col-sm-12 col-xl-8 my-auto">
                      <div class="d-flex d-sm-block d-md-flex align-items-center">
                        <h2 class="mb-0"><?php echo $patient_count; ?></h2>
                      </div>
                    </div>
                    <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                      <i class="icon-lg mdi mdi-pill text-success ml-auto"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
