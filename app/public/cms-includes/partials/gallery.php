<?php
    $files = glob("../cms-content/uploads/*.*");
    if($files) {
        echo 
        "<div class='m-4 grid grid-cols-2 md:grid-cols-3 gap-4'>";
        
        for ($i = 0; $i < count($files); $i++) {
            $image = $files[$i];
            echo "<div>";
            echo basename($image); 
            echo "<img class='h-1/2 max-w-full rounded-lg'  src='$image'/>";
            echo "</div>";
        }
        echo "</div>";
    }
    
?>