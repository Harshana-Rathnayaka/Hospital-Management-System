<?php

session_start();
require_once '../includes/dbOperations.php';

$response = array();

if (isset($_POST['btnUploadReport'])) {

    if (isset($_POST['lab_test_id']) && isset($_FILES["lab_report"])) {

        $lab_test_id = $_POST['lab_test_id'];

        $lab_report_name = $_FILES["lab_report"]["name"];
        $lab_report_temp = $_FILES["lab_report"]["tmp_name"];
        $lab_report_size = $_FILES["lab_report"]["size"];

        // checking if the file size is greater than 8mb
        if ($lab_report_size < 8388608) {

            $extension = strtolower(substr($lab_report_name, strpos($lab_report_name, '.') + 1));
            $lab_report_no = rand();
            $report_link = $lab_report_no . "." . $extension;

            if ($extension != 'pdf' && $extension != 'PDF') {

                // not a pdf
                $_SESSION['error'] = "You can select PDF files only!";
                $response['error'] = true;
                $response['message'] = "You can select PDF files only";
                header("location:../staff/ongoing-tests.php");

            } else {

                // saving the file to the folder
                if (move_uploaded_file($lab_report_temp, "../lab-reports/" . $report_link)) {

                    // we can operate the data further
                    $db = new DbOperations();

                    // uploading the file location to the db
                    $result = $db->uploadTheLabReport($lab_test_id, $report_link);

                    if ($result == 0) {

                        $result2 = $db->completeLabReport($lab_test_id);

                        if ($result2 == 0) {

                            // success
                            $_SESSION['success'] = "Lab Report uploaded successfully!";
                            $response['error'] = false;
                            $response['message'] = "Prescription uploaded successfully";
                            header("location:../staff/ongoing-tests.php");

                        } elseif ($result2 == 1) {

                            // some error
                            $_SESSION['error'] = "Something went wrong, Could not mark the lab test as complete. Please try again!";
                            $response['error'] = true;
                            $response['message'] = "Some error occured, please try again";
                            header("location:../staff/ongoing-tests.php");

                        }

                    } elseif ($result == 1) {

                        // some error
                        $_SESSION['error'] = "Something went wrong, Could not upload the report location. Please try again!";
                        $response['error'] = true;
                        $response['message'] = "Some error occured, please try again";
                        header("location:../staff/ongoing-tests.php");

                    }

                } else {

                    // some error
                    $_SESSION['error'] = "Something went wrong, Could not move the report to the folder. Please try again!";
                    $response['error'] = true;
                    $response['message'] = "Some error occured, please try again";
                    header("location:../staff/ongoing-tests.php");

                }
            }
        } else {

            // some error
            $_SESSION['error'] = "The file you selected exceeds the maximum file size allowed!";
            $response['error'] = true;
            $response['message'] = "The file you selected exceeds the maximum file size allowed";
            header("location:../staff/ongoing-tests.php");

        }

    } else {

        // missing fields
        $_SESSION['missing'] = "Required fields are missing.";
        $response['error'] = true;
        $response['message'] = "Required fields are missing";
        header("location:../staff/ongoing-tests.php");

    }
} else {

    // invalid button
    $_SESSION['missing'] = "Wrong button click.";
    $response['error'] = true;
    $response['message'] = "Wrong button click";
    header("location:../staff/ongoing-tests.php");

}

// json output
// echo json_encode($response);
