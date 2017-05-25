<?php
/**
 * Author: Joris Rietveld <jorisrietveld@gmail.com>
 * Created: 25-05-2017 12:30
 * Licence: GNU General Public licence version 3 <https://www.gnu.org/licenses/quick-guide-gplv3.html>
 */
declare(strict_types=1);

/**
 * An function that returnes an fancy dump of the file where an error occurred.
 *
 * @param string $fileName
 * @param int    $lineNumber
 */
function highlightErrorInFile(string $fileName, int $lineNumber)
{
    $returnString = '';

    if (file_exists($fileName))
    {
        $fileLines = file($fileName);
        $range = range($lineNumber - 10, $lineNumber + 10);
        foreach ($range as $line)
        {
            if (array_key_exists($line, $fileLines))
            {
                $returnString .= $fileLines[ $line ];
            }
        }
    }

    return str_replace(['&lt;?php&nbsp;&nbsp;&nbsp;&nbsp;', '?&gt;'], ['', ''], highlight_string('<?php' . $returnString . '?>', true));
}

/**
 * Register an uncaught exception handler if something goes wrong and the exception is not caught by the framework.
 */
set_exception_handler(function ($exception)
{
    if (CLI)
    {
        var_dump($exception);

        return;
    }

    $head = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"/><title>Error!</title></head><body>';
    if (DEBUG)
    {
        echo $head;
        echo '<h1>An unhandled exception was thrown!</h1>';
        if (function_exists('dump'))
        {
            dump($exception);
        }
        else
        {
            var_dump($exception);
        }

        echo '<h3>It happened somewhere in this part of the code</h3>';
        echo highlightErrorInFile($exception->getFile(), $exception->getLine());

        echo '</body>';
    }
    else
    {
        error_log('Fatal uncaught exeption: ' . $exception->getMessage());
        echo $head;
        echo 'Oops... er ging iets mis probeer het later opnieuw of neem <a href="mailto:jorisrietveld@gmail.com">contact</a> op met de administrator.</body>"';
    }
});

/**
 * Register an error handler for when an ErrorException is thrown but never caught. It should never happen but just to
 * be sure...
 */
set_error_handler(function ($errorCode, $errorMessage, $inFile, $onLineNumber)
{
    if (CLI)
    {
        var_dump($errorMessage);

        return;
    }

    $head = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"/><title>Error!</title></head><body>';
    if (DEBUG)
    {
        echo $head;
        echo '<h1>There occurred an ErrorException that was never caught!</h1>';
        echo "<p>";
        printf("Error code: %s \n", $errorCode);
        printf("Error message: %s \n", $errorMessage);
        echo '<h3>It happened somewhere in this part of the code</h3>';
        echo highlightErrorInFile($inFile, $onLineNumber);
        echo "<p>";
        echo '</body>';
    }
    else
    {
        error_log(sprintf('Uncaught ErrorException code: %s message: %s In file: %s On line: %s ', $errorCode, $errorMessage, $inFile, $onLineNumber));
        echo $head;
        echo 'Oops... er ging iets mis probeer het later opnieuw of neem <a href="mailto:jorisrietveld@gmail.com">contact</a> op met de administrator.</body>"';
    }
});


