<?php
/**
* @package PHP5

* @license AGPLv3
* lulzBB is a CMS for the lulz but it's also serious business.
* Copyright (C) 2008 lulzGroup
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as
* published by the Free Software Foundation, either version 3 of the
* License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* LulzCode implementation.

* @author cHoBi
*/
class BBCode
{
    public function arrayParse ($data)
    {
        global $Filter;

        $string = $data['RAW'];

        $parsed['RAW']  = self::parse($string);
        $parsed['HTML'] = $Filter->HTML($parsed['RAW']);
        $parsed['POST'] = $Filter->POST($parsed['RAW']);

        return $parsed;
    }

    public function parse ($string)
    {
        $parsed = $string;

        return $parsed;
    }
}
?>
