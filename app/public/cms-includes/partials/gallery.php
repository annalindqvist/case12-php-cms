<?php
    $files = glob("../cms-content/uploads/*.*");
    if($files) {
        echo "<div>";
        for ($i = 0; $i < count($files); $i++) {
            $image = $files[$i];
            echo basename($image) . "<br />"; 
            echo "<img src='$image' style='max-width:200px; max-height:200px;' /> " . "<br /><br />";
           
        }
        echo "</div>";
    }
    
?>