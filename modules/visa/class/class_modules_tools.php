<?php
/*
*   Copyright 2008-2015 Maarch and Document Image Solutions
*
*   This file is part of Maarch Framework.
*
*   Maarch Framework is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
*
*   Maarch Framework is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief Contains the functions to manage visa and notice workflow.
*
* @file
* @author Nicolas Couture <couture@docimsol.com>
* @date $date$
* @version $Revision$
* @ingroup visa
*/

require_once 'modules/visa/class/class_modules_tools_Abstract.php';

class visa extends visa_Abstract
{
	// custom
}


class PdfNotes extends PdfNotes_Abstract
{
	// custom
}

class ConcatPdf extends ConcatPdf_Abstract
{
    // custom
}

/* EXEMPLE TAB VISA_CIRCUIT

Array
(
    [coll_id] => letterbox_coll
    [res_id] => 190
    [difflist_type] => entity_id
    [sign] => Array
        (
            [users] => Array
                (
                    [0] => Array
                        (
                            [user_id] => sgros
                            [lastname] => GROS
                            [firstname] => Sébastien
                            [entity_id] => CHEFCABINET
                            [entity_label] => Chefferie
                            [visible] => Y
                            [viewed] => 0
                            [difflist_type] => VISA_CIRCUIT
                            [process_date] => 
                            [process_comment] => 
                        )

                )

        )

    [visa] => Array
        (
            [users] => Array
                (
                    [0] => Array
                        (
                            [user_id] => sbes
                            [lastname] => BES
                            [firstname] => Stéphanie
                            [entity_id] => CHEFCABINET
                            [entity_label] => Chefferie
                            [visible] => Y
                            [viewed] => 0
                            [difflist_type] => VISA_CIRCUIT
                            [process_date] => 
                            [process_comment] => 
                        )

                    [1] => Array
                        (
                            [user_id] => fbenrabia
                            [lastname] => BENRABIA
                            [firstname] => Fadela
                            [entity_id] => POLESOCIAL
                            [entity_label] => Pôle social
                            [visible] => Y
                            [viewed] => 0
                            [difflist_type] => VISA_CIRCUIT
                            [process_date] => 
                            [process_comment] => 
                        )

                    [2] => Array
                        (
                            [user_id] => bpont
                            [lastname] => PONT
                            [firstname] => Brieuc
                            [entity_id] => POLEAFFAIRESETRANGERES
                            [entity_label] => Pôle affaires étrangères
                            [visible] => Y
                            [viewed] => 0
                            [difflist_type] => VISA_CIRCUIT
                            [process_date] => 
                            [process_comment] => 
                        )

                )

        )

)





<h3>Document</h3><pre>Array
(
    [0] => Array
        (
            [id] => 197
            [label] => 123456
            [format] => pdf
            [filesize] => 46468
            [attachment_type] => 
            [is_version] => 
            [version] => 
        )

)
</pre><h3>Document</h3><pre>Array
(
    [0] => Array
        (
            [id] => 400
            [label] => reponse 1 v5
            [format] => docx
            [filesize] => 36219
            [attachment_type] => response_project
            [is_version] => 
            [version] => 
        )

    [1] => Array
        (
            [id] => 409
            [label] => Nouvelle PJ
            [format] => pdf
            [filesize] => 1204460
            [attachment_type] => simple_attachment
            [is_version] => 
            [version] => 
        )

    [2] => Array
        (
            [id] => 410
            [label] => pj 2
            [format] => pdf
            [filesize] => 361365
            [attachment_type] => simple_attachment
            [is_version] => 
            [version] => 
        )

)

*/
?>
