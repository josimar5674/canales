<?php

namespace App\Support;

use Illuminate\Support\HtmlString;

class MessageContentFormatter


{

    public function hasTable(string $content): bool
{
    $content = str_replace(["\r\n", "\r"], "\n", $content);

    $lines = explode("\n", $content);

    $validRows = 0;

    foreach ($lines as $line) {

        if (!str_contains($line, "\t")) {
            continue;
        }

        $columns = explode("\t", $line);

        $nonEmptyColumns = 0;

        foreach ($columns as $column) {

            if (trim($column) !== '') {
                $nonEmptyColumns++;
            }
        }

        /*
         * Una fila con al menos 2 valores
         * es suficiente para considerarla
         * parte de una posible tabla.
         */
        if ($nonEmptyColumns >= 2) {

            $validRows++;

            /*
             * Con dos filas estructuradas
             * consideramos que existe tabla.
             */
            if ($validRows >= 2) {
                return true;
            }
        }
    }

    return false;
}
    public function render(string $content): HtmlString
    {
        $content = str_replace(["\r\n", "\r"], "\n", $content);

        $lines = explode("\n", $content);

        $html = '';
        $textBuffer = [];
        $tableBuffer = [];

        /*
         * =========================================================
         * TEXTO NORMAL
         * =========================================================
         */
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

        /*
         * =========================================================
         * RENDERIZAR TABLA
         * =========================================================
         */
        $flushTable = function () use (&$html, &$tableBuffer) {

            if (empty($tableBuffer)) {
                return;
            }

            /*
             * Buscar la cantidad máxima de columnas.
             */
            $columnCount = 0;

            foreach ($tableBuffer as $row) {

                if (
                    is_array($row)
                    && isset($row['_type'])
                ) {

                    if (
                        isset($row['_column_count'])
                        && $row['_column_count'] > 0
                    ) {

                        $columnCount = max(
                            $columnCount,
                            $row['_column_count']
                        );
                    }

                    continue;
                }

                $columnCount = max(
                    $columnCount,
                    count($row)
                );
            }

            if ($columnCount < 2) {

                $tableBuffer = [];

                return;
            }

            /*
             * =====================================================
             * TABLA
             * =====================================================
             */
$html .= '
    <div class="my-3 w-full max-w-full min-w-0 overflow-x-auto">
        <table
            class="
                border-collapse
                text-sm
                border
                border-gray-300
                dark:border-gray-600
            "
        >
';

            $headerRendered = false;

            foreach ($tableBuffer as $row) {

                /*
                 * =================================================
                 * TÍTULO / CELDA COMBINADA SUPERIOR
                 * =================================================
                 */
                if (
                    is_array($row)
                    && isset($row['_type'])
                    && $row['_type'] === 'combined'
                ) {

                    $html .= '
                        <tr>
                            <th
                                colspan="' . $columnCount . '"
                                class="
                                    border
                                    border-gray-300
                                    dark:border-gray-600
                                    px-3
                                    py-2
                                    text-center
                                    font-semibold
                                    bg-gray-200
                                    text-gray-900
                                "
                            >
                                ' . e($row['_value']) . '
                            </th>
                        </tr>
                    ';

                    continue;
                }

                /*
                 * =================================================
                 * TOTAL / SUBTOTAL
                 * =================================================
                 */
                if (
                    is_array($row)
                    && isset($row['_type'])
                    && $row['_type'] === 'footer'
                ) {

                    $label = $row['_label'] ?? 'Total';
                    $value = $row['_value'] ?? '';

                    /*
                     * Posición original del valor.
                     *
                     * Ejemplo:
                     *
                     * Total | | | | | | | | 11663.49
                     *
                     * Si el valor estaba en la columna 9,
                     * respetamos esa posición.
                     */
                    $valueIndex = $row['_value_index'] ?? ($columnCount - 1);

                    /*
                     * Asegurarnos de que esté dentro de la tabla.
                     */
                    $valueIndex = max(
                        1,
                        min(
                            $valueIndex,
                            $columnCount - 1
                        )
                    );

                    $labelColspan = $valueIndex;

                    $html .= '<tr>';

                    /*
                     * CELDA TOTAL
                     */
                    $html .= '
                        <td
                            colspan="' . $labelColspan . '"
                            class="
                                border
                                border-gray-300
                                dark:border-gray-600
                                px-3
                                py-2
                                font-semibold
                                text-gray-700
                                dark:text-gray-300
                            "
                        >
                            ' . e($label) . '
                        </td>
                    ';

                    /*
                     * VALOR DEL TOTAL
                     */
                    $html .= '
                        <td
                            class="
                                border
                                border-gray-300
                                dark:border-gray-600
                                px-3
                                py-2
                                font-semibold
                                text-gray-700
                                dark:text-gray-300
                            "
                        >
                            ' . e($value) . '
                        </td>
                    ';

                    /*
                     * Celdas restantes.
                     */
                    for (
                        $i = $valueIndex + 1;
                        $i < $columnCount;
                        $i++
                    ) {

                        $html .= '
                            <td
                                class="
                                    border
                                    border-gray-300
                                    dark:border-gray-600
                                    px-3
                                    py-2
                                    text-gray-700
                                    dark:text-gray-300
                                "
                            ></td>
                        ';
                    }

                    $html .= '</tr>';

                    continue;
                }

                /*
                 * =================================================
                 * FILA NORMAL
                 * =================================================
                 */
                $html .= '<tr>';

                $isHeader = !$headerRendered;

                for ($i = 0; $i < $columnCount; $i++) {

                    $value = $row[$i] ?? '';

                    if ($isHeader) {

                        /*
                         * ENCABEZADOS
                         *
                         * Blanco + texto negro.
                         */
                        $html .= '
                            <th
                                class="
                                    border
                                    border-gray-300
                                    dark:border-gray-600
                                    px-3
                                    py-2
                                    text-left
                                    font-semibold
                                    bg-white
                                    text-gray-900
                                "
                            >
                                ' . e($value) . '
                            </th>
                        ';

                    } else {

                        /*
                         * DATOS
                         */
                        $html .= '
                            <td
                                class="
                                    border
                                    border-gray-300
                                    dark:border-gray-600
                                    px-3
                                    py-2
                                    text-gray-700
                                    dark:text-gray-300
                                "
                            >
                                ' . e($value) . '
                            </td>
                        ';
                    }
                }

                $html .= '</tr>';

                if ($isHeader) {

                    $headerRendered = true;
                }
            }

            $html .= '
                    </table>
                </div>
            ';

            $tableBuffer = [];
        };

        /*
         * =========================================================
         * DIVIDIR UNA FILA EN COLUMNAS
         * =========================================================
         */
        $parseColumns = function (string $line): array {

            $columns = explode("\t", $line);

            return array_map(
                fn ($column) => trim($column),
                $columns
            );
        };

        /*
         * =========================================================
         * DETERMINAR SI UNA FILA TIENE ESTRUCTURA TABULAR
         * =========================================================
         *
         * Importante:
         *
         * Una línea con solamente TAB al final NO es considerada
         * automáticamente una fila de tabla.
         *
         * Esto permite detectar correctamente:
         *
         * Pagos de trabajadores...
         *
         * aunque Outlook haya dejado TABs al final.
         */
        $isTableLine = function (string $line) use ($parseColumns): bool {

            if (!str_contains($line, "\t")) {

                return false;
            }

            $columns = $parseColumns($line);

            $nonEmptyColumns = 0;

            foreach ($columns as $column) {

                if ($column !== '') {

                    $nonEmptyColumns++;
                }
            }

            /*
             * Una fila normal debe tener al menos
             * dos valores.
             */
            return $nonEmptyColumns >= 2;
        };

        /*
         * =========================================================
         * ENCABEZADO COMBINADO
         * =========================================================
         */
        $isCombinedHeader = function (string $line): bool {

            $line = trim($line);

            if ($line === '') {

                return false;
            }

            /*
             * Si después de trim no hay TAB,
             * puede ser un título combinado.
             */
            if (str_contains($line, "\t")) {

                return false;
            }

            if (mb_strlen($line) < 5) {

                return false;
            }

            /*
             * Evitamos considerar frases normales
             * como encabezados combinados.
             *
             * Aceptamos:
             *
             * PAGOS DE TRABAJADORES
             * IMPUESTO AMDC BIENES INMUEBLES 2026
             *
             * También permitimos títulos normales que tengan
             * la primera letra en mayúscula.
             */
            return true;
        };

        /*
         * =========================================================
         * TOTAL
         * =========================================================
         */
        $isFooterLabel = function (string $value): bool {

            $value = trim($value);

            $normalized = mb_strtolower($value);

            return
                $normalized === 'total' ||
                $normalized === 'subtotal' ||
                $normalized === 'total general' ||
                $normalized === 'gran total';
        };

        /*
         * =========================================================
         * VALOR MONETARIO
         * =========================================================
         */
        $isMoneyValue = function (string $value): bool {

            $value = trim($value);

            if ($value === '') {

                return false;
            }

            return preg_match(
                '/^[L$€£]?\s?[\d,.]+$/u',
                $value
            ) === 1;
        };

        /*
         * =========================================================
         * PROCESAR MENSAJE
         * =========================================================
         */
        $i = 0;

        while ($i < count($lines)) {

            $line = $lines[$i];

            /*
             * =====================================================
             * POSIBLE TABLA
             * =====================================================
             */
            if ($isTableLine($line)) {

                $possibleTable = [];

                /*
                 * -------------------------------------------------
                 * BUSCAR TÍTULO ANTERIOR
                 * -------------------------------------------------
                 */
                $combinedHeader = null;

                if ($i > 0) {

                    $previousLine = $lines[$i - 1];

                    if ($isCombinedHeader($previousLine)) {

                        $combinedHeader = trim($previousLine);

                        /*
                         * Eliminarlo del texto normal.
                         */
                        if (!empty($textBuffer)) {

                            $lastTextIndex = count($textBuffer) - 1;

                            if (
                                trim($textBuffer[$lastTextIndex])
                                === $combinedHeader
                            ) {

                                array_pop($textBuffer);
                            }
                        }
                    }
                }

                /*
                 * -------------------------------------------------
                 * RECORRER FILAS
                 * -------------------------------------------------
                 */
                $j = $i;

                while ($j < count($lines)) {

                    $currentLine = trim($lines[$j]);

                    /*
                     * ---------------------------------------------
                     * TOTAL / SUBTOTAL
                     * ---------------------------------------------
                     *
                     * Lo comprobamos ANTES de isTableLine(),
                     * porque un Total también puede tener TABs.
                     */
                    $currentColumns = [];

                    if (str_contains($currentLine, "\t")) {

                        $currentColumns = $parseColumns($currentLine);
                    } else {

                        $currentColumns = [$currentLine];
                    }

                    /*
                     * Detectar una fila que empieza con TOTAL.
                     */
                    if (
                        isset($currentColumns[0])
                        &&
                        $isFooterLabel($currentColumns[0])
                    ) {

                        $valueIndex = null;
                        $value = '';

                        /*
                         * Buscar el primer valor no vacío después
                         * de "Total".
                         */
                        for (
                            $k = 1;
                            $k < count($currentColumns);
                            $k++
                        ) {

                            if ($currentColumns[$k] !== '') {

                                $valueIndex = $k;
                                $value = $currentColumns[$k];

                                break;
                            }
                        }

                        /*
                         * Si encontramos un valor, guardar footer.
                         */
                        if ($valueIndex !== null) {

                            $possibleTable[] = [
                                '_type' => 'footer',
                                '_label' => $currentColumns[0],
                                '_value' => $value,
                                '_value_index' => $valueIndex,
                                '_column_count' => count($currentColumns),
                            ];

                            $j++;

                            continue;
                        }
                    }

                    /*
                     * ---------------------------------------------
                     * FILA NORMAL
                     * ---------------------------------------------
                     */
                    if ($isTableLine($currentLine)) {

                        $possibleTable[] = $currentLine;

                        $j++;

                        continue;
                    }

                    /*
                     * ---------------------------------------------
                     * TOTAL SIN TAB
                     * ---------------------------------------------
                     *
                     * Ejemplo:
                     *
                     * L31,000,000.00
                     */
                    if (
                        !empty($possibleTable)
                        &&
                        $isMoneyValue($currentLine)
                    ) {

                        $possibleTable[] = [
                            '_type' => 'footer',
                            '_label' => 'Total',
                            '_value' => $currentLine,
                            '_value_index' => 0,
                            '_column_count' => 0,
                        ];

                        $j++;

                        continue;
                    }

                    /*
                     * Ya no pertenece a la tabla.
                     */
                    break;
                }

                /*
                 * =================================================
                 * VALIDAR QUE REALMENTE SEA TABLA
                 * =================================================
                 */
                if (count($possibleTable) >= 2) {

                    $normalRows = [];

                    foreach ($possibleTable as $possibleRow) {

                        /*
                         * Filas especiales.
                         */
                        if (
                            is_array($possibleRow)
                            && isset($possibleRow['_type'])
                        ) {

                            $normalRows[] = $possibleRow;

                            continue;
                        }

                        /*
                         * Filas normales.
                         */
                        $normalRows[] = $parseColumns(
                            $possibleRow
                        );
                    }

                    /*
                     * Buscar la primera fila normal.
                     */
                    $firstColumnCount = 0;

                    foreach ($normalRows as $row) {

                        if (
                            is_array($row)
                            && isset($row['_type'])
                        ) {

                            continue;
                        }

                        $firstColumnCount = count($row);

                        break;
                    }

                    /*
                     * Debe haber al menos 2 columnas.
                     */
                    $validTable = $firstColumnCount >= 2;

                    /*
                     * Validar que haya al menos una fila
                     * con estructura real.
                     */
                    if ($validTable) {

                        $normalRowCount = 0;

                        foreach ($normalRows as $row) {

                            if (
                                is_array($row)
                                && isset($row['_type'])
                            ) {

                                continue;
                            }

                            /*
                             * Una fila puede tener menos columnas.
                             * Simplemente la rellenaremos.
                             */
                            if (count($row) >= 2) {

                                $normalRowCount++;
                            }
                        }

                        $validTable = $normalRowCount >= 2;
                    }

                    /*
                     * =================================================
                     * CONSTRUIR TABLA
                     * =================================================
                     */
                    if ($validTable) {

                        $flushText();

                        /*
                         * Título combinado.
                         */
                        if ($combinedHeader !== null) {

                            $tableBuffer[] = [
                                '_type' => 'combined',
                                '_value' => $combinedHeader,
                                '_colspan' => $firstColumnCount,
                                '_column_count' => $firstColumnCount,
                            ];
                        }

                        /*
                         * Filas.
                         */
                        foreach ($normalRows as $row) {

                            /*
                             * Footer.
                             */
                            if (
                                is_array($row)
                                && isset($row['_type'])
                                && $row['_type'] === 'footer'
                            ) {

                                /*
                                 * Si no conocemos la posición,
                                 * colocar el total en la última
                                 * columna.
                                 */
                                if (
                                    empty($row['_value_index'])
                                    &&
                                    $row['_value_index'] !== 0
                                ) {

                                    $row['_value_index'] =
                                        $firstColumnCount - 1;
                                }

                                $tableBuffer[] = $row;

                                continue;
                            }

                            /*
                             * Fila normal.
                             *
                             * Si tiene menos columnas que el
                             * encabezado, se completa con vacíos.
                             */
                            while (
                                count($row) < $firstColumnCount
                            ) {

                                $row[] = '';
                            }

                            /*
                             * Si por alguna razón tiene más,
                             * conservamos solamente las columnas
                             * esperadas.
                             */
                            if (
                                count($row) > $firstColumnCount
                            ) {

                                $row = array_slice(
                                    $row,
                                    0,
                                    $firstColumnCount
                                );
                            }

                            $tableBuffer[] = $row;
                        }

                        /*
                         * Renderizar.
                         */
                        $flushTable();

                        /*
                         * Continuar después de la tabla.
                         */
                        $i = $j;

                        continue;
                    }
                }
            }

            /*
             * =====================================================
             * TEXTO NORMAL
             * =====================================================
             */
            $textBuffer[] = $line;

            $i++;
        }

        /*
         * Vaciar buffers.
         */
        $flushTable();
        $flushText();

        return new HtmlString($html);
    }
}