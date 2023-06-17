<?php
include_once '../src/Cezpdf.php';

$pdf = new CezPDF('a4');
$pdf->selectFont('Helvetica');
$pdf->allowTags(['fontsize']);
$txt = "<b><c:fontsize:16>Lorem ipsum dolor sit amet</c:fontsize></b>\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas tristique nec odio id euismod. Curabitur euismod ex volutpat bibendum eleifend. Donec pretium pretium nibh, at consectetur turpis porta et. Mauris pretium risus eget feugiat pellentesque. Quisque egestas sodales turpis, et euismod nisl vulputate at. Etiam convallis, turpis eu suscipit viverra, nulla ipsum vehicula metus, vitae posuere metus urna vitae dolor. Aliquam commodo non mi rutrum pretium. Ut facilisis turpis id quam tempus condimentum. In dapibus non est ac facilisis. Nulla vulputate suscipit ligula, vel finibus arcu ullamcorper id. Duis ut facilisis felis. Nam ullamcorper nunc eget sapien placerat aliquet. Etiam nec elit risus. In vitae neque et felis imperdiet rutrum. Sed sed metus erat. Duis sit amet lacus eu nisl fringilla venenatis.

<i>Proin nulla nunc, eleifend nec turpis feugiat, iaculis scelerisque nisl. In vehicula id dui vitae varius. Cras rutrum vitae odio convallis accumsan. Sed eu ullamcorper lorem. Donec et sagittis magna. Nulla ac nisi dui. Praesent faucibus justo vel tortor consequat hendrerit. Phasellus libero leo, suscipit ac neque at, laoreet luctus est.</i>

Vestibulum at pulvinar felis. Nullam ultricies nisi dignissim est luctus, vehicula ultricies metus pretium. Quisque sed porta dolor. Curabitur id massa id justo posuere accumsan. Duis eu ipsum ut dolor commodo auctor a et diam. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum dui elit, sodales sit amet ipsum ut, eleifend cursus nisi. Donec ut mi vitae urna condimentum dignissim at ac enim.

<u>Phasellus eu suscipit turpis</u>. Ut bibendum gravida lacus, sit amet blandit dolor porta rhoncus. Suspendisse et tempor augue, quis fermentum nulla. Cras diam nisi, porttitor eu lectus nec, vestibulum sagittis eros. Nam congue, urna accumsan eleifend porttitor, nunc odio convallis metus, sit amet euismod turpis orci vitae sem. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque egestas neque elit, quis fermentum ipsum ornare in. Maecenas accumsan dui diam, eget condimentum sapien efficitur sit amet. In hac habitasse platea dictumst. Nunc nunc ligula, scelerisque quis laoreet nec, sollicitudin eu nulla. Morbi interdum enim sed ultricies dictum. Vestibulum suscipit iaculis sapien ac imperdiet. Cras sed turpis quam. Aliquam ornare a sem ut consequat. In hac habitasse platea dictumst.

Maecenas orci metus, interdum vel risus aliquam, condimentum dignissim lectus. Ut sit amet vulputate massa. Praesent mollis commodo tortor, vitae scelerisque libero. Donec tincidunt tortor tortor, ac venenatis libero porttitor sed. Ut luctus, nisl ut rutrum pretium, nisl sem gravida nisi, nec tristique neque risus sollicitudin mauris. Praesent elit lectus, molestie sit amet diam at, sollicitudin varius dui. In congue luctus nisi, at dictum mauris convallis vel. Aenean nec varius risus, et convallis erat. Morbi ac ipsum volutpat, aliquam libero vitae, faucibus elit. Ut dapibus dolor sed aliquam condimentum. Cras pretium, dolor a dignissim sagittis, risus sem convallis orci, non molestie arcu velit vitae metus.";

