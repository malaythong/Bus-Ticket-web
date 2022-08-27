<?php
    require 'assets/partials/_functions.php';
    $conn = db_connect();    

    if(!$conn) 
        die("Connection Failed");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ຈ່ອງປີ້ລົດເມ</title>
    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500&display=swap" rel="stylesheet">
    <!-- Font-awesome -->
    <script src="https://kit.fontawesome.com/d8cfbe84b9.js" crossorigin="anonymous"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <!-- CSS -->
    <?php 
        require 'assets/styles/styles.php'
    ?>
</head>
<body>
    <?php
    
    if(isset($_GET["booking_added"]) && !isset($_POST['pnr-search']))
    {
        if($_GET["booking_added"])
        {
            echo '<div class="my-0 alert alert-success alert-dismissible fade show" role="alert">
                <strong>ສຳເລັດ!</strong> ເພີ່ມການຈອງແລ້ວ, ລະຫັດປີ້ ຂອງທ່ານແມ່ນ <span style="font-weight:bold; color: #272640;">'. $_GET["pnr"] .'</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        }
        else{
            // Show error alert
            echo '<div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
            <strong>ລົ້ມເຫຼວ!</strong> ການຈ່ອງບໍ່ສຳເລັດ
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
    }

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["pnr-search"]))
    {
        $pnr = $_POST["pnr"];

        $sql = "SELECT * FROM bookings WHERE booking_id='$pnr'";
        $result = mysqli_query($conn, $sql);

        $num = mysqli_num_rows($result);

        if($num)
        {
            $row = mysqli_fetch_assoc($result);
            $route_id = $row["route_id"];
            $customer_id = $row["customer_id"];
            
            $customer_name = get_from_table($conn, "customers", "customer_id", $customer_id, "customer_name");

            $customer_phone = get_from_table($conn, "customers", "customer_id", $customer_id, "customer_phone");

            $customer_route = $row["customer_route"];
            $booked_amount = $row["booked_amount"];

            $booked_seat = $row["booked_seat"];
            $booked_timing = $row["booking_created"];

            $dep_date = get_from_table($conn, "routes", "route_id", $route_id, "route_dep_date");

            $dep_time = get_from_table($conn, "routes", "route_id", $route_id, "route_dep_time");

            $bus_no = get_from_table($conn, "routes", "route_id", $route_id, "bus_no");
            ?>

            <div class="alert alert-dark alert-dismissible fade show" role="alert">
            
            <h4 class="alert-heading">ຂໍ້ມູນການຈ່ອງ!</h4>
            <p>
                <button class="btn btn-sm btn-success"><a href="assets/partials/_download.php?pnr=<?php echo $pnr; ?>" class="link-light">ດາວໂຫຼດ</a></button>
                <button class="btn btn-danger btn-sm" id="deleteBooking" data-bs-toggle="modal" data-bs-target="#deleteModal" data-pnr="<?php echo $pnr;?>" data-seat="<?php echo $booked_seat;?>" data-bus="<?php echo $bus_no; ?>">
                    ລຶບ
                </button>
            </p>
            <hr>
                <p class="mb-0">
                    <ul class="pnr-details">
                        <li>
                            <strong>ລະຫັດປີ້ : </strong>
                            <?php echo $pnr; ?>
                        </li>
                        <li>
                            <strong>ຊື່ຜູ້ໂດຍສານ : </strong>
                            <?php echo $customer_name; ?>
                        </li>
                        <li>
                            <strong>ເບີໂທຜູ້ໂດຍສານ : </strong>
                            <?php echo $customer_phone; ?>
                        </li>
                        <li>
                            <strong>ເສັ້ນທາງ : </strong>
                            <?php echo $customer_route; ?>
                        </li>
                        <li>
                            <strong>ທະບຽນລົດເມ : </strong>
                            <?php echo $bus_no; ?>
                        </li>
                        <li>
                            <strong>ເລກຕັ່ງ : </strong>
                            <?php echo $booked_seat; ?>
                        </li>
                        <li>
                            <strong>ວັນທີເດີນອອກລົດ : </strong>
                            <?php echo $dep_date; ?>
                        </li>
                        <li>
                            <strong>ເວລາອອກລົດ : </strong>
                            <?php echo $dep_time; ?>
                        </li>
                        <li>
                            <strong>ເວລາຈ່ອງ : </strong>
                            <?php echo $booked_timing; ?>
                        </li>

                </p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php }
        else{
            echo '<div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
                <strong>ລົ້ມເຫຼວ!</strong> ບໍ່ມີຂໍ້ມູນ
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        }
        
    ?>   
    
    <?php 

    if (isset($_POST['submit'])) {
        $guest_name = ['name'];
        $guest_email = ['email'];
        $guest_mss = ['messenge'];

        $mailTo = "malaithong861@gmail.com";
        $headers = "From: ".$guest_email;
        $txt = "You have recieved an email from ".$guest_name.".\n\n".$guest_email;

        mail($mailTo, $txt, $headers);
        header("Location: index.php?mailsend");
    }
}


        // Delete Booking
        if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["deleteBtn"]))
        {
            $pnr = $_POST["id"];
            $bus_no = $_POST["bus"];
            $booked_seat = $_POST["booked_seat"];

            $deleteSql = "DELETE FROM `bookings` WHERE `bookings`.`booking_id` = '$pnr'";

                $deleteResult = mysqli_query($conn, $deleteSql);
                $rowsAffected = mysqli_affected_rows($conn);
                $messageStatus = "danger";
                $messageInfo = "";
                $messageHeading = "ລົ້ມເຫຼວ!";

                if(!$rowsAffected)
                {
                    $messageInfo = "ບໍ່ມີຂໍ້ມູນ";
                }

                elseif($deleteResult)
                {   
                    $messageStatus = "success";
                    $messageInfo = "ຂໍ້ມູນການຈ່ອງຖືກລຶບແລ້ວ";
                    $messageHeading = "ສຳເລັດ!";

                    // Update the Seats table
                    $seats = get_from_table($conn, "seats", "bus_no", $bus_no, "seat_booked");

                    // Extract the seat no. that needs to be deleted
                    $booked_seat = $_POST["booked_seat"];

                    $seats = explode(",", $seats);
                    $idx = array_search($booked_seat, $seats);
                    array_splice($seats,$idx,1);
                    $seats = implode(",", $seats);

                    $updateSeatSql = "UPDATE `seats` SET `seat_booked` = '$seats' WHERE `seats`.`bus_no` = '$bus_no';";
                    mysqli_query($conn, $updateSeatSql);
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
    ?>

    
    <header>
        <nav>
            <div>
                    <a href="#" class="nav-item nav-logo">ຄິວລົດເມຈຳປາ</a>
                    <!-- <a href="#" class="nav-item">Gallery</a> -->
            </div>
                
            <ul>
                <li><a href="#" class="nav-item">ໜ້າຫຼັກ</a></li>
                <li><a href="#about" class="nav-item">ກ່ຽວກັບ</a></li>
                <li><a href="#contact" class="nav-item">ຕິດຕໍ່</a></li>
            </ul>
            <div>
                <a href="#" class="login nav-item" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="fas fa-sign-in-alt" style="margin-right: 0.4rem;"></i>ເຂົ້າສູ່ລະບົບ</a>
                <a href="#pnr-enquiry" class="pnr nav-item">ຄົ້ນຫາການຈ່ອງ</a>
            </div>
        </nav>
    </header>
    <!-- Login Modal -->
    <?php require 'assets/partials/_loginModal.php'; 
        require 'assets/partials/_getJSON.php';

        $routeData = json_decode($routeJson);
        $busData = json_decode($busJson);
        $customerData = json_decode($customerJson);
    ?>
    

    <section id="home">
        <div id="route-search-form">
            <h1>ລະບົບການຈ່ອງປີ້ລົດເມ</h1>

            <p class="text-center">ຍິນດີຕ້ອນຮັບສູ່ລະບົບການຈອງປີ້ລົດເມແບບງ່າຍດາຍ. ເຂົ້າສູ່ລະບົບດຽວນີ້ເພື່ອຈັດການປີ້ລົດເມ ແລະ ຟັງຊັນອື່ນໆອີກ ຫຼື ພຽງແຕ່ເລື່ອນລົງ ເພື່ອກວດເບິ່ງຂໍ້ມູນປີ້ໂດຍສານທີ່ຈ່ອງໄວ້ (ລະຫັດປີ້ລົດເມ)</p>

            <center>
                <button class="btn btn-danger " data-bs-toggle="modal" data-bs-target="#loginModal">ເຂົ້າສູ່່ລະບົບເພື່ອຈັດການຂໍ້ມູນ</button>
                
            </center>

            <br>
            <center>
            <a href="#pnr-enquiry"><button class="btn btn-primary">ເລື່ອນລົງ <i class="fa fa-arrow-down"></i></button></a>
            </center>
            
        </div>
    </section>
    <div id="block">
        <section id="info-num">
            <figure>
                <img src="assets/img/route.svg" alt="Bus Route Icon" width="100px" height="100px">
                <figcaption>
                    <span class="num counter" data-target="<?php echo count($routeData); ?>">999</span>
                    <span class="icon-name">ເສັ້ນທາງໂດຍສານ</span>
                </figcaption>
            </figure>
            <figure>
                <img src="assets/img/bus.svg" alt="Bus Icon" width="100px" height="100px">
                <figcaption>
                    <span class="num counter" data-target="<?php echo count($busData); ?>">999</span>
                    <span class="icon-name">ລົດເມ</span>
                </figcaption>
            </figure>
            <figure>
                <img src="assets/img/customer.svg" alt="Happy Customer Icon" width="100px" height="100px">
                <figcaption>
                    <span class="num counter" data-target="<?php echo count($customerData); ?>">999</span>
                    <span class="icon-name">ລູກຄ້າທີ່ໃຊ້ບໍລິການ</span>
                </figcaption>
            </figure>
            <figure>
                <img src="assets/img/ticket.svg" alt="Instant Ticket Icon" width="100px" height="100px">
                <figcaption>
                    <span class="num"><span class="counter" data-target="20">999</span> ວິນາທີ</span> 
                    <span class="icon-name">ໃນການກວດສອບປີ້ລົດ</span>
                </figcaption>
            </figure>
        </section>
        <section id="pnr-enquiry">
            <div id="pnr-form">
                <h2>ປ້ອນລະຫັດປີ້ລົດເມ ເພື່ອເບິ່ງຂໍ້ມູນ</h2>
                <form action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="POST">
                    <div>
                        <input type="text" name="pnr" id="pnr" placeholder="ລະຫັດປີ້ລົດເມ">
                    </div>
                    <button type="submit" name="pnr-search">ກວດສອບ</button>
                </form>
            </div>
        </section>

        <section id="about">
            <div>
                <h1>About Us</h1>
                <h4>Wanna know were it all started?</h4>
                <p>
                    Lorem ipsum dolor sit amet consecteturadipisicing elit. Perferendis soluta voluptas eaque, numquam veritatis aperiam expedita deleniti, nesciunt cum alias velit. Cupiditate commodi
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Accusamus cum nisi ea optio unde aliquam quia reprehenderit atque eum tenetur! 
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Sed placeat debitis corporis voluptates modi quibusdam quidem voluptatibus illum, maiores sequi.
                </p>
            </div>
        </section>
        <section id="contact">
            <div id="contact-form">
                <h1>ຕິດຕໍ່ພວກເຮົາ</h1>
                <form action="">
                    <div>
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name">
                    </div>
                    <div>
                        <label for="email">Email Address</label>
                        <input type="email" name="email" id="email">
                    </div>
                    <div>
                        <label for="message">Message</label>
                        <textarea name="message" id="message" cols="30" rows="10"></textarea>  
                    </div>
                    <br><br>
                    <input type="submit" name="submit" value="Submit">    

                </form>
            </div>
        </section>
        <footer>
        <p>
                        <i class="far fa-copyright"></i> <?php echo date('Y');?> - ລະບົບຈ່ອງປີ້ລົດເມ | ໂດຍ ນ ມະໄລທອງ ແລະ ນ ວຽງ 3CS1
                        </p>
        </footer>
    </div>
    
    <!-- Delete Booking Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-exclamation-circle"></i></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <h2 class="text-center pb-4">
                    ທ່ານໝັ້ນໃຈບໍ?
            </h2>
            <p>
                ທ່ານຕ້ອງການລຶບຂໍ້ມູນການຈ່ອງບໍ? <strong>ຂະບວນການບໍ່ສຳເລັດ.</strong>
            </p>
            <!-- Needed to pass pnr -->
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" id="delete-form"  method="POST">
                    <input id="delete-id" type="hidden" name="id">
                    <input id="delete-booked-seat" type="hidden" name="booked_seat">
                    <input id="delete-booked-bus" type="hidden" name="bus">
            </form>
      </div>
      <div class="modal-footer">
        <button type="submit" form="delete-form" class="btn btn-primary btn-danger" name="deleteBtn">ລຶບ</button>
      </div>
    </div>
  </div>
</div>
     <!-- Option 1: Bootstrap Bundle with Popper -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <!-- External JS -->
    <script src="assets/scripts/main.js"></script>
</body>
</html>