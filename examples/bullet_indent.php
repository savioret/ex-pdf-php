<?php
include_once '../src/Cezpdf.php';

$pdf = new CezPDF('a4');
$pdf->selectFont('Helvetica');

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

<c:bullet:margin=5>1 cup unsalted butter, softened</li>
<c:bullet:margin=5>1 cup granulated sugar</li>
<c:bullet:margin=5>1 cup packed brown sugar</li>
<c:bullet:margin=5>2 large eggs</li>
<c:bullet:margin=5>1 teaspoon vanilla extract</li>
<c:bullet:margin=5>3 cups all-purpose flour</li>
<c:bullet:margin=5>1 teaspoon baking soda</li>
<c:bullet:margin=5>2 cups chocolate chips</li>
";

// Using bullet callback, shape
$txt2 = "<b>Classic Chocolate Chip Cookies</b>

<c:bullet:shape=square>1 cup unsalted butter, softened</li>
<c:bullet:shape=square>1 cup granulated sugar</li>
<c:bullet:shape=square>1 cup packed brown sugar</li>
<c:bullet:shape=square>2 large eggs</li>
<c:bullet:shape=square>1 teaspoon vanilla extract</li>
<c:bullet:shape=square>3 cups all-purpose flour</li>
<c:bullet:shape=square>1 teaspoon baking soda</li>
<c:bullet:shape=square>2 cups chocolate chips</li>
";

// Using bullet callback, size and color
$txt3 = "<b>Classic Chocolate Chip Cookies</b>

<c:bullet:bullet_size=14,bullet_color=0#0.6#0.2>1 cup unsalted butter, softened</li>
<c:bullet:bullet_size=14,bullet_color=0#0.6#0.2>1 cup granulated sugar</li>
<c:bullet:bullet_size=14,bullet_color=0#0.6#0.2>1 cup packed brown sugar</li>
<c:bullet:bullet_size=14,bullet_color=0#0.6#0.2>2 large eggs</li>
<c:bullet:bullet_size=14,bullet_color=0#0.6#0.2>1 teaspoon vanilla extract</li>
<c:bullet:bullet_size=14,bullet_color=0#0.6#0.2>3 cups all-purpose flour</li>
<c:bullet:bullet_size=14,bullet_color=0#0.6#0.2>1 teaspoon baking soda</li>
<c:bullet:bullet_size=14,bullet_color=0#0.6#0.2>2 cups chocolate chips</li>
";

$pdf->ezColumnsStart($options = ['num' => 2]);
$pdf->ezText($txt);
$pdf->ezText($txt1);
$pdf->ezNewPage();
$pdf->ezText($txt2);
$pdf->ezText($txt3);
$pdf->ezColumnsStop();


$pdf->ezText(
    "\n\n\n<b>Example showing text indentation</b>

<c:indent:100><u>Phasellus eu suscipit turpis</u>. Ut bibendum gravida lacus, sit amet blandit dolor porta rhoncus. Suspendisse et tempor augue, quis fermentum nulla. Cras diam nisi, porttitor eu lectus nec, vestibulum sagittis eros. Nam congue, urna accumsan eleifend porttitor, nunc odio convallis metus, sit amet euismod turpis orci vitae sem. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque egestas neque elit, quis fermentum ipsum ornare in. Maecenas accumsan dui diam, eget condimentum sapien efficitur sit amet. In hac habitasse platea dictumst. Nunc nunc ligula, scelerisque quis laoreet nec, sollicitudin eu nulla. Morbi interdum enim sed ultricies dictum. Vestibulum suscipit iaculis sapien ac imperdiet. Cras sed turpis quam. Aliquam ornare a sem ut consequat. In hac habitasse platea dictumst.

Maecenas orci metus, interdum vel risus aliquam, condimentum dignissim lectus. Ut sit amet vulputate massa. Praesent mollis commodo tortor, vitae scelerisque libero. Donec tincidunt tortor tortor, ac venenatis libero porttitor sed. Ut luctus, nisl ut rutrum pretium, nisl sem gravida nisi, nec tristique neque risus sollicitudin mauris. Praesent elit lectus, molestie sit amet diam at, sollicitudin varius dui. In congue luctus nisi, at dictum mauris convallis vel. Aenean nec varius risus, et convallis erat. Morbi ac ipsum volutpat, aliquam libero vitae, faucibus elit. Ut dapibus dolor sed aliquam condimentum.</c:indent>

<u>Phasellus eu suscipit turpis</u>. Ut bibendum gravida lacus, sit amet blandit dolor porta rhoncus. Suspendisse et tempor augue, quis fermentum nulla. Cras diam nisi, porttitor eu lectus nec, vestibulum sagittis eros. Nam congue, urna accumsan eleifend porttitor, nunc odio convallis metus, sit amet euismod turpis orci vitae sem. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque egestas neque elit, quis fermentum ipsum ornare in. Maecenas accumsan dui diam, eget condimentum sapien efficitur sit amet. In hac habitasse platea dictumst. Nunc nunc ligula, scelerisque quis laoreet nec, sollicitudin eu nulla. Morbi interdum enim sed ultricies dictum. Vestibulum suscipit iaculis sapien ac imperdiet. Cras sed turpis quam. Aliquam ornare a sem ut consequat. In hac habitasse platea dictumst.

Maecenas orci metus, interdum vel risus aliquam, condimentum dignissim lectus. Ut sit amet vulputate massa. Praesent mollis commodo tortor, vitae scelerisque libero. Donec tincidunt tortor tortor, ac venenatis libero porttitor sed. Ut luctus, nisl ut rutrum pretium, nisl sem gravida nisi, nec tristique neque risus sollicitudin mauris. Praesent elit lectus, molestie sit amet diam at, sollicitudin varius dui. In congue luctus nisi, at dictum mauris convallis vel. Aenean nec varius risus, et convallis erat. Morbi ac ipsum volutpat, aliquam libero vitae, faucibus elit. Ut dapibus dolor sed aliquam condimentum.",
    0,
    ['justification' => 'full']
);


if (isset($_GET['d']) && $_GET['d']) {
    echo "<pre>" . $pdf->ezOutput(true) . "</pre>";
} else {
    $pdf->ezStream();
}
