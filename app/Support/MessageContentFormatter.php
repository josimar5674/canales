<?php

namespace App\Support;

use Illuminate\Support\HtmlString;

class MessageContentFormatter
{
    public function render(string $content): HtmlString
    {
        $content = str_replace(["\r\n", "\r"], "\n", $content);

        $lines = explode("\n", $content);

        $html = '';
        $textBuffer = [];
        $tableBuffer = [];

        $flushText = function () use (&$html, &$textBuffer) {

            if (empty($textBuffer)) {
                return;
            }

            $text = implode("\n", $textBuffer);

            $html .= '<div class="leading-6 whitespace-pre-wrap break-words">';
            $html .= e($text);
            $html .= '</div>';

            $textBuffer = [];
        };

        $flushTable = function () use (&$html, &$tableBuffer) {

            if (empty($tableBuffer)) {
                return;
            }

            $rows = [];

            foreach ($tableBuffer as $line) {

                $columns = explode("\t", $line);

                $columns = array_map(
                    fn ($column) => trim($column),
                    $columns
                );

                $rows[] = $columns;
            }

            $columnCount = max(
                array_map('count', $rows)
            );

            $html .= '
                <div class="my-3 w-full overflow-x-auto">
                    <table class="
                        border-collapse
                        text-sm
                        min-w-max
                        w-full
                        border
                        border-gray-300
                        dark:border-gray-600
                        
                    ">
            ';

            foreach ($rows as $index => $row) {

                $html .= '<tr>';

                for ($i = 0; $i < $columnCount; $i++) {

                    $value = $row[$i] ?? '';

                    if ($index === 0) {

                        $html .= '
  <th class="
    border
    border-gray-300
    dark:border-gray-600
    px-3
    py-2
    text-left
    font-semibold
    bg-white
    text-gray-800
">
    ' . e($value) . '
</th>
                        ';

                    } else {

                        $html .= '
                            <td class="
                                border
                                border-gray-300
                                dark:border-gray-600
                                px-3
                                py-2
                                text-gray-700
                                dark:text-gray-300
                                
                            ">
                                ' . e($value) . '
                            </td>
                        ';
                    }
                }

                $html .= '</tr>';
            }

            $html .= '
                    </table>
                </div>
            ';

            $tableBuffer = [];
        };

        $isTableLine = function (string $line): bool {

            return str_contains($line, "\t");
        };

        $i = 0;

        while ($i < count($lines)) {

            $line = $lines[$i];

            /*
             * Detectamos el inicio de una posible tabla.
             */
            if ($isTableLine($line)) {

                $possibleTable = [];
                $j = $i;

                while (
                    $j < count($lines)
                    && $isTableLine($lines[$j])
                ) {

                    $possibleTable[] = $lines[$j];

                    $j++;
                }

                /*
                 * Una tabla debe tener al menos
                 * dos filas.
                 */
                if (count($possibleTable) >= 2) {

                    /*
                     * Verificamos que las filas tengan
                     * una estructura consistente.
                     */
                    $columnCounts = array_map(
                        function ($row) {
                            return count(explode("\t", $row));
                        },
                        $possibleTable
                    );

                    $firstColumnCount = $columnCounts[0];

                    $validTable = $firstColumnCount >= 2;

                    foreach ($columnCounts as $count) {

                        if ($count !== $firstColumnCount) {
                            $validTable = false;
                            break;
                        }
                    }

                    if ($validTable) {

                        $flushText();

                        $tableBuffer = $possibleTable;

                        $flushTable();

                        $i = $j;

                        continue;
                    }
                }
            }

            $textBuffer[] = $line;

            $i++;
        }

        $flushTable();
        $flushText();

        return new HtmlString($html);
    }
}