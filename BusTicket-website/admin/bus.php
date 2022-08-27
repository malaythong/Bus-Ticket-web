<!-- Show these admin pages only when the admin is logged in -->
<?php  require '../assets/partials/_admin-check.php';   ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ລົດເມ</title>
        <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/d8cfbe84b9.js" crossorigin="anonymous"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <!-- CSS -->
    <?php 
        require '../assets/styles/admin.php';
        require '../assets/styles/admin-options.php';
        $page="bus";
    ?>
</head>
<body>
    <!-- Requiring the admin header files -->
    <?php require '../assets/partials/_admin-header.php';?>

    <!-- Add, Edit and Delete Buses -->
    <?php
        /*
            1. Check if an admin is logged in
            2. Check if the request method is POST
        */
        if($loggedIn && $_SERVER["REQUEST_METHOD"] == "POST")
        {
            if(isset($_POST["submit"]))
            {
                /*
                    ADDING BUSES
                 Check if the $_POST key 'submit' exists
                */
                // Should be validated client-side
                $busno = $_POST["busnumber"];
        
                $bus_exists = exist_buses($conn,$busno);
                $bus_added = false;
        
                if(!$bus_exists)
                {
                    // Route is unique, proceed
                    $sql = "INSERT INTO `buses` (`bus_no`, `bus_created`) VALUES ('$busno', current_timestamp());";

                    $result = mysqli_query($conn, $sql);
                    //$rowsAffected = mysqli_affected_rows($conn);
                    if($result)
                        $bus_added = true;
                }
    
                if($bus_added)
                {
                    // Show success alert
                    echo '<div class="my-0 alert alert-success alert-dismissible fade show" role="alert">
                    <strong>ສຳເລັດ!</strong> ຂໍ້ມູນລົດເມໄດ້ບັນທຶກແລ້ວ
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                    // Add the bus to seats table
                    $seatSql = "INSERT INTO `seats` (`bus_no`) VALUES ('$busno');";
                    $result = mysqli_query($conn, $seatSql);
                }
                else{
                    
                    // Show error alert
                    echo '<div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>ຫຼົ້ມເຫຼວ!</strong> ລົດເມບັນທຶກບໍ່ສຳເລັດ
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                }
            }
            if(isset($_POST["edit"]))
            {
                // EDIT ROUTES
                $busno = strtoupper($_POST["busno"]);
                $id = $_POST["id"];
                $id_if_bus_exists = exist_buses($conn, $busno);
                
                if(!$id_if_bus_exists || $id == $id_if_bus_exists)
                {
                    $updateSql = "UPDATE `buses` SET `bus_no` = '$busno' WHERE `buses`.`id` = $id;";
    
                    $updateResult = mysqli_query($conn, $updateSql);
                    $rowsAffected = mysqli_affected_rows($conn);
                    
                    $messageStatus = "danger";
                    $messageInfo = "";
                    $messageHeading = "ບໍ່ສຳເລັດ!";

                    if(!$rowsAffected)
                    {
                        $messageInfo = "ແກ້ໄຂບໍ່ສຳເລັດ!";
                    }
    
                    elseif($updateResult)
                    {
                        // Show success alert
                        $messageStatus = "success";
                        $messageHeading = "ສຳເລັດ!";
                        $messageInfo = "ຂໍ້ມູນບົດເມແກ້ໄຂສຳເລັດແລ້ວ";
                    }
                    else{
                        // Show error alert
                        $messageInfo = "ການຮ້ອງຂໍຂອງທ່ານບໍ່ສາມາດດໍາເນີນການໄດ້ເນື່ອງຈາກບັນຫາດ້ານເຕັກນິກຂັດຂ້ອງ. ພວກເຮົາເສຍໃຈກັບຄວາມບໍ່ສະດວກທີ່ເກີດຂຶ້ນ";
                    }
                    
                    // MESSAGE
                    echo '<div class="my-0 alert alert-'.$messageStatus.' alert-dismissible fade show" role="alert">
                    <strong>'.$messageHeading.'</strong> '.$messageInfo.'
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                }
                else 
                {
                    // If bus details already exists
                    echo '<div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>ຫຼົ້ມເຫຼວ!</strong> ລົດເມບັນທຶກບໍ່ສຳເລັດ
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                }

            }
            if(isset($_POST["delete"]))
            {
                // DELETE BUS
                $id = $_POST["id"];
                $bus_no = get_from_table($conn, "buses", "id", $id, "bus_no");
                // Delete the bus with id => id
                $deleteSql = "DELETE FROM `buses` WHERE `buses`.`id` = $id";

                $deleteResult = mysqli_query($conn, $deleteSql);
                $rowsAffected = mysqli_affected_rows($conn);
                $messageStatus = "danger";
                $messageInfo = "";
                $messageHeading = "ຫຼົ້ມເຫຼວ!";

                if(!$rowsAffected)
                {
                    $messageInfo = "ບໍ່ພົບຂໍ້ມູນ";
                }

                elseif($deleteResult)
                {   
                    // echo $num;
                    // Show success alert
                    $messageStatus = "success";
                    $messageInfo = "ຂໍ້ມູນລົດເມໄດ້ຖືກລຶບແລ້ວ";
                    $messageHeading = "ສຳເລັດ!";

                    // Delete Bus from Seat table
                    $sql = "DELETE from seats WHERE bus_no='$bus_no'";
                    mysqli_query($conn,$sql);
                }
                else{
                    // Show error alert
                    $messageInfo = "ການຮ້ອງຂໍຂອງທ່ານບໍ່ສາມາດດໍາເນີນການໄດ້ເນື່ອງຈາກບັນຫາດ້ານເຕັກນິກຂັດຂ້ອງ. ພວກເຮົາເສຍໃຈກັບຄວາມບໍ່ສະດວກທີ່ເກີດຂຶ້ນ";
                }
                // Message
                echo '<div class="my-0 alert alert-'.$messageStatus.' alert-dismissible fade show" role="alert">
                <strong>'.$messageHeading.'</strong> '.$messageInfo.'
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }
        }
        ?>
        <?php
            $resultSql = "SELECT * FROM `buses` ORDER BY bus_created DESC";
                            
            $resultSqlResult = mysqli_query($conn, $resultSql);

            if(!mysqli_num_rows($resultSqlResult)){ ?>
                <!-- Buses are not present -->
                <div class="container mt-4">
                    <div id="noCustomers" class="alert alert-dark " role="alert">
                        <h1 class="alert-heading">ບໍ່ພົບລົດເມ!!</h1>
                        <p class="fw-light">ເພີ່ມເປັນຄົນທຳອິດ!</p>
                        <hr>
                        <div id="addCustomerAlert" class="alert alert-success" role="alert">
                                Click on <button id="add-button" class="button btn-sm"type="button"data-bs-toggle="modal" data-bs-target="#addModal">ADD <i class="fas fa-plus"></i></button> to add a bus!
                        </div>
                    </div>
                </div>
            <?php }
            else { ?>             
            <!-- If Buses are present -->
            <section id="bus">
                <div id="head">
                    <h4>ສະຖານີລົດເມ</h4>
                </div>
                <div id="bus-results">
                    <div>
                        <button id="add-button" class="button btn-sm" type="button"data-bs-toggle="modal" data-bs-target="#addModal">ເພີ່ມຂໍ້ມູນລົດເມ <i class="fas fa-plus"></i></button>
                    </div>
                    
                    <table class="table table-hover table-bordered">
                        <thead>
                            <th>ລຳດັບ</th>
                            <th>ທະບຽນລົດເມ</th>
                            <th>ແກ້ໄຂຂໍ້ມູນ</th>
                        </thead>
                        <?php
                            $ser_no = 0;
                            while($row = mysqli_fetch_assoc($resultSqlResult))
                            {   
                                $ser_no++;
                                // echo "<pre>";
                                // var_export($row);
                                // echo "</pre>";

                                $id = $row["id"];
                                $busno = $row["bus_no"]; 
                        ?>
                        <tr>
                            <td>
                                <?php
                                    echo $ser_no;
                                ?>
                            </td>
                            <td>
                                <?php
                                    echo $busno;
                                ?>
                            </td>
                            <td>
                            <button class="button edit-button " data-link="<?php echo $_SERVER['REQUEST_URI']; ?>" data-id="<?php 
                                                echo $id;?>" data-busno="<?php 
                                                echo $busno;?>"
                                                >ແກ້ໄຂ</button>
                                            <button class="button delete-button" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?php 
                                                echo $id;?>">ລຶບ</button>
                            </td>
                        </tr>
                        <?php 
                        }
                    ?>
                    </table>
                </div>
            </section>
            <?php } ?> 
        </div>
    </main>
    <!-- All Modals Here -->
    <!-- Add Buses Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">ເພີ່ມລົດເມ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addBusForm" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
                            <div class="mb-3">
                                <label for="busnumber" class="form-label">ເພີ່ມທະບຽນລົດເມ</label><br>

                                </span>
                                <input type="text" class="form-control" id="busnumber" name="busnumber" required>
                                <span id="error" class="error">
                            </div>
                            <button type="submit" class="btn btn-success" name="submit">ບັນທຶກ</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <!-- Add Anything -->
                    </div>
                    </div>
                </div>
        </div>
        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-exclamation-circle"></i></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h2 class="text-center pb-4">
                    ໝັ້້ນໃຈບໍ?
                </h2>
                <p>
                    ທ່ານຕ້ອງການລຶບຂໍ້ມູນລົດເມບໍ? <strong>ຂະບວນການບໍ່ສຳເລັດ.</strong>
                </p>
                <!-- Needed to pass id -->
                <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="delete-form"  method="POST">
                    <input id="delete-id" type="hidden" name="id">
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ຍົກເລີກ</button>
                <button type="submit" form="delete-form" name="delete" class="btn btn-danger">ລຶບ</button>
            </div>
            </div>
        </div>
    </div>
    <!-- External JS -->
    <script src="../assets/scripts/admin_bus.js"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>