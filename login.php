<?php
session_start();
function ta($in)
{
    echo ('<pre class ="ta">');
    print_r($in);
    echo ' </pre>';
}
// $servername = "localhost";
// $username = "root";
// $password = "";
// $datenbank = "dbs8882846";

$servername = 'db5010488295.hosting-data.io';
$username = 'dbu1319524';
$password = "Strawberry09170917!";
$datenbank = "dbs8882846";
// Create connection
$conn = new mysqli($servername, $username, $password, $datenbank);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$_SESSION['time'] = date('d.m.Y H:i',time());
$_SESSION['ip']=getUserIP();
$_SESSION['normIP']= getVisIPAddr();
$stranger = true;
if (!isset($_SESSION['firstTime'])) {
  $_SESSION['firstTime']=1;
}
//echo ' user is : ' . $_POST['user'] . 'and pass is : ' . $_POST['pass'];
if (isset($_POST['user']) && (isset($_POST['pass']))) {
  $sql = 'SELECT  `NAME`, `EMAIL`, `PASSWORD` FROM `user_tb`';

  $result = $conn->query($sql)or die("the Query error is: ".$conn->error);
  //ta($result);
  foreach ($result as $row) {
    //ta($row);
    $email = $row['EMAIL'];
    $savedpw = $row['PASSWORD'];
    $name = $row['NAME'];
    //ta($row);
    if ($_POST['user'] == $email && $_POST['pass'] == $savedpw) {
      $_SESSION['loggedin'] = true;
      $_SESSION['mail'] = $row['EMAIL'];
      $_SESSION['pass'] = $row['PASSWORD'];
      $_SESSION['name'] = $row['NAME'];
      $_SESSION['user'] = $_POST['user'];
      //$_SERVER['REQUEST_TIME'] 
      $stranger = false;
      //echo ' hi im here ';
      header("Location: NanoCloud.php");
      exit();
      //echo "<script>window.top.location='https://nano-suits.com/NanoCloud.php'</script>";
    }else {
      $_SESSION['loggedin'] = false;
      $_SESSION['firstTime']=$_SESSION['firstTime']+1;
      $stranger = true;
    }
  }
  // while ($row = $result->fetch_object()) {

  //echo $_SESSION['firstTime'] . $stranger;

  // }
}
function getVisIpAddr() {
      
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      return $_SERVER['HTTP_CLIENT_IP'];
  }
  else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      return $_SERVER['HTTP_X_FORWARDED_FOR'];
  }
  else {
      return $_SERVER['REMOTE_ADDR'];
  }
}


function getUserIP()
{
  // Get real visitor IP behind CloudFlare network
  if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
  }
  $client  = @$_SERVER['HTTP_CLIENT_IP'];
  $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
  $remote  = $_SERVER['REMOTE_ADDR'];

  if (filter_var($client, FILTER_VALIDATE_IP)) {
    $ip = $client;
  } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
    $ip = $forward;
  } else {
    $ip = $remote;
  }

  return $ip;
}


?>



<!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />

  <title>Safe login</title>

  <!-- slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.carousel.min.css" />

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
  <link rel="icon" type="image/x-icon" href="images/favicon.ico">

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Roboto:400,500&display=swap" rel="stylesheet" />
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />
</head>

