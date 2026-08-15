<?php
session_start();

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



define("MAXSIZE", 15 * 1024 * 1024);

function ta($in)
{
    echo ('<pre class ="ta">');
    print_r($in);
    echo ' </pre>';
}

define("FILETYPES", array(
    "image/jpeg",
    "image/gif",
    "image/png",
    "application/pdf"
));
define("DATADIR", "./Daten/");


$pfad = DATADIR;
$msg="";


if (count($_POST)>0) {
    $pfad=$_POST['VZ'];
    ta($_POST);

    switch ($_POST['wasTun']) {
        case 'anlegen':
            $ok=@mkdir($_POST['VZ'].$_POST['VZNeu'],0755,false);
            if ($ok) {
                $msg='<p class="error"> na baba ? ye chizaii baladia  o_O </p>';
            }else{
                $msg='<p class="error"> folder am balad nisti dorost koni ?omidi be zende budanet nist  :)))) </p>';
            }
            echo($msg);
            break;
        case 'löschen':
            if (isset($_POST['auswahl'])) {
                foreach ($_POST['auswahl'] as $dvv) {
                    if (is_dir($dvv)) {
                        $msg='<p class="error"> foldere '.$dvv. ' ro pak kardi, motmaeni bayad pak mikardi ?  o_O </p>';
                        @loescheVZ($dvv .'/');
                    }else {
                        $msg='<p class="error"> '.$dvv. ' ro pak kardi, motmaeni bayad pak mikardi ?  o_O </p>';
                        @unlink($dvv);
                    }
                    echo($msg);
                }
            }else {
                $msg='<p class="error"> chio pak mikoni? -_o </p>';
                echo($msg);
            }
            break;
        case 'verschieben':
            if (isset($_POST['auswahl'])) {
                foreach ($_POST['auswahl'] as $key) {
                    $temp = explode("/", $key);
                    $name = $temp[count($temp) - 1];
                    rename($key, $_POST["VZToMove"] . $name);
                }
            }
            break;
        case 'umbenennen':#
            if (isset($_POST['auswahl'])&&count($_POST['auswahl'])==1) {
                if (isset($_POST["DVVUmbenennen"])&&$_POST["DVVUmbenennen"]!=null) {
                    $pfad_alt=$_POST['auswahl'][0];
                    $temp=explode('/',$pfad_alt);
                    $temp_part=array_slice($temp,0,count($temp)-1);
                    //ta($temp_part);
                    $pfad_new = implode('/', $temp_part) . '/' . $_POST["DVVUmbenennen"];
                
                    rename($pfad_alt, $pfad_new );
                }else {
                    $msg='<p class="error"> esme jadid nazashti, be chi mikhai taghir bedi ? o_o </p>';
                    echo($msg);
                }

            
            }else {
                $msg='<p class="error"> faghat yeki entekhab kon -_- </p>';
                echo($msg);
            }
            break;
    }
}
if (count($_FILES)>0) {
    $file=$_FILES['auswahlUL'];
    //ta($_FILES);
    if (count($file['name'])>0 &&strlen($file['name'][0]>0)) {
        for ($i=0; $i < count($file['error']) ; $i++) { 
            if ($file['error'][$i]==0) {
                $ok=move_uploaded_file($file['tmp_name'][$i],$pfad.$file['name'][$i]);
                if ($ok) {
                    $msg='<p class="error"> upload am baladi ? dg chi baladi? '.$file['name'][$i].' upload shod </p>';
                }else {
                    $msg='<p class="error"> upload am baladi nisti ? '.$file['name'][$i].' upload nashod </p>';
                }
            } else {
                $msg='<p class="error"> upload nashod '.$file['name'][$i].' ro baz upload kon </p>';
            }
            echo($msg);
            
        }
    }
    

    // foreach ($_FILES["auswahlUL"]['error'] as $key) {
    //     if ($key==0) {
    //         move_uploaded_file($);
    //     } else {
    //         # code...
    //     }
        
    // }
}
function loescheVZ($root){
    $inhalt= scandir($root);
    foreach ($inhalt as $key) {
        if ($key!='.'&&$key!='..') {
            if (is_dir($root.$key)) {
                loescheVZ($root . $key . '/');
            } else {
                unlink($root . $key);
            }
            
        }
    }
    rmdir($root);
}
function zeigeVzInhalt($pfadIN)
{
    $html = '
        <table>
            <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Name</th>
                    <th scope="col">Size</th>
                    <th scope="col">last update</th>
                </tr>
            </thead>
            <tbody>
        ';
    $inhalt = scandir($pfadIN);
    foreach ($inhalt as $dvv) {
        $code="";
        $size="";
        $mb = "";
        $dateTime = "";
        $dateTime = date('d.m.Y H:i',filemtime($pfadIN . $dvv));
        switch (TRUE) {
            case is_dir($pfadIN . $dvv):
                $class = 'dir';
                $code = 'onDblclick="JS_leseVZ(\'' . $pfadIN . $dvv . '/\');"';
                $size ="Folder";
                break;

            case is_file($pfadIN . $dvv):
                $class = 'file';
                $sizeB = filesize($pfadIN . $dvv);
                $sizeNr = $sizeB/1024/1000;
                $size =round($sizeNr, 2);
                $mb = ' MB';
                break;
            case is_link($pfadIN . $dvv):
                $class = 'link';
                break;
        }
        if ($dvv != "." && $dvv != "..") {
            $html .= '
                <tr class="'.$class.'">
                    <td><input type="checkbox" name="auswahl[]" value="'.$pfadIN . $dvv.'"></td>
                    <td class="dvvname">
                        <span '.$code.'>' . $dvv . '</span></td>
                    <td>'.$size.$mb.'</td>
                    <td>'.$dateTime.'</td>
                </tr>
                ';
        }
    }
    $html .= '
        </tbody>
        <tfoot>
        </tfoot>
    </table>
    ';
    return $html;
}
function erzeugeBrotkruemelnav($pfadIn){
    $html="<ul class='brotkruemel' >";
    
    $arr=explode("/",$pfadIn);
    $pfad=$arr[0].'/';
    for ($i=1; $i < count($arr)-1 ; $i++) { 
        $pfad.=$arr[$i].'/';
        $html.='<li>
                    <a onclick="JS_leseVZ(\''.$pfad.'\');">'.$arr[$i].'</a>
                </li>';
    }
    $html.="</ul";
    return $html;
}

