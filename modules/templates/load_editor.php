<?php  

/*
*   Copyright 2008-2015 Maarch
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

?>

<!-- tinyMCE -->
<script  type="text/javascript" src="<?php echo $_SESSION['config']['businessappurl'].'tools/';?>tiny_mce/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({

        selector: "textarea#template_content",
        statusbar : false,

        language : "fr_FR",
        height : "300",
        plugins: [
                 "advlist autolink link lists charmap print preview hr",
                 "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime nonbreaking",
                 "save table contextmenu directionality paste textcolor"
        ],
        toolbar: "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | preview fullpage | forecolor backcolor", 

    });

    // Custom save callback, gets called when the contents is to be submitted
    function customSave(id, content) {
        alert(id + "=" + content);
    }
</script>
<!-- /tinyMCE -->
