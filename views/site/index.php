<?php 
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\web\AssetManager;
?>
<h1>Categories</h1>
<?php 
function drawTreeStructure($ar) {
    foreach($ar as $key => $item) {
        if (is_array($item)) {
            echo "<ul>";
            echo "<li>";
            echo "<div class=\"line-wrapper\">";
            echo "<div class=\"line\"></div>";
            echo "<a href=\"/category/" . $key . "\">" . $item['name'] . "</a>";
            echo "</div>";
            drawTreeStructure($item);
            echo "</li>";
            echo "</ul>";
        }
    }
}

function sortNestedArrayAssoc(&$ar) {
    if (!is_array($ar)) {
        return false;
    }
    ksort($ar);
    foreach ($ar as $key => $value) {
        sortNestedArrayAssoc($ar[$key]);
    }
    return true;
}

$tmp = array();
$cat = array();
$out = array();
$final = array();

/* Convert object to array */
foreach($categories as $category) {
    $cat[$category['id']] = array(
        "parentId" => $category['parentId'],
        "closestParentId" => $category['closestParentId'],
        "level" => $category['level'],
        "name" => $category['name']
    );
}

foreach($categories as $category) {
    $parentId = $category['id'];
    $out[$category['id']] = array(
        "parentId" => $category['parentId'],
        "closestParentId" => $category['closestParentId'],
        "level" => $category['level'],
        "name" => $category['name']
    );
    for($i = $category['level']; $i > 0; $i--) {
        $tmp[] = $cat[$parentId]['closestParentId'];
        $parentId = $cat[$parentId]['closestParentId'];
    }
    foreach($tmp as $pId) {
        $out = [
            $pId => $out
        ];
    }
    ###### Объединяем промежуточный массив с финальным #####
    $final = array_replace_recursive($out, $final);
    $tmp = array();
    $out = array();
}

sortNestedArrayAssoc($final);
drawTreeStructure($final);

##############################
##### Генерация категорий ####
##############################

/*
function getParentId(&$categories, $category) {

    $tmp = $category;
    $closest = null;
    $current = null;
    $level = 0;

    while ($tmp['closestParentId'] != null) {
        $closest = $tmp['closestParentId'] - 1;
        $current = $categories[$closest]['id'];
        $tmp['closestParentId'] = $categories[$closest]['closestParentId'];
        $level++;
    }

    $category['parentId'] = $current;
    $category['level'] = $level;
    $categories[(int)$category['id'] - 1] = $category;

}

function getClosestParent(&$availiableParents, &$parId) {
    $randKey = array_rand($availiableParents);
    array_push($availiableParents, $parId);
    $parId++;
    return $availiableParents[$randKey];
}

$availiableParents = array(null);
$parId = 1;
$parentIds = array();

##### Рандомим ближайшего родителя #####
for($i = 0; $i < 5000; $i++) {
    $parentId = getClosestParent($availiableParents, $parId);
    array_push($parentIds, array("id" => $i+1, "closestParentId" =>  $parentId));
}
sort($parentIds);

##### Ищем самого дальнего родителя #####
foreach($parentIds as $key => $value) {
    getParentId($parentIds, $value);
}

$i = 0;
foreach($parentIds as $key => $value) {
    $i++;
    if (is_null($value['parentId'])) {
        $value['parentId'] = "null";
    }

    if (is_null($value['closestParentId'])) {
        $value['closestParentId'] = "null";
    }
    echo "( " . $value['parentId'] . ", " . $value['closestParentId'] . ", " . $value['level'] . ", \"cat" . $i . "\"),<br>"; 
}
*/
