<?php 
end($categoryNames);
$lastKey = key($categoryNames);
foreach($categoryNames as $key => $value) {
    if ($lastKey != $key) {
        echo "<a href=\"/category/" . $categoryIds[$key] ."\">" . $value . "</a>" . " --> ";
    } else {
        echo $value;
    }
}
