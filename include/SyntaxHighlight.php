<?php

// Simple and lightweight PHP highlight class
//
// Based on code by Dominic Szablewski
// http://phoboslab.org/log/2007/08/generic-syntax-highlighting-with-regular-expressions
//
//
// echo SyntaxHighlight::process( $your_code );
//
class SyntaxHighlight {
	
	static $tokens = array(); // This array will be filled from the regexp-callback
    
    static $colors = [
        'N' => '#e700f1',    // Numbers (Dark Purple)
        'S' => '#982603',    // Strings (Purple)
        'C' => '#808080',    // Comments (Gray)
        'K' => '#0000FF',    // Keywords (Blue)
        'V' => '#007869',    // Vars (Twice Darker)
        'D' => '#FF9A5D',    // Defines (Orange)
        'P' => '#444444',    // Punctuation (Black)
    ];

    public static function hex2float($hex) {
        // Remove the leading hash symbol if present
        $hex = ltrim($hex, '#');

        // Extract individual color components
        $red = hexdec(substr($hex, 0, 2));
        $green = hexdec(substr($hex, 2, 2));
        $blue = hexdec(substr($hex, 4, 2));

        // Normalize the color values to the range [0, 1]
        $red = number_format($red/255, 2);
        $green = number_format($green/255, 2);
        $blue = number_format($blue/255, 2);

        // Return the normalized color values as an array
        return "$red,$green,$blue";
    }

    public static function floatColor($type){
        return self::hex2float(self::$colors[$type]);
    }

    public static function colorTag($type, $content) {
        return '<colorspan ' .$type.'>'. $content . '</colorspan>';
    }

	public static function process($s) {
        $s = htmlspecialchars($s);

        // Workaround for escaped backslashes
        $s = str_replace('\\\\','\\\\<e>', $s);

        $regexp = [

            // Punctuations
            '/([\-\!\%\^\*\(\)\+\|\~\=\`\{\}\[\]\:\"\'<>\?\,\.\/]+)/'
            => self::colorTag('P', '$1'),

            // Numbers (also look for Hex)
            '/(?<!\w)(
                (0x|\#)[\da-f]+|
                \d+|
                \d+(px|em|cm|mm|rem|s|\%)
            )(?!\w)/ix'
            => self::colorTag('N', '$1'),

            // Make the bold assumption that an
            // all uppercase word has a special meaning
            '/(?<!\w|>|\#)(
                [A-Z_0-9]{2,}
            )(?!\w)/x'
            => self::colorTag('D', '$1'),

            // Keywords
            '/(?<!\w|\$|\%|\@|>)('
                
				.'abstract|and|array|array|array_cast|array_splice|as|
				bool|boolean|break|
				case|catch|char|class|clone|close|const|continue|
				declare|default|define|delete|die|do|double|
				echo|else|elseif|empty|enddeclare|endfor|endforeach|endif|endswitch|
				endwhile|eval|exit|exit|explode|extends|eventsource|
				false|file|file_exists|final|finally|float|flush|for|foreach|function|
				global|goto|
				header|
				if|implements|implode|include|include_once|ini_set|instanceof|int|integer|interface|isset|
				json_encode|json_decode
				list|long|
				namespace|new|new|null|
				ob_flush|object|on|or|
				parse|print|private|protected|public|published|
				real|require|require_once|resource|return|
				self|short|signed|sleep|static|string|struct|switch|
				then|this|throw|true|try|
				unset|unsigned|use|usleep|
				var|var|void|
				while|
				xor|'
				
				.'RewriteEngine|RewriteRule|ErrorDocument
            )(?!\w|=")/ix'
            => self::colorTag('K', '$1'),

            // PHP/Perl-Style Vars: $var, %var, @var
            '/(?<!\w)(
                (\$|\%|\@)(\-&gt;|\w)+
            )(?!\w)/ix'
            => self::colorTag('V', '$1')

        ];

		 $s = preg_replace_callback( '/(
                \/\*.*?\*\/|
                \/\/.*?\n|
                \#.[^a-fA-F0-9]+?\n|
                \&lt;\!\-\-[\s\S]+\-\-\&gt;|
                (?<!\\\)&quot;.*?(?<!\\\)&quot;|
                (?<!\\\)\'(.*?)(?<!\\\)\'
            )/isx' , array(self::class, 'replaceId'),$s);
			
        $s = preg_replace(array_keys($regexp), array_values($regexp), $s);

        // Paste the comments and strings back in again
        $s = str_replace(array_keys(SyntaxHighlight::$tokens), array_values(SyntaxHighlight::$tokens), $s);

        // Delete the "Escaped Backslash Workaround Token" (TM)
        // and replace tabs with four spaces.
        $s = str_replace(array('<e>', "\t"), array('', '    '), $s);

        $pattern = '/<colorspan (\w)>/i';

        $s = preg_replace_callback($pattern, function($matches) {
            $char = $matches[1];
            $ordValue = self::floatColor($char);
            return "<c:color:$ordValue>";
        }, $s);
        $s = str_replace('</colorspan>', '</c:color>', $s);

        return '<pre>'.html_entity_decode($s).'</pre>';
    }

    // Regexp-Callback to replace every comment or string with a uniqid and save
    // the matched text in an array
    // This way, strings and comments will be stripped out and wont be processed
    // by the other expressions searching for keywords etc.
    public static function replaceId($match) {
        $id = "##r" . uniqid() . "##";
	
        // String or Comment?
        if(substr($match[1], 0, 2) == '//' || substr($match[1], 0, 2) == '/*' || substr($match[1], 0, 2) == '##' || substr($match[1], 0, 7) == '&lt;!--') {
            SyntaxHighlight::$tokens[$id] = self::colorTag('C', $match[1]);
        } else {
           SyntaxHighlight::$tokens[$id] = self::colorTag('S', $match[1]);
        }
		
        return $id;
    }
}