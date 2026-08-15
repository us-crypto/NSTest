<?php
session_start();
if (isset($_SESSION['loggedin'])&&$_SESSION['loggedin']==true) {
    echo '
    welcome back '.$_SESSION['name'].', logged in via 
    "' . $_SESSION['mail'] . '"? u nasty bitch :D   well then suck it up 8=====>';

} else {
    echo ' uve connected a protected page Illegaly, ur IP will be saved and send to our security team ';
    header('Location: login.php'); 
}

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

define( "Filetypes",array(
    "image/jpeg",
    "image/gif",
    "image/png"
));
define( "Datadir", "./characters/");
$adress=Datadir;
if (isset($_POST['VZ'])) {
  $adress=$_POST['VZ'];
}
function ta($in){
  echo ('<pre class ="ta">');
  print_r($in);
  echo ' </pre>';
}
define("FILETYPES", array(
  "image/jpeg",
  "image/gif",
  "image/png"
));
define("DATADIR", "./characters/");
$adress=DATADIR;
ta($_SESSION);
function showfolder($adressIN){
  $html= '
      <table>
          <thead>
              <tr>
                  <th scope ="col"></th>
                  <th scope ="col">Name</th>
                  <th scope ="col">Description</th>
                  <th scope ="col">uploaded by</th>
                  <th scope ="col">Serie</th>
                  <th scope ="col">uploaded on</th>
                  </tr>
              </thead>       
          <tbody>
  ';
  $inhalt=scandir($adressIN);
  foreach ($inhalt as $key ) {
      if ($key!="."&& $key!="..") {
          switch (true) {
              case is_dir($adressIN.$key):
                  $class="dir";
                  //showfolder($adressIN.$key);
                  break;
              case is_file($adressIN.$key):
                  $class="file";
                  break;
              case is_link($adressIN.$key):
                  $class="link";
                  break;
          }
          $html.= 
              '<tr class="'.$class.'">
                  <td></td>
                  <td class="dvvname">'.$key.'</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
              </tr>';
      }
  }
  $html.= '
                  </tbody>
              <tfoot>
              </tfoot>
          </table>
      ';
  return $html;
}


    

?>






