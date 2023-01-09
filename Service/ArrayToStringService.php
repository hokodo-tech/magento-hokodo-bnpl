<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Service;

class ArrayToStringService
{
    /**
     * A function that convert error array to string.
     *
     * @param array $array
     *
     * @return string
     */
    public function errorArrayToString(array $array): string
    {
        $result = [];
        foreach ($array as $code => $vitem) {
            if (isset($vitem[0])) {
                $result[] = (is_string($vitem)) ? $vitem : ($code . ' : ' . implode(', ', $vitem));
            } else {
                $item = [];
                foreach ($vitem as $i => $j) {
                    if (trim($i) != '') {
                        if (is_array($j)) {
                            $item[] = $i . ' : ' . implode(', ', $j);
                        } else {
                            $item[] = $i . ' : ' . $j;
                        }
                    }
                }
                if (trim(implode(', ', $item)) != '') {
                    $result[] = implode(', ', $item);
                }
            }
        }

        return implode(', ', $result);
    }
}
