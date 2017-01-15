<?php
/*
*    Copyright 2008 - 2011 Maarch
*
*  This file is part of Maarch Framework.
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
*    along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief Entities tables declarations
*
*
* @file
* @author  Claire Figueras  <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup folder
*/

if (! defined('FOLD_FOLDERS_TABLE')) {
    define('FOLD_FOLDERS_TABLE', 'folders');
}
if (! defined('FOLD_FOLDERS_OUT_TABLE')) {
    define('FOLD_FOLDERS_OUT_TABLE', 'folders_out');
}
if (! defined('FOLD_FOLDERTYPES_TABLE')) {
    define('FOLD_FOLDERTYPES_TABLE', 'foldertypes');
}
if (! defined('FOLD_FOLDERTYPES_DOCTYPES_TABLE')) {
    define('FOLD_FOLDERTYPES_DOCTYPES_TABLE', 'foldertypes_doctypes');
}
if (! defined('FOLD_FOLDERTYPES_DOCTYPES_LEVEL1_TABLE')) {
    define('FOLD_FOLDERTYPES_DOCTYPES_LEVEL1_TABLE', 'foldertypes_doctypes_level1');
}
if (! defined('FOLD_FOLDERTYPES_INDEXES_TABLE')) {
    define('FOLD_FOLDERTYPES_INDEXES_TABLE', 'foldertypes_indexes');
}