<!DOCTYPE html>
<html lang="en">

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
    <link rel="stylesheet" href="cloud.css">
  <title>Upload room</title>

  <!-- slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.carousel.min.css" />

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

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
              <a class="nav-link" href="#">Login </a>
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
    <?php
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    } else {
        echo 'you have tried to acsess a forbidden secret page without permission, ur  IP has been sent to our security team ';
        header('Location: login.php');
    }
    echo '
        <form method="post" action="upload.php">
            <label for="choice"> choose ur destination :</label>

            <select name="choice" id="choice">
                <option value="files">files</option>
                <option value="messages">messages</option>
            </select><br><br>
            <input type="submit" value="Submit">
        </form>';

        if (isset($_POST['Delete'])) {
            $counter=0;
            foreach ($_POST['Delete'] as $key ) {
                $sql='DELETE FROM `file_tb` WHERE
                fileID=' . $key . '
                ';
                $result = $conn->query($sql);

            }

        }
        if (isset($_POST['DeleteMessage'])) {
            $counter=0;
            foreach ($_POST['DeleteMessage'] as $key ) {
                $sql='DELETE FROM `messages_tb` WHERE
                MID=' . $key . '
                ';
                $result = $conn->query($sql);

            }

        }

    if (isset($_POST['choice']) && $_POST['choice'] == 'files') {
        $sql = 'SELECT file_tb.Name AS FiName , file_tb.Description AS FiDesc, user_tb.Name AS UsName, 
        series_tb.Name AS SeName, `time`, file_tb.fileID AS FilID ,series_tb.SerieID AS SeID
        FROM upload_tb
        LEFT JOIN file_tb ON upload_tb.fileFK=file_tb.fileID
        LEFT JOIN user_tb ON upload_tb.userFK=user_tb.userID
        LEFT JOIN series_tb ON upload_tb.SeriesFK=series_tb.SerieID;
        ';
        $result = $conn->query($sql);

        echo '<form method="post" action="upload.php" id="frm" >
        <input type="text" name="VZ" id="VZ" >';
        $content=showfolder($adress);
        echo $content;

        echo '<input type="submit" value="Submit">
        </form>';

        if ($result->num_rows > 0) {
            $counter=0;
            // output data of each row
            echo ' <form method="post" action="upload.php"  >';

            while ($row = $result->fetch_object()) {
                echo '

                <table>
                    <tr>
                        <th>File Name</th>
                        <th>Uploader</th>
                        <th>Animation/Series</th>
                        <th>Uploaded on</th>
                        <th>Description</th>
                        <th>Delete</th>
                        <th>download</th>
                    </tr>
                    <tr>
                        <td><a name ="chosenFile" value="' . $row->FilID . '</" href="upload.php">' . $row->FiName . '</a></td>
                        <td>' . $row->UsName . '</td>
                        <td><a name ="chosenSerie" value="' . $row->SeID . '</" href="upload.php">' . $row->SeName . '</a></td>
                        <td>' . $row->time . '</td>
                        <td>' . $row->FiDesc . '</td>
                        
                        
                        <td><input type="checkbox" id="Delete" name="Delete[' . $counter . ']" value="' . $row->FilID . '">
                        <label for="Delete">X</label></td><br>
                        <td><input type="checkbox" id="Download" name="Download[' . $counter . ']" value="' . $row->FilID . '">
                        <label for="Download">Download</label></td><br>
                    </tr>
                </table>

                ';
                $counter++;
            }
            echo '                <input type="submit" value="Submit">
                        </form>';
        } else {
            echo "0 results";
        }
    } elseif (isset($_POST['choice']) && $_POST['choice'] == 'messages') {

        $sql = 'SELECT * FROM `messages_tb`';
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $counter=0;
            // output data of each row MID	Name	Email	tel	message
            echo '<form method="post" action="upload.php">';
            while ($row = $result->fetch_object()) {
                echo '

                <table>
                    <tr>
                        <th>Sender Name</th>
                        <th>Email</th>
                        <th>Tel</th>
                        <th>Message</th>
                        <th>Delete</th>
                    </tr>
                    <tr>
                        <td>' . $row->Name . '</td>
                        <td>' . $row->Email . '</td>
                        <td>' . $row->tel . '</td>
                        <td>' . $row->message . '</td>
                        
                        
                        <td><input type="checkbox" id="Delete" name="DeleteMessage[' . $counter . ']" value="' . $row->MID . '">
                        <label for="Delete">X</label></td><br>

                    </tr>
                </table>

                ';
                $counter++;
            }
            echo '                <input type="submit" value="Submit">
                        </form>';
        } else {
            echo "0 results";
        }

    } elseif (isset($_POST['chosenFile'])) {

    } elseif (isset($_POST['chosenSerie'])) {
        $sql = 'SELECT file_tb.Name AS FiName , file_tb.Description AS FiDesc, user_tb.Name AS UsName, 
        series_tb.Name AS SeName, `time`, file_tb.fileID AS FilID ,series_tb.SerieID AS SeID
        FROM upload_tb
        LEFT JOIN file_tb ON upload_tb.fileFK=file_tb.fileID
        LEFT JOIN user_tb ON upload_tb.userFK=user_tb.userID
        LEFT JOIN series_tb ON upload_tb.SeriesFK=series_tb.SerieID
        WHERE SeID=' . $_POST['chosenSerie'] . ';
        ';
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            echo '                <form method="post" action="upload.php">';
            while ($row = $result->fetch_object()) {
                echo '

                <table>
                    <tr>
                        <th>File Name</th>
                        <th>Uploader</th>
                        <th>Animation/Series</th>
                        <th>Uploaded on</th>
                        <th>Description</th>
                    </tr>
                    <tr>
                        <td><a name ="chosenFile" value="' . $row->FilID . '</" href="upload.php">' . $row->FiName . '</a></td>
                        <td>' . $row->UsName . '</td>
                        <td><a name ="chosenSerie" value="' . $row->SeID . '</" href="upload.php">' . $row->SeName . '</a></td>
                        <td>' . $row->time . '</td>
                        <td>' . $row->FiDesc . '</td>

                        <td><input type="checkbox" id="Delete" name="Delete[' . $counter . ']" value="' . $row->FilID . '">
                        <label for="Delete">X</label></td><br>
                        <td><input type="checkbox" id="Download" name="Download[' . $counter . ']" value="' . $row->FilID . '">
                        <label for="Download">Download</label></td><br>
                    </tr>
                </table>

                ';
            }
            echo '<input type="submit" value="Submit">
                        </form>';
        } else {
            echo "0 results";
        }
    } else {
        echo ' choose something';
    }



    ?>




 <!-- info section -->
 <section class="info_section layout_padding">
    <div class="container">
      <div class="row">
        <div class=" col-md-4 info_detail">
          <div>

          </div>
        </div>
        <div class=" col-md-4 address_container">
          <div>

            <div class="address_link-container">

              </a>
            </div>
          </div>
        </div>
        <div class=" col-md-4 news_container">
          <div>
            <div>

              
            </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
      <section class="container-fluid footer_section">
    <p>
      Copyright &copy; 2022 All Rights Reserved 
    </a>
    </p>
  </section>
  <!-- footer section -->

  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js">
  </script>
  <script type="text/javascript">
    function readfolder(js_pfad) {
        document.getElementById("VZ").value=js_pfad;
    }
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

  <main>
    
  </main>


</body>

</html>


