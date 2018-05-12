<?php

$lines = file("vibram.csv");
$headers = array_map("trim", str_getcsv(array_shift($lines), ";"));

$dot = "digraph {\n";
for ($j = 2018; $j > 2006; $j--) {
    $dot .= "  " . $j . "->" . ($j - 1) . "\n";
}

foreach ($lines as $line) {
    $data = array_map("trim", array_combine($headers, str_getcsv($line, ";")));
    $dot .= "  " . toNode($data) . "\n";
    if (!empty($data['Vorgänger'])) $dot .= "  " . toKey($data['Name']) . "->" . toKey($data['Vorgänger']) . "\n";

    if (is_numeric($data['Beginn'])) {
        $dot .= "  {rank = same; " . toKey($data['Name']) . "; " . $data['Beginn'] . ";}\n";
    }
}
$dot .= "}\n";

file_put_contents("graph.dot", $dot);
echo exec("dot graph.dot -Tpng -o graph.png", $out, $ret);
var_dump($out, $ret);


function toNode(array $data)
{
    return toKey($data['Name']) . " [label=\"" . $data['Name'] . "\"]";

}

function toKey($label)
{
    return str_replace(["-", "/", " "], "", $label);
}