<!-- Show these admin pages only when the admin is logged in -->
<?php  require '../assets/partials/_admin-check.php';   ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ລູກຄ້າ</title>
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
        $page="customer";
    ?>
</head>
<body>
    <!-- Requiring the admin header files -->
    <?php require '../assets/partials/_admin-header.php';?>

    <!-- Add, Edit and Delete Customers -->
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
                    ADDING Customers
                 Check if the $_POST key 'submit' exists
                */
                // Should be validated client-side
                $cname = $_POST["cfirstname"] . " " . $_POST["clastname"];
                $cphone = $_POST["cphone"];
        
                $customer_exists = exist_customers($conn,$cname,$cphone);
                $customer_added = false;
        
                if(!$customer_exists)
                {
                    // Route is unique, proceed
                    $sql = "INSERT INTO `customers` (`customer_name`, `customer_phone`, `customer_created`) VALUES ('$cname', '$cphone', current_timestamp());";
                    $result = mysqli_query($conn, $sql);
                    // Gives back the Auto Increment id
                    $autoInc_id = mysqli_insert_id($conn);
                    // If the id exists then, 
                    if($autoInc_id)
                    {
                        $code = rand(1,99999);
                        // Generates the unique userid
                        $customer_id = "CUST-".$code.$autoInc_id;
                        
                        $query = "UPDATE `customers` SET `customer_id` = '$customer_id' WHERE `customers`.`id` = $autoInc_id;";
                        $queryResult = mysqli_query($conn, $query);

                        if(!$queryResult)
                            echo "Not Working";
                    }

                    if($result)
                        $customer_added = true;
                }
    
                if($customer_added)
                {
                    // Show success alert
                    echo '<div class="my-0 alert alert-success alert-dismissible fade show" role="alert">
                    <strong>ສຳເລັດ!</strong> ຂໍ້ມູນລູກຄ້າບັນທຶກສຳເລັດແລ້ວ
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                }
                else{
                    // Show error alert
                    echo '<div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>ຫຼົ້ມເຫຼວ!</strong> ຂໍ້ມູນລູກຄ້າບັນທຶກບໍ່ສຳເລັດແລ້ວ
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                }
            }
            if(isset($_POST["edit"]))
            {
                // EDIT ROUTES
                $cname = $_POST["cname"];
                $cphone = $_POST["cphone"];
                $id = $_POST["id"];
                $id_if_customer_exists = exist_customers($conn,$cname,$cphone);

                if(!$id_if_customer_exists || $id == $id_if_customer_exists)
                {
                    $updateSql = "UPDATE `customers` SET
                    `customer_name` = '$cname',
                    `customer_phone` = '$cphone' WHERE `customers`.`customer_id` = '$id';";

                    $updateResult = mysqli_query($conn, $updateSql);
                    $rowsAffected = mysqli_affected_rows($conn);
    
                    $messageStatus = "danger";
                    $messageInfo = "";
                    $messageHeading = "ຫຼົ້ມເຫຼວ!";
    
                    if(!$rowsAffected)
                    {
                        $messageInfo = "ແກ້ໄຂບໍ່ສຳເລັດ!";
                    }
    
                    elseif($updateResult)
                    {
                        // Show success alert
                        $messageStatus = "success";
                        $messageHeading = "ສຳເລັດ!";
                        $messageInfo = "ຂໍ້ມູນລູກຄ້າແກ້ໄຂສຳເລັດແລ້ວ";
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
                else{
                    // If customer details already exists
                    echo '<div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>ຫຼົ້ມເຫຼວ!</strong> ບໍ່ພົບຂໍ້ມູນລູກຄ້າ
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                }

            }
            if(isset($_POST["delete"]))
            {
                // DELETE ROUTES
                $id = $_POST["id"];
                // Delete the route with id => id
                $deleteSql = "DELETE FROM `customers` WHERE `customers`.`id` = $id";

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
                    $messageStatus = "success";
                    $messageInfo = "ຂໍ້ມູນລູກຄ້າໄດ້ຖືກລຶບແລ້ວ";
                    $messageHeading = "ສຳເລັດ!";
                }
                else{

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
            $resultSql = "SELECT * FROM `customers` ORDER BY customer_created DESC";
                            
            $resultSqlResult = mysqli_query($conn, $resultSql);

            if(!mysqli_num_rows($resultSqlResult)){ ?>
                <!-- Customers are not present -->
                <div class="container mt-4">
                    <div id="noCustomers" class="alert alert-dark " role="alert">
                        <h1 class="alert-heading">ບໍ່ພົບຂໍ້ມູນລູກຄ້າ!!</h1>
                        <p class="fw-light">ເພີ່ມເປັນຄົນທຳອິດ!</p>
                        <hr>
                        <div id="addCustomerAlert" class="alert alert-success" role="alert">
                                ຄລິກ <button id="add-button" class="button btn-sm"type="button"data-bs-toggle="modal" data-bs-target="#addModal">ເພີ່ມ <i class="fas fa-plus"></i></button> ເພື່ອເພີ່ມຂໍຂ້ມູນລູກຄ້າ!
                        </div>
                    </div>
                </div>
            <?php }
            else { ?>   
            <!-- If Customers are present -->
            <section id="customer">
                <div id="head">
                    <h4>ຂໍ້ມູນລູກຄ້າ</h4>
                </div>
                <div id="customer-results">
                    <div>
                        <button id="add-button" class="button btn-sm"type="button"data-bs-toggle="modal" data-bs-target="#addModal">ເພີ່ມຂໍ້ມູນລູກຄ້າ <i class="fas fa-plus"></i></button>
                    </div>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <th>ລະຫັດລູກຄ້າ</th>
                            <th>ຊື່ລູກຄ້າ</th>
                            <th>ເບີໂທ</th>
                            <th>ແກ້ໄຂຂໍ້ມູນ</th>
                        </thead>
                        <?php
                            while($row = mysqli_fetch_assoc($resultSqlResult))
                            {
                                    // echo "<pre>";
                                    // var_export($row);
                                    // echo "</pre>";
                                $id = $row["id"];
                                $customer_id = $row["customer_id"];
                                $customer_name = $row["customer_name"];
                                $customer_phone = $row["customer_phone"]; 
                        ?>
                        <tr>
                            <td>
                                <?php
                                    echo $customer_id;
                                ?>
                            </td>
                            <td>
                                <?php
                                    echo $customer_name;
                                ?>
                            </td>
                            <td>
                                <?php
                                    echo $customer_phone;
                                ?>
                            </td>
                            <td>
                            <button class="button edit-button " data-link="<?php echo $_SERVER['REQUEST_URI']; ?>" data-id="<?php 
                                                echo $customer_id;?>" data-name="<?php 
                                                echo $customer_name;?>" data-phone="<?php 
                                                echo $customer_phone;?>"
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
    <!-- Add Route Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">ເພີ່ມຂໍ້ມູນລູກຄ້າ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addCustomerForm" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
                            <div class="mb-3">
                                <label for="cfirstname" class="form-label">ຊື່ລູກຄ້າ</label>
                                <input type="text" class="form-control" id="cfirstname" name="cfirstname">
                            </div>
                            <div class="mb-3">
                                <label for="clastname" class="form-label">ນາມສະກຸນລູກຄ້າ</label>
                                <input type="text" class="form-control" id="clastname" name="clastname">
                            </div>
                            <div class="mb-3">
                                <label for="cphone" class="form-label">ເບີໂທ</label>
                                <input type="tel" class="form-control" id="cphone" name="cphone">
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
                    ໝັ້ນໃຈບໍ?
                </h2>
                <p>
                    ທ່ານຕ້ອງການລຶບຂໍ້ມູນລູກຄ້າບໍ? <strong>ຂະບວນການບໍ່ສຳເລັດ.</strong>
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
    <script src="../assets/scripts/admin_customer.js"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>