function zeigeVZStruktur($root,$isRoot=true){
    $html = "<ul>";
    if ($isRoot) {
        $temp = explode('/', DATADIR);
        $mainRoot = $temp[1];
        $html .= "<li><label> <input type='radio' name='VZToMove' value='".DATADIR."'>".$mainRoot.'</label><ul>';
    }
    $inhalt = scandir($root);
    foreach ($inhalt as $key) {
        if ($key!="."&&$key!="..") {
            if (is_dir($root.$key)) {
                $html .= "<li><label> <input type='radio' name='VZToMove' value='".$root.$key."/'>" . $key."</label>";
                $html .= zeigeVZStruktur($root.$key."/",false);
                $html .= "</li>";
            }
        }
    }
    if ($isRoot) {
        $html .= '</li></ul>';
    }
    $html .= "</ul>";
    return $html;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CIA Secret Lab</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="stylesheet" href="cloud.css">
    <script src="js/jquery-3.4.1.min.js"></script>
    <script>
        function JS_leseVZ(js_pfad) {
            document.getElementById("VZ").value=js_pfad;
            document.getElementById("frm").submit();
        }
        function JS_legeVZAn(){
            document.getElementById("wasTun").value="anlegen";
            document.getElementById("frm").submit();
        }
        function JS_loeschDVV(){
            document.getElementById("wasTun").value="löschen";
            document.getElementById("frm").submit();
        }
        function JS_blendeVZStructurEin(){
            $("#fsStruktur").toggle();
        }
        function JS_verschiebe(){
            document.getElementById("wasTun").value="verschieben";
            document.getElementById("frm").submit();
        }
        function JS_blendeUmbennenenFeldEin(){
            $("#fsUmbenennen").toggle();
        }
        function JS_Umbenennen() {
            document.getElementById("wasTun").value="umbenennen";
            document.getElementById("frm").submit();
        }
    </script>
</head>

<body>

    <!-- <?php echo($msg); ?> -->
    <form method="post" id="frm" enctype="multipart/form-data">
        <input type="hidden" name="VZ" id="VZ" value="<?php echo($pfad); ?>">
        <input type="hidden" name="wasTun" id="wasTun">
            <fieldset id="fsAnlegeVZ" >
                <input type="text" id="VZNeu" name="VZNeu">
                <input type="button" value="Folder besaz" onclick="JS_legeVZAn();">
            </fieldset>
            <fieldset id="fsHochladen">
                <input type="file" name="auswahlUL[]" multiple>
                <input type="submit" value="upload">
            </fieldset>
            <fieldset>
                <fieldset id="fsStruktur">
                    <?php 
                    $Struktur= zeigeVZStruktur(DATADIR);
                    echo ($Struktur);
                    
                    ?>
                    <button type="button" onclick="JS_verschiebe();">inja </button>
                </fieldset>
                <fieldset id="fsUmbenennen">
                    <input type="text" name="DVVUmbenennen">
                    <button type="button" onclick="JS_Umbenennen();">ok</button>
                </fieldset>
            <header>
            </header>
            
            <nav>
                <?php 
                    $brotkruemelnav= erzeugeBrotkruemelnav($pfad);
                    echo($brotkruemelnav);
                    
                ?>
            </nav>
            <main>
                <?php
                
                $content = zeigeVzInhalt($pfad);
                echo ($content);
                ?>
                <button type="button" onclick="JS_loeschDVV();"> gand zadam pak kon</button>
                <button type="button" onclick="JS_blendeVZStructurEin();">ina ke alamat zadam o move bede...</button>
                <button type="button" onclick="JS_blendeUmbennenenFeldEin();">rename kon</button>
            </main>
            </fieldset>
        <footer></footer>
    </form>
</body>

</html>