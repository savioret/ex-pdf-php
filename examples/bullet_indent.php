<?php
include_once '../src/Cezpdf.php';

$pdf = new CezPDF('a4');
$pdf->selectFont('Helvetica');
$pdf->allowTags(['bullet', 'indent']);

// Using <li> tags
$txt = "<b>Classic Chocolate Chip Cookies</b>

<li>1 cup unsalted butter, softened</li>
<li>1 cup granulated sugar</li>
<li>1 cup packed brown sugar</li>
<li>2 large eggs</li>
<li>1 teaspoon vanilla extract</li>
<li>3 cups all-purpose flour</li>
<li>1 teaspoon baking soda</li>
<li>2 cups chocolate chips</li>
";


// Using bullet callback, margin
$txt1 = "<b>Classic Chocolate Chip Cookies</b>

<c:bullet:margin=5>1 cup unsalted butter, softened</c:bullet>
<c:bullet:margin=5>1 cup packed brown sugar</c:bullet>
<c:bullet:margin=5>2 large eggs</c:bullet>
<c:bullet:margin=5>1 teaspoon vanilla extract</c:bullet>
<c:bullet:margin=5>3 cups all-purpose flour</c:bullet>
<c:bullet:margin=5>1 teaspoon baking soda</c:bullet>
<c:bullet:margin=5>2 cups chocolate chips</c:bullet>
";

// Using bullet callback, shape
$txt2 = "<b>Classic Chocolate Chip Cookies</b>

<c:bullet:shape=square>1 cup unsalted butter, softened</c:bullet>
<c:bullet:shape=square>1 cup packed brown sugar</c:bullet>
<c:bullet:shape=square>2 large eggs</c:bullet>
<c:bullet:shape=square>1 teaspoon vanilla extract</c:bullet>
<c:bullet:shape=square>3 cups all-purpose flour</c:bullet>
<c:bullet:shape=square>1 teaspoon baking soda</c:bullet>
<c:bullet:shape=square>2 cups chocolate chips</c:bullet>
";

// Using bullet callback, size and color
$txt3 = "<b>Classic Chocolate Chip Cookies</b>

<c:bullet:bullet_size=14,bullet_color=0#0.6#0.2>1 cup unsalted butter, softened</c:bullet>
<c:bullet:bullet_size=14,bullet_color=0#0.6#0.2>1 cup packed brown sugar</c:bullet>
<c:bullet:bullet_size=14,bullet_color=0#0.6#0.2>2 large eggs</c:bullet>
<c:bullet:bullet_size=14,bullet_color=0#0.6#0.2>1 teaspoon vanilla extract</c:bullet>
<c:bullet:bullet_size=14,bullet_color=0#0.6#0.2>3 cups all-purpose flour</c:bullet>
<c:bullet:bullet_size=14,bullet_color=0#0.6#0.2>1 teaspoon baking soda</c:bullet>
<c:bullet:bullet_size=14,bullet_color=0#0.6#0.2>2 cups chocolate chips</c:bullet>
";

$pdf->ezColumnsStart($options = ['num' => 2]);
$pdf->ezText($txt);
$pdf->ezText($txt1);
$pdf->ezNewPage();
$pdf->ezText($txt2);
$pdf->ezText($txt3);
$pdf->ezColumnsStop();


$myoptions = array(
    'width' => $pdf->ezContentWidth()
);
$enum = "<c:bullet:margin=5>One</c:bullet>\n"
    . "<c:bullet:margin=5>Two</c:bullet>\n"
    . "<c:bullet:margin=5>Three</c:bullet>\n"
    . "<c:bullet:margin=5>Four</c:bullet>";
$enum = "<c:bullet:margin=5>Five</c:bullet>\n"
    . "<c:bullet:margin=5>Six</c:bullet>\n"
    . "<c:bullet:margin=5>Seven</c:bullet>\n"
    . "<c:bullet:margin=5>Eight</c:bullet>";
$data = [[$enum, $enum, $enum, $enum], [$enum, $enum, $enum, $enum]];

$pdf->ezTable($data, ['<b>column1</b>', '<b>column2</b>', '<b>column3</b>', '<b>column4</b>'], "\n", $myoptions);


$pdf->ezText(
    "\n\n\n<b>Example showing text indentation</b>

<c:indent:100><u>Phasellus eu suscipit turpis</u>. Ut bibendum gravida lacus, sit amet blandit dolor porta rhoncus. Suspendisse et tempor augue, quis fermentum nulla. Cras diam nisi, porttitor eu lectus nec, vestibulum sagittis eros. Nam congue, urna accumsan eleifend porttitor, nunc odio convallis metus, sit amet euismod turpis orci vitae sem.

Maecenas orci metus, interdum vel risus aliquam, condimentum dignissim lectus. Ut sit amet vulputate massa. Praesent mollis commodo tortor, vitae scelerisque libero. Donec tincidunt tortor tortor, ac venenatis libero porttitor sed. Ut luctus, nisl ut rutrum pretium, nisl sem gravida nisi, nec tristique neque risus sollicitudin mauris.</c:indent>

<u>Phasellus eu suscipit turpis</u>. Ut bibendum gravida lacus, sit amet blandit dolor porta rhoncus. Suspendisse et tempor augue, quis fermentum nulla. Cras diam nisi, porttitor eu lectus nec, vestibulum sagittis eros. Nam congue, urna accumsan eleifend porttitor, nunc odio convallis metus, sit amet euismod turpis orci vitae sem. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque egestas neque elit, quis fermentum ipsum ornare in.

Maecenas orci metus, interdum vel risus aliquam, condimentum dignissim lectus. Ut sit amet vulputate massa. Praesent mollis commodo tortor, vitae scelerisque libero. Donec tincidunt tortor tortor, ac venenatis libero porttitor sed. Ut luctus, nisl ut rutrum pretium, nisl sem gravida nisi, nec tristique neque risus sollicitudin mauris.",
    0,
    ['justification' => 'full']
);


if (isset($_GET['d']) && $_GET['d']) {
    echo "<pre>" . $pdf->ezOutput(true) . "</pre>";
} else {
    $pdf->ezStream();
}
