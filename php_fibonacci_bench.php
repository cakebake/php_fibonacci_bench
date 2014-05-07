<?php
    //ini_set('memory_limit', '1024M');
    //ini_set('max_execution_time', 300); //300 = 5m
    $fibonacci = php_fibonacci_bench(isset($_GET['max_numbers']) ? $_GET['max_numbers'] : 100); // 1, 1, 2, 3, 5, 8, 13, 21, 34, 55, ...
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Fibonacci sequence</title>
        <style type="text/css">
        /* <![CDATA[ */
            .container {
                width:80%;
                margin:20px auto;
                word-wrap: break-word;
                -webkit-hyphens: auto;
                -moz-hyphens: auto;
                -o-hyphens: auto;
                -ms-hyphens: auto;
                hyphens: auto;
                text-align:left;
                color:#666;
                font-family: Courier, Verdana, Arial;
                font-size:11px;
                line-height:1;
            }
            b, strong {
                font-weight:bolder;
                color:black;
            }
            table {
                width:100%;
                border-spacing:5px;
            }
            table td {
                border-style: ridge;
                border-width:1px;
                border-color:#DDD;
                background-color:#FFF;
                border-radius:3px;
                padding:15px 10px;
                vertical-align:top;
            }
            table td.k {
                background-color:#F1F1F1;
                font-weight:bold;
                text-shadow: 1px 1px #FFF;
                border-right-color:#AAA;
                border-bottom-color:#AAA;
                width:34%;
                text-align:right;
            }
            table td.v {
                min-width:377px;
                border-color:#EEE;
            }
            table td.v .big {
                max-width:377px;
            }
        /* ]]> */
        </style>
    </head>
    <body>
        <div class="container">
            <form action="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>" method="GET">
                <h1>Fibonacci number statistics*</h1>
                <table>
                    <tr>
                        <td class="k"><label for="max_numbers">Fibonacci sequence steps:</label></td>
                        <td class="v">
                            <input id="max_numbers" name="max_numbers" type="text" value="<?= count($fibonacci['fibonacci']) ?>" /><input type="submit" value="Start" />
                        </td>
                    </tr>
                    <tr>
                        <td class="k">Fibonacci sequence time:</td>
                        <td class="v"><?= $fibonacci['_diff_seconds'] ?> Seconds (<?= round($fibonacci['_time_used_percent'], 2) ?>% of <?= $fibonacci['_time_available'] ?> Seconds)</td>
                    </tr>
                    <tr>
                        <td class="k">Fibonacci sequence used memory:</td>
                        <td class="v"><?= $fibonacci['_mem_used_mb'] ?>MB (<?= round($fibonacci['_mem_used_percent'], 2) ?>% of <?= $fibonacci['_mem_available_mb'] ?>MB)</td>
                    </tr>
                    <tr>
                        <td class="k">Fibonacci Number Range:</td>
                        <td class="v">
                            <div class="big">
                                [<?= $fibonacci['fibonacci'][1] ?>]&nbsp;<strong>to</strong>&nbsp;[<?= $fibonacci['fibonacci'][count($fibonacci['fibonacci'])] ?>]
                                <br /><br />
                                <?php if (!isset($_GET['print_range'])) : ?>
                                    <a href="<?= strtok($_SERVER['REQUEST_URI'], '?') ?>?max_numbers=<?= count($fibonacci['fibonacci']) ?>&print_range=true" title="WARNING">Show all Numbers (<?= count($fibonacci['fibonacci']) ?>)</a>
                                <?php else : ?>
                                    <a href="<?= strtok($_SERVER['REQUEST_URI'], '?') ?>?max_numbers=<?= count($fibonacci['fibonacci']) ?>">Hide all Numbers (<?= count($fibonacci['fibonacci']) ?>)</a>
                                    <pre><?php var_dump($fibonacci['fibonacci']) ?></pre>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="k">Biggest Fibonacci Number Length:</td>
                        <td class="v"><?= strlen($fibonacci['fibonacci'][count($fibonacci['fibonacci'])]) ?></td>
                    </tr>
                </table>
            </form>
            <p>* <a href="http://en.wikipedia.org/wiki/Fibonacci_number" target="_blank">What is Fibonacci sequence?</a></p>
        </div>
    </body>
</html>

<?php
/**
* Builds a List of very big Fibonacci numbers
*
* @author Jens A.
* @version 0.1
* @see http://en.wikipedia.org/wiki/Fibonacci_number
* @see http://fengmk2.cnpmjs.org/blog/2011/fibonacci/nodejs-python-php-ruby-lua.html
*
* @param int $max Fibonacci sequence steps
* @param int $in Chosen starting point of the sequence
* @return array The fibonacci numbers with php processing information
*/
function php_fibonacci_bench($max = 10, $in = 1)
{
    $current = 0;

    $out['_start'] = microtime(true);
    while ($current++ < $max) {
        if ($current > 2) {
            $out['fibonacci'][$current] = bcadd($out['fibonacci'][$current-1], $out['fibonacci'][$current-2]);
        } else {
            $out['fibonacci'][$current] = (string)$in;
        }
    }
    $out['_end'] = microtime(true);
    $out['_mem_used_bytes'] = memory_get_usage();
    $out['_mem_available_mb'] = (int)ini_get('memory_limit');
    $out['_mem_used_kb'] = $out['_mem_used_bytes'] / 1024;
    $out['_mem_used_mb'] = $out['_mem_used_kb'] / 1024;
    $out['_mem_used_percent'] =  $out['_mem_used_mb'] / ($out['_mem_available_mb'] / 100);
    $out['_diff_seconds'] = $out['_end'] - $out['_start'];
    $out['_time_available'] = (int)ini_get('max_execution_time');
    $out['_time_used_percent'] = $out['_diff_seconds'] / ($out['_time_available'] / 100);

    return $out;
}
?>