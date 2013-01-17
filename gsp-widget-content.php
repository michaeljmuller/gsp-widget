<?php 

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://golfshot.com/members/0025095730/rounds");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);

$scores = '';

$rounds = array();

foreach (preg_split("/((\r?\n)|(\r\n?))/", $result) as $line) {
    if (preg_match("/round\('([\d\-]+)'\)/", $line, $matches)) {
        $scores .= $matches[1];
        $round['id'] = $matches[1];
    }
    if (preg_match('/score">(.*?)<\/td/', $line, $matches)) {
        $scores .= $matches[1];
        $round['score'] = $matches[1];
    }
    if (preg_match('/course">(.*?)<\/td/', $line, $matches)) {
        $scores .= $matches[1];
        $round['course'] = $matches[1];
    }
    if (preg_match('/date">(.*?)<\/td/', $line, $matches)) {
        $scores .= $matches[1];
        $round['date'] = $matches[1];
        $round['dateObj'] = DateTime::createFromFormat('m-j-y', $round['date']);
    }
    if (preg_match('/post"><\/td/', $line, $matches)) {
        array_push(&$rounds, $round);
        $scores .= $round['score'];
    }
} 

$score = $rounds[0]['score'];
$date = date_format($rounds[0]['dateObj'], 'M jS');
$course = $rounds[0]['course'];
$id = $rounds[0]['id'];

curl_setopt($ch, CURLOPT_URL, "http://golfshot.com/Rounds/Detail/$id");
$result = curl_exec($ch);

$scorecard = array();
foreach (preg_split("/((\r?\n)|(\r\n?))/", $result) as $line) {
    if (strcasecmp(trim($line), '<tr class="net">') == 0) {
        $capture = TRUE;
    }
    if (strcasecmp(trim($line), '</tr>') == 0) {
        $capture = FALSE;
    }
    if ($capture) {
        if (preg_match('/<td.*?>(.*?)<\/td>/', $line, $matches)) {
            array_push($scorecard, $matches[1]);
        }
    }
}

if (count($scorecard) == 22) {
    $hole1 = $scorecard[1];
    $hole2 = $scorecard[2];
    $hole3 = $scorecard[3];
    $hole4 = $scorecard[4];
    $hole5 = $scorecard[5];
    $hole6 = $scorecard[6];
    $hole7 = $scorecard[7];
    $hole8 = $scorecard[8];
    $hole9 = $scorecard[9];
    $out = $scorecard[10];
    $hole10 = $scorecard[11];
    $hole11 = $scorecard[12];
    $hole12 = $scorecard[13];
    $hole13 = $scorecard[14];
    $hole14 = $scorecard[15];
    $hole15 = $scorecard[16];
    $hole16 = $scorecard[17];
    $hole17 = $scorecard[18];
    $hole18 = $scorecard[19];
    $in = $scorecard[20];
}

echo "<div id='gsp-content' class='gsp-content'>";
echo "<div class='gsp-most-recent'>";
echo "<div class='gsp-most-recent-date'>$date</div>";
echo "<div class='gsp-most-recent-score'><a href='http://golfshot.com/Rounds/Detail/$id'>$score</a></div>";
echo "<div class='gsp-most-recent-course'>$course</div>";
echo "<div class='gsp-card'>";

echo <<<SCORECARD
<table>
<tr class="gsp-card-header">
  <td>1</td>
  <td>2</td>
  <td>3</td>
  <td>4</td>
  <td>5</td>
  <td>6</td>
  <td>7</td>
  <td>8</td>
  <td>9</td>
  <td>&nbsp;</td>
</tr>
<tr class="gsp-card-data">
  <td>$hole1</td>
  <td>$hole2</td>
  <td>$hole3</td>
  <td>$hole4</td>
  <td>$hole5</td>
  <td>$hole6</td>
  <td>$hole7</td>
  <td>$hole8</td>
  <td>$hole9</td>
  <td class="gsp-inout">$out</td>
</tr>
</table>
<table>
<tr class="gsp-card-header">  
  <td>10</td>
  <td>11</td>
  <td>12</td>
  <td>13</td>
  <td>14</td>
  <td>15</td>
  <td>16</td>
  <td>17</td>
  <td>18</td>
  <td>&nbsp;</td>
</tr>
<tr class="gsp-card-data">
  <td>$hole10</td>
  <td>$hole11</td>
  <td>$hole12</td>
  <td>$hole13</td>
  <td>$hole14</td>
  <td>$hole15</td>
  <td>$hole16</td>
  <td>$hole17</td>
  <td>$hole18</td>
  <td class="gsp-inout">$in</td>
</tr>
</table>
SCORECARD;

echo "</div>";
echo "</div>";

for ($i = 1; $i < 5; $i++) {

    $score = $rounds[$i]['score'];
    $date = date_format($rounds[$i]['dateObj'], 'M jS');
    $course = $rounds[$i]['course'];
    $id = $rounds[$i]['id'];

    echo "<div class='gsp-less-recent'>";

    echo "<div class='gsp-score'><a href='http://golfshot.com/Rounds/Detail/$id'>$score</a></div>";
    echo "<div class='gsp-date-and-course'>";
    echo "<div class='gsp-date'>$date</div>";
    echo "<div class='gsp-course'>$course</div>";
    echo "</div>";
    
    echo "</div>";
}

echo "</div>";


?>