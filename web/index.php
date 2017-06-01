<?php
/**
 * Author: Joris Rietveld <jorisrietveld@gmail.com>
 * Created: 25-05-2017 15:27
 * Licence: GNU General Public licence version 3 <https://www.gnu.org/licenses/quick-guide-gplv3.html>
 */
declare(strict_types=1);

require __DIR__ . DIRECTORY_SEPARATOR . "bootstrap.php";
$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

if ($request->server->get('SERVER_NAME') === 'loc.api.inf1i.ga')
{

}
else
{
    ?>
    <!doctype html>
    <head>
        <meta charset="UTF-8"/>
        <meta lang="ENG"/>
        <title>INF1i - WaterUpApi</title>
        <style>
            body {
                text-align: center;
                padding: 150px;
            }

            h1 {
                font-size: 50px;
            }

            body {
                font: 20px Helvetica, sans-serif;
                color: #333;
            }

            article {
                display: block;
                text-align: left;
                width: 650px;
                margin: 0 auto;
            }

            a {
                color: #00AF00;
                text-decoration: none;
            }

            a:hover {
                color: #333;
                text-decoration: none;
            }
        </style>
    </head>
    <body>
    <article>
        <h1>Inf1i WaterUp Api</h1>
        <div>
            <p>Welcome to the INF1i WaterUp projects api. The api is currently being constructed, if you like to
                <a href="mailto:jorisrietveld@gmail.com">contact us</a> feel free to send a mail.</p>
            <p>&mdash; The INF1I project team </p>
        </div>
    </article>
    </body>
    <?php
}
?>