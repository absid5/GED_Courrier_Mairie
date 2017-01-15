<?php

/*
*   Copyright 2008-2016 Maarch
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

if (!empty($_POST['size']) && isset($_POST['contactId'])) {
    $nb = $_POST['size'];

    if (empty($_POST['contactId'])) {
        unset($_SESSION['transmissionContacts'][$nb]);
    } else {
        $db = new Database();

        if (is_numeric($_POST['contactId'])) {
            if (isset($_POST['addressId'])) {
                $stmt = $db->query('SELECT * FROM view_contacts WHERE contact_id = ? AND ca_id = ?', [$_POST['contactId'], $_POST['addressId']]);
            } else {
                $stmt = $db->query('SELECT * FROM view_contacts WHERE contact_id = ?', [$_POST['contactId']]);
            }
        } else {
            $stmt = $db->query('SELECT firstname, lastname, user_id, mail, phone, initials FROM users WHERE user_id = ?', [$_POST['contactId']]);
        }

        $contact = $stmt->fetchObject();
        if (!isset($_SESSION['transmissionContacts']))
            $_SESSION['transmissionContacts'] = [];

        foreach($contact as $key => $value) {
            $_SESSION['transmissionContacts'][$nb][$key] = $value;
        }
        if (is_numeric($_POST['contactId'])) {
            $_SESSION['transmissionContacts'][$nb]['firstname'] = $contact->contact_firstname == '' ? $contact->firstname : $contact->contact_firstname;
            $_SESSION['transmissionContacts'][$nb]['lastname']  = $contact->contact_lastname == '' ? $contact->lastname : $contact->contact_lastname;
            $_SESSION['transmissionContacts'][$nb]['title']     = $contact->contact_title == '' ? $contact->title : $contact->contact_title;
        }
    }
}