$txta = "<b><c:fontsize:16>Lorem ipsum dolor sit amet</c:fontsize></b>\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas tristique nec odio id euismod. Curabitur euismod ex volutpat bibendum eleifend. Donec pretium pretium nibh, at consectetur turpis porta et. Mauris pretium risus eget feugiat pellentesque. Quisque egestas sodales turpis, et euismod nisl vulputate at. Etiam convallis, turpis eu suscipit viverra, nulla ipsum vehicula metus, vitae posuere metus urna vitae dolor. Aliquam commodo non mi rutrum pretium. Ut facilisis turpis id quam tempus condimentum. In dapibus non est ac facilisis. Nulla vulputate suscipit ligula, vel finibus arcu ullamcorper id. Duis ut facilisis felis. Nam ullamcorper nunc eget sapien placerat aliquet. Etiam nec elit risus. In vitae neque et felis imperdiet rutrum. Sed sed metus erat. Duis sit amet lacus eu nisl fringilla venenatis.

<i>Proin nulla nunc, eleifend nec turpis feugiat, iaculis scelerisque nisl. In vehicula id dui vitae varius. Cras rutrum vitae odio convallis accumsan. Sed eu ullamcorper lorem. Donec et sagittis magna. Nulla ac nisi dui. Praesent faucibus justo vel tortor consequat hendrerit. Phasellus libero leo, suscipit ac neque at, laoreet luctus est.</i>
";

$txtb = "<u>Phasellus eu suscipit turpis</u>. Ut bibendum gravida lacus, sit amet blandit dolor porta rhoncus. Suspendisse et tempor augue, quis fermentum nulla. Cras diam nisi, porttitor eu lectus nec, vestibulum sagittis eros. Nam congue, urna accumsan eleifend porttitor, nunc odio convallis metus, sit amet euismod turpis orci vitae sem. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque egestas neque elit, quis fermentum ipsum ornare in. Maecenas accumsan dui diam, eget condimentum sapien efficitur sit amet. In hac habitasse platea dictumst. Nunc nunc ligula, scelerisque quis laoreet nec, sollicitudin eu nulla. Morbi interdum enim sed ultricies dictum. Vestibulum suscipit iaculis sapien ac imperdiet. Cras sed turpis quam. Aliquam ornare a sem ut consequat. In hac habitasse platea dictumst.

Maecenas orci metus, interdum vel risus aliquam, condimentum dignissim lectus. Ut sit amet vulputate massa. Praesent mollis commodo tortor, vitae scelerisque libero. Donec tincidunt tortor tortor, ac venenatis libero porttitor sed. Ut luctus, nisl ut rutrum pretium, nisl sem gravida nisi, nec tristique neque risus sollicitudin mauris. Praesent elit lectus, molestie sit amet diam at, sollicitudin varius dui. In congue luctus nisi, at dictum mauris convallis vel. Aenean nec varius risus, et convallis erat. Morbi ac ipsum volutpat, aliquam libero vitae, faucibus elit. Ut dapibus dolor sed aliquam condimentum. Cras pretium, dolor a dignissim sagittis, risus sem convallis orci, non molestie arcu velit vitae metus.";

$pdf->ezColumnsStart(['num' => 2]);
$options = ['justification' => 'full'];
$pdf->ezText($txt, 0, $options);
$pdf->ezNewPage();
$pdf->ezText($txta, 0, $options);
$data = [
    ['num' => 1, 'name' => 'gandalf', 'type' => 'wizard']
    ,
    [
        'num' => 2,
        'name' => 'bilbo',
        'type' => 'hobbit',
        'url' => 'https://github.com/rospdf/pdf-php'
    ]
    ,
    ['num' => 3, 'name' => 'frodo', 'type' => 'hobbit']
    ,
];

$pdf->ezTable($data, '', '', [
    'gridlines' => EZ_GRIDLINE_DEFAULT,
    'shadeHeadingCol' => [0.6, 0.6, 0.5],
    'alignHeadings' => 'center',
    'width' => $pdf->ezContentWidth(),
    'cols' => [
        'name' => ['bgcolor' => [0.9, 0.9, 0.7]],
        'type' => ['bgcolor' => [0.6, 0.4, 0.2]]
    ]
]);
$pdf->ezText("\n$txtb", 0, $options);
$pdf->ezColumnsStop();

if (isset($_GET['d']) && $_GET['d']) {
    echo "<pre>" . $pdf->ezOutput(true) . "</pre>";
} else {
    $pdf->ezStream();
}