<body>
  <!-- header section strats -->
  <header class="header_section">
    <div class="container">
      <nav class="navbar navbar-expand-lg custom_nav-container ">
        <a class="navbar-brand" href="#">
          <div class="logo_box">
            <img src="images/logo.png" alt="" />
          </div>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto mr-2">
            <li class="nav-item active">
              <a class="nav-link" href="index.html">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="about.html">About Us </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="service.html">Services</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="platform.html">Platforms</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="blog.html">Blog</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="contact.html">Contact us</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="login.php">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="Pe/indexPe.html">فارسی</a>
            </li>
          </ul>
          <form class="form-inline my-2 my-lg-0">
            <input class="form-control nav_search-input mr-sm-2 d-none" type="search" placeholder="Search" aria-label="Search" value="" />
            <button class="btn  my-2 my-sm-0 nav_search-btn" type="submit"></button>
          </form>
        </div>
      </nav>
    </div>
  </header>
  <!-- end header section -->



  <!-- contact section -->
  <section class="contact_section layout_padding">
    <div class="container-fluid">
      <div class="row">
        <div class=" col-md-6">
          <div id="map" class="h-100 w-100 ">
              <?php
              $css = ' <style>
                          #map{
                            display: flex;
                            flex-direction: column;
                            justify-content: center;
                            align-items: center;
                            background-image: url("images/pexels-pixabay-60504.jpg");
                          }
                        </style>';
              $cssClear = '  <style>
                                #clear{
                                  display: flex;
                                  flex-direction: column;
                                  justify-content: center;
                                  align-items: center;
                                  flex-wrap: nowrap;
                                }
                              </style>';
              if ($stranger == true && $_SESSION['firstTime'] ==2) {
                echo '<div id="clear" style="color: red;">';
                echo '<p>by entering wrong User/pass u will agree that your IP will be saved</p>';
                echo '<p>با وارد کردن یوزر یا پسورد اشتباه آ پی شما ذخیره خواهد شد</p>';
                echo '</div>';
                echo ($cssClear);
              } else if (isset($_POST['user'])&&$_POST['user']!=null&&isset($_POST['pass'])&&$_POST['pass']!=null&&$stranger==true&&$_SESSION['firstTime']>=3) {
                
                  echo '<div style="color: aliceblue;">';
                  echo '<p>your log in has failed 3 times, ur IP '.$_SESSION['ip'].' will be saved in our log for security reasons</p> ';
                  echo '<p>. آ پی شما '.$_SESSION['normIP'].' برای بررسی ذخیره شد</p>'; // Output IP address [Ex: 177.87.193.134]
                  echo '</div>';
                  echo ($css);
                  $sql = 'INSERT INTO `attacker_login`( `IP`, `NORMIP`, `TIME`)
                          VALUES ("'.$_SESSION['ip'].'","'.$_SESSION['normIP'].'","'.$_SESSION['time'].'")';
                  $result = $conn->query($sql) or die("th Query error is: ".$conn->error);
                  

              } else {
                  echo '<div id="clear" style="color: black;">';
                  echo '<p>by entering wrong User/pass u will agree that your IP will be saved</p>';
                  echo '<p>با وارد کردن یوزر یا پسورد اشتباه آ پی شما ذخیره خواهد شد</p>';
                  echo '</div>';
                  echo ($cssClear);
              }   


                
                ?>
          </div>
        </div>
        <div class=" col-md-6 contact_form-container">
          <div class="contact_box">
            <?php
            echo '<p style="color: blue;">Employee login</p>';
            echo ' <form method="post" action="login.php">
                      <label for="user">user:</label><br>
                      <input type="text" id="user" name="user" placeholder="user"><br>
                      <label for="pass">pass:</label><br>
                      <input type="text" id="pass" name="pass" placeholder="pass"><br><br>
                      <input type="submit" value="Submit">
                    </form> ';
            ?>
          </div>
          </form>
        </div>
      </div>
    </div>

    </div>
    </div>
  </section>

  <!-- end contact section -->

  <!-- info section -->
  <section class="info_section layout_padding">
    <div class="container">
      <div class="row">
        <div class=" col-md-4 info_detail">
          <div>
            <p>
              to contact us please fill the form or send an email.<br>
              with newsletter u will be notified with release of each of our series, definately no spamm thing.
            </p>
          </div>
        </div>
        <div class=" col-md-4 address_container">
          <div>
            <h3>
              Address:
            </h3>
            <div class="address_link-container">
              <a href="">
                <img src="images/location.png" alt="">
                <span> Address:
                </span>
              </a>
              <a href="">
                <img src="images/phone.png" alt="">
                <span> Tel:
                </span>
              </a>
              <a href="">
                <img src="images/mail.png" alt="">
                <span>
                  Email: 
                </span>
              </a>
            </div>
          </div>
        </div>
        <div class=" col-md-4 news_container">
          <div>
            <div>
              <h3>
                newsletter

              </h3>
              <form action="">
                <input type="email" placeholder="ENTER YOUR EMAIL">
                <div>
                  <button type="submit">
                    Subscribe
                  </button>
                </div>
              </form>
            </div>
            <div class="social_container">
              <div>
                <img src="images/fb.png" alt="">
              </div>
              <div>
                <img src="images/twitter.png" alt="">
              </div>
              <div>
                <img src="images/linkedin.png" alt="">
              </div>
              <div>
                <img src="images/youtube.png" alt="">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end info section -->


  <!-- footer section -->
  <section class="container-fluid footer_section">
    <p>
      Copyright &copy; 2019 All Rights Reserved By
      <a href="https://html.design/"> Html Templates</a>
    </p>
  </section>
  <!-- footer section -->

  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js">
  </script>
  <script type="text/javascript">
    $(".owl-carousel").owlCarousel({
      loop: true,
      margin: 20,
      nav: true,
      navText: [],
      autoplay: true,
      autoplayHoverPause: true,
      responsive: {
        0: {
          items: 1
        },
        600: {
          items: 2
        },
        1000: {
          items: 3
        }
      }
    });
  </script>

  <script>
    $(".nav_search-btn").click(function() {
      if ($(".nav_search-input").hasClass("d-none")) {
        event.preventDefault();
        $(".nav_search-input")
          .animate({
              left: "-1000px"
            },
            "1000000"
          )
          .removeClass("d-none");
      } else {
        $(".nav_search-input")
          .animate({
              left: "0px"
            },
            "1000000"
          )
          .addClass("d-none");
      }
    });
  </script>

  <script>
    // This example adds a marker to indicate the position of Bondi Beach in Sydney,
    // Australia.
    function initMap() {
      var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 11,
        center: {
          lat: 40.645037,
          lng: -73.880224
        },
      });

      var image = 'images/maps-and-flags.png';
      var beachMarker = new google.maps.Marker({
        position: {
          lat: 40.645037,
          lng: -73.880224
        },
        map: map,
        icon: image
      });
    }
  </script>
  <!-- google map js -->

  <!-- end google map js -->

</body>

</html>