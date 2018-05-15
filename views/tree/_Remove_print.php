<div id="chart"></div>
<?php
$this->registerJs('
        var chart = new getOrgChart(document.getElementById("chart"),
        {	
         theme: "monica",
         subtreeSeparation: 200,
         siblingSeparation: 200,
         levelSeparation: 100,
         linkType: "B",
         color: "lightgreen",
         primaryColumns: ["f_name", "l_name", "birth"],
         imageColumn: "image",
         orientation: 1,
         dataSource: ' . $tree . '
         });
');


